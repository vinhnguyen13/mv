<?php
namespace vsoft\craw\controllers;

use yii\web\Controller;
use frontend\models\Elastic;
use yii\helpers\Url;
use vsoft\craw\models\AdProductSearch2;
use vsoft\ad\models\AdBuildingProject;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\Border;
use Box\Spout\Writer\Style\BorderBuilder;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;

class AvgController extends Controller {
	public function init() {
		\Yii::$app->language = 'vi-VN';
	}
	
	public function actionIndex() {
		return $this->render('index');
	}
	
	public function actionCalculate() {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$get = \Yii::$app->request->get();
		
		return ['exportUrl' => Url::to(['export'] + $get), 'url' => $this->buildUrl($get), 'sheets' => $this->buildSheets($get)];
	}
	
	public function actionExport() {
		include str_replace('controllers', 'components', dirname(__FILE__)) . '/Spout/Autoloader/autoload.php';
		
		ini_set('max_execution_time', 0);
		
		$get = \Yii::$app->request->get();
		
		$sheets = $this->buildSheets($get);
		
		$names = explode(', ', $get['location']);
		$name = $get['type'] == 'ward' ? $names[0] . ', ' . $names[1] : $names[0];
		$fileName = 'MV - ' . $name . ' - CH ' . ($get['t'] == 1 ? 'Bán' : 'Cho thuê') . ".xlsx";
		
		$writer = WriterFactory::create(Type::XLSX);
		$writer->openToBrowser($fileName);
		
		foreach ($sheets as $k => $sheet) {
			if($sheet['sheetName']) {
				$excelSheet = ($k == 0) ? $writer->getCurrentSheet() : $writer->addNewSheetAndMakeItCurrent();
				$excelSheet->setName($sheet['sheetName']);
				
				if($k == 0) {
					$writer->addRow(['']);
				}
					
				$rows = [[''], ['Data Point'], ['AVG Price'], ['AVG SQM'], ['AVG $/SQM'], ['AVG Bed'], ['AVG Bath']];
					
				$childs = $sheet['data']['childs'];
					
				foreach ($childs as $child) {
					if($child['name']) {
						$rows[0][] = $child['name'];
						$rows[1][] = $child['value']['Data Point'];
						$rows[2][] = $child['value']['AVG Price'];
						$rows[3][] = $child['value']['AVG SQM'];
						$rows[4][] = $child['value']['AVG $/SQM'];
						$rows[5][] = $child['value']['AVG Bed'];
						$rows[6][] = $child['value']['AVG Bath'];
					}
				}
					
				if(isset($sheet['data']['parent'])) {
					$parent = $sheet['data']['parent'];
				
					$rows[0][] = mb_strtoupper($parent['name'], 'UTF-8');
					$rows[1][] = $parent['value']['Data Point'];
					$rows[2][] = $parent['value']['AVG Price'];
					$rows[3][] = $parent['value']['AVG SQM'];
					$rows[4][] = $parent['value']['AVG $/SQM'];
					$rows[5][] = $parent['value']['AVG Bed'];
					$rows[6][] = $parent['value']['AVG Bath'];
				}
					
				$style = (new StyleBuilder())->setFontSize(11)->build();
				$titleStyle = clone $style;
				$titleStyle->setFontColor(Color::BLUE)->setFontBold();
					
				$writer->addRowWithStyle(array_shift($rows), $titleStyle);
				$writer->addRows($rows, $style);
			}
		}
		
		setcookie("avgComplete", 1);
		$writer->close();
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
		$query->addSelect(['ad_product.ward_id', 'ad_product.project_building_id']);
		
		$products = $query->all(\Yii::$app->dbCraw);
		
		$sheets = [];
		
		$fn = 'buildSheets' . ucfirst($get['type']);
		$sheets = $this->$fn($get, $products);
		
		return $sheets;
	}
	
