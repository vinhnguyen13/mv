<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 4/5/2016 11:13 AM
 */

namespace vsoft\express\models;

use yii\mongodb\ActiveRecord;

class SysEmail extends ActiveRecord
{
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'sys_email';
    }
    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['to_email'], 'required'],
            [['to_email'], 'email'],
            [['time'], 'integer'],
            [['from_user', 'from_email', 'to_email', 'subject', 'content', 'send_from', 'ip'], 'string'],
        ];
    }
    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return ['_id', 'from_user', 'from_email', 'to_email', 'subject', 'content', 'send_from', 'time', 'ip'];
    }
}