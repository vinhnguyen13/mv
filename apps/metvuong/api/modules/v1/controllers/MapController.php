<?php
namespace api\modules\v1\controllers;

use yii\rest\Controller;
use frontend\models\MapSearch;
use yii\db\Query;
use frontend\models\frontend\models;

class MapController extends Controller {
	public function actionSearch() {
		
		$mapSearch = new MapSearch();
		
		$query = $mapSearch->search(\Yii::$app->request->get());

		$response = [];
		
		if($mapSearch->rm) {
			$markerQuery = clone $query;
			
			$markerQuery->limit(500);
				
			$response['rm'] = $markerQuery->all();
		}
		
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
		
		if($mapSearch->rl) {
			$list = $mapSearch->getList($query);
			
			$response['rl'] = $this->renderPartial('@frontend/web/themes/mv_desktop1/views/ad/_partials/list', $list);
		}
		
		return $response;
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
}