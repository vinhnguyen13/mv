<?php

namespace vsoft\coupon\models;

use vsoft\coupon\models\base\CouponHistoryBase;
use vsoft\news\models\Status;
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
    public static function checkCoupon($code)
    {
        $coupon = CouponCode::find()->where('code = :c', [':c' => $code])->andWhere(['status' => Status::STATUS_ACTIVE])->one();
        
        if(count($coupon) > 0){
            $coupon_event = $coupon->couponEvent;
            $time = time();
            if($coupon_event->start_date <= $time && $coupon_event->end_date >= $time) {
                $check = true;
                $user_id = Yii::$app->user->isGuest ? null : Yii::$app->user->id;
                $coupon_id = $coupon->id;
                $history = null;
                if (!empty($user_id))
                    $history = CouponHistory::find()->where(['cp_code_id' => $coupon_id, 'user_id' => $user_id])->asArray()->one();
                else
                    $check = false;

                if (count($history) > 0) {
                    $check = false;
                }

                if ($coupon->type == 1 && $coupon->count >= 1) {
                    $check = false;
                }

                if ($check) {
                    $cp_history = new CouponHistory();
                    $cp_history->user_id = $user_id;
                    $cp_history->cp_code_id = $coupon_id;
                    $cp_history->cp_event_id = $coupon->cp_event_id;
                    $cp_history->created_at = time();
                    if ($cp_history->save()) {
                        $coupon->count = $coupon->count + 1;
                        $coupon->update(false);
                    }
                    return $cp_history;
                }
            }
        }
        return null;
    }
}
