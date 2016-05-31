<?php

namespace vsoft\coupon\models\base;

use Yii;

/**
 * This is the model class for table "cp_event".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 *
 */
class CouponEvent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cp_event';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'created_at', 'created_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 3200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('coupon', 'ID'),
            'name' => Yii::t('coupon', 'Name'),
            'description' => Yii::t('coupon', 'Description'),
            'status' => Yii::t('coupon', 'Status'),
            'created_at' => Yii::t('coupon', 'Created At'),
            'created_by' => Yii::t('coupon', 'Created By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getCpCodes()
//    {
//        return $this->hasMany(CpCode::className(), ['cp_event_id' => 'id']);
//    }
}
