<?php
namespace vsoft\ad\models;

use common\models\AdProductAutoSave as AAS;
use vsoft\user\models\User;

class AdProductAutoSave extends AAS {


	public function getImages()
	{
		return $this->hasMany(AdProductAutoSaveImages::className(), ['product_id' => 'id']);
	}
	
	public function getCategory()
	{
		return $this->hasOne(AdCategory::className(), ['id' => 'category_id']);
	}
	
	public function getCity()
	{
		return $this->hasOne(AdCity::className(), ['id' => 'city_id']);
	}
	
	public function getDistrict()
	{
		return $this->hasOne(AdDistrict::className(), ['id' => 'district_id']);
	}
	
	public function getStreet()
	{
		return $this->hasOne(AdStreet::className(), ['id' => 'street_id']);
	}
	
	public function getWard()
	{
		return $this->hasOne(AdWard::className(), ['id' => 'ward_id']);
	}
	
	public function getProject()
	{
		return $this->hasOne(AdBuildingProject::className(), ['id' => 'project_building_id']);
	}
	
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
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