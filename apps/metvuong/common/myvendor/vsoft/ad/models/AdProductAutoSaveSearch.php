<?php
namespace vsoft\ad\models;

use yii\data\ActiveDataProvider;

class AdProductAutoSaveSearch extends AdProductAutoSave {
	public $project_search;
	public $city_search;
	public $district_search;
	public $ward_search;
	public $street_search;
	
	public function rules()
	{
		return array_merge(parent::rules(), [
			[['project_search', 'city_search', 'district_search', 'ward_search', 'street_search'], 'safe']
		]);
	}
	
	public function formName() {
		return '';
	}
	public function search($params)
	{
		$query = self::find();
		$query->joinWith(['user', 'category', 'district', 'city', 'street', 'ward', 'project']);
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
	
		if ($this->load($params) && !$this->validate()) {
			return $dataProvider;
		}
    	
    	$dataProvider->sort->attributes['category'] = [
    		'asc' => ['ad_category.name' => SORT_ASC],
    		'desc' => ['ad_category.name' => SORT_DESC]
    	];
    	
    	$dataProvider->sort->attributes['project'] = [
    		'asc' => ['ad_building_project.name' => SORT_ASC],
    		'desc' => ['ad_building_project.name' => SORT_DESC]
    	];
    	
    	$dataProvider->sort->attributes['city'] = [
    		'asc' => ['ad_city.name' => SORT_ASC],
    		'desc' => ['ad_city.name' => SORT_DESC]
    	];
    	
    	$dataProvider->sort->attributes['district'] = [
    		'asc' => ['ad_district.name' => SORT_ASC],
    		'desc' => ['ad_district.name' => SORT_DESC]
    	];
    	
    	$dataProvider->sort->attributes['ward'] = [
    		'asc' => ['ad_ward.name' => SORT_ASC],
    		'desc' => ['ad_ward.name' => SORT_DESC]
    	];
    	
    	$dataProvider->sort->attributes['street'] = [
    		'asc' => ['ad_street.name' => SORT_ASC],
    		'desc' => ['ad_street.name' => SORT_DESC]
    	];

    	$query->andFilterWhere(['like', 'ad_building_project.name', $this->project_search]);
    	$query->andFilterWhere(['like', 'ad_city.name', $this->city_search]);
    	$query->andFilterWhere(['like', 'ad_district.name', $this->district_search]);
    	$query->andFilterWhere(['like', 'ad_ward.name', $this->ward_search]);
    	$query->andFilterWhere(['like', 'ad_street.name', $this->street_search]);
    	
		$query->andFilterWhere([
			'type' => $this->type,
			'category_id' => $this->category_id
		]);
	
		return $dataProvider;
	}
}