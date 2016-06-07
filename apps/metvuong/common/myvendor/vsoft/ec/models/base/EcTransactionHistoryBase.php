<?php

namespace vsoft\ec\models\base;

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
}
