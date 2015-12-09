<?php
namespace vsoft\express\components;

use yii\image\drivers\Image;
class ImageHelper {
	private $options = [
		'thumbSuffix'	=>	'thumb',
		'thumbWidth'	=>	240,
		'thumbHeight'	=>	160,
		'mediumSuffix'	=>	'medium',
		'mediumWidth'	=>	480,
		'mediumHeight'	=>	320,
		'largeSuffix'	=>	'large',
		'largeWidth'	=> 	960,
		'largeHeight'	=>	640,
	];
	
	private $resource;
	private $path;
	
	public function __construct($path, $options = []) {
		$this->options = array_merge($this->options, $options);
		$this->resource = \Yii::$app->image->load($path);
		$this->path = $path;
	}
	
	public function makeThumb($keepRatio = false, $path = null) {
		$path = $this->path($this->options['thumbSuffix'], $path);
		$this->resize($this->options['thumbWidth'], $this->options['thumbHeight'], $keepRatio, $path);
	}
	
	public function makeMedium($keepRatio = false, $path = null) {
		$path = $this->path($this->options['mediumSuffix'], $path);
		
		$this->resize($this->options['mediumWidth'], $this->options['mediumHeight'], $keepRatio, $path);
	}
	
	public function makeLarge($keepRatio = false, $path = null) {
		$path = $this->path($this->options['largeSuffix'], $path);
		
		$this->resize($this->options['largeWidth'], $this->options['largeHeight'], $keepRatio, $path);
	}
	
	private function path($suffix, $path = null) {
		if(!$path) {
			$pathinfo = pathinfo($this->path);
			$path = $pathinfo['dirname'] . DIRECTORY_SEPARATOR . $pathinfo['filename'] . '.' . $suffix . '.' . $pathinfo['extension'];
		}
		return $path;
	}
	
	public function resize($with, $height, $keepRatio = false, $path) {
		$resource = clone $this->resource;
		
		if($keepRatio) {
			$resource->resize($with, $height, Image::AUTO)->save($path);
		} else {
			$resizingConstraints = ($this->resource->width > $this->resource->height) ? Image::HEIGHT : Image::WIDTH;
			$resource->resize($with, $height, $resizingConstraints)->crop($with, $height)->save($path);
		}
	}
}