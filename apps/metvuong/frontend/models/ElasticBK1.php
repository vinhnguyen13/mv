<?php
/**
 * https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/_overview.html
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 11/17/2015
 * Time: 1:56 PM
 */

namespace frontend\models;

use Yii;
use Elasticsearch\ClientBuilder;
use yii\base\Exception;
use vsoft\ad\models\AdProduct;
use common\components\Slug;
use common\components\common\components;
use yii\db\Query;

class Elastic
{
	const RETRY_ON_CONFLICT = 3;
	public static $productEsType = 'all';
	public static $properties = [
		'id' => [
			'type' => 'integer',
			'index' => 'no'
		],
		'favorite' => [
			'type' => 'integer',
			'index' => 'no'
		],
		'share' => [
			'type' => 'integer',
			'index' => 'no'
		],
		'search' => [
			'type' => 'integer',
			'index' => 'no'
		],
		'view' => [
			'type' => 'integer',
			'index' => 'no'
		],
		'category_id' => [
			'type' => 'byte'
		],
		'project_building_id' => [
			'type' => 'integer'
		],
		'project_building' => [
			'type' => 'string',
			'index' => 'no'
		],
		'user_id' => [
			'type' => 'integer'
		],
		'city_id' => [
			'type' => 'integer'
		],
		'district_id' => [
			'type' => 'integer'
		],
		'ward_id' => [
			'type' => 'integer'
		],
		'street_id' => [
			'type' => 'integer'
		],
		'address' => [
			'type' => 'string',
			'index' => 'no'
		],
		'type' => [
			'type' => 'byte'
		],
		'area' => [
			'type' => 'integer'
		],
		'price' => [
			'type' => 'long'
		],
		'location' => [
			'type' => 'geo_point'
		],
		'score' => [
			'type' => 'byte'
		],
		'start_date' => [
			'type' => 'integer'
		],
		'boost_time' => [
			'type' => 'integer'
		],
		'boost_start_time' => [
			'type' => 'integer'
		],
		'boost_sort' => [
			'type' => 'integer'
		],
		'facade_width' => [
			'type' => 'float'
		],
		'land_width' => [
			'type' => 'float'
		],
		'home_direction' => [
			'type' => 'byte'
		],
		'facade_direction' => [
			'type' => 'byte'
		],
		'floor_no' => [
			'type' => 'byte'
		],
		'room_no' => [
			'type' => 'byte'
		],
		'toilet_no' => [
			'type' => 'byte'
		],
		'img' => [
			'type' => 'string',
			'index' => 'no'
		]
	];
	
    protected $client = null;
    
    public static function buildProductDocument($product) {
    	foreach ($product as $k => &$v) {
    		if(is_numeric($v) && $k != 'address') {
    			$v = floatval($v);
    		}
    	}
    	
    	$product['location'] = [
    		'lat' => $product['lat'],
    		'lon' => $product['lng']
    	];
    	
    	$document = [];
    	
    	foreach (self::$properties as $field => $mapping) {
    		if(empty($product[$field])) {
    			if($mapping['type'] == 'string') {
    				$document[$field] = "";
    			} else {
    				$document[$field] = 0;
    			}
    		} else {
    			$document[$field] = $product[$field];
    		}
    	}
    	
    	$index = [
			'index' => [
				'_id' => intval($product['id'])
			]
		];
    	
    	return [$index, $document];
    }
    
