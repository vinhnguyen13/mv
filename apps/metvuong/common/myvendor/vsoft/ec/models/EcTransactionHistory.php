<?php

namespace vsoft\ec\models;

use frontend\models\User;
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

    public function getObjectType()
    {
        $data = [
            self::OBJECT_TYPE_POST => Yii::t('ec', 'Post'),
            self::OBJECT_TYPE_BOOST => Yii::t('ec', 'Boost'),
            self::OBJECT_TYPE_DASHBOARD => Yii::t('ec', 'View dashboard'),
            self::OBJECT_TYPE_BUY_KEYS => Yii::t('ec', 'Buy Keys'),
            self::OBJECT_TYPE_UPDATE_EXPIRED => Yii::t('ec', 'Update expired'),
            self::OBJECT_TYPE_GET_KEYS_FROM_COUPON => Yii::t('ec', 'Get Keys from coupon')
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
        $data = [
            self::OBJECT_TYPE_POST => Yii::t('ec', 'Post for listing'),
            self::OBJECT_TYPE_BOOST => Yii::t('ec', 'Boost for listing'),
            self::OBJECT_TYPE_DASHBOARD => Yii::t('ec', 'View dashboard'),
            self::OBJECT_TYPE_BUY_KEYS => Yii::t('ec', 'Buy Keys'),
            self::OBJECT_TYPE_UPDATE_EXPIRED => Yii::t('ec', 'Update expired'),
            self::OBJECT_TYPE_GET_KEYS_FROM_COUPON => Yii::t('ec', 'Get Keys from coupon')
        ];
        if($this->object_type == self::OBJECT_TYPE_BUY_KEYS){

        }
        if (isset($data[$this->object_type])) {
            return $data[$this->object_type];
        } else {
            return null;
        }
    }
}
