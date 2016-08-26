<?php
namespace console\controllers;

use yii\console\Controller;
use frontend\models\Elastic;
use yii\db\ActiveRecord;
use vsoft\ad\models\AdProduct;
use yii\db\Query;
use common\models\SlugSearch;
use yii\db\yii\db;
use yii\helpers\ArrayHelper;

class ElasticController extends Controller {
	
	private $tCity = 'city';
	private $tDistrict = 'district';
	private $tWard = 'ward';
	private $tStreet = 'street';
	private $tProject = 'project_building';
	private $tProjectId = 'project_building_id';
	private $tSplit = ', ';
	private $tSpace = ' ';
	private $tCityTable = '';
	private $tDistrictTable = '';
	private $tWardTable = '';
	private $tStreetTable = '';
	private $tProjectTable = 'ad_building_project';
	private $tcityId = '';
	private $tDistrictId = '';
	private $tWardId = '';
	private $tStreetId = '';
	
	public function init() {
		$this->tCityTable = 'ad_' . $this->tCity;
		$this->tDistrictTable = 'ad_' . $this->tDistrict;
		$this->tWardTable = 'ad_' . $this->tWard;
		$this->tStreetTable = 'ad_' . $this->tStreet;
		$this->tcityId = $this->tCity . '_id';
		$this->tDistrictId = $this->tDistrict . '_id';
		$this->tWardId = $this->tWard . '_id';
		$this->tStreetId = $this->tStreet . '_id';
	}
	
	public static $where = [
		'`ad_product`.`status`' => 1,
		'`ad_product`.`is_expired`' => 0,
		//'`ad_product`.`verified`' => 1
	];
	
