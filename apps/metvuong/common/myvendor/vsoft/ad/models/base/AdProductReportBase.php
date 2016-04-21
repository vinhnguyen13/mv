<?php

namespace vsoft\ad\models\base;

use frontend\models\User;
use vsoft\ad\models\AdProduct;
use Yii;

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
class AdProductReportBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_product_report';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'product_id', 'report_at'], 'required'],
            [['user_id', 'product_id', 'type', 'status', 'report_at'], 'integer'],
            [['description'], 'string', 'max' => 500],
            [['ip'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'product_id' => 'Product ID',
            'type' => 'Type',
            'description' => 'Description',
            'ip' => 'Ip',
            'status' => 'Status',
            'report_at' => 'Report At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(AdProduct::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
