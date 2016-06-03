<?php
namespace api\modules\v1\controllers;

use yii\rest\Controller;
use frontend\models\MapSearch;
use yii\db\Query;
use frontend\models\frontend\models;
use frontend\models\Elastic;
use vsoft\ad\models\AdProduct;

class MapController extends Controller {
	public function actionGet() {
		$v = \Yii::$app->request->get('v');
		$t = \Yii::$app->request->get('t', AdProduct::TYPE_FOR_SELL);
		
    	$v = Elastic::transform($v);
    	
    	$response = [];
    	
//     	$sort = ($t == AdProduct::TYPE_FOR_SELL) ? [AdProduct::TYPE_FOR_SELL_TOTAL => 'desc'] : [AdProduct::TYPE_FOR_RENT_TOTAL => 'desc'];
    	
//     	$params = [
// 			'query' => [
// 				'match_phrase_prefix' => [
// 					'search_field' => [
// 						'query' => $v,
//  						'max_expansions' => 100
// 					]
// 				]
// 			],
// 			'sort' => $sort,
// 			'_source' => ['full_name', AdProduct::TYPE_FOR_SELL_TOTAL, AdProduct::TYPE_FOR_RENT_TOTAL]
// 		];

    	$should = [
    					[
    						"match" => [
    							"_type" => [
    								"query" => "city",
    								"boost" => 1	
    							]	
    						]
    					],
    					[
    						"match" => [
    							"city_id" => [
    								"query" => 1,
    								"boost" => 1	
    							]	
    						]
    					]
    	];

    	if(strpos($v, "du an") !== false) {
    		$sv = str_replace("du an", "", $v);
    	
    		if(trim($sv) != '') {
    			$v = $sv;
    			
    			$should[] = [
    						"match" => [
    							"_type" => [
    								"query" => "project_building",
    								"boost" => 2	
    							]	
    						]
    					];
    		}
    	} else {
    		$should[] = [
    				"match" => [
    						"_type" => [
    								"query" => "project_building",
    								"boost" => 0.001
    						]
    				]
    		];
    	}
    	
    	$sentence = explode(' ', $v);
    	$firstWord = $sentence[0];
    	
    	$should[] = [
    						"span_first" => [
    							"match" => [
    								"span_term" => [
    									"search_name" => $firstWord
    								]
    							],
    							"end" => 1,
    							"boost" => 3
    						]	
    					];
    	
    	$params = [
    		"query" => [
    			"bool" => [
    				"must" => [
    					"bool" => [
    						"should" => [
    							[
    								"match" => [
    									"search_name" => [
    										"query" => $v,
    										"operator" => "and"	
    									]
    								]
    							],
    							[
    								"match_phrase_prefix" => [
    									"search_name" => [
    										"query" => $v,
    										"operator" => "and",
    										"slop" => 5
    									]
    								]
    							],
    							[
    								"match_phrase_prefix" => [
    									"search_field" => [
    										"query" => $v,
    										"operator" => "and",
    										"slop" => 8
    									]
    								]
    									
    							]
    						]	
    					]
    				],
    				"should" => $should
    			]	
    		],
    		'_source' => ['full_name', AdProduct::TYPE_FOR_SELL_TOTAL, AdProduct::TYPE_FOR_RENT_TOTAL]
    	];
    	
    	$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . '/term/_search');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    		 
		$result = json_decode(curl_exec($ch), true);

		foreach ($result['hits']['hits'] as $k => $hit) {
			$response[$k]['full_name'] = $hit['_source']['full_name'];
			$response[$k]['type'] = $hit['_type'];
			$response[$k]['id'] = $hit['_id'];
		}
		
		return $response;
	}
}