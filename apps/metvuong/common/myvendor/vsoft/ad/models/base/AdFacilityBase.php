<?php

namespace vsoft\ad\models\base;

use Yii;

/**
 * This is the model class for table "ad_facility".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $status
 */
class AdFacilityBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_facility';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'created_by', 'updated_by', 'status'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 1022],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('facility', 'ID'),
            'name' => Yii::t('facility', 'Name'),
            'description' => Yii::t('facility', 'Description'),
            'created_at' => Yii::t('facility', 'Created At'),
            'updated_at' => Yii::t('facility', 'Updated At'),
            'created_by' => Yii::t('facility', 'Created By'),
            'updated_by' => Yii::t('facility', 'Updated By'),
            'status' => Yii::t('facility', 'Status'),
        ];
    }
}
