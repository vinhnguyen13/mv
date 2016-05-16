<?php
namespace console\controllers;



use yii\console\Controller;
use vsoft\ad\models\AdProduct;

class ProductController extends Controller {
	public function actionCheckExpired() {
		$now = time();
		$products = AdProduct::find()->where("`end_date` < $now AND `is_expired` = 0")->limit(10000)->asArray(true)->all();
		
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
}