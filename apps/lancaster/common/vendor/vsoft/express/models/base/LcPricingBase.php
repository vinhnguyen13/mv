<?php

namespace vsoft\express\models\base;

use vsoft\express\models\LcApartmentType;
use Yii;

/**
 * This is the model class for table "lc_pricing".
 *
 * @property integer $id
 * @property integer $apart_type_id
 * @property string $area
 * @property string $monthly_rates
 * @property string $daily_rates
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property LcApartmentType $apartType
 */
class LcPricingBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lc_pricing';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['apart_type_id', 'created_by', 'updated_by'], 'integer'],
//            [['area', 'monthly_rates', 'daily_rates'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('pricing', 'ID'),
            'apart_type_id' => Yii::t('pricing', 'Apart Type ID'),
            'area' => Yii::t('pricing', 'Area'),
            'monthly_rates' => Yii::t('pricing', 'Monthly Rates'),
            'daily_rates' => Yii::t('pricing', 'Daily Rates'),
            'description' => Yii::t('pricing', 'Description'),
            'created_at' => Yii::t('pricing', 'Created At'),
            'updated_at' => Yii::t('pricing', 'Updated At'),
            'created_by' => Yii::t('pricing', 'Created By'),
            'updated_by' => Yii::t('pricing', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApartType()
    {
        return $this->hasOne(LcApartmentType::className(), ['id' => 'apart_type_id']);
    }
}
