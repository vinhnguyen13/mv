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
		// $cities = $this->getTermFromDb('ad_city', ['id' => 1]);
		
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
		
		$totalCityBulk = count($cityTermBulk);
		
		for($i = 0; $i < $totalCityBulk; $i += 2) {
			$cityTermBulk[$i+1]['city_id'] = $cityTermBulk[$i]['index']['_id'];
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
		
		$mappings = [
			'_default_' => [
				'properties' => [
					'name' => [
						'type' => 'string',
						'index' => 'no'
					],
					'search_name' => [
						'type' => 'string',
						"analyzer" => "my_synonyms"
					],
					'full_name' => [
						'type' => 'string',
						'index' => 'no'
					],
					'search_field' => [
						'type' => 'string',
						"analyzer" => "my_synonyms",
						"index_options" => "docs"
					],
					'total_sell' => [
						'type' => 'integer'
					],
					'total_rent' => [
						'type' => 'integer'
					],
					'city_id' => [
						'type' => 'integer'
					],
					'district_id' => [
						'type' => 'integer'
					]
				]
			]
		];
		
		$synonyms = ["hcm,ho chi minh", "cmt,cach mang thang", "1,mot,nhat", "2,hai", "3,ba", "4,bon", "5,nam", "6,sau", "7,bay", "8,tam", "9,chin", "q,quan", "p,phuong"];
		$settings = [
			'analysis' => [
				'filter' => [
					'my_synonym_filter' => [
						'type' => 'synonym',
						'synonyms' => $synonyms
					]
				],
				'analyzer' => [
					'my_synonyms' => [
						'tokenizer' => 'standard',
						'filter' => ['lowercase', 'my_synonym_filter']
					]
				]
			]	
		];
		
		$config = [
			'mappings' => $mappings,
			'settings' => $settings
		];
		
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($config));
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
				'_id' => intval($id)
			]
		];
		
		$term[] = [
			'name'	=> $name,
			'search_name' => Elastic::transform($name),
			'full_name' => $fullName,
			'search_field' => Elastic::transform($fullName),
			AdProduct::TYPE_FOR_SELL_TOTAL => $total['sell'],
			AdProduct::TYPE_FOR_RENT_TOTAL => $total['rent']
		];
		
		return $term;
	}
	
	private function countProducts($where) {
		$where['status'] = 1;
		$where['is_expired'] = 0;
		$where['verified'] = 1;
		
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