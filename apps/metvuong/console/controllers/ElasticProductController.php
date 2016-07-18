<?php
namespace console\controllers;

use yii\console\Controller;
use yii\db\Query;
use frontend\models\Elastic;
use vsoft\ad\models\AdProduct;

class ElasticProductController extends Controller {
	
	public function actionBuildIndex() {
		$indexName = \Yii::$app->params['indexName']['product'];
		
		if(!$indexName) {
			echo 'missing param indexName.product';
			
			return;
		}
		
		if($this->indexExist($indexName)) {
			$this->deleteIndex($indexName);
		}
		
		$this->createIndex($indexName);
		
		$limit = 1000;
		
		for($i = 0; true; $i += $limit) {
			$query = Elastic::buildQueryProduct();
			$query->where(ElasticController::$where);
			$query->groupBy("`ad_product`.`id`");
			$query->limit($limit);
			$query->offset($i);
			
			$products = $query->all();
			
			$productsBulk = [];
			
			foreach ($products as $product) {
				$productsBulk = array_merge($productsBulk, Elastic::buildProductDocument($product));
			}
				
			Elastic::insertProducts($indexName, Elastic::$productEsType, $productsBulk);
			
			if(count($products) < $limit) {
				break;
			}
		}

		$productsBoost = (new Query())->from('`ad_product`')->where(ElasticController::$where)->andWhere(['>', 'boost_start_time', 0])->limit(AdProduct::BOOST_SORT_LIMIT)->orderBy('boost_start_time DESC')->all();
		foreach ($productsBoost as $productBoost) {
			$indexName = \Yii::$app->params['indexName']['product'];
			$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . "/$indexName/" . Elastic::$productEsType . "/" . $productBoost['id'] . "/_update");
			$update = [
				"doc" => ["boost_sort" => intval($productBoost['boost_start_time'])]
			];
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($update));
			curl_exec($ch);
			curl_close($ch);
		}
	}
	
	public function createIndex($indexName) {
		$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . '/' . $indexName . '?pretty');
	
		$mappings = [
			'_default_' => [
				'properties' => Elastic::$properties
			]
		];
	
		$config = [
			'mappings' => $mappings
		];
	
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($config));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_exec($ch);
		curl_close($ch);
	}
	
	public function indexExist($indexName) {
		$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . '/' . $indexName);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "HEAD");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, true);
	
		curl_exec($ch);
	
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
		curl_close($ch);
	
		return $httpcode == 200;
	}
	
	public function deleteIndex($indexName) {
		$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . '/' . $indexName . '?pretty');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_exec($ch);
		curl_close($ch);
	}
}