    public static function buildQueryProduct() {
    	$query = new Query();
    	$query->select("`ad_product`.`id`, `ad_product`.`category_id`, `ad_product`.`project_building_id`, `ad_building_project`.`name` `project_building`,
							`ad_product`.`user_id`, `ad_product`.`city_id`, `ad_product`.`district_id`, `ad_product`.`ward_id`, `ad_product`.`street_id`, `ad_product`.`type`,
							`ad_product`.`area`, `ad_product`.`price`, `ad_product`.`lat`, `ad_product`.`lng`, `ad_product`.`score`, `ad_product`.`start_date`, `ad_product`.`boost_time`,
							`ad_product`.`boost_start_time`, `ad_product_addition_info`.`facade_width`, `ad_product_addition_info`.`land_width`, `ad_product_addition_info`.`home_direction`,
							`ad_product_addition_info`.`facade_direction`, `ad_product_addition_info`.`floor_no`, `ad_product_addition_info`.`room_no`, `ad_product_addition_info`.`toilet_no`");
    	$query->addSelect([
    			"CONCAT_WS(', ', IF(`ad_product`.`show_home_no` = 1 AND `ad_product`.`home_no`, `ad_product`.`home_no`, NULL), IF(`ad_product`.`street_id` IS NOT NULL, CONCAT(`ad_street`.`pre`, ' ', `ad_street`.`name`), NULL)) `address`",
    			"IF(`ad_images`.`folder` != '' OR `ad_images`.`folder` IS NULL, CONCAT('/store/', `ad_images`.`folder`, '/240x180/', `ad_images`.`file_name`), REPLACE(`ad_images`.`file_name`, '745x510', '350x280')) `img`"
    	]);
    		
    	$query->from("`ad_product`");
    	$query->leftJoin("`ad_building_project`", "`ad_building_project`.`id` = `ad_product`.`project_building_id`");
    	$query->leftJoin("`ad_city`", "`ad_city`.`id` = `ad_product`.`city_id`");
    	$query->leftJoin("`ad_district`", "`ad_district`.`id` = `ad_product`.`district_id`");
    	$query->leftJoin("`ad_ward`", "`ad_ward`.`id` = `ad_product`.`ward_id`");
    	$query->leftJoin("`ad_street`", "`ad_street`.`id` = `ad_product`.`street_id`");
    	$query->leftJoin("`ad_product_addition_info`", "`ad_product_addition_info`.`product_id` = `ad_product`.`id`");
    	$query->leftJoin("`ad_images`", "`ad_images`.`product_id` = `ad_product`.`id` AND `ad_images`.`order` = 0");
    	
    	return $query;
    }

    public static function transform($str) {
    	$str = trim(mb_strtolower($str, 'UTF-8'));
    	$str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
    	$str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
    	$str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
    	$str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
    	$str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
    	$str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
    	$str = preg_replace('/(đ)/', 'd', $str);
    	return $str;
    }
    
    public static function slug($str) {
    	$slug = new Slug();
    	
    	return $slug->slugify($str);
    }
    
    public static function buildParams($v) {
    	/*
    	 * escape for elasticsearch must done
    	 */
    	$v = str_replace('/', '\/', $v);
    	$v = str_replace([',', '.'], ' ', $v);
    	$v = trim(preg_replace('/\s+/', ' ', $v));
    	
    	$functions = [
    		[
    			"filter" => [
    				"match" => [
    					"s1" => [
    						"query" => $v,
    						"operator" => "and"
    					]
    				]
    			],
    			"weight" => 1
    		],
    		[
    			"filter" => [
    				"match" => [
    					"s2" => $v
    				]
    			],
    			"weight" => 1
    		],
    		[
    			"filter" => [
    				"match" => [
    					"s3" => $v
    				]
    			],
    			"weight" => 1
    		],
    		[
    			"filter" => [
    				"match" => [
    					"s4" => $v
    				]
    			],
    			"weight" => 1
    		],
    		[
    			"filter" => [
    				"match" => [
    					"s5" => [
    						"query" => $v,
    						"operator" => "and"
    					]
    				]
    			],
    			"weight" => 1
    		],
    		[
    			"filter" => [
    				"match" => [
    					"s6" => [
    						"query" => $v,
    						"operator" => "and"
    					]
    				]
    			],
    			"weight" => 1
    		],
    		[
    			"filter" => [
    				"match" => [
    					"s7" => [
    						"query" => $v,
    						"operator" => "and"
    					]
    				]
    			],
    			"weight" => 1
    		],
    		[
    			"filter" => [
    				"match" => [
    					"s8" => [
    						"query" => $v,
    						"operator" => "and"
    					]
    				]
    			],
    			"weight" => 1
    		],
    		[
    			"filter" => [
    				"match" => [
    					"s9" => [
    						"query" => $v,
    						"operator" => "and"
    					]
    				]
    			],
    			"weight" => 1
    		],
    		[
    			"filter" => [
    				"term" => [
    					"_type" => "city"
    				]
    			],
    			"weight" => 0.02
    		],
    		[
    			"filter" => [
    				"term" => [
    					"_type" => "district"
    				]
    			],
    			"weight" => 0.01
    		],
    		[
    			"filter" => [
    				"term" => [
    					"city_id" => 1
    				]
    			],
    			"weight" => 0.01
    		]
    	];
    	
    	$query = self::buildQueryString($v);
    	
    	$params = [
    		"query" => [
    			"function_score" => [
    				"query" => [
    					"query_string" => [
    						"default_field" => "s10",
    						"query" => $query
    					]
    				],
    				"functions" => $functions,
    				"boost_mode" => "replace",
    				"score_mode" => "sum"
    			]
    		]
    	];
  	
    	return $params;
    }
    
    public static function searchAreasRankByTotal($v) {
    	$params = self::buildParams($v);
    	
    	$params['query']['function_score']['functions'][] = [
    		"field_value_factor" => [
    			"field" => "total_sell",
    			"modifier" => "log1p",
    			"factor" => 0.00001
    		]
    	];
    	
    	$params['query']['function_score']['functions'][] = [
    		"field_value_factor" => [
    			"field" => "total_rent",
    			"modifier" => "log1p",
    			"factor" => 0.00001
    		]
    	];
    	
    	return self::requestResult($params, self::elasticUrl());
    }
    
    public static function searchAreas($v, $t) {
		$params = self::buildParams($v);
    	
		$field = ($t == AdProduct::TYPE_FOR_SELL) ? AdProduct::TYPE_FOR_SELL_TOTAL : AdProduct::TYPE_FOR_RENT_TOTAL;
		
    	$params['query']['function_score']['functions'][] = [
    		"field_value_factor" => [
    			"field" => $field,
    			"modifier" => "log1p",
    			"factor" => 0.00001
    		]
    	];
		
		return self::requestResult($params, self::elasticUrl());
    }
    
    public static function searchAllProjects($v) {
    	$params = self::buildParams($v);
  	
    	return self::requestResult($params, self::elasticUrl('/project_building'));
    }
    
    public static function elasticUrl($type = '') {
    	return \Yii::$app->params['elastic']['config']['hosts'][0] . '/' . \Yii::$app->params['indexName']['countTotal'] . $type . '/_search';
    }
    
    public static function requestResult($params, $url) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 
		$result = json_decode(curl_exec($ch), true);
		
		return $result;
    }
	
