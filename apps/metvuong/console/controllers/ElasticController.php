<?php
namespace console\controllers;

use yii\console\Controller;
use frontend\models\Elastic;
use yii\db\ActiveRecord;
use vsoft\ad\models\AdProduct;

class ElasticController extends Controller {
	public function actionBuildIndex() {
		$indexName = 'term';
		
		if($this->indexExist($indexName)) {
			$this->deleteIndex($indexName);
		}
		
		$this->createIndex($indexName);
		
		$cities = $this->getTermFromDb('ad_city');
		
		$cityTermBulk = [];
		
		foreach($cities as $city) {
			$cityTermBulk = array_merge($cityTermBulk, $this->buildTerm($city['id'], $city['name'], $city['name'], $this->countProducts(['city_id' => $city['id']])));
			
			$districts = $this->getTermFromDb('ad_district', ['city_id' => $city['id']]);
			$districtTermBulk = [];
			
			foreach ($districts as $district) {
				$districtName = $district['pre'] ? trim($district['pre'] . ' ' . $district['name']) : $district['name'];
				$districtFullName = $districtName . ', ' . $city['name'];
				$districtTerm = $this->buildTerm($district['id'], $districtName, $districtFullName, $this->countProducts(['district_id' => $district['id']]));
				$districtTerm[1]['city_id'] = $city['id'];
				$districtTermBulk = array_merge($districtTermBulk, $districtTerm);
				
				$this->buildTermsBelongDistrict('ad_ward', $city['id'], $district['id'], $districtFullName, $indexName, 'ward');
				$this->buildTermsBelongDistrict('ad_street', $city['id'], $district['id'], $districtFullName, $indexName, 'street');
				$this->buildTermsBelongDistrict('ad_building_project', $city['id'], $district['id'], $districtFullName, $indexName, 'project_building');
			}
			
			$this->batchInsert($indexName, 'district', $districtTermBulk);
		}
		
		$this->batchInsert($indexName, 'city', $cityTermBulk);
	}
	
	public function buildTermsBelongDistrict($table, $cityId, $districtId, $districtFullName, $indexName, $type) {
		$terms = $this->getTermFromDb($table, ['district_id' => $districtId]);
		
		$termBulk = [];
		
		foreach ($terms as $term) {
			$name = $term['name'];
			$name = empty($term['pre']) ? $name : $term['pre'] . ' ' . $name;
			$fullName = $name . ', ' . $districtFullName;
			
			$buildTerm = $this->buildTerm($term['id'], $name, $fullName, $this->countProducts([$type . "_id" => $term['id']]));
			$buildTerm[1]['city_id'] = $cityId;
			$buildTerm[1]['district_id'] = $districtId;
			
			$termBulk = array_merge($termBulk, $buildTerm);
		}
		
		$this->batchInsert($indexName, $type, $termBulk);
	}
	
	public function batchInsert($indexName, $type, $bulk) {
		$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . "/$indexName/$type/_bulk?pretty");
		
		$temp = [];
		
		foreach ($bulk as $b) {
			$temp[] = json_encode($b);
		}
		
		$bulk = implode("\n", $temp) . "\n";
		
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $bulk);
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
	
	public function createIndex($indexName) {
		$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . '/' . $indexName . '?pretty');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_exec($ch);
		curl_close($ch);
	}
	
	public function deleteIndex($indexName) {
		$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . '/' . $indexName . '?pretty');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_exec($ch);
		curl_close($ch);
	}
	
	private function buildTerm($id, $name, $fullName, $total) {
		$term = [];
		
		$term[] = [
			'index' => [
				'_id' => $id
			]
		];
		
		$term[] = [
			'name'	=> $name,
			'full_name' => $fullName,
			'search_field' => Elastic::transform($fullName),
			'total_sell' => $total['sell'],
			'total_rent' => $total['rent']
		];
		
		return $term;
	}
	
	private function countProducts($where) {
		$products = AdProduct::find()->select('type')->where($where)->asArray(true)->all();
		
		$total = ['sell' => 0, 'rent' => 0];
		
		foreach ($products as $product) {
			if($product['type'] == AdProduct::TYPE_FOR_SELL) {
				$total['sell'] += 1;
			} else {
				$total['rent'] += 1;
			}
		}
		
		return $total;
	}
	
	private function getTermFromDb($table, $where = []) {
		return ActiveRecord::find()->from($table)->where($where)->asArray(true)->all();
	}
}