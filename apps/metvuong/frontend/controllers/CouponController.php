<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 6/23/2016
 * Time: 1:58 PM
 */

namespace frontend\controllers;

use frontend\components\Controller;
use frontend\models\Payment;
use vsoft\coupon\models\CouponHistory;
use Yii;
use yii\web\Response;

class CouponController extends Controller
{
    public function actionProcess()
    {
        $this->checkAccess();
        if(Yii::$app->request->isAjax) {
            /**
             * delete coupon history of user
             */
//            CouponHistory::deleteAll(['user_id'=>Yii::$app->user->id]);
            /**
             *
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            $code = \Yii::$app->request->post('code');
            $res = CouponHistory::checkCoupon(Yii::$app->user->id, $code);
            if (!empty($res['error_code'] == 0) && !empty($res['result']->couponCode->amount)) {
                Payment::me()->processTransactionByCoupon($res['result']);
                return ['error_code'=>0, 'result'=>Yii::t('coupon', 'Thank you for using coupon')];
            }
            if($res['error_message']){
                return ['error_code'=>2, 'error_message'=>$res['error_message']];
            }
        }
        return false;
    }
}