<?php

namespace common\vendor\vsoft\ad\models;

use Yii;
use common\vendor\vsoft\ad\models\base\AdImagesBase;

class AdImages extends AdImagesBase
{
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'file_name' => 'File Name',
            'uploaded_at' => 'Uploaded At',
        ];
    }
}
