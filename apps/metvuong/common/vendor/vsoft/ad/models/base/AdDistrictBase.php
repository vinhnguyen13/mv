<?php

namespace common\vendor\vsoft\ad\models\base;

use Yii;
use common\vendor\vsoft\ad\models\AdStreet;

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
class AdDistrictBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_district';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city_id', 'name'], 'required'],
            [['city_id', 'order', 'status'], 'integer'],
            [['name', 'pre'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'city_id' => 'City ID',
            'name' => 'Name',
            'pre' => 'Pre',
            'order' => 'Order',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(AdCity::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdProducts()
    {
        return $this->hasMany(AdProduct::className(), ['district_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdStreets()
    {
        return $this->hasMany(AdStreet::className(), ['district_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdWards()
    {
        return $this->hasMany(AdWard::className(), ['district_id' => 'id']);
    }
}
