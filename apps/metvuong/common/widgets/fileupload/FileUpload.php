<?php
namespace common\widgets\fileupload;

use dosamigos\fileupload\FileUploadUI;
use yii\web\View;

class FileUpload extends FileUploadUI {
	public $inputFileName = 'upload';
	public $files = [];
	
	public function init() {
		parent::init();
		
		$clientOptions = [
			'autoUpload' => true,
			'formData' => [],
		];
		
		$this->clientOptions = array_merge($clientOptions, $this->clientOptions);
		$this->gallery = false;
	}
	
	public function registerClientScript() {
		parent::registerClientScript();
		
		if($this->files) {
			$id = $this->options['id'];
			$files = json_encode($this->files);
			
			$script = <<<EOD
				var files = $files;
				$('#$id').fileupload('option', 'done').call($('#$id'), $.Event('done'), {result: {files: files}});
EOD;
			$this->getView()->registerJs($script, View::POS_READY);
		}
	}
}