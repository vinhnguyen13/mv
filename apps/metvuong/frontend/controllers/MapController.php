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
use yii\helpers\ArrayHelper;
use yii\db\yii\db;

class MapController extends ActiveController {
	
	public $modelClass = 'frontend\models\MapSearch';
	
	public function actionSearchProject() {
		$v = Elastic::transform(\Yii::$app->request->get('v'));
		
		$response = [];
		
		$result = Elastic::searchProjects($v);

		foreach ($result['hits']['hits'] as $k => $hit) {
			$response[$k]['full_name'] = $hit['_source']['full_name'];
			$response[$k]['id'] = $hit['_id'];
		}
		
		return $response;
	}
	
	public function actionGet() {
		$mapSearch = new MapSearch();
		$mapSearch->load(\Yii::$app->request->get());
		
		$response = [];
		
		$result = $mapSearch->search();
		
		if($mapSearch->rl) {
			$list = $result['aggregations']['rl']['hits'];
				
			$response['rl'] = $this->renderPartial('@frontend/web/themes/mv_desktop1/views/ad/_partials/side-list', ['searchModel' => $mapSearch, 'list' => $list]);
		}
		
		if($mapSearch->ra) {
			$areas = ArrayHelper::map($result['aggregations']['ra']['buckets'], "key", "doc_count");
			
			if(isset($areas[0])) {
				unset($areas[0]);
			}
			
			$table = 'ad_' . $mapSearch->ra;
			
			$areasDb = (new Query())->select("id, center, geometry, name, pre")->from($table)->where(['id' => array_keys($areas)])->all();
			
			foreach ($areasDb as &$area) {
				$area['total'] = $areas[$area['id']];
			}
			
			$response['ra'] = $areasDb;
		}
		
		if($mapSearch->rm) {
			$products = [];
			
			foreach ($result['aggregations']['rm']['hits']['hits'] as $hit) {
				$products[] = array_values($hit['_source']);
			}
			
			$response['rm'] = $products;
		}
/*		 
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
			
			$mapSearch->sort($query);
			$mapSearch->fetchValues();
			
			if($mapSearch->rect) {
				$rect = explode(',', $mapSearch->rect);
					
				$query->andWhere(['>=', 'ad_product.lat', $rect[0]]);
				$query->andWhere(['<=', 'ad_product.lat', $rect[2]]);
				$query->andWhere(['>=', 'ad_product.lng', $rect[1]]);
				$query->andWhere(['<=', 'ad_product.lng', $rect[3]]);
			}
			 
			if($mapSearch->rm) {
				$markerQuery = clone $query;
				
				$markerQuery->addSelect(['`ad_images`.`file_name` AS f', '`ad_images`.`folder` AS d']);
				$markerQuery->leftJoin('`ad_images`', '`ad_images`.`product_id` = `ad_product`.`id` AND `ad_images`.`order` = 0');
				$markerQuery->groupBy('`ad_product`.`id`');
					
				$markerQuery->limit(500);
					
				$markers = $markerQuery->all();
				
				foreach ($markers as &$marker) {
					$address = [];
				
					if($marker['show_home_no'] && $marker['home_no']) {
						$address[] = $marker['home_no'];
					}
					
					if(isset($mapSearch->streets[$marker['street_id']])) {
						$street = $mapSearch->streets[$marker['street_id']];
						
						$address[] = $street['pre'] . ' ' . $street['name'];
					}
					
					if(isset($mapSearch->wards[$marker['ward_id']])) {
						$ward = $mapSearch->wards[$marker['ward_id']];
						
						$address[] = $ward['pre'] . ' ' . $ward['name'];
					}
					
					if(isset($mapSearch->districts[$marker['district_id']])) {
						$district = $mapSearch->districts[$marker['district_id']];
						
						$address[] = trim($district['pre'] . ' ' . $district['name']);
					}
					
					$address = implode(', ', array_filter($address));
					
					$marker['a'] = $address;
					
					unset($marker['show_home_no']);
					unset($marker['home_no']);
				}
				
				$response['rm'] = $markers;
			}
			 
			if($mapSearch->rl) {
				$list = $mapSearch->getList($query);
					
				$response['rl'] = $this->renderPartial('@frontend/web/themes/mv_desktop1/views/ad/_partials/side-list', ['searchModel' => $mapSearch, 'list' => $list]);
			}
		}
	*/	
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
				$result = Elastic::searchAreasRankByTotal($v);
				
				foreach ($result['hits']['hits'] as $k => $hit) {
	    			$response[$k] = $hit['_source'];
	    			$response[$k]['url_sale'] = Url::to(['/ad/index1', 'params' => $hit['_source']['slug']]);
	    			$response[$k]['url_rent'] = Url::to(['/ad/index2', 'params' => $hit['_source']['slug']]);
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