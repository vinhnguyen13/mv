<?php

namespace vsoft\coupon\models;

use vsoft\coupon\models\base\CouponCodeBase;
use vsoft\coupon\Module;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

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
 *
 * @property CouponEvent $couponEvent
 */
class CouponCode extends CouponCodeBase
{
    const TYPE_ONE = 1;
    const TYPE_MANY = 2;

    const AMOUNT_TYPE_PERCENT = 1;
    const AMOUNT_TYPE_PRICE = 2;

    public static function getTypes($id = null)
    {
        $data = [
            self::TYPE_ONE => Module::t('coupon', 'One'),
            self::TYPE_MANY => Module::t('coupon', 'Many'),
        ];

        if ($id !== null && isset($data[$id])) {
            return $data[$id];
        } else {
            return $data;
        }
    }

    public static function getAmountTypes($id = null)
    {
        $data = [
            self::AMOUNT_TYPE_PERCENT => Module::t('coupon', 'Percent'),
            self::AMOUNT_TYPE_PRICE => Module::t('coupon', 'Amount'),
        ];

        if ($id !== null && isset($data[$id])) {
            return $data[$id];
        } else {
            return $data;
        }
    }

    public static function generateCodeExists($code, $length, $prefix, $suffix, $numbers, $letters, $symbols, $random_register, $mask)
    {
        if(!empty($code)){
            $coupon = CouponCode::find()->where('code = :c',[':c' => $code])->one();
            if(count($coupon) > 0) {
                $code = Coupon::generate($length, $prefix, $suffix, $numbers, $letters, $symbols, $random_register, $mask);
                return CouponCode::generateCodeExists($code, $length, $prefix, $suffix, $numbers, $letters, $symbols, $random_register, $mask);
            }
            else {
                return $code;
            }
        }
        return null;
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at','updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at'
                ],
                'value' => new Expression('UNIX_TIMESTAMP()'),
            ]
        ];
    }

}
