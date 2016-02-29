<?php

namespace vsoft\ad\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use common\models\AdProductSaved as APS;

/**
 * This is the model class for table "ad_product_saved".
 *
 * @property integer $user_id
 * @property integer $product_id
 * @property integer $saved_at
 *
 * @property AdProduct $product
 * @property User $user
 */
class AdProductSaved extends APS
{
    public $time;
    public function behaviors()
    {
        return [
            /*[
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_VALIDATE => 'saved_at',
                ],
                'value' => function ($event) {
                    return time();
                },
            ],*/
        ];
    }
}
