<?php
namespace console\controllers;

use common\components\Slug;
use console\models\batdongsan\Listing;
use console\models\Helpers;
use console\models\Metvuong;
use console\models\Product;
use vsoft\ad\models\AdImages;
use vsoft\craw\models\AdProductFile;
use Yii;
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
use vsoft\express\components\StringHelper;
use vsoft\ad\models\AdWard;

class ProductController extends Controller {
	
	public function actionUpdateScore($id) {
		$product = AdProduct::findOne($id);
		$product->updateEs(['score' => $product->score]);
	}
	
	public function actionCheckWrongScore($hasIp = false) {
		$query = AdProduct::find();
		$query->where(ElasticController::$where);
		
		if($hasIp) {
			$query->andWhere('`ip` IS NOT NULL');
		}
		
		$query->limit(1000);
		
		for($i = 0; true; $i += $query->limit) {
			$query->offset($i);
			
			$products = $query->all();
			
			foreach ($products as $product) {
				$score = AdProduct::calcScore($product);
				
				if($score != $product->score) {
					
					if($product->ip) {
						echo $product->id . ': ' . $score . ' ' . $product->score . ' ' . $product->ip . "\n";
					}
					
					\Yii::$app->db->createCommand("UPDATE `ad_product` SET `score` = {$score} WHERE `id` = {$product->id}")->execute();
				}
			}
					
			if(count($products) < $query->limit) {
				break;
			}
		}
	}
	
	public function actionUpdateNullLatLng() {
		$products = \Yii::$app->db->createCommand("SELECT * FROM `ad_product` WHERE `lat` IS NULL OR `lat` = '' OR `lng` IS NULL OR `lng` = ''")->queryAll();
		
		echo "Total Products: " . count($products) . "\n";
		
		$updateTotal = 0;
		
		foreach ($products as $product) {
			$latLng = [];
			
			if($product['ward_id']) {
				$ward = AdWard::findOne($product['ward_id']);
				
				if($ward->center) {
					$latLng = json_decode($ward->center);
				}
			}
			
			if(empty($latLng)) {
				$district = AdDistrict::findOne($product['district_id']);
				
				if($district->center) {
					$latLng = json_decode($district->center);
				}
			}
			
			if(empty($latLng)) {
				$city = AdCity::findOne($product['city_id']);
				
				if($city->center) {
					$latLng = json_decode($city->center);
				}
			}
			
			if(!empty($latLng)) {
				$updateTotal++;
				\Yii::$app->db->createCommand("UPDATE `ad_product` SET `lat` = '{$latLng[0]}', `lng` = '{$latLng[1]}' WHERE `id` = {$product['id']}")->execute();
			}
		}
		
		echo "Update Products: " . $updateTotal . "\n";
	}
	
	public function actionDelete($id) {
		$product = AdProduct::findOne($id);
		
		if($product) {
			$name = $product->street->pre . ' ' . $product->street->name;

			$address = array_filter([
				$product->home_no,
				$name
			]);
			
			$message = [];
			$message[] = "ID: MV" . $id;
			$message[] = "Address: " . implode(", ", $address);
			$message[] = "Price: " . $product->price;
			$message[] = "Area: " . $product->area;
			$message[] = "Post Date: " . StringHelper::previousTime($product->start_date);
			$message[] = "Are you sure Delete this product ?";
			
			if($this->confirm(implode("\n", $message) . "\n")) {
				echo "Delete From Elastic And Decrease counter\n";
				$product->removeEs();
				
				echo "Delete Addition Info\n";
				$product->adProductAdditionInfo->delete();
				echo "Delete Contact Info\n";
				$product->adContactInfo->delete();

				echo "Delete Images\n";
				foreach ($product->adImages as $image) {
					$image->delete();
				}
				
				echo "Delete Product\n";
				$product->delete();
				echo "OK";
			}
		} else {
			echo "MV$id is not exist.";
		}
	}
	
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
	
