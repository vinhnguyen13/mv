<?php

namespace vsoft\express\models\base;

use Yii;

/**
 * This is the model class for table "lc_apartment_type".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property LcPricing[] $lcPricings
 */
class LcApartmentTypeBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lc_apartment_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 60],
            [['description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('apartment_type', 'ID'),
            'name' => Yii::t('apartment_type', 'Name'),
            'description' => Yii::t('apartment_type', 'Description'),
            'created_at' => Yii::t('apartment_type', 'Created At'),
            'updated_at' => Yii::t('apartment_type', 'Updated At'),
            'created_by' => Yii::t('apartment_type', 'Created By'),
            'updated_by' => Yii::t('apartment_type', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLcPricings()
    {
        return $this->hasMany(LcPricing::className(), ['apart_type_id' => 'id']);
    }
}
