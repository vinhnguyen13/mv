<?php

namespace vsoft\craw\models;

use Yii;
use yii\data\ActiveDataProvider;

class AdProductSearch extends AdProduct
{
	public $ward;
	public $district;
	public $city;
	public $street;
	public $category;
	public $project;
    
    public function rules()
    {
        return [
            [['ward', 'category', 'project', 'category_id', 'district', 'city', 'street', 'type'], 'safe'],
        ];
    }
    
    public function search($params)
    {
    	$query = AdProduct::find();
    	$query->joinWith(['ward', 'category', 'project', 'district', 'city', 'street']);
    
    	$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
    
    	$dataProvider->sort->attributes['ward'] = [
			'asc' => ['ad_ward.pre' => SORT_ASC, 'ad_ward.name' => SORT_ASC],
			'desc' => ['ad_ward.pre' => SORT_DESC, 'ad_ward.name' => SORT_DESC]
    	];
    	
    	$dataProvider->sort->attributes['district'] = [
    		'asc' => ['ad_district.pre' => SORT_ASC, 'ad_district.name' => SORT_ASC],
    		'desc' => ['ad_district.pre' => SORT_DESC, 'ad_district.name' => SORT_DESC]
    	];
    	
    	$dataProvider->sort->attributes['city'] = [
    		'asc' => ['ad_city.name' => SORT_ASC],
    		'desc' => ['ad_city.name' => SORT_DESC]
    	];
    	
    	$dataProvider->sort->attributes['street'] = [
    		'asc' => ['ad_street.name' => SORT_ASC],
    		'desc' => ['ad_street.name' => SORT_DESC]
    	];
    	
    	$dataProvider->sort->attributes['project'] = [
    		'asc' => ['ad_building_project.name' => SORT_ASC],
    		'desc' => ['ad_building_project.name' => SORT_DESC]
    	];
    	
    	$dataProvider->sort->attributes['category_id'] = [
    		'asc' => ['ad_category.name' => SORT_ASC],
    		'desc' => ['ad_category.name' => SORT_DESC]
    	];

    	$dataProvider->sort->attributes['type'] = [
    		'asc' => ['type' => SORT_ASC],
    		'desc' => ['type' => SORT_DESC]
    	];
    	
    	if (!($this->load($params) && $this->validate())) {
    		return $dataProvider;
    	}
    	
    	$query->andFilterWhere([
			'category_id' => $this->category_id,
    		'type' => $this->type,
    	]);
    	$query->andFilterWhere(['like', 'CONCAT(ad_ward.pre, " ", ad_ward.name)', $this->ward]);
    	$query->andFilterWhere(['like', 'CONCAT(ad_district.pre, " ", ad_district.name)', $this->district]);
    	$query->andFilterWhere(['like', 'ad_building_project.name', $this->project]);
    	$query->andFilterWhere(['like', 'ad_city.name', $this->city]);
    	$query->andFilterWhere(['like', 'CONCAT(ad_street.pre, " ", ad_street.name)', $this->street]);
    
    	return $dataProvider;
    }
}
