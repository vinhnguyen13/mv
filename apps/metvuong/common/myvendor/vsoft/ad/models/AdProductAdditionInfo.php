<?php

namespace vsoft\ad\models;

use Yii;
use common\models\AdProductAdditionInfo as APAI;

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
class AdProductAdditionInfo extends APAI
{
	public function rules()
    {
        return [
            [['product_id', 'home_direction', 'facade_direction', 'floor_no', 'room_no', 'toilet_no'], 'integer'],
            [['facade_width', 'land_width'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/'],
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
    
    public static function directionList() {
    	return [
			0 => 'KXĐ',
			1 => 'Đông',
			2 => 'Tây',
			3 => 'Nam',
			4 => 'Bắc',
			5 => 'Đông-Bắc',
			6 => 'Tây-Bắc',
			7 => 'Đông-Nam',
			8 => 'Tây-Nam'
    	];
    }
    
	public function beforeSave($insert) {
		if($this->facade_width) {
			$this->facade_width = str_replace(',', '.', $this->facade_width);
		}
		
		if($this->land_width) {
			$this->land_width = str_replace(',', '.', $this->land_width);
		}
		
		return parent::beforeSave($insert);
	}
}
