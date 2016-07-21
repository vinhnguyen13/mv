<?php
namespace console\controllers;

use console\models\Metvuong;
use vsoft\ad\models\AdImages;
use yii\console\Controller;
use vsoft\ad\models\AdProduct;
use vsoft\ad\models\AdBuildingProject;
use vsoft\ad\models\AdCity;
use frontend\models\Elastic;
use common\models\common\models;
use vsoft\ad\models\AdDistrict;
use yii\db\Query;
use yii\db\yii\db;
use yii\helpers\ArrayHelper;

class ProductController extends Controller {
	
	public function actionCheckLatLng() {
		$projects = AdBuildingProject::find()->asArray(true)->all();
		$command = \Yii::$app->db->createCommand();
		
		foreach ($projects as $project) {
			$command->update(AdProduct::tableName(), ['lat' => $project['lat'], 'lng' => $project['lng']], 'project_building_id = ' . $project['id'])->execute();
		}
	}
	
	public function actionUpdate() {
		if($this->checkExpired() || $this->checkEndBoost()) {
			$this->checkBoostSort();
		}
	}
	
	public function checkExpired() {
		$now = time();
		$limit = 1000;
		
		$updateCounter = [
			'city' => [],
			'district' => [],
			'ward' => [],
			'street' => [],
			'project_building' => []
		];
		
		$deleteProducts = [];
		
		for($i = 0; true; $i += $limit) {
			$products = (new Query())->select("`id`, `type`, `city_id`, `district_id`, `ward_id`, `street_id`, `project_building_id`")->from('ad_product')->where("`end_date` < {$now} AND `is_expired` = 0 AND `status` = " . AdProduct::STATUS_ACTIVE)->limit($limit)->offset($i)->all();
			
			$deleteProducts = array_merge($deleteProducts, ArrayHelper::getColumn($products, 'id'));
			
			foreach ($products as $product) {
				foreach ($updateCounter as $type => &$bulk) {
					$termId = $product[$type . '_id'];
					
					if($termId) {
						if(isset($bulk[$termId])) {
							if($product['type'] == AdProduct::TYPE_FOR_SELL) {
								$bulk[$termId][AdProduct::TYPE_FOR_SELL_TOTAL] += 1;
							} else {
								$bulk[$termId][AdProduct::TYPE_FOR_RENT_TOTAL] += 1;
							}
						} else {
							if($product['type'] == AdProduct::TYPE_FOR_SELL) {
								$bulk[$termId][AdProduct::TYPE_FOR_SELL_TOTAL] = 1;
							} else {
								$bulk[$termId][AdProduct::TYPE_FOR_RENT_TOTAL] = 1;
							}
						}
					}
				}
			}
			
			if(count($products) < $limit) {
				break;
			}
		}
		
		if(!empty($deleteProducts)) {
			
			/*
			 * Delete Product Elastic
			*/
			$deleteBulk = array_map(function($deleteProduct){
				return '{"delete":{"_id":"' . $deleteProduct . '"}}';
			}, $deleteProducts);
			
			$deleteBulk = implode("\n", $deleteBulk) . "\n";
		
			$indexName = \Yii::$app->params['indexName']['product'];
			$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . "/$indexName/" . Elastic::$productEsType . "/_bulk");
		
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $deleteBulk);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_exec($ch);
			curl_close($ch);
		
			/*
			 * Update Counter
			*/
			$updateBulk = [];
			$retryOnConflict = Elastic::RETRY_ON_CONFLICT;
			
