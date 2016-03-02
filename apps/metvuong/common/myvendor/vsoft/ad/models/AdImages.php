<?php

namespace vsoft\ad\models;

use Yii;
use yii\helpers\Url;
use vsoft\express\components\StringHelper;
use common\models\AdImages as AI;
use vsoft\express\components\AdImageHelper;

class AdImages extends AI
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
    
    public function getUrl($size = self::SIZE_MEDIUM) {
    	$sizeFolder = AdImageHelper::makeFolderName(AdImageHelper::$sizes[$size]);
    	
    	return "/store/{$this->folder}/$sizeFolder/{$this->file_name}";
    }
    
    public static function defaultImage() {
    	return '/themes/metvuong2/resources/images/default-ads.jpg';
    }
}
