<?php

namespace vsoft\coupon\models\base;

use vsoft\coupon\models\CouponEvent;
use Yii;

/**
 * This is the model class for table "cp_code".
 *
 * @property integer $id
 * @property string $code
 * @property integer $cp_event_id
 * @property integer $status
 * @property integer $count
 * @property integer $type
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $amount
 * @property integer $amount_type
 *
 * @property CouponEvent $couponEvent
 */
class CouponCodeBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cp_code';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cp_event_id', 'status', 'count', 'type', 'created_at', 'updated_at', 'amount_type', 'amount', 'amount_type'], 'integer'],
            [['amount'], 'number'],
            [['amount'], 'required'],
            [['code'], 'string', 'max' => 32],
            [['code'], 'unique'],
            [['cp_event_id'], 'exist', 'skipOnError' => true, 'targetClass' => CouponEvent::className(), 'targetAttribute' => ['cp_event_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('coupon', 'ID'),
            'code' => Yii::t('coupon', 'Code'),
            'cp_event_id' => Yii::t('coupon', 'Cp Event ID'),
            'status' => Yii::t('coupon', 'Status'),
            'count' => Yii::t('coupon', 'Count'),
            'type' => Yii::t('coupon', 'Type'),
            'created_at' => Yii::t('coupon', 'Created At'),
            'updated_at' => Yii::t('coupon', 'Updated At'),
            'amount' => Yii::t('coupon', 'Amount'),
            'amount_type' => Yii::t('coupon', 'Amount Type'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCouponEvent()
    {
        return $this->hasOne(CouponEvent::className(), ['id' => 'cp_event_id']);
    }
}
