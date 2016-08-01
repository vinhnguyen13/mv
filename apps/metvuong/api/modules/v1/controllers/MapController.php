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
		
		$type = \Yii::$app->request->get('t', AdProduct::TYPE_FOR_SELL);
		$v = \Yii::$app->request->get('v');
		
		$result = Elastic::searchAreas($v, $type);
		
		if($result['hits']['total'] == 0) {
			$result = Elastic::searchAreas(Elastic::transform($v), $type);
		}

		foreach ($result['hits']['hits'] as $k => $hit) {
			$response[$k]['full_name'] = $hit['_source']['full_name'];
			$response[$k]['type'] = $hit['_type'];
			$response[$k]['slug'] = $hit['_source']['slug'];
			$response[$k]['id'] = $hit['_id'];
		}
		
		return $response;
	}
}