			foreach ($updateCounter as $type => $uc) {
				foreach ($uc as $id => $total) {
					foreach ($total as $t => $count) {
						$updateBulk[] = '{ "update" : { "_id" : "' . $id . '", "_type" : "' . $type . '", "_retry_on_conflict": ' . $retryOnConflict . ' } }';
						$updateBulk[] = '{ "script" : { "inline": "ctx._source.' . $t . ' -= ' . $count . '"} }';
					}
				}
			}
			$updateBulk = implode("\n", $updateBulk) . "\n";
			$indexName = \Yii::$app->params['indexName']['countTotal'];
			$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . "/$indexName/_bulk");
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $updateBulk);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_exec($ch);
			curl_close($ch);
			
			/*
			 * Delete DB
			 */
			$connection = \Yii::$app->db;
			$connection->createCommand()->update('ad_product', ['is_expired' => 1], ['id' => $deleteProducts])->execute();
		}
		
		return count($deleteProducts);
	}
	
	public function checkEndBoost() {
		$now = time();
		$limit = 1000;
		
		$endBoosts = [];
		
		$connection = \Yii::$app->db;
		$transaction = $connection->beginTransaction();
		
		try {
			for($i = 0; true; $i += $limit) {
				$products = $connection->createCommand("SELECT `id` FROM `ad_product` WHERE `boost_time` < $now AND `boost_time` > 0 LIMIT $i,1000 FOR UPDATE")->queryAll();
			
				$endBoosts = array_merge($endBoosts, ArrayHelper::getColumn($products, 'id'));
			
				if(count($products) < $limit) {
					break;
				}
			}
			
			if(!empty($endBoosts)) {
				/*
				 * Update DB
				 */
				$connection = \Yii::$app->db;
				$connection->createCommand()->update('ad_product', ['boost_time' => 0, 'boost_start_time' => null], ['id' => $endBoosts])->execute();
					
				/*
				 * Update Elastic
				*/
				$updateBulk = [];
					
				foreach ($endBoosts as $id) {
					$updateBulk[] = '{ "update" : { "_id" : "' . $id . '" } }';
					$updateBulk[] = '{ "doc" : {"boost_sort" : 0, "boost_time": 0, "boost_start_time": 0} }';
				}
					
				$updateBulk = implode("\n", $updateBulk) . "\n";
					
				$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . "/$indexName/" . Elastic::$productEsType . "/_bulk");
					
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $updateBulk);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_exec($ch);
				curl_close($ch);
			}
			
			$transaction->commit();
		} catch (\Exception $e) {
			$transaction->rollBack();
		}
		
		return count($endBoosts);
	}
	
	public function checkBoostSort() {
		AdProduct::reSortBoost(AdProduct::TYPE_FOR_SELL);
		AdProduct::reSortBoost(AdProduct::TYPE_FOR_RENT);
	}

    public function actionCheckScore(){
        $start = time();
        $products = AdProduct::find()->where("`score` = 0")->orderBy(['updated_at' => SORT_DESC])->limit(1000)->all();
        if(count($products) > 0) {
            $no = 0;
            foreach ($products as $product) {
                $score = AdProduct::calcScore($product);
                \Yii::$app->db->createCommand()->update(AdProduct::tableName(), ['score' => $score], 'id = ' . $product['id'])->execute();
                if($no >0 && $no % 100 == 0) {
                    print_r(PHP_EOL);
                    print_r("Checked {$no} records...");
                    print_r(PHP_EOL);
                }
                $no++;
            }
            $stop = time();
            $time = $stop-$start;
            print_r(PHP_EOL);
            print_r("Checked {$no} records... DONE! - Time: {$time}s");
        } else {
//            print_r(PHP_EOL);
            print_r(" Products have checked score!");
        }
    }

    public $code;
    public function options()
    {
        return ['code'];
    }
    public function optionAliases()
    {
        return ['code' => 'code'];
    }

    /*
        Marketing contact send mail: php yii product/send-mail-contact -code=123456
    */
    public function actionSendMailContact(){
        Metvuong::sendMailContact($this->code);
    }

    public function actionDownloadImage($limit = 100)
    {
        $images = AdImages::find()->where('folder = :f', [':f' => ''])->orWhere(['folder' => null])->limit($limit)->all();
        if(count($images) > 0){
            $no = 0;
            foreach ($images as $image) {
                $result = Metvuong::DownloadImage($image->file_name, $image->uploaded_at);
                if(!empty($result)){
                    $image->file_name = $result[0];
                    $image->folder = $result[1];
                    $image->update(false);
                }
                if($no > 0 && $no % 100 == 0) {
                    print_r(PHP_EOL);
                    print_r("Updated {$no} images...");
                    print_r(PHP_EOL);
                }
                $no++;
                sleep(1);
            }
            print_r(PHP_EOL);
            print_r("Updated {$no} images...");
            print_r(PHP_EOL);
        }
    }

}