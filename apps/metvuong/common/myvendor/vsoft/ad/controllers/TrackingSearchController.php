<?php
namespace vsoft\ad\controllers;

use yii\web\Controller;
use vsoft\ad\models\TrackingSearch;
use vsoft\ad\models\TrackingSearchGroup;

class TrackingSearchController extends Controller {
	public function actionIndex() {
		$searchModel = new TrackingSearch();
		$dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}	
	public function actionGroup() {
		$searchModel = new TrackingSearchGroup();
		$dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
		
		return $this->render('group', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
}