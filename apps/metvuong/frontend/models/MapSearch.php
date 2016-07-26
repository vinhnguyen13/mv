<?php
namespace frontend\models;

use yii\db\Query;
use vsoft\ad\models\AdProduct;
use yii\data\Pagination;
use vsoft\express\components\StringHelper;
use vsoft\ad\models\AdStreet;
use vsoft\ad\models\AdWard;
use vsoft\ad\models\AdBuildingProject;
use vsoft\ad\models\AdDistrict;
use frontend\components\SearchUrlManager;
use \Yii;
use common\models\SlugSearch;
use vsoft\ad\models\AdCategoryGroup;

class MapSearch extends AdProduct {
	
	public $price_min;
	public $price_max;
	
	public $size_min;
	public $size_max;
	
	public $created_before;
	public $order_by;
	
	public $room_no;
	public $toilet_no;
	
	public $rect;
	public $rm;
	public $ra;
	public $ra_k;
	public $rl;
	public $iz;
	public $z;
	public $c;
	public $did;
	public $page;
	
	public $streets;
	public $wards;
	public $districts;
	
	public static $defaultSlug = 'ho-chi-minh-quan-1';
	
	public static function fieldsMapping() {
		return [
			\Yii::t('ad', 'phong-ngu') => 'room_no',
			\Yii::t('ad', 'phong-tam') => 'toilet_no',
			\Yii::t('ad', 'gia') => 'price',
			\Yii::t('ad', 'dien-tich') => 'size',
			\Yii::t('ad', 'sap-xep') => 'order_by',
			'rect' => 'rect',
			'rm' => 'rm',
			'ra' => 'ra',
			'rl' => 'rl',
			'iz' => 'iz',
			'z' => 'z',
			'c' => 'c',
			'did' => 'did',
			\Yii::t('ad', 'trang') => 'page'
		];
	}
	
	public static function sortSlugMapping() {
		return [
			\Yii::t('ad', 'diem') => '-score',
			\Yii::t('ad', 'moi-nhat') => '-start_date',
			\Yii::t('ad', 'gia-thap-dan') => '-price',
			\Yii::t('ad', 'gia-cao-dan') => 'price'
		];
	}
	
	public static function parseParams($params) {
		if($slugSearch = SlugSearch::findOne(['slug' => array_shift($params)])) {
			$get = [];
			
			$keyId = ($slugSearch->table == 'ad_building_project') ? 'project_building_id' : str_replace("ad_", "", $slugSearch->table) . "_id";
			$get[$keyId] = $slugSearch->value;
			
			if($params) {
				if(strpos($params[0], '_') === FALSE) {
					$catGroupSlugs = AdCategoryGroup::slugMap();
					$catSlug = array_shift($params);
					
					if(isset($catGroupSlugs[$catSlug])) {
						$get['category_id'] = $catGroupSlugs[$catSlug];
					}
				}
				
				$fieldsMapping = self::fieldsMapping();
				
				foreach ($params as $param) {
					$pos = strpos($param, '_');
					$fakeKey = substr($param, 0, $pos);
					
					if(isset($fieldsMapping[$fakeKey])) {
						$realKey = $fieldsMapping[$fakeKey];
						$value = substr($param, $pos + 1);
						
						if(strpos($value, '-') !== FALSE && ctype_digit(str_replace('-', '', $value))) {
							$range = explode('-', $value);
					
							if(!empty($range[0])) {
								$get[$realKey . '_min'] = $range[0];
							}
							
							if(!empty($range[1])) {
								$get[$realKey . '_max'] = $range[1];
							}
						} else {
							$get[$realKey] = $value;
						}
					}
				}
				
				if(isset($get['order_by'])) {
					$sortSlugMapping = self::sortSlugMapping();
					
					$get['order_by'] = $sortSlugMapping[$get['order_by']];
				}
			}
			
			return $get;
		} else {
			return false;
		}
	}
	
	public static function mapSort() {
		return [
			'-score' => Yii::t('ad', 'Point'),
			'-start_date' => Yii::t('ad', 'Newest'),
			'-price' =>Yii::t('ad', 'Price (High to Low)'),
			'price' =>Yii::t('ad', 'Price (Low to Hight)'),
		];
	}
	
