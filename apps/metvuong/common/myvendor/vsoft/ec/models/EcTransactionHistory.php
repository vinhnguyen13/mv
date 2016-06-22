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

    const OBJECT_TYPE_POST  = 1;
    const OBJECT_TYPE_BOOST  = 2;
    const OBJECT_TYPE_DASHBOARD  = 3;
    const OBJECT_TYPE_BUY_KEYS  = 4;

    public static function getObjectType($id=null)
    {
        $data = [
            self::OBJECT_TYPE_POST => Module::t('ec', 'Post'),
            self::OBJECT_TYPE_BOOST => Module::t('ec', 'Boost'),
            self::OBJECT_TYPE_DASHBOARD => Module::t('ec', 'View dashboard'),
            self::OBJECT_TYPE_BUY_KEYS => Module::t('ec', 'Buy Keys'),
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
            self::STATUS_FAIL => Module::t('ec', 'Failed'),
            self::STATUS_PENDING => Module::t('ec', 'Pending'),
            self::STATUS_PROCESSING => Module::t('ec', 'Processing'),
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
}