	public static function buildQueryString($s) {
		$consonantArr = ["ngh", "ph", "th", "tr", "gi", "ch", "nh", "ng", "kh", "gh"];
		
		$consonantLv = [
				1 => "c|q|k|t|r|h|b|m|v|n|l|x|p|s|d|g",
				2 => "ph|th|tr|gi|ch|nh|ng|kh|gh",
				3 => "ngh"
		];
		
		$consonant = "(" . $consonantLv[3] . "|" . $consonantLv[2] . "|" . $consonantLv[1] . ")";
		$vowel = "(a|\x{00E0}|\x{00E1}|\x{1EA1}|\x{1EA3}|\x{00E3}|\x{00E2}|\x{1EA7}|\x{1EA5}|\x{1EAD}|\x{1EA9}|\x{1EAB}|\x{0103}|\x{1EB1}|\x{1EAF}|\x{1EB7}|\x{1EB3}|\x{1EB5}|o|\x{00F2}|\x{00F3}|\x{1ECD}|\x{1ECF}|\x{00F5}|\x{00F4}|\x{1ED3}|\x{1ED1}|\x{1ED9}|\x{1ED5}|\x{1ED7}|\x{01A1}|\x{1EDD}|\x{1EDB}|\x{1EE3}|\x{1EDF}|\x{1EE1}|y|\x{1EF3}|\x{00FD}|\x{1EF5}|\x{1EF7}|\x{1EF9}|e|\x{00E8}|\x{00E9}|\x{1EB9}|\x{1EBB}|\x{1EBD}|\x{00EA}|\x{1EC1}|\x{1EBF}|\x{1EC7}|\x{1EC3}|\x{1EC5}|u|\x{00F9}|\x{00FA}|\x{1EE5}|\x{1EE7}|\x{0169}|\x{01B0}|\x{1EEB}|\x{1EE9}|\x{1EF1}|\x{1EED}|\x{1EEF}|i|\x{00EC}|\x{00ED}|\x{1ECB}|\x{1EC9}|\x{0129})";
		
		$words = explode(" ", $s);
		
		foreach ($words as &$word) {
			$correctText = preg_replace_callback("/$consonant(?=$consonant)/", function($matches) use ($consonantArr) {
				if(in_array($matches[0].$matches[2], $consonantArr)) {
					return $matches[0];
				} else {
					return $matches[0] . " ";
				}
			}, $word);
		
			$correctText = preg_replace("/(?<=$vowel)$consonant(?=$vowel)/u", " $0", $correctText);
			$correctText = preg_replace("/(\D{2,})(\d+)/", "$1 $2", $correctText);
	
			if($correctText != $word) {
				$correctText = implode(" AND ", explode(" ", $correctText));
				$word = "($word OR ($correctText))";
			}
		}
		
		return implode(" AND ", $words);
	}
	
