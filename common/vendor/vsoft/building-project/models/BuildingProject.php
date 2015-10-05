<?php
namespace vsoft\buildingProject\models;

use funson86\cms\models\CmsShow;
use yii\base\Model;
use funson86\cms\Module;
/**
 * This is the model class for table "cms_show".
 *
 * @property integer $bpfOverview
 */
class BuildingProject extends CmsShow {
	private $buildingFields = array();
	
	public function rules()
	{
		return [
			
		];
	}
	
	public function __set($name, $value) {
		$this->buildingFields[$name] = $value;
	}
	
	public function __get($name) {
		if(array_key_exists($name, $this->buildingFields)) {
            return $this->buildingFields[$name];
        }
        
        return null;
	}
	
	/*
	 * @override
	 */
	function loadDefaultValues($skipIfSet = true) {
		parent::loadDefaultValues($skipIfSet);
	}
	
	/*
	 * @override
	 */
	function load($data, $formName = null) {
		parent::load($data, $formName);
	}
	
	public function attributeLabels()
	{
		return [
			'bpLogo' => Module::t('cms', 'Logo dự án'),
			'bpGallery' => Module::t('cms', 'Gallery'),
			'bpLocation' => Module::t('cms', 'Vị trí dự án'),
			'bpType' => Module::t('cms', 'Loại hình đầu tư'),
			'bpAcreage' => Module::t('cms', 'Diện tích khu đất'),
			'bpApartmentNo' => Module::t('cms', 'Số lượng căn hộ'),
			'bpFloorNo' => Module::t('cms', 'Số tầng'),
			'bpFacilities' => Module::t('cms', 'Tiện ích'),
		];
	}
}