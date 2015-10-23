<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 9/23/2015
 * Time: 5:35 PM
 */

namespace vsoft\express\models;


use johnb0\gallery\models\Gallery;
use vsoft\express\models\base\LcHomeGalleryBase;

class LcHomeGallery extends LcHomeGalleryBase
{
    public function getGallery()
    {
//        return Gallery::findOne($this->gallery_id);
        return $this->hasOne(Gallery::className(), ['id' => 'gallery_id']);
    }

}