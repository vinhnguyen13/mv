<?php

namespace vsoft\ec\models;

use frontend\models\NganLuong;
use frontend\models\Payment;
use frontend\models\User;
use vsoft\ad\models\AdProduct;
use vsoft\coupon\models\Coupon;
use vsoft\coupon\models\CouponCode;
use vsoft\coupon\models\CouponEvent;
use vsoft\ec\models\base\EcTransactionHistoryBase;
use Yii;

/**
 * This is the model class for table "ec_transaction_history".
 *
 * @property string $id
 * @property string $code
 * @property integer $user_id
 * @property integer $object_id
 * @property integer $object_type
 * @property integer $amount
 * @property string $balance
 * @property integer $charge_id
 * @property integer $status
 * @property string $params
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property EcCharge $charge
 * @property User $user
 */
class EcTransactionHistory extends EcTransactionHistoryBase
{

    const STATUS_PENDING = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_FAIL = -2;
    const STATUS_CANCELED = -1;

    /* object_id from table: ad_product*/
    const OBJECT_TYPE_POST  = 1;
    /* object_id from table: ad_product*/
    const OBJECT_TYPE_BOOST  = 2;
    /* object_id from table: */
    const OBJECT_TYPE_DASHBOARD  = 3;
    /* object_id from Method of NganLuong class: METHOD_BANKING, METHOD_MOBILE_CARD, METHOD_SMS*/
    const OBJECT_TYPE_BUY_KEYS  = 4;
    /* object_id from table: ad_product*/
    const OBJECT_TYPE_UPDATE_EXPIRED  = 5;
    /* object_id from table: cp_history*/
    const OBJECT_TYPE_GET_KEYS_FROM_COUPON  = 6;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ec_transaction_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'object_id', 'object_type', 'charge_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['amount'], 'number'],
            [['params'], 'string', 'max' => 32],
            [['charge_id'], 'exist', 'skipOnError' => true, 'targetClass' => EcCharge::className(), 'targetAttribute' => ['charge_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCharge()
    {
        return $this->hasOne(EcCharge::className(), ['id' => 'charge_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getTransactions($user_id)
    {
        return EcTransactionHistory::find()->where('user_id = :u',[':u' => $user_id])->all();
    }

    public function getPaymentMethod($id = null)
    {
        $data = [
            NganLuong::METHOD_BANKING => Yii::t('ec', 'Banking'),
            NganLuong::METHOD_MOBILE_CARD => Yii::t('ec', 'Mobile Credit'),
            NganLuong::METHOD_SMS => Yii::t('ec', 'SMS'),
        ];

        if (isset($data[$id])) {
            return $data[$id];
        } else {
            return null;
        }
    }

    public function getObjectType()
    {
        $data = [
            self::OBJECT_TYPE_POST => Yii::t('ec', 'Post'),
            self::OBJECT_TYPE_BOOST => Yii::t('ec', 'Boost'),
            self::OBJECT_TYPE_DASHBOARD => Yii::t('ec', 'Dashboard'),
            self::OBJECT_TYPE_BUY_KEYS => Yii::t('ec', 'Buy Keys'),
            self::OBJECT_TYPE_UPDATE_EXPIRED => Yii::t('ec', 'Update expired'),
            self::OBJECT_TYPE_GET_KEYS_FROM_COUPON => Yii::t('ec', 'Get Keys')
        ];

        if (isset($data[$this->object_type])) {
            return $data[$this->object_type];
        } else {
            return null;
        }
    }

    public function getTransactionStatus()
    {
        $data = [
            self::STATUS_SUCCESS => Yii::t('ec', 'Success'),
            self::STATUS_FAIL => Yii::t('ec', 'Failed'),
            self::STATUS_PENDING => Yii::t('ec', 'Pending'),
            self::STATUS_PROCESSING => Yii::t('ec', 'Processing'),
        ];

        if (isset($data[$this->status])) {
            return $data[$this->status];
        } else {
            return null;
        }
    }

    public function getNote()
    {
        switch($this->object_type){
            case self::OBJECT_TYPE_POST:
                $object = AdProduct::findOne(['id'=>$this->object_id]);
                if($object){
                    return Yii::t('ec', 'Post for listing {product}', ['product'=>'MV'.$object->id]);
                }
                break;
            case self::OBJECT_TYPE_BOOST:
                $object = AdProduct::findOne(['id'=>$this->object_id]);
                if($object){
                    return Yii::t('ec', 'Boost for listing {product}', ['product'=>'MV'.$object->id]);
                }
                break;
            case self::OBJECT_TYPE_DASHBOARD:
                $object = AdProduct::findOne(['id'=>$this->object_id]);
                if($object){
                    return Yii::t('ec', 'View dashboard {product}', ['product'=>'']);
                }
                break;
            case self::OBJECT_TYPE_BUY_KEYS:
                $object = Payment::me()->getTransactionWithNganluong(['code'=>$this->code]);
                if($object){
                    return Yii::t('ec', 'You use {payment_method} transfer {amount_VND} VND to {amount_keys} Keys', [
                        'payment_method'=>self::getPaymentMethod($object['payment_method']),
                        'amount_VND'=>$object['amount_vnd'],
                        'amount_keys'=>$object['amount']
                    ]);
                }
                break;
            case self::OBJECT_TYPE_UPDATE_EXPIRED:
                $object = AdProduct::findOne(['id'=>$this->object_id]);
                if($object){
                    return Yii::t('ec', 'Update expired for listing {product}', ['product'=>'MV'.$object->id]);
                }
                break;
            case self::OBJECT_TYPE_GET_KEYS_FROM_COUPON:
                $object = CouponCode::findOne(['id'=>$this->object_id]);
                if(!empty($object->couponEvent) && $object->couponEvent->type == CouponEvent::TYPE_PUBLIC){
                    return Yii::t('ec', 'Get {amount} Keys from coupon by code {code}', ['amount'=>$object->amount, 'code'=>$object->code]);
                }elseif(!empty($object->couponEvent) && $object->couponEvent->type == CouponEvent::TYPE_SYSTEM){
                    return Yii::t('ec', 'Get {amount} Keys from MetVuong system', ['amount'=>$object->amount, 'code'=>$object->code]);
                }
                break;

        }
        return null;
    }
}
