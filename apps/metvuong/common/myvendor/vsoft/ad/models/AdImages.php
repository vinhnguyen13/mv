<?php

namespace vsoft\ad\models;

use Yii;
use vsoft\ad\models\base\AdImagesBase;
use yii\helpers\Url;
use vsoft\express\components\StringHelper;

class AdImages extends AdImagesBase
{
	const SIZE_THUMB = 'thumb';
	const SIZE_MEDIUM = 'medium';
	const SIZE_LARGE = 'large';
	
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
    	if(StringHelper::startsWith($fileName, 'http')) {
    		$defaultSize = '745x510';
    		
    		if($size == 'thumb') {
    			$s = '350x280';
    		} else {
    			$s = $defaultSize;
    		}
    		
    		return str_replace($defaultSize, $s, $fileName);
    	} else {
    		$pathinfo = pathinfo($fileName);
    		return Url::to('/store/ad/' . $pathinfo['filename'] . '.' . $size . '.' . $pathinfo['extension']);
    	}
    }
    
    public static function defaultImage() {
    	return '/themes/metvuong2/resources/images/default-ads.jpg';
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
