<?php
namespace common\widgets;

use dosamigos\fileupload\FileUploadUI as FU;
use yii\web\View;
use yii\helpers\Url;

class FileUploadUI extends FU {
	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
		
		$clientOptions = [
			'autoUpload' => true,
			'uploadTemplateId' => null,
			'downloadTemplateId' => null,
			'previewCrop' => true,
			'previewMinWidth' => 120,
			'previewMinHeight' => 120,
			'previewMaxWidth' => 120,
			'previewMaxHeight' => 120,
			'disableExifThumbnail' => true,
			'formData' => [],
		];
		$fieldOptions = ['name' => 'upload'];
		$clientEvents = [
			'fileuploadadd' => 'function(e, data) {return customFileUpload.fileuploadadd(e, data, this);}',
			'fileuploaddone' => 'function(e, data) {customFileUpload.fileuploaddone(e, data, this);}',
			'fileuploaddestroyed' => 'function(e, data) {customFileUpload.fileuploaddestroyed(e, data, this);}',
			'fileuploaddestroy' => 'function(e, data) {return customFileUpload.fileuploaddestroy(e, data, this);}',
		];
		
		$this->clientOptions = array_merge($clientOptions, $this->clientOptions);
		$this->fieldOptions = array_merge($fieldOptions, $this->fieldOptions);
		$this->clientEvents = array_merge($clientEvents, $this->clientEvents);
		$this->gallery = false;
		
		if(isset($this->clientOptions['maxNumberOfFiles']) && $this->clientOptions['maxNumberOfFiles'] == 1) {
			$this->fieldOptions['multiple'] = false;
		}
	}
	
	/**
     * Registers required script for the plugin to work as jQuery File Uploader UI
     */
    public function registerClientScript()
    {
    	$view = $this->getView();
    	
    	$view->registerJsFile(\Yii::getAlias('@web') . '/js/upload.js', ['depends' => ['yii\web\YiiAsset']]);
    	$view->registerCssFile(\Yii::getAlias('@web') . '/css/upload.css', ['depends' => ['yii\bootstrap\BootstrapAsset']]);
    	
		parent::registerClientScript();
		
		$id = $this->options['id'];
		$fieldVar = $this->attribute . 'upload';
		
		$field = $this->attribute;
		
		if($this->model) {
			$values = $this->model->$field;
		} else {
			$values = $this->fieldOptions['values'];
		}
		
		$values = array_filter(explode(',', $values));
		
		if($values) {
			$files = [];
			
			foreach ($values as $value) {
				$pathInfo = pathinfo($value);
					
				$thumb = $pathInfo['filename'] .  '.thumb.' . $pathInfo['extension'];
					
				$files[] = [
					'url'           => Url::to('/store/building-project-images/' . $value),
					'thumbnailUrl'  => Url::to('/store/building-project-images/' . $thumb),
					'name'          => $value,
					'type'          => 'image/jpeg',
					'size'          => '1',
					'deleteUrl'     => Url::to(['/express/upload/delete-image', 'orginal' => $value, 'thumbnail' => $thumb, 'deleteLater' => 1]),
					'deleteType'    => 'DELETE',
					'deleteLater'	=> 1,
				];
			}
			
			$files = json_encode($files);
			
			$script = <<<EOD
			var $fieldVar = $('#$id');
			var files = $files;
			$fieldVar.fileupload('option', 'done').call($fieldVar, $.Event('done'), {result: {files: files}});
EOD;
			$view->registerJs($script, View::POS_READY);
		}
    }
}