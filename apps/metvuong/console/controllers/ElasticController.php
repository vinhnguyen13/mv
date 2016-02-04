<?php
namespace console\controllers;

use yii\console\Controller;
use frontend\models\Elastic;
use yii\db\ActiveRecord;
use vsoft\ad\models\AdProduct;

class ElasticController extends Controller {
	public function actionBuildIndex() {
// 		$elastic = new Elastic();
// 		$elastic->connect();
		
// 		$params = [
// 			'index' => 'term',
// 			'type' => 'city',
// 			'id' => 1,
// 			'body' => [
// 				'name' => 'abc',
// 				'nor' => 'abc'
// 			]
// 		];
		
// 		$elastic->index($params);
		
// 		$params = [
// 			'index' => 'term',
// 			'type' => 'city',
// 			'body' => [
// 				'query' => [
// 					'match_all' => [],
// 				]
// 			],
// 		];
		
// 		$result = $elastic->search($params);
		
// 		var_dump($result);
		$elastic = new Elastic();
		$elastic->connect();
		
		$cities = $this->getTermFromDb('ad_city');
		
		foreach($cities as $city) {
			$cityTerm = $this->buildTerm('city', $city['id'], $city['name'], $city['name'], intval($this->countProducts(['city_id' => $city['id']])));
			$elastic->index($cityTerm);
			
			$districts = $this->getTermFromDb('ad_district', ['city_id' => $city['id']]);
			
			foreach ($districts as $district) {
				$districtName = $district['pre'] ? $district['pre'] . ' ' . $district['name'] : $district['name'];
				$districtFullName = $districtName . ', ' . $city['name'];
				$districtTerm = $this->buildTerm('district', $district['id'], $districtName, $districtFullName, intval($this->countProducts(['district_id' => $district['id']])));
				$elastic->index($districtTerm);
				
				$this->buildTermsBelongDistrict('ad_ward', 'ward', $district['id'], $districtFullName);
				$this->buildTermsBelongDistrict('ad_street', 'street', $district['id'], $districtFullName);
				$this->buildTermsBelongDistrict('ad_building_project', 'project_building', $district['id'], $districtFullName, false);
			}
		}
	}
	public function actionRebuildIndex() {
		$this->actionRemoveIndex();
		$this->actionBuildIndex();
	}
	public function actionRemoveIndex() {
		
	}
	public function actionRebuildTotal() {
		
	}
	private function updateTotal($id, $type, $total) {
		
	}
	private function addTerm($type, $term) {
		
	}
	private function addTerms($type, $terms) {
		
	}
	private function buildTermsBelongDistrict($table, $type, $districtId, $districtFullName, $hasPre = true) {
		$elastic = new Elastic();
		$elastic->connect();
		
		$terms = $this->getTermFromDb($table, ['district_id' => $districtId]);
		
		foreach ($terms as $term) {
			$name = $term['name'];
			
			if($hasPre) {
				$name = $term['pre'] ? $term['pre'] . ' ' . $name : $name;
			}
			
			$fullName = $name . ', ' . $districtFullName;
			
			$buildTerm = $this->buildTerm($type, $term['id'], $name, $fullName, intval($this->countProducts([$type . "_id" => $term['id']])));
			
			$elastic->index($buildTerm);
		}
	}
	private function getTermFromDb($table, $where = []) {
		return ActiveRecord::find()->from($table)->where($where)->asArray(true)->all();
	}
	private function countProducts($where) {
		$p = AdProduct::find()->select(['count' => 'COUNT(*)'])->where($where)->asArray(true)->one();
		return $p['count'];
	}
	private function buildTerm($type, $id, $name, $fullName, $total = 0) {
		return $params = [
			'index' => 'term',
			'type' => $type,
			'id' => $id,
			'body' => [
				'full_name' 	=> $fullName,
				'name'			=> $name,
				'search_field'	=> Elastic::transform($name),
				'total'			=> $total
			]
		];
	}
}