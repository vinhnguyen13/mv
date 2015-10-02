<?php
namespace common\widgets;

use mihaildev\ckeditor\CKEditor;

class CKEditor extends CKEditor {
	public function run() {
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