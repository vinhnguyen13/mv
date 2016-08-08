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
    public static function checkCoupon($user_id, $code)
    {
        if(empty($user_id)){
            return ['error_code'=>1, 'error_message'=>Yii::t('coupon', 'User not found')];
        }
        $coupon = CouponCode::find()->where('code = :c', [':c' => $code])->andWhere(['status' => Status::STATUS_ACTIVE])->one();

        if(count($coupon) > 0){
            $coupon_event = $coupon->couponEvent;
            $time = time();
            if($coupon_event->start_date <= $time && $coupon_event->end_date >= $time) {
                $coupon_id = $coupon->id;
                $history = CouponHistory::find()->where(['cp_code_id' => $coupon_id, 'user_id' => $user_id])->asArray()->one();
                if(!empty($history)){
                    return ['error_code'=>1, 'error_message'=>Yii::t('coupon', 'You used this code')];
                }
                if (!$coupon->check()) {
                    return ['error_code'=>1, 'error_message'=>Yii::t('coupon', 'This code is used')];
                }

                $cp_history = new CouponHistory();
                $cp_history->user_id = $user_id;
                $cp_history->cp_code_id = $coupon_id;
                $cp_history->cp_event_id = $coupon->cp_event_id;
                $cp_history->created_at = time();
                if ($cp_history->save()) {
                    $coupon->count = $coupon->count + 1;
                    $coupon->update(false);
                }
                return ['error_code'=>0, 'result'=>$cp_history];

            }else{
                return ['error_code'=>1, 'error_message'=>Yii::t('coupon', 'Event has expired')];
            }
        }
        return ['error_code'=>1, 'error_message'=>Yii::t('coupon', 'Code not found')];
    }

    public function getEvent()
    {
        return $this->hasOne(CouponEvent::className(), ['id' => 'cp_event_id']);
    }

    public function getCouponCode()
    {
        return $this->hasOne(CouponCode::className(), ['id' => 'cp_event_id']);
    }
}
