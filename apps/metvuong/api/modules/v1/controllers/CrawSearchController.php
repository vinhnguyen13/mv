<?php
namespace api\modules\v1\controllers;

use yii\rest\Controller;
use frontend\models\MapSearch;
use yii\db\Query;
use frontend\models\frontend\models;
use frontend\models\Elastic;
use vsoft\ad\models\AdProduct;

class CrawSearchController extends Controller {
	public function actionGet() {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$v = \Yii::$app->request->get('v');
		
		$params = Elastic::buildParams($v);
		
		$params['query']['function_score']['functions'][] = [
				"field_value_factor" => [
						"field" => "total_sell",
						"modifier" => "log1p",
						"factor" => 0.1
				]
		];
			
		$params['query']['function_score']['functions'][] = [
				"field_value_factor" => [
						"field" => "total_rent",
						"modifier" => "log1p",
						"factor" => 0.1
				]
		];
		
		$result = Elastic::requestResult($params, Elastic::elasticUrl('/district,ward,project_building'));
		
		$response = [];
		
		foreach ($result['hits']['hits'] as $hit) {
			$response[] = [
					'id' => $hit['_id'],
					'type' => $hit['_type'],
					'full_name' => $hit['_source']['full_name']
			];
		}
		
		return $response;
	}
	
	public function actionGetM() {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$v = \Yii::$app->request->get('value');
		$type = \Yii::$app->request->get('type');
		
		$params = Elastic::buildParams($v);
		
		$params['query']['function_score']['functions'][] = [
				"field_value_factor" => [
						"field" => "total_sell",
						"modifier" => "log1p",
						"factor" => 0.1
				]
		];
			
		$params['query']['function_score']['functions'][] = [
				"field_value_factor" => [
						"field" => "total_rent",
						"modifier" => "log1p",
						"factor" => 0.1
				]
		];
		
		$result = Elastic::requestResult($params, Elastic::elasticUrl('/' . $type));
		
		$response = [];
		
		foreach ($result['hits']['hits'] as $hit) {
			$response[] = [
					'id' => $hit['_id'],
					'full_name' => $hit['_source']['full_name']
			];
		}
		
		return $response;
	}
}