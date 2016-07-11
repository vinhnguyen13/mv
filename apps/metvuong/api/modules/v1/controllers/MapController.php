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
		$response = [];
		
		$result = Elastic::searchAreas(Elastic::transform(\Yii::$app->request->get('v', '')), \Yii::$app->request->get('t', AdProduct::TYPE_FOR_SELL));

		foreach ($result['hits']['hits'] as $k => $hit) {
			$response[$k]['full_name'] = $hit['_source']['full_name'];
			$response[$k]['type'] = $hit['_type'];
			$response[$k]['id'] = $hit['_id'];
		}
		
		return $response;
	}
}