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
        	['facility', 'safe'],
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
            'facade_width' => Yii::t('ad', 'Facade'),
            'land_width' => Yii::t('ad', 'Entry width'),
            'home_direction' => Yii::t('ad', 'House direction'),
            'facade_direction' => Yii::t('ad', 'Balcony direction'),
            'floor_no' => Yii::t('ad', 'Number of storeys'),
            'room_no' => Yii::t('ad', 'Beds'),
            'toilet_no' => Yii::t('ad', 'Baths'),
            'interior' => Yii::t('ad', 'Furniture'),
        ];
    }
    
    public static function directionList() {
    	return [
			0 => Yii::t('ad', 'NA'),
			1 => Yii::t('ad', 'East'),
			2 => Yii::t('ad', 'West'),
			3 => Yii::t('ad', 'South'),
			4 => Yii::t('ad', 'North'),
			5 => Yii::t('ad', 'East-North'),
			6 => Yii::t('ad', 'West-North'),
			7 => Yii::t('ad', 'East-South'),
			8 => Yii::t('ad', 'West-South')
    	];
    }
    
    public function afterFind() {
    	$this->facility = explode(',', $this->facility);
    	
    	return parent::afterFind();
    }
    
	public function beforeSave($insert) {

		if($this->facility) {
			$this->facility = implode(',', $this->facility);
		}
		
		if($this->facade_width) {
			$this->facade_width = str_replace(',', '.', $this->facade_width);
		}
		
		if($this->land_width) {
			$this->land_width = str_replace(',', '.', $this->land_width);
		}
		
		return parent::beforeSave($insert);
	}
}
