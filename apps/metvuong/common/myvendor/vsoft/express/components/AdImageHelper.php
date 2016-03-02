<?php
namespace vsoft\express\components;

use yii\image\drivers\Image;

class AdImageHelper {
	public $tempFolderName = 'temp';
	public $adFolderName = 'ads';
	
	public static $sizes = [
		'large' => [960, 720],
		'medium' => [480, 360],
		'thumb' => [240, 180]
	];
	
	public function getTempFolderPath($tempFolder) {
		return \Yii::getAlias('@store') . DIRECTORY_SEPARATOR . $this->tempFolderName . DIRECTORY_SEPARATOR . $this->adFolderName . DIRECTORY_SEPARATOR . $tempFolder;
	}
	
	public function getAbsoluteUploadFolderPath($time) {
		return $this->adFolderName . DIRECTORY_SEPARATOR . date('Y', $time) . DIRECTORY_SEPARATOR . date('m', $time) . DIRECTORY_SEPARATOR . date('d', $time);
	}
	
	public function getUploadFolderPath($absolutePath) {
		return \Yii::getAlias('@store') . DIRECTORY_SEPARATOR . $absolutePath;
	}

	public function makeFolderSizes($parentFolder) {
		foreach(self::$sizes as $size) {
			$folder = $parentFolder . DIRECTORY_SEPARATOR . self::makeFolderName($size);
			mkdir($folder, 0777);
		}
	}
	
	public function resize($orginal) {
		$pathInfo = pathinfo($orginal);
		$resource = \Yii::$app->image->load($orginal);
		
		foreach(self::$sizes as $k => $size) {
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
			foreach(self::$sizes as $size) {
				unlink($tempFolder . DIRECTORY_SEPARATOR . self::makeFolderName($size) . DIRECTORY_SEPARATOR . $file);
			}
		}
	}
	
	public function moveTempFile($tempFolder, $newFolder, $file) {
		$original = $tempFolder . DIRECTORY_SEPARATOR . $file;
		if(file_exists($original)) {
			rename($original, $newFolder . DIRECTORY_SEPARATOR . $file);
			foreach(self::$sizes as $size) {
				$oldname = $tempFolder . DIRECTORY_SEPARATOR . self::makeFolderName($size) . DIRECTORY_SEPARATOR . $file;
				$newname = $newFolder . DIRECTORY_SEPARATOR . self::makeFolderName($size) . DIRECTORY_SEPARATOR . $file;
				rename($oldname, $newname);
			}
		}
	}
	
	public static function makeFolderName($size) {
		return $size[0] . 'x' . $size[1];
	}
}