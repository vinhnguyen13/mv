<?php
namespace vsoft\ad\controllers;

use yii\web\Controller;
use vsoft\ad\models\AdCitySearch;
use vsoft\ad\models\AdCity;

class GeoController extends Controller {
	function actionIndex() {
		$searchModel = new AdCitySearch();
		$dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider
		]);
	}
	
	function actionGeoRecursive($cityId) {
		$city = AdCity::find()->asArray(true)->where(['id' => $cityId])->one();
		$geoFiles = array_diff(scandir(\Yii::getAlias('@store') . '/data/geometry'), array('..', '.'));
		
		return $this->render('geo-recursive', [
			'city' => $city,
			'geoFiles' => $geoFiles
		]);
	}
	
	public function actionEncodeGeometry() {
		$table = $_POST['table'];
		$id = $_POST['id'];
		$geometry = $_POST['geometry'];
		
		$connection = \Yii::$app->db;
		$connection->createCommand()->update('ad_' . $table, ['geometry' => $geometry], 'id = ' . $id)->execute();
	}
}