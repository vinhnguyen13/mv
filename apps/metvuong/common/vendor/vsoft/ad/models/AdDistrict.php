<?php

namespace vsoft\ad\models;

use Yii;
use vsoft\ad\models\base\AdDistrictBase;

/**
 * This is the model class for table "ad_district".
 *
 * @property integer $id
 * @property integer $city_id
 * @property string $name
 * @property string $pre
 * @property integer $order
 * @property integer $status
 *
 * @property AdCity $city
 * @property AdProduct[] $adProducts
 * @property AdStreet[] $adStreets
 * @property AdWard[] $adWards
 */
class AdDistrict extends AdDistrictBase
{
	public function getStreets()
	{
		return $this->hasMany(AdStreet::className(), ['district_id' => 'id']);
	}
	

	public function getWards()
	{
		return $this->hasMany(AdWard::className(), ['district_id' => 'id']);
	}
}
