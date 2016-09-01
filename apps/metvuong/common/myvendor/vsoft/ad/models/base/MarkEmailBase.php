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
 * @property integer $read_time
 * @property integer $click_time
 * @property integer $read_ip
 * @property integer $click_ip
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
            [['type', 'count', 'status', 'send_time', 'read_time', 'click_time'], 'integer'],
            [['email', 'read_ip', 'click_ip'], 'string', 'max' => 255],
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
            'read_time' => Yii::t('mark', 'Read Time'),
            'click_time' => Yii::t('mark', 'Click Time'),
            'read_ip' => Yii::t('mark', 'Read Ip'),
            'click_ip' => Yii::t('mark', 'Click Ip'),
        ];
    }
}
