<?php
namespace console\controllers;

use console\models\Metvuong;
use vsoft\ad\models\AdImages;
use yii\console\Controller;
use vsoft\ad\models\AdProduct;
use vsoft\ad\models\AdBuildingProject;
use vsoft\ad\models\AdCity;
use frontend\models\Elastic;
use common\models\SlugSearch;
use common\models\common\models;
use vsoft\ad\models\AdDistrict;
use yii\db\Query;

class ProductController extends Controller {
	
	private $slugs = [];
	private $connection;
	
	public function actionSlugSearch() {
		$this->connection = \Yii::$app->db;
		
		$cities = AdCity::find()->select('id, name')->asArray(true)->all();
		
		foreach ($cities as $city) {
			$citySlug = $this->uniqueSlug(Elastic::slug($city['name']));
			$this->saveSlug(['slug' => $citySlug, 'table' => 'ad_city', 'value' => $city['id']]);
			$this->connection->createCommand()->update('ad_city', ['slug' => $citySlug], 'id = ' . $city['id'])->execute();
			
			$districts = AdDistrict::find()->select('id, pre, name')->where(['city_id' => $city['id']])->asArray(true)->all();
			
			foreach ($districts as $district) {
				$districtName = ctype_digit($district['name']) ? $district['pre'] . " " . $district['name'] : $district['name'];
				
				$districtSlug = $citySlug . '-' . Elastic::slug($districtName);
				$districtSlug = $this->uniqueSlug($districtSlug);
				$this->saveSlug(['slug' => $districtSlug, 'table' => 'ad_district', 'value' => $district['id']]);
				$this->connection->createCommand()->update('ad_district', ['slug' => $districtSlug], 'id = ' . $district['id'])->execute();
				
				$this->saveSlugBelongDistrict('ad_ward', $district['id'], $districtSlug);
				$this->saveSlugBelongDistrict('ad_street', $district['id'], $districtSlug);
			}
		}
		
		$projects = AdBuildingProject::find()->select('id, name, slug')->asArray(true)->all();
		
		foreach ($projects as $project) {
			$projectSlug = Elastic::slug($project['name']);
			$projectSlugUnique = $this->uniqueSlug($projectSlug);
			
			if($projectSlug != $projectSlugUnique) {
				echo $projectSlug . " exist" . "\n";
			}
			
			$this->saveSlug(['slug' => $projectSlugUnique, 'table' => 'ad_building_project', 'value' => $project['id']]);
			
			if($project['slug'] != $projectSlugUnique) {
				echo $project['id'] . 'Khac ' . $project['slug'] . ' * ' . $projectSlugUnique;
			} else {
				$this->connection->createCommand()->update('ad_building_project', ['slug' => $projectSlugUnique], 'id = ' . $project['id'])->execute();
			}
		}
	}
	
	function saveSlugBelongDistrict($table, $districtId, $districtSlug) {
		$items = (new Query())->from($table)->where(['district_id' => $districtId])->select('id, pre, name')->all();
		
		foreach ($items as $item) {
			$itemName = $item['pre'] . " " . $item['name'];
			
			$itemSlug = $districtSlug . '-' . Elastic::slug($itemName);
			$itemSlug = $this->uniqueSlug($itemSlug);
			
			$this->saveSlug(['slug' => $itemSlug, 'table' => $table, 'value' => $item['id']]);
			$this->connection->createCommand()->update($table, ['slug' => $itemSlug], 'id = ' . $item['id'])->execute();
		}
	}
	
	function saveSlug($data) {
		$slug = new SlugSearch();
		$slug->load($data, '');
		$slug->save(false);
	}
	
	function uniqueSlug($slug, $increase = 0) {
		$checkSlug = $increase ? $slug . '-' . $increase : $slug;
	
		if(in_array($checkSlug, $this->slugs)) {
			return $this->uniqueSlug($slug, ++$increase);
		} else {
			$this->slugs[] = $checkSlug;
			
			return $checkSlug;
		}
	}
	
	public function actionCheckLatLng() {
		$projects = AdBuildingProject::find()->asArray(true)->all();
		$command = \Yii::$app->db->createCommand();
		
		foreach ($projects as $project) {
			$command->update(AdProduct::tableName(), ['lat' => $project['lat'], 'lng' => $project['lng']], 'project_building_id = ' . $project['id'])->execute();
		}
	}
	
	public function actionCheckExpired() {
		$now = time();
		$products = AdProduct::find()->where("`end_date` < {$now} AND `is_expired` = 0")->limit(1000)->asArray(true)->all();
		
		$connection = \Yii::$app->db;
		
		foreach ($products as $product) {
			$connection->createCommand()->update('ad_product', ['is_expired' => 1], 'id = ' . $product['id'])->execute();
			
			$totalType = ($product['type'] == AdProduct::TYPE_FOR_SELL) ? AdProduct::TYPE_FOR_SELL_TOTAL : AdProduct::TYPE_FOR_RENT_TOTAL;
			
			AdProduct::updateElasticCounter('city', $product['city_id'], $totalType, false);
			AdProduct::updateElasticCounter('district', $product['district_id'], $totalType, false);
			
			if($product['ward_id']) {
				AdProduct::updateElasticCounter('ward', $product['ward_id'], $totalType, false);
			}
			if($product['street_id']) {
				AdProduct::updateElasticCounter('street', $product['street_id'], $totalType, false);
			}
			if($product['project_building_id']) {
				AdProduct::updateElasticCounter('project_building', $product['project_building_id'], $totalType, false);
			}
		}
		
		echo 'Update Total: ' . count($products);
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

    public function actionDownloadImage()
    {
        $images = \Yii::$app->db->cache( function() {
            return AdImages::find()->where('folder = :f', [':f' => ''])->orWhere(['folder' => null])->limit(1000)->all();
        });
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