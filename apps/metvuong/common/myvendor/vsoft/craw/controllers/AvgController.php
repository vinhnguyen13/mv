<?php
namespace vsoft\craw\controllers;

use yii\web\Controller;
use frontend\models\Elastic;
use yii\helpers\Url;
use vsoft\craw\models\AdProductSearch2;

class AvgController extends Controller {
	public function init() {
		\Yii::$app->language = 'vi-VN';
	}
	
	public function actionIndex() {
		return $this->render('index');
	}
	
	public function actionSearch() {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
	
		$v = \Yii::$app->request->get('v');
	
		$params = Elastic::buildParams($v);
		
		$params['query']['function_score']['functions'][] = [
			"field_value_factor" => [
				"field" => "total_sell",
				"modifier" => "log1p",
				"factor" => 0.1
			]
		];
			
		$params['query']['function_score']['functions'][] = [
			"field_value_factor" => [
				"field" => "total_rent",
				"modifier" => "log1p",
				"factor" => 0.1
			]
		];
	
		$result = Elastic::requestResult($params, Elastic::elasticUrl('/district,ward,project_building'));
	
		$response = [];
	
		foreach ($result['hits']['hits'] as $hit) {
			$response[] = [
				'id' => $hit['_id'],
				'type' => $hit['_type'],
				'full_name' => $hit['_source']['full_name']
			];
		}
	
		return $response;
	}
	
	public function actionCalculate() {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$get = \Yii::$app->request->get();
		
		return ['url' => $this->buildUrl($get), 'sheets' => $this->buildSheets($get)];
	}
	
	public function buildUrl($get) {
		$params = $this->buildParams($get);
		
		array_unshift($params, 'manager/index2');
		
		return Url::to($params);
	}
	
	public function buildSheets($get) {
		$params = $this->buildParams($get);
		
		$serachModel  = new AdProductSearch2();
		$provider = $serachModel->search($params);
		$query = $provider->query;
		$query->select(array_values(AdProductSearch2::$_columns));
		$query->addSelect(['ad_product.ward_id']);
		
		$products = $query->all(\Yii::$app->dbCraw);
		
		$sheets = [];
		
		$fn = 'buildSheets' . ucfirst($get['type']);
		$sheets = $this->$fn($get, $products);
		
		return $sheets;
	}
	
	public function buildSheetsDistrict($get, $products) {
		$groupByWard = $this->groupByWard($products);
		$parentName = current(explode(', ', $get['location']));
		
		$sheets[] = $this->buildSheetFromGroupData($groupByWard, $parentName, $parentName . ' - Overview');
		
		foreach ($groupByWard as $wardName => $wardProducts) {
			$sheets[] = $this->_buildSheetsWard($wardName, $wardName, $wardProducts);
		}
		
		return $sheets;
	}
	
	public function buildSheetFromGroupData($groupData, $parentName, $SheetName) {
		$childs = [];
			
		foreach ($groupData as $name => $products) {
			$childs[] = [
				'name' => $name,
				'value' => $this->avg($products)
			];
		}
			
		$parent = ['name' => $parentName];
			
		$totalPoint = $totalPrice = $totalSize = $totalBed = $totalBath = $totalPriceSize = 0;
			
		foreach ($childs as $sos) {
			$sos = $sos['value'];
				
			$totalPoint += $sos['Data Point'];
			$totalPrice += $sos['AVG Price'];
			$totalSize += $sos['AVG SQM'];
			$totalPriceSize += $sos['AVG $/SQM'];
			$totalBed += $sos['AVG Bed'];
			$totalBath += $sos['AVG Bath'];
		}
			
		$count = count($childs);
			
		$parent['value'] = [
			'Data Point' => $totalPoint,
			'AVG Price' => 	$totalPrice / $count,
			'AVG SQM' => 	$totalSize / $count,
			'AVG $/SQM' => $totalPriceSize / $count,
			'AVG Bed' => $totalBed,
			'AVG Bath' => $totalBath
		];
		
		return [
			'SheetName' => $SheetName,
			'data' => ['childs' => $childs, 'parent' => $parent]
		];
	}
	
	public function buildSheetsWard($get, $products) {
		$parentName = current(explode(', ', $get['location']));
		
		return $this->_buildSheetsWard($parentName . ' - Overview', $parentName, $products);
	}
	
	public function buildSheetsProject_building($get, $products) {
		$name = current(explode(', ', $get['location']));
		
		$childs[] = [
			'name' => $name,
			'value' => $this->avg($products)
		];
		
		return [[
			'SheetName' => $name,
			'data' => ['childs' => $childs]
		]];
	}
	
	public function _buildSheetsWard($sheetName, $name, $products) {
		$groupByProject = $this->groupByProject($products);

		$sheet = $this->buildSheetFromGroupData($groupByProject, $name, $sheetName);
		
		return $sheet;
	}
	
	public function avg($products) {
		$totalPrice = $totalSize = $totalBed = $totalBath = 0;
		
		$totalPriceSize = 0;
		$totalHasPriceSize = 0;
	
		$totalHasPrice = 0;
		$totalHasSize = 0;
		
		foreach ($products as $product) {
			if($product['price']) {
				$totalPrice += $product['price'];
				$totalHasPrice++;
			}
			
			if($product['area']) {
				$totalSize += $product['area'];
				$totalHasSize++;
			}
			
			if($product['price'] && $product['area']) {
				$totalHasPriceSize++;
				$totalPriceSize += ($product['price'] / $product['area']);
			}
			
			$totalBed += $product['room_no'];
			$totalBath += $product['toilet_no'];
		}
		
		$avgPrice = $totalHasPrice ? $totalPrice/$totalHasPrice : 0;
		$avgSize = $totalHasSize ? $totalSize/$totalHasSize : 0;
		$avgPriceSize = $totalHasPriceSize ? $totalPriceSize/$totalHasPriceSize : 0;

		return ['Data Point' => count($products), 'AVG Price' => $avgPrice, 'AVG SQM' => $avgSize, 'AVG $/SQM' => $avgPriceSize, 'AVG Bed' => $totalBed, 'AVG Bath' => $totalBath];
	}
	
	public function groupByProject($products) {
		$groupByProject = [];

		foreach ($products as $product) {
			$groupByProject[$product['project_name']][] = $product;
		}
			
		uksort($groupByProject, "strnatcmp");
		
		return $groupByProject;
	}
	
	public function groupByWard($products) {
		$groupByWard = [];
		
		foreach ($products as $product) {
			$groupByWard[$product['ward_name']][] = $product;
		}
		
		uksort($groupByWard, "strnatcmp");
		
		return $groupByWard;
	}
	
	public function buildParams($get) {
		$urlMapping = ['district' => ['district_name_mask', 'district_id'],	'ward' => ['ward_name_mask', 'ward_id'], 'project_building' => ['project_name_mask', 'project_building_id']];
		$urlMapping = $urlMapping[$get['type']];
		
		$params = ['category_id' => 6, 'type' => $get['t'], $urlMapping[0] => $get['location'], $urlMapping[1] => $get['id']];
		
		if($get['type'] == 'ward') {
			$params['ward_name_filter'] = 3;
			$params['project_name_filter'] = 1;
		} else if($get['type'] == 'project_building') {
			$params['project_name_filter'] = 3;
		} else {
			$params['project_name_filter'] = 1;
			$params['ward_name_filter'] = 1;
		}
		
		return $params;
	}
}