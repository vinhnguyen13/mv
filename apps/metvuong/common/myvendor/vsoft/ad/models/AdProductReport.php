<?php

namespace vsoft\ad\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use common\models\AdProductReport as APR;

/**
 * This is the model class for table "ad_product_report".
 *
 * @property integer $user_id
 * @property integer $product_id
 * @property integer $type
 * @property string $description
 * @property string $ip
 * @property integer $status
 * @property integer $report_at
 *
 * @property AdProduct $product
 * @property User $user
 */
class AdProductReport extends APR
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'report_at',
                ],
                'value' => new Expression('UNIX_TIMESTAMP()'),
            ]
        ];
    }
    public function attributeLabels()
    {
        return [
            'user_id' => 'User',
            'product_id' => 'Product',
            'ip' => 'Ip Address',
        ];
    }

}
