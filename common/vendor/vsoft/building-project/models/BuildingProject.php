<?php
namespace vsoft\buildingProject\models;

use funson86\cms\models\CmsShow;
use yii\base\Model;
use funson86\cms\Module;
/**
 * This is the model class for table "cms_show".
 *
 * @property string $bpProgress
 */
class BuildingProject extends CmsShow {
	const BUILDING_CATEGORY_ID = 1;
	
	private static $customFields = ['bpGallery', 'bpLogo', 'bpLocation', 'bpType', 'bpAcreage', 'bpApartmentNo', 'bpFloorNo', 'bpFacilities', 
									'bpMapLocation', 'bpFacilitiesDetail', 'bpVideo', 'bpProgress'];
	
	public function rules()
	{
		return [
			[['catalog_id', 'click', 'status'], 'integer'],
			[['title'], 'required'],
			[['content', 'bpLocation', 'bpGallery', 'bpLogo', 'bpType', 'bpAcreage', 'bpApartmentNo', 'bpFloorNo', 'bpFacilities', 'bpMapLocation', 'bpFacilitiesDetail', 'bpVideo', 'bpProgress'], 'string'],
			[['title', 'seo_title', 'seo_keywords', 'seo_description', 'banner', 'template_show', 'author'], 'string', 'max' => 255],
			[['surname'], 'string', 'max' => 128],
			[['brief'], 'string', 'max' => 1022]
		];
	}
	
	/*
	 * @override
	 */
	function loadDefaultValues($skipIfSet = true) {
		parent::loadDefaultValues($skipIfSet);
		
		$this->catalog_id = self::BUILDING_CATEGORY_ID;
	}
	
	/*
	 * @override
	 */
	function load($data, $formName = null) {
		parent::load($data, $formName);
		
		$this->bpProgress = isset($data['BuildingProject']['bpProgress']) ? json_encode(array_values($data['BuildingProject']['bpProgress'])) : null;
	}
	
	public function attributeLabels()
	{
		return [
			'title' => Module::t('cms', 'Tên dự án'),
			'bpLogo' => Module::t('cms', 'Logo dự án'),
			'bpGallery' => Module::t('cms', 'Thư viện ảnh'),
			'bpLocation' => Module::t('cms', 'Vị trí dự án'),
			'bpType' => Module::t('cms', 'Loại hình đầu tư'),
			'bpAcreage' => Module::t('cms', 'Diện tích khu đất'),
			'bpApartmentNo' => Module::t('cms', 'Số lượng căn hộ'),
			'bpFloorNo' => Module::t('cms', 'Số tầng'),
			'bpFacilities' => Module::t('cms', 'Tiện ích'),
			'bpMapLocation' => Module::t('cms', 'Bản đồ vị trí'),
			'bpFacilitiesDetail' => Module::t('cms', 'Tiện ích'),
			'bpVideo' => Module::t('cms', 'Phim 3D dự án'),
			'bpProgress' => Module::t('cms', 'Tiến độ xây dựng'),
		];
	}
	
	public function attributes()
    {
        return array_merge(parent::attributes(), self::$customFields);
    }
    
    public function beforeSave($insert)
    {
    	if(parent::beforeSave($insert))
    	{
    		$content = [];
    		
    		foreach (self::$customFields as $field) {
    			$content[$field] = $this->$field;
    			
    			unset($this->$field);
    		}
    		
    		$this->content = json_encode($content);
    		
    		return true;
    	}
    	else
    		return false;
    }
    public static function findOne($condition)
    {
    	$buildingProject = static::findByCondition($condition)->one();
    	$customFields = json_decode($buildingProject->content);
    	
    	foreach ($customFields as $field => $value) {
    		$buildingProject->$field = $value;
    	}
    	
    	return $buildingProject;
    }
}