	public function buildSheetsDistrict($get, $products) {
		$groupByWard = $this->groupByWard($products);
		$parentName = current(explode(', ', $get['location']));
		
		$sheets[] = [
			'sheetName' => $parentName . ' - Overview',
			'data' => [
				'parent' => [
					'name' => $parentName,
					'value' => $this->avg($products)
				],
				'childs' => $this->buildChildsFromGroupData($groupByWard)
			]
		];
		
		foreach ($groupByWard as $wardName => $wardProducts) {
			$sheets[] = $this->_buildSheetsWard($wardName, $wardName, $wardProducts);
		}
		
		return $sheets;
	}
	
	public function buildChildsFromGroupData($groupData) {
		$childs = [];
			
		foreach ($groupData as $name => $products) {
			$childs[] = [
				'name' => $name,
				'value' => $this->avg($products)
			];
		}
		
		return $childs;
	}
	
	public function buildSheetsWard($get, $products) {
		$parentName = current(explode(', ', $get['location']));
		
		return [$this->_buildSheetsWard($parentName . ' - Overview', $parentName, $products)];
	}
	
	public function buildSheetsProject_building($get, $products) {
		$name = current(explode(', ', $get['location']));
		
		$childs[] = [
			'name' => $name,
			'value' => $this->avg($products)
		];
		
		return [[
			'sheetName' => $name . ' - Overview',
			'data' => ['childs' => $childs]
		]];
	}
	
	public function _buildSheetsWard($sheetName, $name, $products) {
		$groupByProject = $this->groupByProject($products);
		
// 		$additionProjectData = [];
		
// 		foreach ($groupByProject as $name => $projectProducts) {
// 			if($projectProducts[0]['project_building_id']) {
// 				$additionProjectData[$name] = AdBuildingProject::findOne($projectProducts[0]['project_building_id']);
// 			}
// 		}

		$sheet = [
			'sheetName' => $sheetName,
			'data' => [
				'parent' => [
					'name' => $name,
					'value' => $this->avg($products)
				],
				'childs' => $this->buildChildsFromGroupData($groupByProject)
			]
		];
		
// 		foreach ($sheet['data']['childs'] as &$child) {
// 			$project = $additionProjectData[$child['name']];
// 			$child['value']['AdditionData'] = [];
			
// 			if($project['location']) {
// 				$child['value']['AdditionData']['Address'] = current(explode(', ', $project['location']));
// 			}
// 		}
		
		return $sheet;
	}
	
	public function avg($products) {
		$totalPrice = $totalSize = $totalBed = $totalBath = 0;
		
		$totalPriceSize = 0;
		$totalHasPriceSize = 0;
	
		$totalHasPrice = 0;
		$totalHasSize = 0;
		$totalHasBed = 0;
		$totalHasBath = 0;
		
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
				$totalPriceSize += ($product['price'] / $product['area']);
				$totalHasPriceSize++;
			}
			
			if($product['room_no']) {
				$totalBed += $product['room_no'];
				$totalHasBed++;
			}
			
			if($product['toilet_no']) {
				$totalBath += $product['toilet_no'];
				$totalHasBath++;
			}
		}
		
		$avgPrice = $totalHasPrice ? $totalPrice/$totalHasPrice : 0;
		$avgSize = $totalHasSize ? $totalSize/$totalHasSize : 0;
		$avgPriceSize = $totalHasPriceSize ? $totalPriceSize/$totalHasPriceSize : 0;
		$avgBed = $totalHasBed ? $totalBed/$totalHasBed : 0;
		$avgBath = $totalHasBath ? $totalBath/$totalHasBath : 0;

		return ['Data Point' => count($products), 'AVG Price' => $avgPrice, 'AVG SQM' => $avgSize, 'AVG $/SQM' => $avgPriceSize, 'AVG Bed' => $avgBed, 'AVG Bath' => $avgBath];
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
			if($get['hasProject']) {
				$params['project_name_filter'] = 1;
			}
		} else if($get['type'] == 'project_building') {
			$params['project_name_filter'] = 3;
		} else {
			if($get['hasProject']) {
				$params['project_name_filter'] = 1;
			}
			if($get['hasWard']) {
				$params['ward_name_filter'] = 1;
			}
		}
		
		return $params;
	}
}