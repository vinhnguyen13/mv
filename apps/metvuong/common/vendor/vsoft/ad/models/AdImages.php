<?php

namespace common\vendor\vsoft\ad\models;

use Yii;
use common\vendor\vsoft\ad\models\base\AdImagesBase;

/**
 * This is the model class for table "ad_images".
 *
 * @property integer $id
 * @property integer $product_id
 * @property string $file_name
 * @property integer $uploaded_at
 *
 * @property AdProduct $product
 */
class AdImages extends AdImagesBase
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
            [['product_id', 'uploaded_at'], 'integer'],
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
            'product_id' => 'Product ID',
            'file_name' => 'File Name',
            'uploaded_at' => 'Uploaded At',
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
