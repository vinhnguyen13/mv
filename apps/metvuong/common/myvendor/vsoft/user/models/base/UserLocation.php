<?php

namespace vsoft\user\models\base;

use Yii;

/**
 * This is the model class for table "user_location".
 *
 * @property integer $user_id
 * @property integer $city_id
 * @property integer $district_id
 * @property integer $ward_id
 * @property integer $street_id
 */
class UserLocation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_location';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'city_id', 'district_id', 'ward_id', 'street_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('user_location', 'User ID'),
            'city_id' => Yii::t('user_location', 'City ID'),
            'district_id' => Yii::t('user_location', 'District ID'),
            'ward_id' => Yii::t('user_location', 'Ward ID'),
            'street_id' => Yii::t('user_location', 'Street ID'),
        ];
    }
}
