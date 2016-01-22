<?php

namespace vsoft\craw\models;

use Yii;
use vsoft\ad\models\AdProductAdditionInfo as APAI;

/**
 * This is the model class for table "ad_product_addition_info".
 *
 * @property integer $product_id
 * @property double $facade_width
 * @property double $land_width
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
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_product_addition_info';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('dbCraw');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id'], 'required'],
            [['product_id', 'home_direction', 'facade_direction', 'floor_no', 'room_no', 'toilet_no'], 'integer'],
            [['facade_width', 'land_width'], 'number'],
            [['interior'], 'string', 'max' => 3200],
            [['product_id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
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
            'interior' => 'Interior',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(AdProduct::className(), ['id' => 'product_id']);
    }
    
    public function getHomeDirection() {
    	$dl = self::directionList();
    	
    	return empty($dl[$this->home_direction]) ? null : $dl[$this->home_direction];
    }
    
    public function getFacadeDirection() {
    	$dl = self::directionList();
    	
    	return empty($dl[$this->facade_direction]) ? null : $dl[$this->facade_direction];
    }
}
