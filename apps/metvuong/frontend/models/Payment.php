<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 6/16/2016
 * Time: 4:47 PM
 */

namespace frontend\models;

use Yii;
use yii\base\Component;
use yii\db\Query;
use yii\helpers\Url;


class Payment extends Component
{
    const PRICE_LIST = [
        2000,50000,100000,200000,500000
    ];
    public static function me()
    {
        return Yii::createObject(self::className());
    }

    public function payByBank(){
        NganLuong::me()->payByBank([
            'return_url' => Url::to(['/payment/success'], true),
            'cancel_url' => Url::to(['/payment/cancel', 'tid'=>'transactionid'], true),
        ]);
    }

    public function payByMobiCard(){
        NganLuong::me()->payByMobiCard();
    }

    public function updateBalance($user_id, $amount){
        $query = new Query();
        $query->select('id, amount')->from('ec_balance')->where(['user_id'=>$user_id])->limit(1);
        $balance = $query->one();
        if(!empty($balance)){
            $amount += $balance['amount'];
            Yii::$app->db->createCommand()
                ->update('ec_balance', [
                    'amount' => $amount,
                ], 'user_id='.$user_id)->execute();
        }else{
            Yii::$app->db->createCommand()
                ->insert('ec_balance', [
                    'user_id' => $user_id,
                    'amount' => $amount,
                ])->execute();
        }
        return true;
    }

    public function updateTransactionNganluong($token){
        $query = new Query();
        $query->select('id')->from('ec_transaction_nganluong')->where(['token'=>$token])->limit(1);
        $transaction_nganluong = $query->one();
        if(empty($transaction_nganluong)){
            Yii::$app->db->createCommand()
                ->insert('ec_transaction_nganluong', [
                    'token' => $token,
                ])->execute();
        }
        return true;
    }

    public function getTransactionByUniqid($uniqid){
        $query = new Query();
        $query->select('status')->from('ec_transaction_history')->where(['uniqid'=>$uniqid])->limit(1);
        $transaction = $query->one();
        if(!empty($transaction)){
            return $transaction;
        }
    }

    public function updateTransaction($token){
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $getToken = NganLuong::me()->getTransactionDetail($token);
            if(!empty($getToken)){
                $transaction = $this->getTransactionByUniqid($getToken['order_code']);
                if(!empty($transaction)){
                    Yii::$app->db->createCommand()
                        ->update('ec_transaction_history', [
                            'status' => 1,
                        ], 'id='.$transaction['id'])->execute();
                }

            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function success(){

    }

    public function cancel($tid){

    }
}