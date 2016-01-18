<?php
namespace common\widgets;

use dosamigos\fileupload\FileUploadUI as FU;
use yii\web\View;
use yii\helpers\Url;

class FileUploadAvatar extends FU {
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
			'previewMinWidth' => 128,
			'previewMinHeight' => 128,
			'previewMaxWidth' => 128,
			'previewMaxHeight' => 128,
			'disableExitThumbnail' => false,
			'formData' => [],
		];
		$fieldOptions = ['name' => 'upload'];
		$clientEvents = [
			'fileuploadadd' => 'function(e, data) {return customFileUpload.fileuploadadd(e, data, this);}',
			'fileuploaddone' => 'function(e, data) {customFileUpload.fileuploaddone(e, data, this);}',
			'fileuploadcompleted' => 'function(e, data) {customFileUpload.fileuploadcompleted(e, data, this);}',
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
		
		$folder = isset($this->fieldOptions['folder']) ? $this->fieldOptions['folder'] : 'avatar';
		
		if($values) {
			$files = [];
			foreach ($values as $value) {
				$pathInfo = pathinfo($value);
				$thumb = $pathInfo['filename'] .  '.thumb.' . $pathInfo['extension'];
				$files[] = [
					'url'           => Url::to($value),
					'thumbnailUrl'  => Url::to("/store/$folder/" . $thumb),
					'name'          => $pathInfo["basename"],
					'type'          => 'image/jpeg',
					'size'          => '1',
					'deleteUrl'     => Url::to(['/user-management/delete-image', 'orginal' => $pathInfo["basename"], 'thumbnail' => $thumb, 'folder'=>$folder]),
					'deleteType'    => 'DELETE',
					'deleteLater'	=> false,
				];
			}
			
			$files = json_encode($files);
//			$deleteDefault = Url::to(['user-management/delete-image', 'orginal' => 'default-avatar.jpg', 'thumbnail' => 'default-avatar.thumb.jpg', 'folder' => 'avatar'], true);
			$script = <<<EOD
			var $fieldVar = $('#$id');
			var files = $files;
			$fieldVar.fileupload('option', 'done').call($fieldVar, $.Event('done'), {result: {files: files}});
EOD;
			$view->registerJs($script, View::POS_READY);
//            $scriptDeleteDefaultAjax = <<<EOD2
//            var timer = 0;
//			$(document).on('click', '#profile-form-avatar', function () {
//                clearTimeout(timer);
//                timer = setTimeout(function () {
//                    $.ajax({
//                        type: "post",
//                        dataType: 'json',
//                        url: '$deleteDefault',
//                        success: function (data) {
//                            console.log(data);
//                        }
//                    });
//                }, 500);
//                return false;
//            });
//EOD2;
//            $view->registerJs($scriptDeleteDefaultAjax, View::POS_READY);
		}
    }
}