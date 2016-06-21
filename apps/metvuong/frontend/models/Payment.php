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
    public static function me()
    {
        return Yii::createObject(self::className());
    }

    public function payWithNganLuong(){
        if(isset($_POST['nlpayment'])){
            NganLuong::me()->payByBank([
                'return_url' => Url::to(['/payment/success'], true),
                'cancel_url' => Url::to(['/payment/cancel', 'tid'=>'transactionid'], true),
                'transaction_id' => 1,
            ]);
        }
        if(isset($_POST['NLNapThe'])){
            NganLuong::me()->payByMobiCard();
        }
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

    public function transactionNganluong($token, $transactionID){
        $transaction_nganluong = $this->getTransactionNganluong(['token'=>$token]);
        if(empty($transaction_nganluong)){
            Yii::$app->db->createCommand()
                ->insert('ec_transaction_nganluong', [
                    'token' => $token,
                    'transaction_id' => $transactionID,
                ])->execute();
        }
        return $transaction_nganluong;
    }

    public function getTransactionNganluong($condition){
        $query = new Query();
        $transaction_nganluong = $query->select('id,token,transaction_id')->from('ec_transaction_nganluong')->where($condition)->one();
        if(!empty($transaction_nganluong)){
            return $transaction_nganluong;
        }
    }

    public function getTransaction($condition){
        $query = new Query();
        $transaction = $query->select('id,user_id,object_id,object_type,amount,balance,action_type,action_detail,status,params,created_at,updated_at')
            ->from('ec_transaction_history')->where($condition)->one();
        if(!empty($transaction)){
            return $transaction;
        }
    }

    protected function processTransaction($token){
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $getToken = NganLuong::me()->getTransactionDetail($token);
            if(!empty($getToken->order_code)){
                $transactionHistory = $this->getTransaction(['id'=>$getToken->order_code]);
                if(!empty($transactionHistory['id'])){
                    $checkUpdate = Yii::$app->db->createCommand()
                        ->update('ec_transaction_history', [
                            'created_at' => time(),
                        ], 'id=:id', [':id'=>$transactionHistory['id']])->execute();
                    if($checkUpdate){
                        $this->updateBalance($transactionHistory['user_id'], $transactionHistory['amount']);
                    }
                }

            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function success($token){
        $this->processTransaction($token);
    }

    public function cancel($tid){

    }
}