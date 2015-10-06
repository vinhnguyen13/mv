<?php
namespace common\widgets;

use kartik\file\FileInput as FI;
use yii\helpers\Url;

class FileInput extends FI {
	/**
	 * @var array initialize the FileInput widget
	 */
	public function init()
	{
		$pluginOptions = [
			'dropZoneEnabled' => false,
        	'previewFileType' => 'image',
        	'uploadUrl' => Url::to('/express/upload/building-project-image'),
			'showRemove' => false,
			'showUpload' => false,
			'showClose' => false,
			'uploadAsync' => false,
			'autoReplace' => true,
			'validateInitialCount' => true
		];
		
		$pluginEvents = [
			'filebatchselected' => 'function(event, files) { customFileUpload.filebatchselected(event, files) }',
			'filebatchuploadsuccess' => 'function(event, data, previewId, index) { customFileUpload.filebatchuploadsuccess(event, data, previewId, index) }'
		];
		
		$this->pluginOptions = array_merge($pluginOptions, $this->pluginOptions);
		$this->pluginEvents = array_merge($pluginEvents, $this->pluginEvents);
		
		parent::init();
	}
}