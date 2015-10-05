<?php
namespace vsoft\express\components;
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 10/5/2015
 * Time: 1:32 PM
 */
use yii\image\drivers\Image;
class UploadHelper{
    public $image;
    public $destination;

    public function __construct($image, $destination){
        $this->image = $image;
        $this->destination = $destination;
    }

    public function upload(){
        $image = $this->image;
        if(!empty($image)){
            $fileName = \yii\helpers\Inflector::slug($image->baseName) . '.' . $image->extension;
            $file = $this->destination.'/' . $fileName;
            list($width, $height, $type, $attr) = getimagesize($image->tempName);

            if($width > 950){
                $master = Image::WIDTH;
            }elseif($height > 610){
                $master = Image::HEIGHT;
            }
            $image->saveAs($file);
            $image = \Yii::$app->image->load($file);
            $isSaved = $image->resize(200, 200, $master)->crop(200, 200)->sharpen(20)->save();
            if($isSaved){
                return $fileName;
            }
        }
    }
}