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
	private $tCityId = '';
	private $tDistrictId = '';
	private $tWardId = '';
	private $tStreetId = '';
	
	public function init() {
		$this->tCityTable = 'ad_' . $this->tCity;
		$this->tDistrictTable = 'ad_' . $this->tDistrict;
		$this->tWardTable = 'ad_' . $this->tWard;
		$this->tStreetTable = 'ad_' . $this->tStreet;
		$this->tCityId = $this->tCity . '_id';
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
		$districts = $this->getAreas($this->tDistrictTable, [$this->tCityId]);
		$wards = $this->getAreas($this->tWardTable, [$this->tCityId, $this->tDistrictId]);
		$streets = $this->getAreas($this->tStreetTable, [$this->tCityId, $this->tDistrictId]);
		$projects = $this->getAreas($this->tProjectTable, [$this->tCityId, $this->tDistrictId]);
		
		$cityTerm = "";
		foreach ($cities as &$city) {
			$city = $this->buildCityDocument($city);
			$cityTerm .= $this->buildTerm($city[$this->tCityId], $city);
		}
		$this->batchInsert($indexName, $this->tCity, $cityTerm);
		
		$districtTerm = "";
		foreach ($districts as &$district) {
			$district = $this->buildDistrictDocument($district, $cities[$district[$this->tCityId]]);
			$districtTerm .= $this->buildTerm($district[$this->tDistrictId], $district);
		}
		$this->batchInsert($indexName, $this->tDistrict, $districtTerm);
		
		$wardTerm = "";
		foreach ($wards as $ward) {
			$wardTerm .= $this->buildTerm($ward['id'], $this->buildDocument($ward, $districts[$ward[$this->tDistrictId]], $cities[$ward[$this->tCityId]], true));		
		}
		$this->batchInsert($indexName, $this->tWard, $wardTerm);
		
		$streetTerm = "";
		foreach ($streets as $street) {
			$streetTerm .= $this->buildTerm($street['id'], $this->buildDocument($street, $districts[$street[$this->tDistrictId]], $cities[$street[$this->tCityId]]));
		}
		$this->batchInsert($indexName, $this->tStreet, $streetTerm);
		
		$projectTerm = "";
		foreach ($projects as $project) {
			$projectTerm .= $this->buildTerm($project['id'], $this->buildProjectDocument($project, $districts[$project[$this->tDistrictId]], $cities[$project[$this->tCityId]]));
		}
		$this->batchInsert($indexName, $this->tProject, $projectTerm);
		
		/*
		 * Update Counter
		 */
		$this->updateCounter('tCity', $indexName);
		$this->updateCounter('tDistrict', $indexName);
		$this->updateCounter('tWard', $indexName);
		$this->updateCounter('tStreet', $indexName);
		$this->updateCounter('tProject', $indexName);
		
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
	
	private function updateCounter($t, $indexName) {
		$id = $t . 'Id';
		$table = $t . 'Table';
		$type = $this->$t;
		
		$counts = $this->countProducts($this->$id, $this->$table);
		
		$bulk = "";
		
		foreach ($counts as $i => $count) {
			$bulk .= '{ "update" : {"_id" : "' . $i . '"} }';
			$bulk .= "\n";
			$bulk .= '{ "doc" : {"total_sell" : ' . $count['total_sell'] . ', "total_rent": ' . $count['total_rent'] . '} }';
			$bulk .= "\n";
		}
		
		$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . "/$indexName/$type/_bulk");
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $bulk);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_exec($ch);
		curl_close($ch);
	}
	
	public function buildCityDocument($city) {
		$name = $city['name'];
		$nameWithPrefix = $city['pre'] . ' ' . $name;
		$acronym = Elastic::acronym($name);
		
		return [
			'full_name' => $name,
			'slug' => $city['slug'],
			'total_sell' => 0,
			'total_rent' => 0,
			'city_id' => $city['id'],
			's1' => $name,
			's2' => $name,
			's3' => $nameWithPrefix,
			's4' => $acronym,
			's5' => $acronym,
			's6' => $acronym,
			's7' => $nameWithPrefix,
			's8' => $nameWithPrefix,
			's9' => $nameWithPrefix,
			's10' => $nameWithPrefix
		];
	}
	
	public function buildDistrictDocument($district, $city) {
		$name = $district['name'];
		$nameWithPrefix = $district['pre'] . ' ' . $name;
		$fullName = $nameWithPrefix . $this->tSplit . $city['full_name'];
		$acronym = ctype_digit($name) ? Elastic::acronym($nameWithPrefix) . $this->tSpace . Elastic::acronym($name) : Elastic::acronym($name);
		$acronymFullName1 = $acronym . $this->tSpace . $city['s4'];
		$nameFullText = Elastic::standardSearchDistrict($nameWithPrefix);
		$fullNameSearch = $nameFullText . $this->tSpace . $city['full_name'];
		
		return [
			'full_name' => $fullName,
			'slug' => $district['slug'],
			'total_sell' => 0,
			'total_rent' => 0,
			'city_id' => $city['city_id'],
			'district_id' => $district['id'],
			's1' => $name,
			's2' => $name,
			's3' => $nameWithPrefix,
			's4' => $acronym,
			's5' => $acronymFullName1,
			's6' => $acronymFullName1,
			's7' => $nameFullText,
			's8' => $fullNameSearch,
			's9' => $fullNameSearch,
			's10' => $fullNameSearch
		];
	}
	
	public function buildDocument($item, $district, $city, $isWard = false) {
		$name = $item['name'];
		$nameWithPrefix = $item['pre'] . ' ' . $name;
		$fullName = $nameWithPrefix . $this->tSplit . $district['full_name'];
		
		if($isWard) {
			$acronym = ctype_digit($name) ? Elastic::acronym($nameWithPrefix) . $this->tSpace . Elastic::acronym($name) : Elastic::acronym($name);
			$nameFullText = Elastic::standardSearchWard($nameWithPrefix);
		} else {
			$acronym = Elastic::acronym($name);
			$nameFullText = Elastic::standardSearch($nameWithPrefix);
		}
		
		$acronymFullName1 = $acronym . $this->tSpace . $city['s4'];
		$acronymFullName = $acronym . $this->tSpace . $district['s5'];
		
		$fullName1 = $nameFullText . $this->tSpace . $city['full_name'];
		$fullNameSearch = $nameFullText . $this->tSpace . $district['s9'];
		
		return [
			'full_name' => $fullName,
			'slug' => $item['slug'],
			'total_sell' => 0,
			'total_rent' => 0,
			'city_id' => $district['city_id'],
			'district_id' => $district['district_id'],
			's1' => $name,
			's2' => $name,
			's3' => $nameWithPrefix,
			's4' => $acronym,
			's5' => $acronymFullName1,
			's6' => $acronymFullName,
			's7' => $nameFullText,
			's8' => $fullName1,
			's9' => $fullNameSearch,
			's10' => $fullNameSearch
		];
	}
	
	public function buildProjectDocument($project, $district, $city) {
		$document = $this->buildDocument($project, $district, $city);
		
		$document['full_name'] = mb_substr($document['full_name'], 6, NULL, 'UTF-8');

		return $document;
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
					/*
					 * Tên - chia theo khoảng trắng
					 * Hồ Chí Minh -> hồ,chí,minh,ho,chi,minh
					 * 1 -> 1
					 * Tân Bình -> tân,bình,tan,binh 
					 */
					's1' => [
						'type' => 'string',
						'analyzer' => 'standard_search',
						'search_analyzer' => 'search'
					],
					/*
					 * Tên: theo keyword + ngram
					 * Hồ Chí Minh -> h,hồ,hồ c,hồ ch,.....,ho chi minh
					 */
					's2' => [
						'type' => 'string',
						'analyzer' => 'keyword_search',
						'search_analyzer' => 'simple_search'
					],
					/*
					 * Prefix + Tên - shingle + ngram
					 * Hồ Chí Minh -> h,hồ,hồ c,....,c,ch,chí,chí m,chí mi....minh
					 */
					's3' => [
						'type' => 'string',
						'analyzer' => 'keyword_shingle_search',
						'search_analyzer' => 'simple_search'
					],
					/*
					 * Tên viết tắt không có prefix - Xử lý thêm bằng php có ngram
					 * PHP: Hồ Chí Minh -> hcm, Đối với quận, phường + số -> q1, p1
					 * hcm -> h,hc,hcm
					 */
					's4' => [
						'type' => 'string',
						'analyzer' => 'full_text_search',
						'search_analyzer' => 'search'
					],
					/*
					 * Full Name 1 (Tên + thêm Thành phố) viết tắt - Xử lý thêm bằng php có ngram
					 * PHP: Quận 1, Hồ Chí Minh -> quan 1 q1 hcm
					 * q1 hcm -> q,q1,h,hc,hcm
					 * nt hcm -> n,nt,h,hc,hcm
					 */
					's5' => [
						'type' => 'string',
						'analyzer' => 'full_text_search',
						'search_analyzer' => 'search'
					],
					/*
					 * Full Name viết tắt - Xử lý thêm bằng php có ngram
					 * PHP: Quận 1, Hồ Chí Minh -> quan 1 q1 hcm
					 * q1 hcm -> q,q1,h,hc,hcm
					 * nt q1 hcm -> n,nt,q,q1,h,hc,hcm
					 */
					's6' => [
						'type' => 'string',
						'analyzer' => 'full_text_search',
						'search_analyzer' => 'search'
					],
					/*
					 * Tên search full + phân tích PHP
					 */
					's7' => [
						'type' => 'string',
						'analyzer' => 'full_text_search',
						'search_analyzer' => 'search'
					],
					/*
					 * Full Name 1 (Tên + thêm Thành phố) search full + phân tích PHP
					 */
					's8' => [
						'type' => 'string',
						'analyzer' => 'full_text_search',
						'search_analyzer' => 'search'
					],
					/*
					 * Fullname search full + phân tích PHP Khong có Ngram
					 */
					's9' => [
						'type' => 'string',
						'analyzer' => 'standard_search',
						'search_analyzer' => 'search'
					],
					/*
					 * Fullname search full + phân tích PHP
					 */
					's10' => [
						'type' => 'string',
						'analyzer' => 'full_text_search',
						'search_analyzer' => 'search'
					]
				]
			]
		];
		
		$synonyms = ["hồ chí minh,hcm", "hà nội,hn"];
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
					],
					'my_shingle' => [
						'type' => 'shingle',
						'max_shingle_size' => 100
					]
				],
				'analyzer' => [
					'standard_search' => [
						'tokenizer' => 'whitespace',
						'filter' => ['lowercase', 'synonym', 'my_ascii_folding', 'unique', 'synonym_number']
					],
					'keyword_search' => [
						'tokenizer' => 'keyword',
						'filter' => ['lowercase', 'my_ascii_folding', 'my_edge_ngram', 'trim', 'unique']
					],
					'keyword_shingle_search' => [
                		'tokenizer' => 'whitespace',
						'filter' =>	["lowercase", "my_shingle", "my_ascii_folding", "my_edge_ngram", "trim", "unique"]
					],
					'full_text_search' => [
						'tokenizer' => 'whitespace',
						'filter' => ['lowercase', 'synonym', 'my_ascii_folding', 'my_edge_ngram', 'unique', 'synonym_number']
					],
					'search' => [
						'tokenizer' => 'whitespace',
						'filter' => ['lowercase']
					],
					'simple_search' => [
						'tokenizer' => 'keyword',
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
				'_id' => $id
			]
		]);
		
		$term .= "\n" . json_encode($document, JSON_UNESCAPED_UNICODE) . "\n";

		return $term;
	}
	
	public function getAreas($table, $addSelect = []) {
		$select = ["`$table`.`id`", "`$table`.`name`", "`$table`.`pre`", "`slug_search`.`slug`"];
		
		if($table == $this->tProjectTable) {
			$select = ["`$table`.`id`", "`$table`.`name`", "'Dự án' AS `pre`", "`slug_search`.`slug`"];
		} else {
			$select = ["`$table`.`id`", "`$table`.`name`", "`$table`.`pre`", "`slug_search`.`slug`"];
		}
		
		$select = array_merge($select, $addSelect);
		
		$query = new Query();
		$query->select($select);
		$query->from($table);
		$query->leftJoin("(SELECT * FROM `slug_search` WHERE `slug_search`.`table` = '$table') AS `slug_search`", "`slug_search`.`value` = `$table`.`id`");
		$query->where($where);
		$query->indexBy('id');
		
		return $query->all();
	}
}