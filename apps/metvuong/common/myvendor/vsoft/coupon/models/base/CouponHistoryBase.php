<?php

namespace vsoft\coupon\models\base;

use Yii;

/**
 * This is the model class for table "cp_history".
 *
 * @property integer $user_id
 * @property integer $cp_code_id
 * @property integer $cp_event_id
 * @property integer $created_at
 */
class CouponHistoryBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cp_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'cp_code_id', 'cp_event_id'], 'required'],
            [['user_id', 'cp_code_id', 'cp_event_id', 'created_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('coupon', 'User ID'),
            'cp_code_id' => Yii::t('coupon', 'Cp Code ID'),
            'cp_event_id' => Yii::t('coupon', 'Cp Event ID'),
            'created_at' => Yii::t('coupon', 'Created At'),
        ];
    }
}
