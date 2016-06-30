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
use yii\web\HttpException;


class Payment extends Component
{
    public static function me()
    {
        return Yii::createObject(self::className());
    }

    public function payWithNganLuong($redirect){
        if(isset($_POST['nlpayment'])){
            $transaction_code = md5(uniqid(rand(), true));
            $amount = NganLuong::me()->VND2Keys(NganLuong::METHOD_BANKING, $_POST['total_amount']);
            if(!empty($amount) && is_numeric($amount)){
                Transaction::me()->saveTransaction($transaction_code, [
                    'code'=>$transaction_code,
                    'user_id'=>Yii::$app->user->identity->id,
                    'object_id'=>NganLuong::METHOD_BANKING,
                    'object_type'=>Transaction::OBJECT_TYPE_BUY_KEYS,
                    'amount'=>$amount,
                    'balance'=>0,
                    'status'=>Transaction::STATUS_PENDING,
                ]);
                return NganLuong::me()->payByBank([
                    'return_url' => Url::to(['/payment/success', 'redirect'=>$redirect], true),
                    'cancel_url' => Url::to(['/payment/cancel'], true),
                    'transaction_code' => $transaction_code,
                ]);
            }else{
                throw new HttpException(500, Yii::t('yii', 'Amount not real.'));
            }
        }
        if(isset($_POST['NLNapThe'])){
            $transaction_code = md5(uniqid(rand(), true));
            Transaction::me()->saveTransaction($transaction_code, [
                'code'=>$transaction_code,
                'user_id'=>Yii::$app->user->identity->id,
                'object_id'=>NganLuong::METHOD_MOBILE_CARD,
                'object_type'=>Transaction::OBJECT_TYPE_BUY_KEYS,
                'amount'=>0,
                'balance'=>0,
                'status'=>Transaction::STATUS_PROCESSING,
            ]);
            return NganLuong::me()->payByMobiCard([
                'transaction_code' => $transaction_code,
            ]);
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
                    'updated_at' => time(),
                ], 'user_id='.$user_id)->execute();
        }else{
            Yii::$app->db->createCommand()
                ->insert('ec_balance', [
                    'user_id' => $user_id,
                    'amount' => $amount,
                    'created_at' => time(),
                ])->execute();
        }
        Yii::$app->db->schema->refresh();
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
        $transaction_nganluong = $query->select('code,user_id,object_id,object_type,b.amount,balance,b.status,b.params,b.created_at,b.updated_at')->from('ec_transaction_nganluong a')
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

    public function processTransactionByBanking($token){
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $getToken = NganLuong::me()->getTransactionDetail($token);
            if(!empty($getToken->order_code)){
                $transactionNL = $this->getTransactionWithNganluong(['token'=>$token]);
                if(!empty($transactionNL['code']) && $transactionNL['status'] != Transaction::STATUS_SUCCESS){
                    $balance = Yii::$app->user->identity->getBalance();
                    $balanceValue = !empty($balance->amount) ? ($balance->amount + $transactionNL['amount']) : $transactionNL['amount'];
                    $checkUpdate = Yii::$app->db->createCommand()
                        ->update('ec_transaction_history', [
                            'status' => Transaction::STATUS_SUCCESS,
                            'balance' => $balanceValue,
                        ], 'code=:code', [':code'=>$transactionNL['code']])->execute();
                    Yii::$app->db->createCommand()
                        ->update('ec_transaction_nganluong', [
                            'status' => Transaction::STATUS_SUCCESS,
                        ], 'transaction_code=:code', [':code'=>$transactionNL['code']])->execute();
                    if($checkUpdate){
                        $this->updateBalance($transactionNL['user_id'], $transactionNL['amount']);
                    }
                }

            }
            $transaction->commit();
            \Yii::$app->getSession()->setFlash('popupsuccess', true);
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function processTransactionByMobileCard($transaction_code, $rs){
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $transactionMV = $this->getTransactionMetVuong(['code'=>$transaction_code]);
            if(!empty($transactionMV['code']) && $transactionMV['status'] != Transaction::STATUS_SUCCESS && !empty($rs) && $rs->error_code == '00'){
                Payment::me()->transactionNganluong($rs->transaction_id, [
                    'transaction_code'=>$transactionMV['code'],
                    'payment_method'=>NganLuong::METHOD_MOBILE_CARD,
                    'amount'=>$rs->card_amount,
                    'buyer_fullname'=>$rs->client_fullname,
                    'buyer_email'=>$rs->client_email,
                    'buyer_mobile'=>$rs->client_mobile,
                    'type_card'=>$rs->type_card,
                    'status'=>Transaction::STATUS_SUCCESS,
                ]);
                $amout = NganLuong::me()->VND2Keys(NganLuong::METHOD_MOBILE_CARD, $rs->card_amount);
                $amout = intval($amout);
                $balance = Yii::$app->user->identity->getBalance();
                $balanceValue = !empty($balance->amount) ? ($balance->amount + $amout) : $amout;
                $checkUpdate = Yii::$app->db->createCommand()
                    ->update('ec_transaction_history', [
                        'status' => Transaction::STATUS_SUCCESS,
                        'balance' => $balanceValue,
                        'amount'=>$amout,
                    ], 'code=:code', [':code'=>$transactionMV['code']])->execute();
                if($checkUpdate){
                    $this->updateBalance($transactionMV['user_id'], $amout);
                }
            }
            $transaction->commit();
            \Yii::$app->getSession()->setFlash('popupsuccess', true);
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function processTransactionByCoupon($coupon_history){
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $transaction_code = md5(uniqid(rand(), true));
            $amout = intval($coupon_history->couponCode->amount);
            $balance = Yii::$app->user->identity->getBalance();
            $balanceValue = !empty($balance->amount) ? ($balance->amount + $amout) : $amout;
            $checkUpdate = Transaction::me()->saveTransaction($transaction_code, [
                'code'=>$transaction_code,
                'user_id'=>Yii::$app->user->identity->id,
                'object_id'=>$coupon_history->couponCode->id,
                'object_type'=>Transaction::OBJECT_TYPE_GET_KEYS_FROM_COUPON,
                'amount'=>$amout,
                'balance'=>$balanceValue,
                'status'=>Transaction::STATUS_SUCCESS,
            ]);
            if($checkUpdate == true){
                $this->updateBalance(Yii::$app->user->id, $amout);
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
        return $this->processTransactionByBanking($token);
    }

    public function cancel(){
        \Yii::$app->getSession()->setFlash('popupcancel', true);
    }
}