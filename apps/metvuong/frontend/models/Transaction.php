<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 6/22/2016
 * Time: 1:35 PM
 */

namespace frontend\models;


use vsoft\ec\models\base\EcTransactionHistoryBase;
use Yii;

class Transaction extends EcTransactionHistoryBase
{
    const STATUS_PENDING = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_FAIL = -2;
    const STATUS_CANCELED = -1;

    const OBJECT_TYPE_POST  = 1;
    const OBJECT_TYPE_BOOST  = 2;
    const OBJECT_TYPE_DASHBOARD  = 3;
    const OBJECT_TYPE_BUY_KEYS  = 4;
    const OBJECT_TYPE_UPDATE_EXPIRED  = 5;

    public static function me()
    {
        return Yii::createObject(self::className());
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function saveTransaction($transactionCode, $data){
        $transactionMv = Transaction::find()->where(['code'=>$transactionCode])->one();
        if(!empty($transactionMv)) {
            $transactionMv->updated_at = time();
        }else{
            $transactionMv = new Transaction();
            $transactionMv->created_at = time();
        }
        $transactionMv->attributes = $data;
        $transactionMv->save();
    }

    public function convertVND2Keys($amount_VND)
    {
        return $amount_VND/1000;
    }
}