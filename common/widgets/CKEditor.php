<?php
namespace common\widgets;

use yii\helpers\Url;
use yii\web\View;

class CKEditor extends \mihaildev\ckeditor\CKEditor {
	private $ttgPreset;
	
	public function init()
	{
		$this->ttgPreset = $this->editorOptions['preset'];
		
		parent::init();
	}
	
	public function run() {
		if($this->ttgPreset == 'basic') {
			$toolbarGroups = [
				['name' => 'paragraph', 'groups' => ['templates', 'list', 'indent', 'align']],
				['name' => 'styles'],
			];
			
			$this->editorOptions['toolbarGroups'] = array_merge($this->editorOptions['toolbarGroups'], $toolbarGroups);
		}
		
		$this->editorOptions['filebrowserUploadUrl'] = Url::to('/express/upload/editor-image');
	
		parent::run();
	
		$uploadFunction = <<<EOD
function refreshCsrf() {
    var form = jQuery('.cke_dialog_ui_input_file iframe').contents().find('form');
	var csrfName = yii.getCsrfParam();
	if (!form.find('input[name=' + csrfName + ']').length) {
		var csrfTokenInput = jQuery('<input/>').attr({'type': 'hidden', 'name': csrfName}).val(yii.getCsrfToken());
		form.append(csrfTokenInput);
	}
}
EOD;
		$this->getView()->registerJs($uploadFunction, View::POS_END, 'ckeditor-upload-image');
	}
}