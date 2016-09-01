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
 * @property string $read_ip
 * @property string $click_ip
 * @property integer $id
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
            [['email'], 'string', 'max' => 255],
            [['read_ip', 'click_ip'], 'string', 'max' => 40],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'type' => 'Type',
            'count' => 'Count',
            'status' => 'Status',
            'send_time' => 'Send Time',
            'read_time' => 'Read Time',
            'click_time' => 'Click Time',
            'read_ip' => 'Read Ip',
            'click_ip' => 'Click Ip',
            'id' => 'ID',
        ];
    }
}
