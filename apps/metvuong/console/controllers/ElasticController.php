<?php
namespace console\controllers;

use yii\console\Controller;
use frontend\models\Elastic;
use yii\db\ActiveRecord;
use vsoft\ad\models\AdProduct;
use yii\db\Query;

class ElasticController extends Controller {
	
	public function actionBuildIndex() {
		$indexName = \Yii::$app->params['indexName']['countTotal'];
		
		if(!$indexName) {
			echo 'missing param indexName';
			
			return;
		}
		
		if($this->indexExist($indexName)) {
			$this->deleteIndex($indexName);
		}
		
		$this->createIndex($indexName);
		
		$countByCity = $this->countProducts('city_id');
		$countByDistrict = $this->countProducts('district_id');
		$countByWard = $this->countProducts('ward_id');
		$countByStreet = $this->countProducts('street_id');
		$countByProjectBuilding = $this->countProducts('project_building_id');
		
		$cities = $this->getAreas('ad_city');
		
		$cityTermBulk = [];
		
		foreach ($cities as $city) {
			if(isset($countByCity[$city['id']])) {
				$totalSell = $countByCity[$city['id']][AdProduct::TYPE_FOR_SELL_TOTAL];
				$totalRent = $countByCity[$city['id']][AdProduct::TYPE_FOR_RENT_TOTAL];
			} else {
				$totalSell = 0;
				$totalRent = 0;
			}
			
			$cityTermBulk = array_merge($cityTermBulk, $this->buildTerm($city['id'], $city['id'], $city['name'], $city['name'], $totalSell, $totalRent, $city['slug']));
		}
		
		$this->batchInsert($indexName, 'city', $cityTermBulk);
		
		foreach ($cities as $city) {
			$districts = $this->getAreas('ad_district', ['city_id' => $city['id']]);
			$districtTermBulk = [];
			
			foreach ($districts as $district) {
				if(isset($countByDistrict[$district['id']])) {
					$totalSell = $countByDistrict[$district['id']][AdProduct::TYPE_FOR_SELL_TOTAL];
					$totalRent = $countByDistrict[$district['id']][AdProduct::TYPE_FOR_RENT_TOTAL];
				} else {
					$totalSell = 0;
					$totalRent = 0;
				}
				
				$districtName = $district['pre'] ? trim($district['pre'] . ' ' . $district['name']) : $district['name'];
				$districtFullName = $districtName . ', ' . $city['name'];
				$districtSlug = $city['slug'] . '/' . $district['slug'];
				
				$districtTermBulk = array_merge($districtTermBulk, $this->buildTerm($district['id'], $city['id'], $districtName, $districtFullName, $totalSell, $totalRent, $districtSlug));
				
				$this->buildTermsBelongDistrict('ad_ward', $city['id'], $district['id'], $districtFullName, $indexName, 'ward', $countByWard, $districtSlug, 'w');
				$this->buildTermsBelongDistrict('ad_street', $city['id'], $district['id'], $districtFullName, $indexName, 'street', $countByStreet, $districtSlug, 's');
				$this->buildTermsBelongDistrict('ad_building_project', $city['id'], $district['id'], $districtFullName, $indexName, 'project_building', $countByProjectBuilding, $districtSlug, 'p');
			}
			
			$this->batchInsert($indexName, 'district', $districtTermBulk);
		}
	}
	
	private function countProducts($countBy) {
		$where = [
			'status' => 1,
			'is_expired' => 0,
			//'verified' => 1	
		];
		
		$query = new Query();
		
		$query->from('ad_product');
		$query->where($where);
		$query->select([
			$countBy,
			"SUM(CASE WHEN `type` = 1 THEN 1 ELSE 0 END) AS " . AdProduct::TYPE_FOR_SELL_TOTAL,
			"SUM(CASE WHEN `type` = 2 THEN 1 ELSE 0 END) AS " . AdProduct::TYPE_FOR_RENT_TOTAL
		]);
		$query->groupBy($countBy);
		$query->indexBy($countBy);
		
		return $query->all();
	}
	
	public function buildTermsBelongDistrict($table, $cityId, $districtId, $districtFullName, $indexName, $type, $counts, $districtSlug, $p) {
		$terms = $this->getAreas($table, ['district_id' => $districtId]);
		
		$termBulk = [];
		
		foreach ($terms as $term) {
			$name = $term['name'];
			$name = empty($term['pre']) ? $name : $term['pre'] . ' ' . $name;
			$fullName = $name . ', ' . $districtFullName;
			
			if(isset($counts[$term['id']])) {
				$totalSell = $counts[$term['id']][AdProduct::TYPE_FOR_SELL_TOTAL];
				$totalRent = $counts[$term['id']][AdProduct::TYPE_FOR_RENT_TOTAL];
			} else {
				$totalSell = 0;
				$totalRent = 0;
			}
			
			$buildTerm = $this->buildTerm($term['id'], $cityId, $name, $fullName, $totalSell, $totalRent, $districtSlug . '/' . $p . '_' . $term['id']);
			
			$buildTerm[1]['district_id'] = intval($districtId);
			
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
					'slug' => [
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
						"analyzer" => "my_synonyms"
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
		
		$synonyms = ["hcm,ho chi minh", "cmt8,cach mang thang tam", "1,mot", "2,hai", "3,ba", "4,bon", "5,nam", "6,sau", "7,bay", "8,tam", "9,chin", "q,quan", "p,phuong"];
		$settings = [
			'analysis' => [
				'char_filter' => [
					'my_char_filter' => [
						'type' => 'mapping',
						'mappings' => ["/=>slash"]
					]	
				],
				'filter' => [
					'my_synonym_filter' => [
						'type' => 'synonym',
						'synonyms' => $synonyms
					]
				],
				'analyzer' => [
					'my_synonyms' => [
						'char_filter' => 'my_char_filter',
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
	
	private function buildTerm($id, $cityId, $name, $fullName, $totalSell, $totalRent, $slug) {
		$term = [];
		
		$term[] = [
			'index' => [
				'_id' => intval($id)
			]
		];
		
		$term[] = [
			'name'	=> $name,
			'slug' => $slug,
			'search_name' => Elastic::transform($name),
			'full_name' => $fullName,
			'search_field' => Elastic::transform(str_replace(',', '', $fullName)),
			AdProduct::TYPE_FOR_SELL_TOTAL => intval($totalSell),
			AdProduct::TYPE_FOR_RENT_TOTAL => intval($totalRent),
			'city_id' => intval($cityId)
		];
		
		return $term;
	}
	
	private function getAreas($table, $where = []) {
		return ActiveRecord::find()->from($table)->where($where)->asArray(true)->all();
	}
}