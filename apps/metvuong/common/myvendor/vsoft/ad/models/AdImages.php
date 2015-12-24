<?php

namespace vsoft\ad\models;

use Yii;
use vsoft\ad\models\base\AdImagesBase;
use yii\helpers\Url;

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
    
    public static function getImageUrl($fileName, $size = 'thumb') {
    	$pathinfo = pathinfo($fileName);
    	
    	return Url::to('/store/ad/' . $pathinfo['filename'] . '.' . $size . '.' . $pathinfo['extension']);
    }
    
    public function getImageThumb() {
    	return self::getImageUrl($this->file_name);
    }
    
    public function getImageMedium() {
    	return self::getImageUrl($this->file_name, 'medium');
    }
    
    public function getImageLarge() {
    	return self::getImageUrl($this->file_name, 'large');
    }
}
