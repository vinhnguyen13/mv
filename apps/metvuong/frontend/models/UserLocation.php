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

    /** @var \dektrium\user\Module */
    protected $module;

    /** @inheritdoc */
    public function init()
    {
        $this->module = Yii::$app->getModule('user');
    }

    public function getUser()
    {
        return $this->hasOne($this->module->modelMap['User'], ['id' => 'user_id']);
    }

}