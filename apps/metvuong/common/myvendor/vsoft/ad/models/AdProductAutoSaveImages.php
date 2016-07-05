<?php
namespace vsoft\ad\models;

use common\models\AdProductAutoSaveImages as AASI;

class AdProductAutoSaveImages extends AASI {
	public function afterDelete() {
		$original = \Yii::getAlias('@store') . DIRECTORY_SEPARATOR . $this->folder . DIRECTORY_SEPARATOR . $this->file_name;
		unlink($original);
	}
}