	public static function insertProducts($indexName, $type, $bulk) {
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
	
	public static function countProducts($products) {
		$updateCounter = [
			'city' => [],
			'district' => [],
			'ward' => [],
			'street' => [],
			'project_building' => []
		];
		
		foreach ($products as $product) {
			foreach ($updateCounter as $type => &$bulk) {
				$termId = $product[$type . '_id'];
					
				if($termId) {
					if(isset($bulk[$termId])) {
						if($product['type'] == AdProduct::TYPE_FOR_SELL) {
							$bulk[$termId][AdProduct::TYPE_FOR_SELL_TOTAL] += 1;
						} else {
							$bulk[$termId][AdProduct::TYPE_FOR_RENT_TOTAL] += 1;
						}
					} else {
						if($product['type'] == AdProduct::TYPE_FOR_SELL) {
							$bulk[$termId][AdProduct::TYPE_FOR_SELL_TOTAL] = 1;
						} else {
							$bulk[$termId][AdProduct::TYPE_FOR_RENT_TOTAL] = 1;
						}
					}
				}
			}
		}
		
		/*
		 * Update Counter
		 */
		$updateBulk = [];
		$retryOnConflict = Elastic::RETRY_ON_CONFLICT;
			
		foreach ($updateCounter as $type => $uc) {
			foreach ($uc as $id => $total) {
				foreach ($total as $t => $count) {
					$updateBulk[] = '{ "update" : { "_id" : "' . $id . '", "_type" : "' . $type . '", "_retry_on_conflict": ' . $retryOnConflict . ' } }';
					$updateBulk[] = '{ "script" : { "inline": "ctx._source.' . $t . ' += ' . $count . '"} }';
				}
			}
		}
		$updateBulk = implode("\n", $updateBulk) . "\n";
		$indexName = \Yii::$app->params['indexName']['countTotal'];
		$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . "/$indexName/_bulk");
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $updateBulk);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_exec($ch);
		curl_close($ch);
	}
	
	public static function standardSearch($s) {
		$s = self::standardSearchlv1($s);
		$s = preg_replace("/(^|(?<=\s))(\d+)/", "số $0", $s);
	
		return $s;
	}
	
	public static function standardSearchlv1($s) {
		$s = preg_replace("/(\S+)(-|'|&)(\S+)/", "$1$2$3 $1$3 $1 $3", $s);
		$s = preg_replace("/(\d+)\/(\d+)/", "$1/$2 tháng $2", $s);
	
		return $s;
	}
	
	public static function standardSearchDistrict($district) {
		return self::standardSearchlv1(preg_replace("/(Quận)\s(\d+)/", "$1 $2 q$2", $district));
	}
	
	public static function standardSearchWard($ward) {
		return self::standardSearchlv1(preg_replace("/(Phường)\s(\d+)/", "$1 $2 p$2", $ward));
	}
	
	public static function acronym($s) {
		$numberArray = [
			"Một" => 1,
			"Hai" => 2,
			"Ba" => 3,
			"Bốn" => 4,
			"Năm" => 5,
			"Sáu" => 6,
			"Bảy" => 7,
			"Tám" => 8,
			"Chín" => 9,
			"Mười" => 10
		];
		$s = str_replace(array_keys($numberArray), $numberArray, $s);
		$lower = mb_strtolower($s, 'UTF-8');
		$words = explode(" ", $lower);
		$acronym = "";
		
		foreach ($words as $word) {
			$acronym .= mb_substr($word, 0, 1, 'UTF-8');
		}
		
		return $acronym;
	}
}