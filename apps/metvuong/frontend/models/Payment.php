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
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


class Payment extends Component
{
    public static function me()
    {
        return Yii::createObject(self::className());
    }

    public function payWithNganLuong(){
        if(isset($_POST['nlpayment'])){
            $transaction_code = md5(uniqid(rand(), true));
            Transaction::me()->saveTransaction($transaction_code, [
                'code'=>$transaction_code,
                'user_id'=>Yii::$app->user->identity->id,
                'object_id'=>NganLuong::METHOD_BANKING,
                'object_type'=>Transaction::OBJECT_TYPE_BUY_KEYS,
                'amount'=>Transaction::me()->convertVND2Keys($_POST['total_amount']),
                'balance'=>0,
                'status'=>Transaction::STATUS_PENDING,
            ]);
            return NganLuong::me()->payByBank([
                'return_url' => Url::to(['/payment/success'], true),
                'cancel_url' => Url::to(['/payment/cancel'], true),
                'transaction_code' => $transaction_code,
            ]);
        }
        if(isset($_POST['NLNapThe'])){
            return NganLuong::me()->payByMobiCard();
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



    public function transactionNganluong($token, $data){
        $transaction_nganluong = $this->getTransactionNganluong(['token'=>$token]);
        if(empty($transaction_nganluong)){
            $columns = ArrayHelper::merge([
                'token' => $token,
                'created_at' => time(),
            ], $data);
            Yii::$app->db->createCommand()->insert('ec_transaction_nganluong', $columns)->execute();
        }
        return $transaction_nganluong;
    }

    public function getTransactionNganluong($condition){
        $query = new Query();
        $transaction_nganluong = $query->select('id,token,transaction_code')->from('ec_transaction_nganluong')->where($condition)->one();
        if(!empty($transaction_nganluong)){
            return $transaction_nganluong;
        }
    }

    public function getTransactionWithNganluong($condition){
        $query = new Query();
        $transaction_nganluong = $query->select('code,user_id,object_id,object_type,b.amount,balance,b.status,params,b.created_at,b.updated_at')->from('ec_transaction_nganluong a')
            ->join('INNER JOIN', 'ec_transaction_history b', 'b.code = a.transaction_code')
            ->where($condition)->one();
        if(!empty($transaction_nganluong)){
            return $transaction_nganluong;
        }
    }

    public function getTransactionMetVuong($condition){
        $query = new Query();
        $transaction = $query->select('id,code,user_id,object_id,object_type,amount,balance,status,params,created_at,updated_at')
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
                $transactionNganLuong = $this->getTransactionWithNganluong(['token'=>$token]);
                if(!empty($transactionNganLuong['code'])){
                    $balance = Yii::$app->user->identity->getBalance();
                    $balanceValue = !empty($balance->amount) ? ($balance->amount + $transactionNganLuong['amount']) : $transactionNganLuong['amount'];
                    $checkUpdate = Yii::$app->db->createCommand()
                        ->update('ec_transaction_history', [
                            'status' => Transaction::STATUS_TRANSFERRED,
                            'balance' => $balanceValue,
                        ], 'code=:code', [':code'=>$transactionNganLuong['code']])->execute();
                    Yii::$app->db->createCommand()
                        ->update('ec_transaction_nganluong', [
                            'status' => Transaction::STATUS_TRANSFERRED,
                        ], 'transaction_code=:code', [':code'=>$transactionNganLuong['code']])->execute();
                    if($checkUpdate){
                        $this->updateBalance($transactionNganLuong['user_id'], $transactionNganLuong['amount']);
                    }
                }

            }
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * @param $token
     * @return bool
     * @throws \Exception
     * link http://localhost/payment/success?error_code=00&token=3221723-e66bec1fc53bff03b5aea93c694fdcc7
     */
    public function success($token){
        return $this->processTransaction($token);
    }

    public function cancel($tid){

    }
}