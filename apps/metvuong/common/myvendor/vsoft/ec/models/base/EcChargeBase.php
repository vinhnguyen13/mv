<?php

namespace vsoft\ec\models\base;

use Yii;

/**
 * This is the model class for table "ec_charge".
 *
 * @property integer $id
 * @property string $charge
 * @property integer $type
 * @property string $description
 * @property integer $status
 * @property integer $created_at
 *
 * @property EcTransactionHistory[] $ecTransactionHistories
 */
class EcChargeBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ec_charge';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['charge'], 'number'],
            [['type', 'status', 'created_at'], 'integer'],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('ec', 'ID'),
            'charge' => Yii::t('ec', 'Charge'),
            'type' => Yii::t('ec', 'Type'),
            'description' => Yii::t('ec', 'Description'),
            'status' => Yii::t('ec', 'Status'),
            'created_at' => Yii::t('ec', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEcTransactionHistories()
    {
        return $this->hasMany(EcTransactionHistory::className(), ['charge_id' => 'id']);
    }
}
