<?php

namespace common\models;

use Yii;

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
 * @property string $facility
 *
 * @property AdProduct $product
 */
class AdProductAdditionInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_product_addition_info';
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
            [['facility'], 'string', 'max' => 255],
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
            'facility' => 'Facility',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(AdProduct::className(), ['id' => 'product_id']);
    }
}
