<?php

namespace vsoft\ad\models;

use Yii;
use vsoft\ad\models\base\AdStreetBase;

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
class AdStreet extends AdStreetBase
{
	
}
