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
		$query->from("(SELECT `alias`, CONCAT_WS(' |*| ', CONCAT('ca : ', `created_at`), CONCAT('r : ', `referer`), CONCAT('f : ', `from`), CONCAT('l : ', `location`), CONCAT('t : ', `type`), CONCAT('ci : ', `category_id`), CONCAT('rn : ', `room_no`), CONCAT('tn : ', `toilet_no`), CONCAT('pi : ', `price_min`), CONCAT('pa : ', `price_max`), CONCAT('si : ', `size_min`), CONCAT('sa : ', `size_max`), CONCAT('o : ', `order_by`)) `content` FROM `tracking_search`) `tracking_search`");
		$query->select(["`alias`", "GROUP_CONCAT(`content` SEPARATOR ' ||| ') `contents`"]);
		$query->groupBy("alias");
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);
		
		return $this->render('group', [
			'dataProvider' => $dataProvider,
		]);
	}
}