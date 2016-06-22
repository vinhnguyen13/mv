<?php

namespace vsoft\ec\models\base;

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
class EcTransactionHistoryBase extends \yii\db\ActiveRecord
{
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
            [['user_id', 'object_id', 'object_type', 'amount', 'balance', 'charge_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['params'], 'string'],
            [['code'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'user_id' => 'User ID',
            'object_id' => 'Object ID',
            'object_type' => 'Object Type',
            'amount' => 'Amount',
            'balance' => 'Balance',
            'charge_id' => 'Charge ID',
            'status' => 'Status',
            'params' => 'Params',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
}
