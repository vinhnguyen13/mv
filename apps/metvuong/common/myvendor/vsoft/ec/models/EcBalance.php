<?php

namespace vsoft\ec\models;

use frontend\models\User;
use vsoft\ec\models\base\EcBalanceBase;
use Yii;

/**
 * This is the model class for table "ec_balance".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $amount
 * @property double $amount_promotion
 *
 * @property User $user
 */
class EcBalance extends EcBalanceBase
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ec_balance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['name'], 'string'],
            [['amount', 'amount_promotion'], 'number'],
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
            'amount' => Yii::t('ec', 'Amount'),
            'amount_promotion' => Yii::t('ec', 'Amount Promotion'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
