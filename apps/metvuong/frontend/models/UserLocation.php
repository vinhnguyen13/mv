<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 3/7/2016 10:28 AM
 */

namespace frontend\models;

use Yii;

class UserLocation extends \vsoft\user\models\base\UserLocation
{
    const DEFAULT_CITY = 1;
    const DEFAULT_DISTRICT = 10;

    public function getUser()
    {
        return $this->hasOne($this->module->modelMap['User'], ['id' => 'user_id']);
    }

}