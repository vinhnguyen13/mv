<?php
namespace frontend\controllers;

use yii\rest\ActiveController;
use frontend\models\Elastic;
use yii\helpers\Url;
use vsoft\ad\models\AdProduct;
use vsoft\express\components\StringHelper;

class MapController extends ActiveController {
	
	public $modelClass = 'frontend\models\MapSearch';
	
	public function actionGetMarkers() {
		
	}
	
	public function actionGetAreas() {
		
	}
	
	public function actionGetList() {
		
	}
	
	public function actionSearch() {
		$v = Elastic::transform(\Yii::$app->request->get('v'));
		
		if(\Yii::$app->request->isAjax) {
			$response = [];
			
			if(StringHelper::startsWith($v, 'mv')) {
				$id = str_replace('mv', '', $v);
				$product = AdProduct::findOne($id);
				
				if($product) {
					$response['address'] = $product->address;
					$response['url'] = $product->urlDetail();
				}
			} else {
				$params = [
					'query' => [
						'match_phrase_prefix' => [
							'search_field' => [
	    						'query' => $v,
	    						'max_expansions' => 100
	    					]
						],
	    			],
	    			'sort' => [
	    				'total_sell' => [
	    					'order' => 'desc',
	    					'mode'	=> 'sum'
						],
	    				'total_rent' => [
	    					'order' => 'desc',
	    					'mode'	=> 'sum'
						],
					],
	    			'_source' => ['full_name', AdProduct::TYPE_FOR_SELL_TOTAL, AdProduct::TYPE_FOR_RENT_TOTAL]
	    		];
				
				$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . '/' . \Yii::$app->params['indexName']['countTotal'] . '/_search');
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				
				$result = json_decode(curl_exec($ch), true);
				
				foreach ($result['hits']['hits'] as $k => $hit) {
	    			$response[$k] = $hit['_source'];
	    			$response[$k]['url_sale'] = Url::to(['/ad/index1', $hit['_type'] . '_id' => $hit['_id'], 's' => 1]);
	    			$response[$k]['url_rent'] = Url::to(['/ad/index2', $hit['_type'] . '_id' => $hit['_id'], 's' => 1]);
	    			$response[$k]['type'] = $hit['_type'];
	    			$response[$k]['id'] = $hit['_id'];
	    		}
				 
				if(!$response && is_numeric($v)) {
					$product = AdProduct::findOne($v);
					 
					if($product) {
						$response['address'] = $product->address;
						$response['url'] = $product->urlDetail();
					}
				}
			}
			
			return $response;
		} else {
			$id = str_replace('mv', '', $v);
			$product = AdProduct::findOne($id);
			
			if($product) {
				return $this->redirect($product->urlDetail());
			} else {
				return $this->redirect(Url::to(['/ad/index1']));
			}
		}
	}
}