<?php

namespace common\vendor\vsoft\ad\models\base;

use Yii;

/**
 * This is the model class for table "ad_city".
 *
 * @property integer $id
 * @property string $name
 * @property integer $status
 *
 * @property AdDistrict[] $adDistricts
 * @property AdProduct[] $adProducts
 */
class AdCityBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdDistricts()
    {
        return $this->hasMany(AdDistrict::className(), ['city_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdProducts()
    {
        return $this->hasMany(AdProduct::className(), ['city_id' => 'id']);
    }
}
