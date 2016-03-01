<?php
namespace vsoft\express\components;

use yii\image\drivers\Image;

class AdImageHelper {
	public $tempFolderName = 'temp';
	public $adFolderName = 'ad';
	
	public $sizes = [
		'large' => [960, 720],
		'medium' => [480, 360],
		'thumb' => [240, 180]
	];
	
	public function getTempFolderPath($tempFolder) {
		return \Yii::getAlias('@store') . DIRECTORY_SEPARATOR . $this->tempFolderName . DIRECTORY_SEPARATOR . $this->adFolderName . DIRECTORY_SEPARATOR . $tempFolder;
	}

	public function makeFolderSizes($parentFolder) {
		foreach($this->sizes as $size) {
			$folder = $parentFolder . DIRECTORY_SEPARATOR . self::makeFolderName($size);
			mkdir($folder, 0777);
		}
	}
	
	public function resize($orginal) {
		$pathInfo = pathinfo($orginal);
		$resource = \Yii::$app->image->load($orginal);
		
		foreach($this->sizes as $k => $size) {
			$path = $pathInfo['dirname'] . DIRECTORY_SEPARATOR . self::makeFolderName($size) . DIRECTORY_SEPARATOR . $pathInfo['basename'];
			
			if($k == 'large') {
				$resource->resize($size[0], $size[1], Image::AUTO)->save($path);
			} else {
				$resizingConstraints = ($resource->width > $resource->height) ? Image::HEIGHT : Image::WIDTH;
				$resource->resize($size[0], $size[1], $resizingConstraints)->crop($size[0], $size[1])->save($path);
			}
		}
	}
	
	public function removeTempFile($tempFolder, $file) {
		$original = $tempFolder . DIRECTORY_SEPARATOR . $file;
		if(file_exists($original)) {
			unlink($original);
			foreach($this->sizes as $size) {
				unlink($tempFolder . DIRECTORY_SEPARATOR . self::makeFolderName($size) . DIRECTORY_SEPARATOR . $file);
			}
		}
	}
	
	public static function makeFolderName($size) {
		return $size[0] . 'x' . $size[1];
	}
}