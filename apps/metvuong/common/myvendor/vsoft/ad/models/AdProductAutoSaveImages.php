<?php
namespace vsoft\ad\models;

use common\models\AdProductAutoSaveImages as AASI;

class AdProductAutoSaveImages extends AASI {
	public function getUrl() {
		return "/store/" . $this->folder . "/" . $this->file_name;
	}
	
	public function afterDelete() {
		$original = \Yii::getAlias('@store') . DIRECTORY_SEPARATOR . $this->folder . DIRECTORY_SEPARATOR . $this->file_name;
		unlink($original);
	}
}