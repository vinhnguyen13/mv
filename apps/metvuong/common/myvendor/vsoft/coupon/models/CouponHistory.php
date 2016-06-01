<?php

namespace vsoft\coupon\models;

use vsoft\coupon\models\base\CouponHistoryBase;
use Yii;

/**
 * This is the model class for table "cp_history".
 *
 * @property integer $user_id
 * @property integer $cp_code_id
 * @property integer $cp_event_id
 * @property integer $created_at
 */
class CouponHistory extends CouponHistoryBase
{

}
