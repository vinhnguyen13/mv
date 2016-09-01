<?php

namespace vsoft\ad\models;

use vsoft\ad\models\base\MarkEmailBase;
use vsoft\ad\Module;
use Yii;

class MarkEmail extends MarkEmailBase
{
    const STATUS_SEND_SUCCESS = 1;
    const STATUS_SEND_FAILED = 0;
    const STATUS_EMAIL_NOT_EXISTS = -1;

    public static function getSendStatus($id=null)
    {
        $data = [
            self::STATUS_SEND_SUCCESS => Module::t('ec', 'Success'),
            self::STATUS_SEND_FAILED => Module::t('ec', 'Failed'),
            self::STATUS_EMAIL_NOT_EXISTS => Module::t('mark', 'Not Exists'),
        ];

        if ($id !== null && isset($data[$id])) {
            return $data[$id];
        } else {
            return $data;
        }
    }

    public static function checkEmailExists($email){
        return MarkEmail::getDb()->cache(function() use($email){
            return $markEmail = MarkEmail::find()->where('email = :e',[':e' => $email])->one();
        });
    }
}