	function rules() {
		return array_merge(parent::rules(), [
			[['order_by', 'rect', 'ra', 'ra_k', 'z', 'c'], 'string'],
			[['price_min', 'price_max', 'size_min', 'size_max', 'created_before', 'room_no', 'toilet_no', 'rm', 'rl', 'page', 'iz', 'did'], 'number']
		]);
	}
	
	function formName() {
		return '';
	}
	
	public function search() {
		$must = [];
		
		if($this->project_building_id) {
			$must[] = [
				"term" => [
					"project_building_id" => intval($this->project_building_id)
				]
			];
		} else if($this->street_id) {
			$must[] = [
				"term" => [
					"street_id" => intval($this->street_id)
				]	
			];
		} else if($this->ward_id) {
			$must[] = [
				"term" => [
					"ward_id" => intval($this->ward_id)
				]	
			];
		} else if($this->district_id) {
			$must[] = [
				"term" => [
					"district_id" => intval($this->district_id)
				]	
			];
		} else if($this->city_id) {
			$must[] = [
				"term" => [
					"city_id" => intval($this->city_id)
				]	
			];
		}
		
		$must[] = [
			"term" => [
				"type" => intval($this->type)
			]
		];
		
		if($this->category_id) {
			$must[] = [
				"terms" => [
					"category_id" => array_map('intval', explode(',', $this->category_id))
				]
			];
		}
		
		$priceRange = [];
		if($this->price_min) {
			$priceRange['gte'] = intval($this->price_min);
		}
		if($this->price_max) {
			$priceRange['lte'] = intval($this->price_max);
		}
		if(!empty($priceRange)) {
			$must[] = [
				"range" => [
					"price" => $priceRange
				]
			];
		}
		
		
		$sizeRange = [];
		if($this->size_min) {
			$sizeRange['gte'] = intval($this->size_min);
		}
		if($this->size_max) {
			$sizeRange['lte'] = intval($this->size_max);
		}
		if(!empty($sizeRange)) {
			$must[] = [
				"range" => [
					"area" => $sizeRange
				]
			];
		}
		
		if($this->room_no) {
			$must[] = [
				"range" => [
					"room_no" => [
						"gte" => intval($this->room_no)
					]
				]
			];
		}
		
		if($this->toilet_no) {
			$must[] = [
				"range" => [
					"toilet_no" => [
						"gte" => intval($this->toilet_no)
					]
				]
			];
		}
		
		$aggs = [];
		
		if($this->ra) {
			$aggs["ra"] = [
				"terms" => [
					"field" => $this->ra . '_id',
					"size" => 1000
				]	
			];
		}
		
		if($this->rm || $this->rl) {
			$sortBy = $this->order_by ? $this->order_by : '-score';
			$doa = StringHelper::startsWith($sortBy, '-') ? 'desc' : 'asc';
			$sortBy = str_replace('-', '', $sortBy);
			
			$sort = [
				[
					"boost_sort" => ["order" => "desc"]		
				]
			];
			
			if($sortBy == 'score') {
				$sort[] = [
					"_script" => [
						"type" => "number",
						"script" => [
							"inline" => "doc['score'].value - ((current_time - doc['start_date'].value) * 0.00001157407)",
							"params" => [
								"current_time" => time()
							]
						],
						"order" => "desc"
					]
				];
			} else {
				$sort[] = [$sortBy => ["order" => $doa]];
			}
		}
		
		if($this->rm) {
			$aggs["rm"] = [
				"top_hits" => [
					"_source" => [
						"include" => ["id", "address", "price", "area", "room_no", "toilet_no", "location", "img"]
					],
					"size" => 500,
					"sort" => $sort
				]
			];
		}
		
		if($this->rl) {
			$limit = \Yii::$app->params['listingLimit'];
			$page = $this->page ? $this->page : 1;
			$offset = ($page - 1) * $limit;
			
			$aggs["rl"] = [
				"top_hits" => [
					"_source" => [
						"include" => ["id", "boost_sort", "user_id", "category_id", "type", "address", "price", "area", "room_no", "toilet_no", "start_date", "score", "img"]
					],
					"size" => $limit,
					"from" => $offset,
					"sort" => $sort
				]
			];
		}
		
		if($this->rect && !$this->ra) {
			$rect = array_map('floatval', explode(',', $this->rect));
		
			$must[] = [
				"geo_bounding_box" => [
					"location" => [
						"bottom_left" => [
							"lat" => $rect[0],
							"lon" => $rect[1]
						],
						"top_right" => [
							"lat" => $rect[2],
							"lon" => $rect[3]
						]
					]
				]
			];
		}
		
		$params = [
			"query" => [
				"bool" => [
					"must" => $must
				]
			],
			"size" => 0,
			"aggs" => $aggs
		];

		$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . '/' . \Yii::$app->params['indexName']['product'] . '/_search');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			
		$result = json_decode(curl_exec($ch), true);
		
