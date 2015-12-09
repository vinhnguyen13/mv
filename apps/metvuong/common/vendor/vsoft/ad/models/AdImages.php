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
    
    public function getImageThumb() {
    	$pathinfo = pathinfo($this->file_name);
    	return Url::to('/store/ads/' . $pathinfo['filename'] . '.thumb.' . $pathinfo['extension']);
    }
    
    public function getImageMedium() {
    	$pathinfo = pathinfo($this->file_name);
    	return Url::to('/store/ads/' . $pathinfo['filename'] . '.medium.' . $pathinfo['extension']);
    }
    
    public function getImageLarge() {
    	$pathinfo = pathinfo($this->file_name);
    	return Url::to('/store/ads/' . $pathinfo['filename'] . '.large.' . $pathinfo['extension']);
    }
}
