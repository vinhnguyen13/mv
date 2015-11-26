<?php

namespace common\vendor\vsoft\ad\models\base;

use Yii;

/**
 * This is the model class for table "ad_product_geocoding".
 *
 * @property integer $product_id
 * @property double $lng
 * @property double $lat
 *
 * @property AdProduct $product
 */
class AdProductGeocodingBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_product_geocoding';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id'], 'integer'],
            [['lng', 'lat'], 'required'],
            [['lng', 'lat'], 'number'],
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
            'lng' => 'Lng',
            'lat' => 'Lat',
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
