<?php

namespace vsoft\ad\models\base;

use Yii;

/**
 * This is the model class for table "report_type".
 *
 * @property integer $id
 * @property string $name
 * @property integer $is_user
 * @property integer $created_at
 * @property integer $created_by
 */
class ReportType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'report_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['is_user', 'created_at', 'created_by'], 'integer'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('report', 'ID'),
            'name' => Yii::t('report', 'Name'),
            'is_user' => Yii::t('report', 'Type'),
            'created_at' => Yii::t('report', 'Created At'),
            'created_by' => Yii::t('report', 'Created By'),
        ];
    }
}
