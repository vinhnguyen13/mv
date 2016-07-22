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
	
	public static $acronyms = [
		'hcm,hồ chí minh',
		'hn,hà nội',
		'cmt8,cách mạng tháng tám',
		'xvnt,xô viết nghệ tĩnh',
		'ntmk,nguyễn thị minh khai',
		'hagl,hoàng anh gia lai',
		'1,một',
		'2,hai',
		'quận 1,quận nhất',
	];
	
	public static $synonyms = [
		'1' => 'một',
		'2' => 'hai',
	];
	
    protected $client = null;
    public function __construct(){
        $this->connect();
    }
    
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

    public function connect(){
        if(!empty(Yii::$app->params['elastic']['config']['hosts'])){
            $hosts = Yii::$app->params['elastic']['config']['hosts'];
            $singleHandler  = ClientBuilder::singleHandler();
            $multiHandler   = ClientBuilder::multiHandler();
            if(empty($this->client)){
                $this->client = ClientBuilder::create()           // Instantiate a new ClientBuilder
                ->setHosts($hosts)      // Set the hosts
                ->setHandler($singleHandler)
                ->build();              // Build the client object
            }
            return $this->client;
        }
        return false;
    }

    public function index($params){
//         $params = [
//             'index' => 'my_index',
//             'type' => 'my_type',
//             'id' => 'my_id',
//             'body' => [ 'testField' => 'abc']
//         ];
        // Document will be indexed to my_index/my_type/my_id
        $response = $this->client->index($params);
        return $response;
    }

    public function delete(){
        $params = [
            'index' => 'my_index',
            'type' => 'my_type',
            'id' => 'my_id'
        ];
        // Delete doc at /my_index/my_type/my_id
        $response = $this->client->delete($params);
        return $response;
    }

    public function update(){
        $params = [
            'index' => 'listing',
            'type' => 'store',
            'id' => '28',
            'body' => [
                'doc' => [
                    'title' => 'Ứng dụng công nghệ Holongram vào trình diễn dự án tại Việt Nam'
                ]
            ]
        ];
        // Update doc at /my_index/my_type/my_id
        $response = $this->client->update($params);
        return $response;
    }

    public function search($params){
//         $params = [
//             'index' => 'listing',
//             'type' => 'store',
//             'body' => [
//                 'query' => [
//                     /*'match' => [
//                         'title' => 'long'
//                     ]*/
//                     /*'bool' => [
//                         'must' => [
//                             [ 'match' => [ 'title' => 'long' ] ],
//                         ]
//                     ]*/
//                     /*'filtered' => [
//                         'filter' => [
//                             'term' => [ 'title' => 'long' ]
//                         ],
//                         'query' => [
//                             'match' => [ 'title' => 'long' ]
//                         ]
//                     ]*/
//                     /*'filtered' => [
//                         'query' => [
//                             'query_string' => [
//                                 'query' => 'long',
//                                 "default_operator" => "OR",
//                                 "fields" => ["title"]
//                             ]
//                         ]
//                     ],
//                     'regexp' => [
//                         'title' => [
//                             'value' => '.*long.*'
//                         ]
//                     ],*/
//                     /*'filtered' => [
//                         'query' => [
//                             'query_string' => [
//                                 'query' => 'Ứng dụng công nghệ', // words want to find
//                                 "default_operator" => "OR", // OR/AND query words
//                                 "fields" => ["title", "content"], // select fields
// //                                "default_field" => "_all", // all filed
//                             ]
//                         ]
//                     ],*/
//                     'regexp' => [
//                         'title' => [
//                             'value' => '.*long.*'
//                         ]
//                     ]
//                 ]
//             ]
//         ];
        $results = $this->client->search($params);
        return $results;
    }

    public function findOne($index, $type, $id, $function = 'getSource'){
        /**
         * must have 2 field
         */
        $params = [
            'index' => $index,
            'type' => $type,
            'id' => $id,
        ];
        // Document will be indexed to my_index/my_type/my_id
        try{

            if($this->client->transport->getConnection()->ping()){
                $chk = $this->client->exists($params);
                if(!empty($chk)){
        //            $result = $client->get($params);
                    $result = $this->client->$function($params);
                    return $result;
                }
                return false;
            }
        }catch (Exception $e){

        }
    }


    public function userData(){
        try{
            $this->connect();
            if($this->client->transport->getConnection()->ping()){
                $params = [
                    'index' => 'users',
                    'type' => 'store',
                    'id' => '28',
                    'body' => [
                        'doc' => [
                            'title' => 'Ứng dụng công nghệ Holongram vào trình diễn dự án tại Việt Nam'
                        ]
                    ]
                ];
                $this->client->index($params);
            }
        }catch (Exception $e){

        }
    }
    
    public function bulk($params) {
    	$results = $this->client->bulk($params);
    	return $results;
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
    
    public static function standardSearch($s) {
    	$s = self::transform($s);
    	
    	$s = preg_replace("/([0-9]*)\/([0-9]*)/", "$1/$2 $1 / $2", $s);
    	$s = preg_replace("/so ([0-9])/", "$1", $s);
    	$s = preg_replace("/(\S+)-(\S+)/", "$1-$2 $1$2", $s);
    	
    	return $s;
    }
    
    public static function slug($str) {
    	$slug = new Slug();
    	
    	return $slug->slugify($str);
    }
    
    public static function buildParams($v) {

    	$v = preg_replace("/so ([0-9]|mot|hai|ba|bon|nam|sau|bay|tam|chin|muoi)/", "$1", $v);
    	$v = preg_replace("/([0-9]*)\/([0-9]*)/", "$1 / $2", $v);
    	
		$slop = 11;
		$maxExpansions = 80;
		
		$should = [
			[
				"match_phrase_prefix" => [
					"search_field" => [
						"query" => $v,
						"slop" => $slop,
						"max_expansions" => $maxExpansions
					]
				]	
			]
		];
		
		$functions = [
			[
				"filter" => [
					"match_phrase" => [
						"search_name" => $v
					]
				],
				"weight" => 3
			],
			[
				"filter" => [
					"match_phrase_prefix" => [
						"search_name" => $v
					]
				],
				"weight" => 2
			],
			[
				"filter" => [
					"match_phrase_prefix" => [
						"search_field" => $v
					]
				],
				"weight" => 1
			],
			[
				"filter" => [
					"match_phrase_prefix" => [
						"search_field" => [
							"query" => $v,
							"slop" => $slop
						]
					]
				],
				"weight" => 3
			],
			[
				"filter" => [
					"match_phrase_prefix" => [
						"search_field_key" => [
							"query" => $v,
							"max_expansions" => 12
						]
					]
				],
				"weight" => 1
			],
			[
				"filter" => [
					"match" => [
						"city_id" => 1
					]
				],
				"weight" => 0.1
			],
		];
		
		$additionSearch = preg_replace("/(q|quan|p|phuong)([0-9])/", "$1 $2", $v);
		$additionSearch = preg_replace("/(^|(?<=\s))(tp|thanh pho|tinh)(\s|.)/", "", $additionSearch);
		
		if(strpos($additionSearch, "du an") !== false) {
			$additionSearch = str_replace("du an", "", $v);
		
			$functions[] = [
				"filter" => [
					"match" => [
						"_type" => "project_building"
					]
				],
				"weight" => 4
			];
		} else {
			$functions[] = [
				"filter" => [
					"match" => [
						"_type" => "city"
					]
				],
				"weight" => 2
			];
			$functions[] = [
				"filter" => [
					"match" => [
						"_type" => "district"
					]
				],
				"weight" => 1
			];
		}
		
		if($additionSearch != $v) {
			$should[] = [
				"match_phrase_prefix" => [
					"search_field" => [
						"query" => $additionSearch,
						"slop" => $slop
					]
				]
			];
			
			$functions[] = [
				"filter" => [
					"match_phrase_prefix" => [
						"search_field" => [
							"query" => $additionSearch,
							"slop" => $slop
						]
					]
				],
				"weight" => 3
			];
		}
		
		
		
		$sentence = explode(' ', $additionSearch);
		$totalWords = count($sentence);
			
		if($totalWords > 1) {
			$lastWord = $sentence[$totalWords-1];
		
			$functions[] = [
				"filter" => [
					"match" => [
						"search_field" => $lastWord
					]
				],
				"weight" => 1
			];
			
			if($totalWords > 2) {
				$lastWord = $sentence[$totalWords-2] . ' ' . $lastWord;
		
				$functions[] = [
					"filter" => [
						"match_phrase_prefix" => [
							"search_field" => $lastWord
						]
					],
					"weight" => 1
				];
			}
		}
		
		
		
		$correctText = self::correctText($additionSearch);
		
		if($correctText != $additionSearch) {
			$should[] = [
				"match_phrase_prefix" => [
					"search_field" => [
						"query" => $correctText,
						"slop" => $slop
					]
				]
			];
			
			$functions[] = [
				"filter" => [
					"match_phrase_prefix" => [
						"search_field" => [
							"query" => $correctText,
							"slop" => $slop
						]
					]
				],
				"weight" => 3
			];
		}
		
		$params = [
			"query" => [
				"function_score" => [
					"query" => [
						"bool" => [
							"should" => $should
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
    			"field" => ["total_sell", "total_rent"],
    			"modifier" => "log1p",
    			"factor" => 1
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
    			"factor" => 1
    		]
    	];
		
		return self::requestResult($params, self::elasticUrl());
    }
    
    public static function searchProjects($v) {
    	$params = self::buildParams($v);
    	
    	$params['query']['function_score']['functions'][] = [
    		"field_value_factor" => [
    			"field" => ["total_sell", "total_rent"],
    			"modifier" => "log1p",
    			"factor" => 1
    		]
    	];
    	
    	$params['query']['function_score']['query']["bool"]["must"][] = [
			"match" => [
				"city_id" => 1
			]
    	];
    	
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
	
	public static function correctText($s) {
		$consonantArr = ["ngh", "ph", "th", "tr", "gi", "ch", "nh", "ng", "kh", "gh"];
		 
		$consonantLv = [
			1 => "c|q|k|t|r|h|b|m|v|n|l|x|p|s|d|g",
			2 => "ph|th|tr|gi|ch|nh|ng|kh|gh",
			3 => "ngh"
		];
		 
		$consonant = "(" . $consonantLv[3] . "|" . $consonantLv[2] . "|" . $consonantLv[1] . ")";
		$vowel = "(a|o|y|e|u|i)";
		 
		$correctText = preg_replace_callback("/$consonant(?=$consonant)/", function($matches) use ($consonantArr) {
			if(in_array($matches[0].$matches[2], $consonantArr)) {
				return $matches[0];
			} else {
				return $matches[0] . " ";
			}
		}, $s);
			 
		$correctText = preg_replace_callback("/(?<=$vowel)$consonant(?=$vowel)/", function($matches) {
			return " " . $matches[0];
		}, $correctText);
		
		return $correctText;
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
}