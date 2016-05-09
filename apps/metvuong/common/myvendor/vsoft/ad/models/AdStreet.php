<?php

namespace vsoft\ad\models;

use Yii;
use common\models\AdStreet as ASt;

/**
 * This is the model class for table "ad_street".
 *
 * @property integer $id
 * @property integer $district_id
 * @property string $name
 * @property string $pre
 * @property integer $order
 * @property integer $status
 *
 * @property AdProduct[] $adProducts
 * @property AdDistrict $district
 */
class AdStreet extends ASt
{
	public static function getListByDistrict($districtId) {
		$items = [];
	
		if($districtId) {
			$streets = self::find()->orderBy('name')->where('`district_id` = :district_id', [':district_id' => $districtId])->all();
	
			usort($streets, function($a, $b) {
				return strnatcmp($a['name'], $b['name']);
			});
			
			foreach($streets as $street) {
				$items[] = [
					'id' => $street['id'],
					'pre' => $street['pre'],
					'name' => $street['name']
				];
			}
		}
	
		return $items;
	}
}
