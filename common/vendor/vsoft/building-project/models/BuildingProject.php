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
	
	private static $areaTypes = [];
	private static $_customFields = [];
	private static $customFields = ['bpGallery', 'bpLogo', 'bpLocation', 'bpType', 'bpAcreage', 'bpAcreageCenter', 'bpApartmentNo', 'bpFloorNo', 'bpFacilities', 
									'bpMapLocation', 'bpMapLocationDes', 'bpFacilitiesDetail', 'bpFacilitiesDetailDes', 'bpVideo', 'bpProgress', 'bpLat', 'bpLng', 'bpHotline', 'bpWebsite'];
	
	public static function getAreaTypes() {
		if(!self::$areaTypes) {
			self::$areaTypes = [
				'bpfApartmentArea' => Module::t('cms', 'Khu căn hộ'),
				'bpfCommercialArea' => Module::t('cms', 'Khu thương mại'),
				'bpfTownhouseArea' => Module::t('cms', 'Khu nhà phố'),
				'bpfOffice' => Module::t('cms', 'Khu Office - Officetel')
			];
		}
		return self::$areaTypes;
	}
	
	public static function getCustomFields() {
		if(!self::$_customFields) {
			
			self::$_customFields = array_merge(self::$customFields, array_keys(self::getAreaTypes()));
		}
		
		return self::$_customFields;
	}
	
	public function rules()
	{
		$customFields = self::getCustomFields();
		
		return [
			[['catalog_id', 'click', 'status'], 'integer'],
			[['title', 'bpLocation'], 'required', 'message' => '{attribute} Không được để trống.'],
			[array_merge(['content'], $customFields), 'string'],
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
		
		$this->catalog_id = \Yii::$app->params['buildingCatID'];
		$this->bpLat = '10.783233';
		$this->bpLng = '106.704479';
		
		$typeAreas = self::getAreaTypes();
		
		foreach ($typeAreas as $k => $typeArea) {
			$this->$k = json_encode([
				'floorPlan' => [],
				'payment' => '',
				'promotion' => '',
				'document' => ''
			]);
		}
	}
	
	/*
	 * @override
	 */
	function load($data, $formName = null) {
		parent::load($data, $formName);
		
		$this->bpProgress = isset($data['BuildingProject']['bpProgress']) ? json_encode(array_values($data['BuildingProject']['bpProgress'])) : null;
		
		$typeAreas = self::getAreaTypes();
		
		foreach ($typeAreas as $k => $typeArea) {
			if(isset($data['BuildingProject'][$k])) {
				if($data['BuildingProject'][$k]['floorPlan']) {
					$data['BuildingProject'][$k]['floorPlan'] = array_values($data['BuildingProject'][$k]['floorPlan']);
				} else {
					$data['BuildingProject'][$k]['floorPlan'] = [];
				}
				$this->$k = json_encode($data['BuildingProject'][$k]);
			} else {
				$this->$k = '';
			}
		}
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
			'bpAcreageCenter' => Module::t('cms', 'Diện tích trung tâm văn phòng dịch vụ'),
			'bpApartmentNo' => Module::t('cms', 'Số lượng căn hộ'),
			'bpFloorNo' => Module::t('cms', 'Số tầng'),
			'bpFacilities' => Module::t('cms', 'Tiện ích'),
			'bpMapLocation' => Module::t('cms', 'Ảnh bản đồ vị trí'),
			'bpFacilitiesDetail' => Module::t('cms', 'Ảnh thể hiện tiện ích'),
			'bpFacilitiesDetailDes' =>  Module::t('cms', 'Mô tả thêm'),
			'bpVideo' => Module::t('cms', 'Phim 3D dự án'),
			'bpProgress' => Module::t('cms', 'Tiến độ xây dựng'),
			'bpHotline' => Module::t('cms', 'Hotline'),
			'bpMapLocationDes' => Module::t('cms', 'Mô tả thêm'),
		];
	}
	
	public function attributes()
    {
    	return array_merge(parent::attributes(), self::getCustomFields());
    }
    
    public function beforeSave($insert)
    {
    	if(parent::beforeSave($insert))
    	{
    		$content = [];
    		$customFields = self::getCustomFields();
    		
    		foreach ($customFields as $field) {
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
    	
    	if($buildingProject) {
    		$customFields = json_decode($buildingProject->content);
    		 
    		foreach ($customFields as $field => $value) {
    			$buildingProject->$field = $value;
    		}
    	}
    	
    	return $buildingProject;
    }
    
    public function formatMultiline($field) {
    	$values = explode(PHP_EOL, $this->$field);
    	$string = '';
    	
    	if(count($values) > 1) {
    		$string .= '<ul class="list">';
    		foreach ($values as $value) {
    			$string .= '<li>' . $value . '</li>';
    		}
    		$string .= '</ul>';
    	} else {
    		$string = $this->$field;
    	}
    	
    	return $string;
    }
    
    public function getVideos() {
    	$bpVideos = array_filter(explode(PHP_EOL, $this->bpVideo));
    	$string = '<ul class="videos">';
    	foreach($bpVideos as $bpVideo) {
    		parse_str(parse_url($bpVideo, PHP_URL_QUERY), $videoParams);
    		$videoId = $videoParams['v'];
    		$string .= '<li><a class="video" href="https://www.youtube.com/embed/' . $videoId . '?autoplay=1"><span class="play"></span><img src="http://img.youtube.com/vi/' . $videoId . '/1.jpg" /></a></li>';
    	}
    	$string .= '</ul>';
    	
    	return $string;
    }
}