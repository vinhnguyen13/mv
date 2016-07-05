<?php
namespace vsoft\ad\models;

use common\models\AdProductAutoSave as AAS;

class AdProductAutoSave extends AAS {
	function beforeValidate() {
		if(!empty($this->facility) && is_array($this->facility)) {
			$this->facility = implode(',', $this->facility);
		}
		
		return parent::beforeValidate();
	}
	
	public function beforeSave($insert) {
		$now = time();
		
		if($insert) {
			$this->created_at = $now;
		} else {
			$this->updated_at = $now;
		}
		
		return parent::beforeSave($insert);
	}
}