<?php
namespace vsoft\ad\models;

use common\models\TrackingSearch as TS;

class TrackingSearch extends TS {
	public static function track($data) {
		$ip = \Yii::$app->request->userIP;
		$now = time();
		
		$previousTracking = TrackingSearch::find()->asArray(true)->select(['MAX(created_at) `created_at`'])->where(['ip' => $ip])->one();
	
		if(!$previousTracking || $now - $previousTracking['created_at'] > 5) {
			$trackingSearch = new TrackingSearch();
			$trackingSearch->load($data, '');
			if(!\Yii::$app->user->isGuest) {
				$trackingSearch->user_id = \Yii::$app->user->id;
			}
			$trackingSearch->ip = $ip;
			$trackingSearch->session = \Yii::$app->session->id;
			$trackingSearch->created_at = $now;
			$trackingSearch->save(false);
		}
	}
}