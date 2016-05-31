<?php

namespace vsoft\coupon\models;

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
class CouponEvent extends \vsoft\coupon\models\base\CouponEvent
{
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
}
