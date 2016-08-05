<?php

namespace vsoft\coupon\models;

use vsoft\coupon\models\base\CouponEventBase;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

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
class CouponEvent extends CouponEventBase
{
    const TYPE_PUBLIC = 1;
    const TYPE_SYSTEM = 2;
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at']
                ],
                'value' => new Expression('UNIX_TIMESTAMP()'),
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_by']
                ],
                'value' => function ($event) {
                    return Yii::$app->user->isGuest ? null : Yii::$app->user->id;
                },
            ],
        ];
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['start_date', 'end_date'], 'integer'],
        ]);
    }

    public static function getTypes($id = null)
    {
        $data = [
            self::TYPE_PUBLIC => Yii::t('coupon', 'Public'),
            self::TYPE_SYSTEM => Yii::t('coupon', 'System'),
        ];

        if ($id !== null && isset($data[$id])) {
            return $data[$id];
        } else {
            return $data;
        }
    }
}
