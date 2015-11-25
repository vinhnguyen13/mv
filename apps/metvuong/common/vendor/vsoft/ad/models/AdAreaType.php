<?php

namespace common\vendor\vsoft\ad\models;

use Yii;
use common\vendor\vsoft\ad\models\base\AdAreaTypeBase;


class AdAreaType extends AdAreaTypeBase
{
	const TYPE_APARTMENT = 1;
	const TYPE_TOWNHOUSE = 2;
	const TYPE_COMMERCIAL = 3;
	const TYPE_OFFICE = 4;
	
	public static function mapLabels() {
		return [
			self::TYPE_APARTMENT => Yii::t('ad', 'Khu căn hộ'),
			self::TYPE_TOWNHOUSE => Yii::t('ad', 'Khu nhà phố'),
			self::TYPE_COMMERCIAL => Yii::t('ad', 'Khu thương mại'),
			self::TYPE_OFFICE => Yii::t('ad', 'Khu office - officetel'),
		];
	}
	
	public static function mapFormName() {
		return [
			self::TYPE_APARTMENT => 'apartment',
			self::TYPE_TOWNHOUSE => 'townhouse',
			self::TYPE_COMMERCIAL => 'commercial',
			self::TYPE_OFFICE => 'office',
		];
	}
	
	public function formName() {
		$mapFormName = self::mapFormName();
		
		if(isset($mapFormName[$this->type])) {
			return $mapFormName[$this->type];
		} else {
			return parent::formName();
		}
	}
	
	public function beforeSave($insert) {
		$this->floor_plan = $this->floor_plan ? json_encode($this->floor_plan) : null;
	
		return parent::beforeSave($insert);
	}
}
