<?php
namespace frontend\controllers;

use yii\rest\ActiveController;
use frontend\models\Elastic;
use yii\helpers\Url;
use vsoft\ad\models\AdProduct;
use vsoft\express\components\StringHelper;
use frontend\models\MapSearch;
use yii\web\Request;
use yii\db\Query;

class MapController extends ActiveController {
	
	public $modelClass = 'frontend\models\MapSearch';
	
	public function actionGet() {
		$mapSearch = new MapSearch();
		$mapSearch->type = $this->getType();
		
		$query = $mapSearch->search(\Yii::$app->request->get());
		 
		$response = [];
		 
		if($mapSearch->ra) {
			$areaQuery = clone $query;
			 
			$allowArea = [
					'city' => ['id'],
					'district' => ['id', 'city_id'],
					'ward' => ['id', 'district_id', 'city_id'],
					'street' => ['id']
			];
			 
			if(in_array($mapSearch->ra, array_keys($allowArea))) {
				$allowKey = $allowArea[$mapSearch->ra];
				$key = $mapSearch->ra_k;
				 
				if(in_array($key, $allowKey)) {
					$value = ($key == 'id') ? $mapSearch->getAttribute($mapSearch->ra . '_id') : $mapSearch->getAttribute($key);
					$areas = $this->getArea($mapSearch->ra, [$key => $value]);
					 
					if($key == 'id') {
						$counts = [$value => ['total' => $areaQuery->count()]];
					} else {
						$group = $mapSearch->ra . '_id';
							
						$counts = $areaQuery->select([$group, 'COUNT(*) AS total'])->groupBy($group)->indexBy($group)->all();
					}
					 
					foreach($areas as &$area) {
						$area['total'] = isset($counts[$area['id']]['total']) ? $counts[$area['id']]['total'] : 0;
					}
					 
					$response['ra'] = $areas;
				}
			}
		}
		 
		if($mapSearch->rm || $mapSearch->rl) {
		
			if($mapSearch->rect) {
				$rect = explode(',', $mapSearch->rect);
					
				$query->andWhere(['>=', 'ad_product.lat', $rect[0]]);
				$query->andWhere(['<=', 'ad_product.lat', $rect[2]]);
				$query->andWhere(['>=', 'ad_product.lng', $rect[1]]);
				$query->andWhere(['<=', 'ad_product.lng', $rect[3]]);
			}
			 
			if($mapSearch->rm) {
				$markerQuery = clone $query;
				 
				$sort = $mapSearch->order_by ? $mapSearch->order_by : '-score';
				$doa = StringHelper::startsWith($sort, '-') ? 'DESC' : 'ASC';
				$sort = str_replace('-', '', $sort);
				 
				$markerQuery->orderBy("$sort $doa");
					
				$markerQuery->limit(500);
					
				$response['rm'] = $markerQuery->all();
			}
			 
			if($mapSearch->rl) {
				$list = $mapSearch->getList($query);
				 
				$mapSearch->fetchValues();
					
				$response['rl'] = $this->renderPartial('@frontend/web/themes/mv_desktop1/views/ad/_partials/side-list', ['searchModel' => $mapSearch, 'list' => $list]);
			}
		}
		
		return $response;
	}
	
	public function getType() {
		$pathInfo = parse_url(\Yii::$app->request->referrer);
		$path = str_replace('/', '', $pathInfo['path']);
		
		return ($path == \Yii::t('url', 'nha-dat-ban')) ? AdProduct::TYPE_FOR_SELL : AdProduct::TYPE_FOR_RENT;
	}
    
    public function getArea($area, $where) {
    	$query = new Query();
    
    	$select = ['id', 'center', 'name', 'geometry'];
    
    	if(($area != 'city')) {
    		$select[] = 'pre';
    	}
    
    	$areas = $query->from('ad_' . $area)->select($select)->where($where)->all();
    
    	return $areas;
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