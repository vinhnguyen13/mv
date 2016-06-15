<?php

namespace vsoft\ad\models\base;

use Yii;

/**
 * This is the model class for table "mark_email".
 *
 * @property string $email
 * @property integer $type
 * @property integer $count
 * @property integer $status
 * @property integer $send_time
 */
class MarkEmailBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mark_email';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['type', 'count', 'status', 'send_time'], 'integer'],
            [['email'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('mark', 'Email'),
            'type' => Yii::t('mark', 'Type'),
            'count' => Yii::t('mark', 'Count'),
            'status' => Yii::t('mark', 'Status'),
            'send_time' => Yii::t('mark', 'Send Time'),
        ];
    }
}