	public function actionCheckBoostSort() {
		$this->checkBoostSort();
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
    public $limit;
    public function options()
    {
        return ['code', 'limit'];
    }
    public function optionAliases()
    {
        return ['code' => 'code', 'limit' => 'limit'];
    }

    /*
        Marketing contact send mail: php yii product/send-mail-contact -code=123456
    */
    public function actionSendMailContact(){
        $limit = $this->limit == null ? 100 : ((intval($this->limit) <= 100 && intval($this->limit) > 0) ? intval($this->limit) : 0);
        Metvuong::sendMailContact($this->code, $limit);
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
                usleep(50000);
            }
            print_r(PHP_EOL);
            print_r("Updated {$no} images...");
            print_r(PHP_EOL);
        }
    }

    public function actionCopyProductFile()
    {
        $start_time = time();
        $count_log_no_city=0;
        $count_log_no_district=0;
        $sql = "SELECT `id`, city_id, district_id, `type`, file_name, product_main_id, created_at, updated_at FROM ad_product where file_name not in (select file from ad_product_file) ";
        $crawl_products = \vsoft\craw\models\AdProduct::getDb()->createCommand($sql)->queryAll();
        if(count($crawl_products) > 0){
            $no = 0;
            foreach ($crawl_products as $product) {
                $file_name = $product['file_name'];
//                print_r(PHP_EOL.$file_name);
                if(!AdProductFile::checkFileExists($file_name)){
                    $product_file = new AdProductFile();
                    $product_file->file = $file_name;
                    // build path
                    $path = null;
                    $city_id = $product['city_id'];
                    $city = \vsoft\craw\models\AdCity::getDb()->cache(function() use($city_id){
                        return \vsoft\craw\models\AdCity::find()->select(['name','slug'])->where(['id' => $city_id])->asArray()->one();
                    });
                    if(count($city) > 0) {
                        $path = $city['slug'];
                        $district_id = $product['district_id'];
                        $district = \vsoft\craw\models\AdDistrict::getDb()->cache(function() use($district_id){
                            return \vsoft\craw\models\AdDistrict::find()->select(['name'])->where(['id' => $district_id])->asArray()->one();
                        });
                        if(count($district) > 0) {
                            $district_slug = Slug::me()->slugify($district['name']);
                            $sales_rents = $product['type'] == "1" ? "sales/nha-dat-ban-{$district_slug}/files" : "rents/nha-dat-cho-thue-{$district_slug}/rent_files";
                            $path = $path."/".$sales_rents;
                        } else {
                            $count_log_no_district++;
                            continue; }
                    } else {
                        $count_log_no_city++;
                        continue;
                    }

                    $product_file->path = $path;
                    $product_file->is_import = 1;
                    $product_file->product_tool_id = (int)$product['id'];
                    $product_file->imported_at = (int)$product['created_at'];
                    $product_file->vendor_link = Listing::DOMAIN."/copy-pr".$file_name;
                    $product_main_id = (int)$product['product_main_id'];
                    if($product_main_id > 0) {
                        $product_file->is_copy = 1;
                        $product_file->copied_at = $product['updated_at'];
                        $product_file->product_main_id = $product_main_id;
                    }
                    $product_file->created_at = time();
                    $product_file->save(false);
                    if ($no > 0 && $no % 100 == 0) {
                        print_r("\nCopied {$no} files...\n");
                    }
                    $no++;
                }
            }
            $end_time = time();
            $total_time = $end_time - $start_time;
            print_r("\nCopied {$no} files...{$total_time} seconds");
            if($count_log_no_city > 0)
                print_r("\nNo city {$count_log_no_city} files");
            if($count_log_no_district > 0)
                print_r("\nNo district {$count_log_no_district} files");

        }
        else{
            print_r("Copied all crawl products have file_name to ad_product_file");
        }
    }

    // Map product main voi product tool va check product main duplicate
    public function actionMapProduct()
    {
        $start_time = time();
        $count_product_duplicate = 0;
        $count_product_not_found = 0;
        $count_product_copied= 0;
        $db_tool_schema = Helpers::getDbTool();
        $path = Yii::getAlias('@console'). "/data/bds_html/map_product/";
        if(!is_dir($path))
        {
            mkdir($path, 0777, true);
        }
        $file_last_id_name = "last_id.json";
        $log = Helpers::loadLog($path, $file_last_id_name);
        $last_id = 0;
        if(!empty($log)){
            $last_id = (int)$log['last_id'];
        }

        $limit = $this->limit == null ? 500 : ((intval($this->limit) <= 500 && intval($this->limit) > 0) ? intval($this->limit) : 0); // Get product main
        if($limit == 0){
            print_r("\nRecord limit from 1 to 500");
            return;
        }
//        $product_main = \vsoft\craw\models\AdProduct::find()->select(['product_main_id'])->where('product_main_id != 0')->asArray()->all();
        $product_main = \vsoft\craw\models\AdProduct::getDb()->createCommand("select product_main_id from ad_product where product_main_id != 0")->queryColumn();
        $not_in_product_main = implode(",", $product_main);

        $duplicate = \vsoft\craw\models\AdProduct::getDb()->createCommand("select product_main_id from map_product_duplicate group by product_main_id")->queryColumn();
        $not_in_product_duplicate = implode(",", $duplicate);

        $sql = "ip is null and id not in ({$not_in_product_main}) and id not in ({$not_in_product_duplicate})";
        if($last_id > 0)
            $sql = $sql. " and id > {$last_id}";

        $main_products = AdProduct::find()->where($sql)
            ->orderBy(['id' => SORT_ASC])->limit($limit)->all();

        $count_main_products = count($main_products);
        if($count_main_products > 0)
        {
            foreach($main_products as $key => $product ){
                $no = $key+1;
                print_r("\n{$no} MainID: {$product->id} ");
                $sql_where = "CAST(lat AS decimal(10,6)) = CAST({$product->lat} AS decimal(10,6)) and CAST(lng AS decimal(10,6)) = CAST({$product->lng}  AS decimal(10,6)) ";
                if(!empty($product->area)){
                    $sql_where = $sql_where . " and CAST(area AS decimal) = CAST({$product->area} AS decimal)";
                }
                // Get product tool
                $crawl_product = \vsoft\craw\models\AdProduct::find()->where([
                    'content' => $product->content,
                    'city_id' => $product->city_id,
                    'district_id' => $product->district_id,
                    'ward_id' => $product->ward_id,
                    'street_id' => $product->street_id,
                    'price' => $product->price,
                    'type' => $product->type,
                    'category_id' => $product->category_id,
                    'start_date' => $product->start_date,
                    'product_main_id' => 0,
                ])
                    ->andWhere($sql_where)
                    ->orderBy(['id' =>SORT_ASC])->one();

                if(count($crawl_product) > 0)
                {
                    $file_name = $crawl_product->file_name;
                    $product_file = AdProductFile::find()->where(['file' => $file_name])->one();
                    if(count($product_file) > 0) {
                        if($product_file->is_import != 1) {
                            $product_file->is_import = 1;
                            $product_file->imported_at = $crawl_product->created_at;
                            $product_file->product_tool_id = $crawl_product->id;
                        }
                        if($crawl_product->product_main_id == 0) {
                            $crawl_product->product_main_id = $product->id;
                            $crawl_product->update(false);

                            $product_file->is_copy = 1;
                            $product_file->copied_at = $product->created_at;
                            $product_file->product_main_id = $product->id;
                        }
                        $product_file->updated_at = time();
                        $res = $product_file->save(false);
                        if($res) {
                            $count_product_copied++;
                            print_r("- Map Tool ID: {$crawl_product->id} - File: {$file_name}");
                        }
                    } else {
                        print_r("- Not found in AdProductFile table.");
                    }
                }
                else {
                    $sql_where = $sql_where. " and product_main_id > 0";
                    $other_products = \vsoft\craw\models\AdProduct::find()
                        ->select(['id', 'product_main_id'])
                        ->where([
                            'content' => $product->content,
                            'city_id' => $product->city_id,
                            'district_id' => $product->district_id,
                            'ward_id' => $product->ward_id,
                            'street_id' => $product->street_id,
                            'price' => $product->price,
                            'type' => $product->type,
                            'category_id' => $product->category_id,
                            'start_date' => $product->start_date
                        ])
                        ->andWhere($sql_where)
                        ->orderBy(['id' =>SORT_ASC])->all();

                    if(count($other_products) > 0) {
                        print_r("- Duplicate");
                        foreach ($other_products as $key_other => $other_product) {
                            $sqlCheckDuplicate = "select * from {$db_tool_schema}.map_product_duplicate where product_main_id = {$product->id}";
                            if(empty($other_product->product_main_id))
                                $sqlCheckDuplicate = $sqlCheckDuplicate. " and duplicate_id is null";
                            else
                                $sqlCheckDuplicate = $sqlCheckDuplicate. " and duplicate_id = {$other_product->product_main_id}";

                            if(empty($other_product->id))
                                $sqlCheckDuplicate = $sqlCheckDuplicate. " and tool_id is null";
                            else
                                $sqlCheckDuplicate = $sqlCheckDuplicate. " and tool_id = {$other_product->id}";

                            $checkProductDuplicate = \vsoft\craw\models\AdProduct::getDb()->createCommand($sqlCheckDuplicate)->queryAll();
                            if(count($checkProductDuplicate) > 0) {
                                print_r(" Duplicate inserted");
                               continue;
                            }
                            $recordDuplicate = [
                                'product_main_id' => $product->id,
                                'duplicate_id' => $other_product->product_main_id,
                                'tool_id' => $other_product->id,
                                'is_duplicate' => 1,
                                'created_at' => time()
                            ];
                            $duplicate_count = \vsoft\craw\models\AdProduct::getDb()->createCommand()
                                ->insert('map_product_duplicate', $recordDuplicate)
                                ->execute();
                            $count_product_duplicate = $count_product_duplicate + $duplicate_count;
                            $print_out = $key_other == 0 ? "ID: ". $other_product->product_main_id : ", ". $other_product->product_main_id;
                            print_r($print_out);
                        }
                    } else {
                        $sqlCheckDuplicate = "select * from {$db_tool_schema}.map_product_duplicate where product_main_id = {$product->id}";
                        $checkProductDuplicate = \vsoft\craw\models\AdProduct::getDb()->createCommand($sqlCheckDuplicate)->queryAll();
                        if(count($checkProductDuplicate) > 0) {
                            print_r(" Not found inserted");
                            continue;
                        }
                        $recordNotFound = [
                            'product_main_id' => $product->id,
                            'duplicate_id' => null,
                            'tool_id' => null,
                            'is_duplicate' => 0,
                            'created_at' => time()
                        ];
                        $not_found_count = \vsoft\craw\models\AdProduct::getDb()->createCommand()
                            ->insert('map_product_duplicate', $recordNotFound)
                            ->execute();
                        $count_product_not_found = $count_product_not_found + $not_found_count;
                        print_r("- Not found DB Tool");
                    }
                }

                if($no >= count($main_products) || $no % 100 == 0){
                    $log['last_id'] = $product->id;
                    $log['last_time'] = date('d M Y H:i', time());
                    Helpers::writeLog($log, $path, $file_last_id_name);
                }
            } // end foreach product
        } else {
            print_r("\n Product Main not found");
        }

        $end_time = time();
        $time = $end_time - $start_time;
        print_r("\n\nTime: {$time}s");
        print_r("\nMap product: {$count_product_copied}");
        print_r("\nProduct duplicate: {$count_product_duplicate}");
        print_r("\nProduct not found: {$count_product_not_found}\n");
    }

    // Map Contact co user thong qua email cap nhat user_id vao product main. Su dung file log email da map: bds_html/map_product/map_contact_product.json
    public function actionMapContactProduct()
    {
        $limit = $this->limit == null ? 1000 : ((intval($this->limit) <= 1000 && intval($this->limit) > 0) ? intval($this->limit) : 0);
        Metvuong::mapContactProduct($limit);
    }

	public function actionUpdateFavorite($step)
	{
		Product::me()->updateStats($step);
	}

}