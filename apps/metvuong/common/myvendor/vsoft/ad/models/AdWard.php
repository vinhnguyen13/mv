<?php

namespace vsoft\ad\models;

use Yii;
use common\models\AdWard as AW;

/**
 * This is the model class for table "ad_ward".
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
class AdWard extends AW
{
	public static function getListByDistrict($districtId) {
		$items = [];
	
		if($districtId) {
			$wards = self::find()->where('`district_id` = :district_id', [':district_id' => $districtId])->all();
				
			foreach($wards as $ward) {
				$items[$ward['id']] = "{$ward['pre']} {$ward['name']}";
			}
		}
	
		return $items;
	}
}
