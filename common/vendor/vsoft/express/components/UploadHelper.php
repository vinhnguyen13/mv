<?php
namespace vsoft\express\components;
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 10/5/2015
 * Time: 1:32 PM
 */
use yii\image\drivers\Image;
use yii\helpers\Url;
use yii\helpers\Html;

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
    
    public static function isImage($pathToImage) {
    	$check = getimagesize($pathToImage);
    	 
    	if($check !== false) {
    		return true;
    	}
    	 
    	return false;
    }
    
    public static function allowImageType($imageFileName) {
    	$imageFileType = pathinfo($imageFileName, PATHINFO_EXTENSION);
    	$allowImageType = ['jpg', 'png', 'jpeg', 'gif'];
    	 
    	if(in_array($imageFileType, $allowImageType)) {
    		return true;
    	}
    	 
    	return false;
    }
    
    public static function getThumbs($files, $html = false, $options = []) {
    	$gallery = explode(',', $files);
    	$images = '';
    	
    	foreach($gallery as $gal) {
    		$pathInfo = pathinfo($gal);
    		$image = Url::to('/store/building-project-images/' . $pathInfo['filename'] .  '.thumb.' . $pathInfo['extension']);
    		
    		$images .= Html::img($image, $options);
    	}
    	return $images;
    }
}