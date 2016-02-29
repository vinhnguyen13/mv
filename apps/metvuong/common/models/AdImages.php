<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ad_images".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $product_id
 * @property string $file_name
 * @property integer $uploaded_at
 * @property integer $order
 *
 * @property AdProduct $product
 */
class AdImages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_images';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'product_id', 'uploaded_at', 'order'], 'integer'],
            [['file_name', 'uploaded_at'], 'required'],
            [['file_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'product_id' => 'Product ID',
            'file_name' => 'File Name',
            'uploaded_at' => 'Uploaded At',
            'order' => 'Order',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(AdProduct::className(), ['id' => 'product_id']);
    }
}
