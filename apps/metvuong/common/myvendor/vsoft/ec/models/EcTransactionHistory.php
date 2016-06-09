<?php

namespace vsoft\ec\models;

use frontend\models\User;
use vsoft\ec\models\base\EcTransactionHistoryBase;
use vsoft\ec\Module;
use Yii;

/**
 * This is the model class for table "ec_transaction_history".
 *
 * @property string $id
 * @property integer $user_id
 * @property integer $object_id
 * @property integer $object_type
 * @property string $amount
 * @property integer $action_type
 * @property integer $action_detail
 * @property integer $charge_id
 * @property integer $status
 * @property string $data_type
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property EcCharge $charge
 * @property User $user
 */
class EcTransactionHistory extends EcTransactionHistoryBase
{
    const OBJECT_TYPE_PRODUCT = 1;
    const OBJECT_TYPE_PACKAGE = 2;
    const OBJECT_TYPE_USER = 3;

    const STATUS_SUCCESS = 1;
    const STATUS_FAILED = 0;

    const ACTION_TYPE_BUY = 1;
    const ACTION_TYPE_RECEIVE = 2;

    const ACTION_DETAIL_POST = 1;
    const ACTION_DETAIL_BOOST = 2;
    const ACTION_DETAIL_VIEW_DASHBOARD = 3;
    const ACTION_DETAIL_TRANSFER = 4;

    public static function getObjectType($id=null)
    {
        $data = [
            self::OBJECT_TYPE_PRODUCT => Module::t('ec', 'Product'),
            self::OBJECT_TYPE_PACKAGE => Module::t('ec', 'Package'),
            self::OBJECT_TYPE_USER => Module::t('ec', 'User'),
        ];

        if ($id !== null && isset($data[$id])) {
            return $data[$id];
        } else {
            return $data;
        }
    }

    public static function getTransactionStatus($id=null)
    {
        $data = [
            self::STATUS_SUCCESS => Module::t('ec', 'Success'),
            self::STATUS_FAILED => Module::t('ec', 'Failed'),
        ];

        if ($id !== null && isset($data[$id])) {
            return $data[$id];
        } else {
            return $data;
        }
    }

    public static function getActionType($id=null)
    {
        $data = [
            self::ACTION_TYPE_BUY => Module::t('ec', 'Buy'),
            self::ACTION_TYPE_RECEIVE => Module::t('ec', 'Receive'),
        ];

        if ($id !== null && isset($data[$id])) {
            return $data[$id];
        } else {
            return $data;
        }
    }

    public static function getActionDetail($id=null)
    {
        $data = [
            self::ACTION_DETAIL_POST => Module::t('ec', 'Post'),
            self::ACTION_DETAIL_BOOST => Module::t('ec', 'Boost'),
            self::ACTION_DETAIL_VIEW_DASHBOARD => Module::t('ec', 'View dashboard'),
            self::ACTION_DETAIL_TRANSFER => Module::t('ec', 'Transfer'),
        ];

        if ($id !== null && isset($data[$id])) {
            return $data[$id];
        } else {
            return $data;
        }
    }

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
            [['user_id', 'object_id', 'object_type', 'action_type', 'action_detail', 'charge_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['amount'], 'number'],
            [['data_type'], 'string', 'max' => 32],
            [['charge_id'], 'exist', 'skipOnError' => true, 'targetClass' => EcCharge::className(), 'targetAttribute' => ['charge_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('ec', 'ID'),
            'user_id' => Yii::t('ec', 'User ID'),
            'object_id' => Yii::t('ec', 'Object ID'),
            'object_type' => Yii::t('ec', 'Object Type'),
            'amount' => Yii::t('ec', 'Amount'),
            'action_type' => Yii::t('ec', 'Action Type'),
            'action_detail' => Yii::t('ec', 'Action Detail'),
            'charge_id' => Yii::t('ec', 'Charge ID'),
            'status' => Yii::t('ec', 'Status'),
            'data_type' => Yii::t('ec', 'Data Type'),
            'created_at' => Yii::t('ec', 'Created At'),
            'updated_at' => Yii::t('ec', 'Updated At'),
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

    public static function createTransaction($user_id, $obj_id, $obj_type, $amount, $action_type, $action_detail, $charge_id, $status, $data_type, $created_at=null, $updated_at=null)
    {
        $transaction = new EcTransactionHistory();
        $transaction->user_id = $user_id;
        $transaction->object_id = $obj_id;
        $transaction->object_type = $obj_type;
        $transaction->amount = $amount;
        $transaction->action_type = $action_type;
        $transaction->action_detail = $action_detail;
        $transaction->charge_id = $charge_id;
        $transaction->status = $status;
        $transaction->data_type = $data_type;
        $transaction->created_at = empty($created_at) ? time() : $created_at;
        $transaction->updated_at = empty($updated_at) ? time() : $updated_at;
        $transaction->save(false);
        return $transaction;
    }

}
