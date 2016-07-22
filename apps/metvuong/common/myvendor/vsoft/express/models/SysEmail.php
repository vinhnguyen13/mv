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
    const OBJECT_TYPE_SHARE     = 1;
    const OBJECT_TYPE_CONTACT   = 2;
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
            [['object_id', 'object_type', 'send_time', 'read_time'], 'integer'],
            [['from_name', 'from_email', 'to_name', 'to_email', 'subject', 'content', 'send_ip', 'read_ip'], 'string'],
        ];
    }
    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return ['_id', 'from_name', 'from_email', 'to_name', 'to_email', 'object_id', 'object_type', 'subject', 'content', 'params', 'send_time', 'send_ip', 'read_time', 'read_ip'];
    }
}