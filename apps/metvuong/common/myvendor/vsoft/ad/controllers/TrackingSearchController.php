<?php
namespace vsoft\ad\controllers;

use yii\web\Controller;
use vsoft\ad\models\TrackingSearch;
use yii\db\Query;
use yii\data\ActiveDataProvider;

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
		$query = new Query();
		$query->from("`tracking_search`");
		$query->select(["`alias`", "MAX(`created_at`) `created_at`"]);
		$query->groupBy("alias");
		$query->orderBy("created_at DESC");
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);
		
		return $this->render('group', [
			'dataProvider' => $dataProvider,
		]);
	}
}