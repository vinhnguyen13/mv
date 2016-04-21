<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 4/6/2016 11:10 AM
 */

namespace frontend\models;


class UserReview extends \vsoft\user\models\base\UserReview
{
    const TYPE_1 = 'Giúp tôi mua nhà';
    const TYPE_2 = 'Giúp tôi thuê nhà';

    public function getUser()
    {
        return $this->hasOne($this->module->modelMap['User'], ['id' => 'user_id']);
    }
}