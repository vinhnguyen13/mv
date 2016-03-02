<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 1/14/2016
 * Time: 1:58 PM
 */
namespace vsoft\user\models\base;
use yii\mongodb\ActiveRecord;

class UserActivity extends ActiveRecord
{
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'user_activity';
    }
    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['user_id', 'username', 'object_owner_id', 'object_owner_username'], 'required'],
            [['user_id', 'object_id', 'object_owner_id'], 'integer'],
        ];
    }
    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return ['_id', 'user_id', 'username', 'action', 'message', 'params', 'ip', 'object_id', 'object_owner_id', 'object_owner_username', 'parent_id', 'status', 'created', 'updated', 'read_status', 'read_time'];
    }

}