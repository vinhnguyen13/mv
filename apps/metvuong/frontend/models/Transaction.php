<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 6/22/2016
 * Time: 1:35 PM
 */

namespace frontend\models;


use vsoft\ec\models\EcTransactionHistory;
use Yii;
use yii\helpers\ArrayHelper;

class Transaction extends EcTransactionHistory
{

    public static function me()
    {
        return Yii::createObject(self::className());
    }

    public function saveTransaction($transactionCode, $data){
        $transactionMv = Transaction::find()->where(['code'=>$transactionCode])->one();
        if(!empty($transactionMv)) {
            $transactionMv->updated_at = time();
        }else{
            $transactionMv = new Transaction();
            $transactionMv->created_at = time();
        }
        $transactionMv->setAttributes($data, false);
        $transactionMv->validate();
        if(!$transactionMv->hasErrors()) {
            return $transactionMv->save();
        }
        return $transactionMv->errors;
    }

}