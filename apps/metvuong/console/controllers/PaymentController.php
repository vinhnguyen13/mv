<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 7/12/2016 10:18 AM
 */

namespace console\controllers;

use vsoft\coupon\models\CouponHistory;
use yii\console\Controller;
use Yii;
use frontend\models\Payment;

class PaymentController extends Controller
{
    
    public function actionTest($start = 0, $end = 1000)
    {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            for($i = $start; $i<=$end; $i ++) {
                Payment::me()->updateBalance(8, 100);
                echo $i.PHP_EOL;
                usleep(5000);
            }
            $transaction->commit();
            print_r('=====DONE !=====');
            exit;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function actionTest2()
    {
        $res = CouponHistory::checkCoupon(8, 'I641J3');
        if (!empty($res['error_code'] == 0) && !empty($res['result']->couponCode->amount)) {
            Payment::me()->processTransactionByCoupon(8, $res['result']);
        }
        echo "<pre>";
        print_r($res);
        echo "</pre>";
        exit;
    }

    public function actionTest3()
    {
        $res = CouponHistory::checkCoupon(8, 'I641J5');
        if (!empty($res['error_code'] == 0) && !empty($res['result']->couponCode->amount)) {
            Payment::me()->processTransactionByCoupon(8, $res['result']);
        }
        echo "<pre>";
        print_r($res);
        echo "</pre>";
        exit;
    }
}