	public function actionFindNotSync() {
		$indexName = \Yii::$app->params['indexName']['product'];
		
		$query = new Query();
		$query->from('ad_product');
		$query->select('id');
		$query->where(self::$where);
		$query->andWhere(['city_id' => 1, 'type' => 1]);
		$result = $query->all();
		$ids = ArrayHelper::getColumn($result, 'id');
		
		foreach ($ids as $id) {
			$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . "/$indexName/all/" . $id);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$response = curl_exec($ch);
			$r = json_decode($response);
			if(!$r->found) {
				echo $id . "\n";
			}
			curl_close($ch);
		}		
	}
	
	public function actionListIndex() {
		$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . '/_cat/indices?v');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$result = curl_exec($ch);
		
		echo $result;
	}
	
	public function actionDeleteIndex($indexName) {
		$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . '/' . $indexName);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$result = curl_exec($ch);
		
		echo $result;
	}
	
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
		
		$cities = $this->getAreas($this->tCityTable);
		$countCities = $this->countProducts($this->tcityId, $this->tCityTable);
		$termsCity = "";
		
		foreach ($cities as $city) {
			if(isset($countCities[$city['id']])) {
				$cityTotalSell = intval($countCities[$city['id']][AdProduct::TYPE_FOR_SELL_TOTAL]);
				$cityTotalRent = intval($countCities[$city['id']][AdProduct::TYPE_FOR_RENT_TOTAL]);
			} else {
				$cityTotalSell = 0;
				$cityTotalRent = 0;
			}
			
			$cityNamePrefix = $city['pre'] . ' ' . $city['name'];
			
			$cityDocument = [
				'full_name' => $city['name'],
				'slug' => $city['slug'],
				'total_sell' => $cityTotalSell,
				'total_rent' => $cityTotalRent,
				'city_id' => $city['id'],
				'search_name' => $city['name'],
				'search_name_with_prefix' => $city['name'], // not $cityNamePrefix thành phố mà thêm prefix sẽ giảm tính cạnh tranh khi search,
				'search_name_full_text' => $cityNamePrefix,
				'search_name_full_text_no_ngram' => $cityNamePrefix,
				'search_full_name' => $cityNamePrefix,
				'search_full_name_no_ngram' => $cityNamePrefix
			];
			$termsCity .= $this->buildTerm($city['id'], $cityDocument);
			
			$districts = $this->getAreas($this->tDistrictTable, [$this->tcityId => $city['id']]);
			$countDistricts = $this->countProducts($this->tDistrictId, $this->tDistrictTable);
			
			$termsDistrict = "";
			
			foreach ($districts as $district) {
				$nameWithPrefix = trim($district['pre'] . $this->tSpace . $district['name']);
				$districtFullName = $nameWithPrefix . $this->tSplit . $city['name'];
				
				if(isset($countDistricts[$district['id']])) {
					$districtTotalSell = intval($countDistricts[$district['id']][AdProduct::TYPE_FOR_SELL_TOTAL]);
					$districtTotalRent = intval($countDistricts[$district['id']][AdProduct::TYPE_FOR_RENT_TOTAL]);
				} else {
					$districtTotalSell = 0;
					$districtTotalRent = 0;
				}
				
				$nameWithPrefixStandardSearch = Elastic::standardSearchDistrict($nameWithPrefix);
				$searchFullName = $nameWithPrefixStandardSearch . $this->tSpace . $cityNamePrefix;
				
				$districtDocument = [
					'full_name' => $districtFullName,
					'slug' => $district['slug'],
					'total_sell' => $districtTotalSell,
					'total_rent' => $districtTotalRent,
					'city_id' => $city['id'],
					'district_id' => $district['id'],
					'search_name' => $district['name'],
					'search_name_with_prefix' => $nameWithPrefix,
					'search_name_full_text' => $nameWithPrefixStandardSearch,
					'search_name_full_text_no_ngram' => $nameWithPrefixStandardSearch,
					'search_full_name' => $searchFullName,
					'search_full_name_no_ngram' => $searchFullName
				];
				
				$termsDistrict .= $this->buildTerm($district['id'], $districtDocument);

				$this->buildTermsBelongDistrict($this->tWardTable, $this->tWardId, $this->tWard, $city['id'], $district['id'], $districtFullName, $searchFullName, $indexName, 'standardSearchWard');
				$this->buildTermsBelongDistrict($this->tStreetTable, $this->tStreetId, $this->tStreet, $city['id'], $district['id'], $districtFullName, $searchFullName, $indexName);
				$this->buildTermsBelongDistrict($this->tProjectTable, $this->tProjectId, $this->tProject, $city['id'], $district['id'], $districtFullName, $searchFullName, $indexName, 'standardSearch', 'buildFullNameProject');
			}
			
			$this->batchInsert($indexName, $this->tDistrict, $termsDistrict);
		}
		
		$this->batchInsert($indexName, $this->tCity, $termsCity);
		
		/*
		 * Update setting
		 */
		$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . "/$indexName/_settings");
		$params = [
			'index' => [
				'refresh_interval' => '1s',
				'number_of_replicas' => 1
			]
		];
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_exec($ch);
		curl_close($ch);
	}
	
	private function countProducts($countBy, $table) {
		$where = self::$where;
		
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
	
	public function buildTermsBelongDistrict($table, $columnId, $type, $cityId, $districtId, $districtFullName, $searchFullName, $indexName, $ss = 'standardSearch', $fn = 'buildFullName') {
		$areas = $this->getAreas($table, ['district_id' => $districtId]);
		$countAreas = $this->countProducts($columnId, $table);
		$terms = "";
		
		foreach ($areas as $area) {
			if(isset($countAreas[$area['id']])) {
				$totalSell = intval($countAreas[$area['id']][AdProduct::TYPE_FOR_SELL_TOTAL]);
				$totalRent = intval($countAreas[$area['id']][AdProduct::TYPE_FOR_RENT_TOTAL]);
			} else {
				$totalSell = 0;
				$totalRent = 0;
			}
				
			$namePrefix = $area['pre'] . ' ' . $area['name'];
			$nameFulltext = call_user_func([Elastic::class, $ss], $namePrefix);
			$searchFullFullName = $nameFulltext . $this->tSpace . $searchFullName;
			
			$document = [
				'full_name' => call_user_func([$this, $fn], $area['pre'], $area['name'], $districtFullName),
				'slug' => $area['slug'],
				'total_sell' => $totalSell,
				'total_rent' => $totalRent,
				'city_id' => intval($cityId),
				'district_id' => intval($districtId),
				'search_name' => $area['name'],
				'search_name_with_prefix' => $namePrefix,
				'search_name_full_text' => $nameFulltext,
				'search_name_full_text_no_ngram' => $nameFulltext,
				'search_full_name' => $searchFullFullName,
				'search_full_name_no_ngram' => $searchFullFullName
			];
			$terms .= $this->buildTerm($area['id'], $document);
		}
		$this->batchInsert($indexName, $type, $terms);
	}
	
	public function buildFullName($pre, $name, $districtFullName) {
		return $pre . ' ' . $name . $this->tSplit . $districtFullName;
	}
	
	public function buildFullNameProject($pre, $name, $districtFullName) {
		return $name . $this->tSplit . $districtFullName;
	}
	
	public function batchInsert($indexName, $type, $bulk) {
		$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . "/$indexName/$type/_bulk");
		
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
					'full_name' => [
						'type' => 'string',
						'index' => 'no'
					],
					'slug' => [
						'type' => 'string',
						'index' => 'no'
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
					],
					'search_name' => [
						'type' => 'string',
						'analyzer' => 'keyword_search',
						'search_analyzer' => 'search'
					],
					'search_name_with_prefix' => [
						'type' => 'string',
						'analyzer' => 'keyword_search',
						'search_analyzer' => 'search'
					],
					'search_name_full_text' => [
						'type' => 'string',
						'analyzer' => 'full_text_search',
						'search_analyzer' => 'my_simple_search'
					],
					'search_name_full_text_no_ngram' => [
						'type' => 'string',
						'analyzer' => 'full_text_search_no_ngram',
						'search_analyzer' => 'my_simple_search'
					],
					'search_full_name' => [
						'type' => 'string',
						'analyzer' => 'full_text_search',
						'search_analyzer' => 'my_simple_search'
					],
					'search_full_name_no_ngram' => [
						'type' => 'string',
						'analyzer' => 'full_text_search_no_ngram',
						'search_analyzer' => 'my_simple_search'
					]
				]
			]
		];
		
		$synonyms = ["cách mạng tháng tám,cmt8", "hồ chí minh,hcm", "hà nội,hn", "nguyễn thị minh khai,ntmk", "xô viết nghệ tĩnh,xvnt"];
		$synonymsNumber = ["1,một,nhất", "1=>mot,nhat", "2,hai", "3,ba", "4,bốn,tư", "4=>bon,tu", "5,năm", "5=>nam", "6,sáu", "6=>sau", "7,bảy", "7=>bay", "8,tám", "8=>tam", "9,chín", "9=>chin", "10,mười", "10=>muoi"];
		$settings = [
			'analysis' => [
				'filter' => [
					'synonym' => [
						'type' => 'synonym',
						'synonyms' => $synonyms
					],
					'my_ascii_folding' => [
						'type' => 'asciifolding',
						'preserve_original' => true
					],
					'my_edge_ngram' => [
						'type' => 'edge_ngram',
						'min_gram' => 1,
						'max_gram' => 1000
					],
					'synonym_number' => [
						'type' => 'synonym',
						'synonyms' => $synonymsNumber
					]
				],
				'analyzer' => [
					'full_text_search' => [
						'tokenizer' => 'whitespace',
						'filter' => ['lowercase', 'synonym', 'my_ascii_folding', 'my_edge_ngram', 'unique', 'synonym_number']
					],
					'full_text_search_no_ngram' => [
						'tokenizer' => 'whitespace',
						'filter' => ['lowercase', 'synonym', 'my_ascii_folding', 'unique', 'synonym_number']
					],
					'keyword_search' => [
						'tokenizer' => 'keyword',
						'filter' => ['lowercase', 'my_ascii_folding', 'my_edge_ngram', 'trim', 'unique']
					],
					'search' => [
						'tokenizer' => 'keyword',
						'filter' => ['lowercase']
					],
					'my_simple_search' => [
						'tokenizer' => 'whitespace',
						'filter' => ['lowercase']
					]
				]
			],
			'index' => [
				'refresh_interval' => '-1',
				'number_of_replicas' => 0
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
	
	private function buildTerm($id, $document) {
		$term = json_encode([
			'index' => [
				'_id' => intval($id)
			]
		]);
		
		$term .= "\n" . json_encode($document, JSON_UNESCAPED_UNICODE) . "\n";

		return $term;
	}
	
	private function getAreas($table, $where = []) {
		$select = ["`$table`.`id`", "`$table`.`name`", "`$table`.`pre`", "`slug_search`.`slug`"];
		
		if($table == $this->tProjectTable) {
			$select = ["`$table`.`id`", "`$table`.`name`", "'Dự án' AS `pre`", "`slug_search`.`slug`"];
		} else {
			$select = ["`$table`.`id`", "`$table`.`name`", "`$table`.`pre`", "`slug_search`.`slug`"];
		}
		
		$query = new Query();
		$query->select($select);
		$query->from($table);
		$query->leftJoin("(SELECT * FROM `slug_search` WHERE `slug_search`.`table` = '$table') AS `slug_search`", "`slug_search`.`value` = `$table`.`id`");
		$query->where($where);
		
		return $query->all();
	}
}