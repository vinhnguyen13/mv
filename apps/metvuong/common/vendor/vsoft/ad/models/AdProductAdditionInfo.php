<?php

namespace common\vendor\vsoft\ad\models;

use Yii;
use common\vendor\vsoft\ad\models\base\AdProductAdditionInfoBase;

/**
 * This is the model class for table "ad_product_addition_info".
 *
 * @property integer $product_id
 * @property integer $facade_width
 * @property integer $land_width
 * @property integer $home_direction
 * @property integer $facade_direction
 * @property integer $floor_no
 * @property integer $room_no
 * @property integer $toilet_no
 * @property string $interior
 *
 * @property AdProduct $product
 */
class AdProductAdditionInfo extends AdProductAdditionInfoBase
{
	public function rules()
	{
		return [
		[['product_id', 'facade_width', 'land_width', 'home_direction', 'facade_direction', 'floor_no', 'room_no', 'toilet_no'], 'integer'],
		[['interior'], 'string', 'max' => 3200],
		[['product_id'], 'unique']
		];
	}
	
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'facade_width' => 'Facade Width',
            'land_width' => 'Land Width',
            'home_direction' => 'Home Direction',
            'facade_direction' => 'Facade Direction',
            'floor_no' => 'Floor No',
            'room_no' => 'Room No',
            'toilet_no' => 'Toilet No',
            'interior' => 'Nội thất',
        ];
    }
}