		return $result;
	}
	
	public function getAutoFillValue() {
		$fill = [];
		
		if($this->street_id) {
			$fill[] = $this->street->pre . ' ' . $this->street->name;
		} else if($this->ward_id) {
			$fill[] = $this->ward->pre . ' ' . $this->ward->name;
		} else if($this->project_building_id) {
			$fill[] = $this->projectBuilding->name;
		}
		
		if($this->district_id) {
			$fill[] = trim($this->district->pre . ' ' . $this->district->name);
		}
		
		$fill[] = $this->city->name;
		
		return implode(', ', $fill);
	}
	
	public function getList($query) {
		$listQuery = clone $query;
			
		$countQuery = clone $listQuery;
		$pages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize' => \Yii::$app->params['listingLimit'], 'route' => '/ad/index' . $this->type, 'urlManager' => new SearchUrlManager()]);
		$pages->setPageSize(\Yii::$app->params['listingLimit']);
				
		$listQuery->offset($pages->offset);
		$listQuery->limit($pages->limit);
		
		$listQuery->addSelect([
			"ad_product.user_id",
			"ad_product.score",
			"ad_product.start_date",
			"ad_product.category_id",
			"ad_product.type",
		]);
		
		$products = $listQuery->all();
		
		if(\Yii::$app->params['tracking']['all']) {
			$tracking = !empty($this->project_building_id) || ((!empty($this->ward_id) || !empty($this->street_id)) && (!empty($this->room_no) || !empty($this->price_min) || !empty($this->price_max)));
			
			if($tracking && \Yii::$app->user->identity) {
				$userId = \Yii::$app->user->id;
				
				foreach ($products as $product) {
					if($product['user_id'] != $userId) {
						Tracking::find()->productFinder($userId, $product['id'], time());
					}
				}
			}
		}
		
		return ['products' => $products, 'pages' => $pages];
	}
	
	public function sort($query) {
		$sort = $this->order_by ? $this->order_by : '-score';
		$doa = StringHelper::startsWith($sort, '-') ? 'DESC' : 'ASC';
		$sort = str_replace('-', '', $sort);
		
		$query->orderBy("boost_time DESC");
		$query->addOrderBy("$sort $doa");
	}
	
	function fetchValues() {
		if(!$this->district_id) {
			if($this->street_id) {
				$this->district_id = $this->street->district_id;
			} else if($this->ward_id) {
				$this->district_id = $this->ward->district_id;
			} else if($this->project_building_id) {
				if($this->projectBuilding->district) {
					$this->district_id = $this->projectBuilding->district_id;
				}
			}
		}
	
		if(!$this->city_id) {
			if($this->district_id) {
				$this->city_id = $this->district->city_id;
			} else {
				$this->city_id = self::DEFAULT_CITY;
			}
		}
	
		if(!$this->type) {
			$this->type = AdProduct::TYPE_FOR_SELL;
		}
		
		if($this->street_id) {
			$this->streets = [$this->street_id => $this->street->getAttributes()];
		} else if($this->district_id) {
			$this->streets = AdStreet::find()->asArray(true)->where(['district_id' => $this->district_id])->indexBy('id')->all();
		} else if($this->city_id) {
			$this->streets = AdStreet::find()->asArray(true)->where(['city_id' => $this->city_id])->indexBy('id')->all();
		}
		
		if($this->ward_id) {
			$this->wards = [$this->ward_id => $this->ward->getAttributes()];
		} else if($this->district_id) {
			$this->wards = AdWard::find()->asArray(true)->where(['district_id' => $this->district_id])->indexBy('id')->all();
		} else if($this->city_id) {
			$this->wards = AdWard::find()->asArray(true)->where(['city_id' => $this->city_id])->indexBy('id')->all();
		}
		
		if($this->district_id) {
			$this->districts = [$this->district_id => $this->district->getAttributes()];
		} else if($this->city_id) {
			$this->districts = AdDistrict::find()->asArray(true)->where(['city_id' => $this->city_id])->indexBy('id')->all();
		}
	}
}