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
use vsoft\ad\models\TrackingSearch;

class MapController extends ActiveController {
	
	public $modelClass = 'frontend\models\MapSearch';
	
	public function actionSearchProject() {
		$v = \Yii::$app->request->get('v');
		
		$response = [];
		
		$result = Elastic::searchAllProjects($v);
		
		if($result['hits']['total'] == 0) {
			$result = Elastic::searchAllProjects(Elastic::transform($v));
		}

		foreach ($result['hits']['hits'] as $k => $hit) {
			$response[$k]['full_name'] = $hit['_source']['full_name'];
			$response[$k]['id'] = $hit['_id'];
		}
		
		return $response;
	}

	public function actionSearchProject2() {
		$v = \Yii::$app->request->get('v');
//		$result = \Yii::$app->dbCraw->createCommand("SELECT * FROM `ad_building_project` WHERE `name` LIKE '%MASTERI MILLENNIUM%'")->execute();
//		echo "<pre>";
//		print_r($result);
//		echo "</pre>";
//		exit;
		$query = new Query();
		$query->select(['id', 'name']);
		$query->from('ad_building_project')->where(['LIKE', 'name', $v])->limit(3);
		return $query->all(\Yii::$app->dbCraw);
	}

	public function actionGet() {
		$mapSearch = new MapSearch();
		$mapSearch->load(\Yii::$app->request->get());
		
		$response = [];
		
		$result = $mapSearch->search();
		
		if($mapSearch->rl) {
			$list = $result['aggregations']['rl']['hits'];
			
			if(isset($result['aggregations']['top'])) {
				$top = $result['aggregations']['top']['hits'];
			} else {
				$top = [];
			}
			
				
			$response['rl'] = $this->renderPartial('@frontend/web/themes/mv_desktop1/views/ad/_partials/side-list', ['searchModel' => $mapSearch, 'list' => $list, 'top' => $top]);
		}
		
		if($mapSearch->ra) {
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
					$areas = ArrayHelper::map($result['aggregations']['ra']['buckets'], "key", "doc_count");
						
					if(isset($areas[0])) {
						unset($areas[0]);
					}
						
					$table = 'ad_' . $mapSearch->ra;
					$value = ($key == 'id') ? $mapSearch->getAttribute($mapSearch->ra . '_id') : $mapSearch->getAttribute($key);
					
					$areasDb = (new Query())->select("id, center, geometry, name, pre")->from($table)->where([$key => $value])->all();
						
					foreach ($areasDb as &$area) {
						if(isset($areas[$area['id']])) {
							$area['total'] = $areas[$area['id']];
						} else {
							$area['total'] = 0;
						}
					}
						
					$response['ra'] = $areasDb;
				}
			}
		}
		
		if($mapSearch->rm) {
			$products = [];
			
			foreach ($result['aggregations']['rm']['hits']['hits'] as $hit) {
				$products[] = array_values($hit['_source']);
			}
			
			$response['rm'] = $products;
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
		$v = \Yii::$app->request->get('v');
		$mv = (stripos($v, 'mv') === 0);
		
		if(\Yii::$app->request->isAjax) {
			$response = [];
			
			if($mv) {
				$response = $this->searchProductStripMv($v);
			} else {
				$response = $this->searchAjax($v);
				
				if(empty($response) && is_numeric($v)) {
					$response = $this->searchProduct($v);
				}
			}
			
			return $response;
		} else {
			if($mv) {
				$response = $this->searchProductStripMv($v);
				$url = $response ? $response['url'] : false;
			} else {
				$url = $this->searchEnter($v);
				
				if(!$url && is_numeric($v)) {
					$response = $this->searchProductStripMv($v);
					$url = $response ? $response['url'] : false;
				}
			}
				
			if($url) {
				return $this->redirect($url);
			} else {
				return $this->redirect(Url::to(['/ad/index1']));
			}
		}
	}
	
	function searchAjax($v) {
		$result = $this->search($v);
		$return = [];
		
		foreach ($result['hits']['hits'] as $k => $hit) {
			$return[$k] = $hit['_source'];
			$return[$k]['url_sale'] = Url::to(['/ad/index1', 'params' => $hit['_source']['slug'], 'tf' => TrackingSearch::FROM_QUICK_SEARCH]);
			$return[$k]['url_rent'] = Url::to(['/ad/index2', 'params' => $hit['_source']['slug'], 'tf' => TrackingSearch::FROM_QUICK_SEARCH]);
			$return[$k]['type'] = $hit['_type'];
			$return[$k]['id'] = $hit['_id'];
		}
			
		return $return;
	}
	
	function searchEnter($v) {
		$result = $this->search($v);
		
		return $result['hits']['total'] > 0 ? Url::to(['/ad/index1', 'params' => $result['hits']['hits'][0]['_source']['slug'], 'tf' => TrackingSearch::FROM_QUICK_SEARCH]) : false;
	}
	
	function search($v) {
		$result = Elastic::searchAreasRankByTotal($v);
		
		if($result['hits']['total'] == 0) {
			$result = Elastic::searchAreasRankByTotal(Elastic::transform($v));
		}
		
		return $result;
	}
	
	function searchProductStripMv($v) {
		$id = str_ireplace('mv', '', $v);
		
		return $this->searchProduct($id);
	}
	
	function searchProduct($id) {
		if($product = AdProduct::find()->where(['id' => $id])->andWhere(['!=', 'status', AdProduct::STATUS_DELETE])->one()) {
			return ['address' => $product->address, 'url' => $product->urlDetail()];
		} else {
			return [];
		}
	}
}