<?php
namespace vsoft\craw\controllers;

use yii\web\Controller;
use yii\data\ActiveDataProvider;
use vsoft\craw\models\AdProductSearch;

class ManagerController extends Controller {
	public function actionIndex() {
		$adProduct = new AdProductSearch();
		$provider = $adProduct->search(\Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'filterModel' => $adProduct,
			'dataProvider' => $provider,
		]);
	}	
}