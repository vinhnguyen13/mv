<?php

namespace vsoft\ad\models;

use Yii;
use vsoft\ad\models\base\AdCityBase;

/**
 * This is the model class for table "ad_city".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property integer $order
 * @property integer $status
 *
 * @property AdDistrict[] $adDistricts
 * @property AdProduct[] $adProducts
 */
class AdCity extends AdCityBase
{
	public function getDistricts()
	{
		return $this->hasMany(AdDistrict::className(), ['city_id' => 'id']);
	}
}
