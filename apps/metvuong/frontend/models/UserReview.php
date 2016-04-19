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

    const REPORT_LIST = [
        3 => 'It is spam',
        4 => 'It is inappropriate',
        5 => 'It insults or attacks someone based on their religion, ethnicity or sexual orientation',
        6 => 'It describes buying or selling drugs, guns or regulated products',
    ];

    public function getUser()
    {
        return $this->hasOne($this->module->modelMap['User'], ['id' => 'user_id']);
    }
}