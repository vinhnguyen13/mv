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
		\Yii::$app->db->createCommand("SET SESSION group_concat_max_len = 1000000;")->execute();
		
		$query = new Query();
		$query->from("`tracking_search`");
		$query->select(["`alias`"]);
		$query->groupBy("alias");
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);
		
		return $this->render('group', [
			'dataProvider' => $dataProvider,
		]);
	}
}