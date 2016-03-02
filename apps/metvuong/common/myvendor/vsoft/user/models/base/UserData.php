<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 1/14/2016
 * Time: 1:58 PM
 */
namespace vsoft\user\models\base;
use yii\mongodb\ActiveRecord;

class UserData extends ActiveRecord
{
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'user_data';
    }
    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['user_id', 'username'], 'required'],
            [['user_id'], 'integer'],
        ];
    }
    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return ['_id', 'user_id', 'username', 'alert', 'search'];
    }

}