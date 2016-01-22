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
	public $homeNoFilter;
	public $areaMin;
	public $areaMax;
	public $areaFilter;
	public $priceMin;
	public $priceMax;
	public $priceFilter;
    public $facadeWidth;
    public $facadeFilter;
    public $landWidth;
    public $landFilter;
    public $homeDirection;
    public $homeDirectionFilter;
    public $facadeDirection;
    public $facadeDirectionFilter;
    public $floor;
    public $floorFilter;
    public $room;
    public $roomFilter;
    public $toilet;
    public $toiletFilter;
    public $interior;
    public $wardFilter;
    public $name;
    public $nameFilter;
    public $address;
    public $addressFilter;
    public $phone;
    public $phoneFilter;
    public $mobile;
    public $mobileFilter;
    public $email;
    public $emailFilter;
    
    public function rules()
    {
        return [
            [['ward', 'category', 'project', 'category_id', 'district', 'city', 'street', 'type', 'homeNoFilter', 'areaMin', 'areaMax', 'areaFilter', 'priceMin', 'priceMax',
            'priceFilter', 'content', 'facadeWidth', 'facadeFilter', 'landWidth', 'landFilter',
            'homeDirection', 'homeDirectionFilter', 'facadeDirection', 'facadeDirectionFilter', 'floor', 'floorFilter', 'room', 'roomFilter',
            'toilet', 'toiletFilter', 'interior', 'wardFilter', 'name', 'nameFilter', 'address', 'addressFilter', 'mobile', 'mobileFilter', 'phone', 'phoneFilter',
            'email', 'emailFilter'], 'safe'],
        ];
    }
    
    public function search($params)
    {
    	$query = AdProduct::find();
    	$query->joinWith(['ward', 'category', 'project', 'district', 'city', 'street', 'adProductAdditionInfo', 'adContactInfo']);
    
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
    	
    	$dataProvider->sort->attributes['facadeWidth'] = [
    		'asc' => ['ad_product_addition_info.facade_width' => SORT_ASC],
    		'desc' => ['ad_product_addition_info.facade_width' => SORT_DESC]
    	];

    	$dataProvider->sort->attributes['landWidth'] = [
    		'asc' => ['ad_product_addition_info.land_width' => SORT_ASC],
    		'desc' => ['ad_product_addition_info.land_width' => SORT_DESC]
    	];
    	
    	$dataProvider->sort->attributes['homeDirection'] = [
    		'asc' => ['ad_product_addition_info.home_direction' => SORT_ASC],
    		'desc' => ['ad_product_addition_info.home_direction' => SORT_DESC]
    	];
    	
    	$dataProvider->sort->attributes['facadeDirection'] = [
    		'asc' => ['ad_product_addition_info.facade_direction' => SORT_ASC],
    		'desc' => ['ad_product_addition_info.facade_direction' => SORT_DESC]
    	];

    	$dataProvider->sort->attributes['floor'] = [
    		'asc' => ['ad_product_addition_info.floor_no' => SORT_ASC],
    		'desc' => ['ad_product_addition_info.floor_no' => SORT_DESC]
    	];

    	$dataProvider->sort->attributes['room'] = [
    		'asc' => ['ad_product_addition_info.room_no' => SORT_ASC],
    		'desc' => ['ad_product_addition_info.room_no' => SORT_DESC]
    	];
    	
    	$dataProvider->sort->attributes['toilet'] = [
    		'asc' => ['ad_product_addition_info.toilet_no' => SORT_ASC],
    		'desc' => ['ad_product_addition_info.toilet_no' => SORT_DESC]
    	];
    	
    	$dataProvider->sort->attributes['interior'] = [
    		'asc' => ['ad_product_addition_info.interior' => SORT_ASC],
    		'desc' => ['ad_product_addition_info.interior' => SORT_DESC]
    	];

    	$dataProvider->sort->attributes['name'] = [
    		'asc' => ['ad_contact_info.name' => SORT_ASC],
    		'desc' => ['ad_contact_info.name' => SORT_DESC]
    	];
    	

    	$dataProvider->sort->attributes['address'] = [
    		'asc' => ['ad_contact_info.address' => SORT_ASC],
    		'desc' => ['ad_contact_info.address' => SORT_DESC]
    	];
    	

    	$dataProvider->sort->attributes['phone'] = [
    		'asc' => ['ad_contact_info.phone' => SORT_ASC],
    		'desc' => ['ad_contact_info.phone' => SORT_DESC]
    	];
    	

    	$dataProvider->sort->attributes['mobile'] = [
    		'asc' => ['ad_contact_info.mobile' => SORT_ASC],
    		'desc' => ['ad_contact_info.mobile' => SORT_DESC]
    	];
    	

    	$dataProvider->sort->attributes['email'] = [
    		'asc' => ['ad_contact_info.email' => SORT_ASC],
    		'desc' => ['ad_contact_info.email' => SORT_DESC]
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
    	
    	if($this->homeNoFilter) {
    		if($this->homeNoFilter == '1') {
    			$query->andWhere(['IS NOT', 'home_no', null]);
    		} else {
    			$query->andWhere(['home_no' => null]);
    		}
    	}
    	
    	if($this->facadeFilter) {
    		if($this->facadeFilter == '1') {
    			$query->andWhere(['IS NOT', 'ad_product_addition_info.facade_width', null]);
    		} else {
    			$query->andWhere(['ad_product_addition_info.facade_width' => null]);
    		}
    	}

    	if($this->landFilter) {
    		if($this->landFilter == '1') {
    			$query->andWhere(['IS NOT', 'ad_product_addition_info.land_width', null]);
    		} else {
    			$query->andWhere(['ad_product_addition_info.land_width' => null]);
    		}
    	}
    	
    	if($this->floorFilter) {
    		if($this->floorFilter == '1') {
    			$query->andWhere(['!=', 'ad_product_addition_info.floor_no', '0']);
    		} else {
    			$query->andWhere(['ad_product_addition_info.floor_no' => '0']);
    		}
    	}

    	if($this->roomFilter) {
    		if($this->roomFilter == '1') {
    			$query->andWhere(['!=', 'ad_product_addition_info.room_no', '0']);
    		} else {
    			$query->andWhere(['ad_product_addition_info.room_no' => '0']);
    		}
    	}

    	if($this->toiletFilter) {
    		if($this->toiletFilter == '1') {
    			$query->andWhere(['!=', 'ad_product_addition_info.toilet_no', '0']);
    		} else {
    			$query->andWhere(['ad_product_addition_info.toilet_no' => '0']);
    		}
    	}
    	
    	if($this->homeDirectionFilter) {
    		if($this->homeDirectionFilter == '1') {
    			$query->andWhere(['IS NOT', 'ad_product_addition_info.home_direction', null]);
    		} else {
    			$query->andWhere(['ad_product_addition_info.home_direction' => null]);
    		}
    	}

    	if($this->facadeDirectionFilter) {
    		if($this->facadeDirectionFilter == '1') {
    			$query->andWhere(['IS NOT', 'ad_product_addition_info.facade_direction', null]);
    		} else {
    			$query->andWhere(['ad_product_addition_info.facade_direction' => null]);
    		}
    	}
    	
    	if($this->areaFilter) {
    		if($this->areaFilter == '1') {
    			$query->andWhere(['!=', 'area', 0]);
    		} else {
    			$query->andWhere(['area' => 0]);
    		}
    	}
    	
    	if($this->wardFilter) {
    		if($this->wardFilter == '1') {
    			$query->andWhere(['IS NOT', 'ward_id', null]);
    		} else {
    			$query->andWhere(['ward_id' => null]);
    		}
    	}

    	if($this->interior) {
    		if($this->interior == '1') {
    			$query->andWhere(['IS NOT', 'ad_product_addition_info.interior', null]);
    		} else {
    			$query->andWhere(['ad_product_addition_info.interior' => null]);
    		}
    	}
    	

    	if($this->content) {
    		if($this->content == '1') {
    			$query->andWhere(['IS NOT', 'content', null]);
    		} else {
    			$query->andWhere(['content' => null]);
    		}
    	}

    	if($this->nameFilter) {
    		if($this->nameFilter == '1') {
    			$query->andWhere(['IS NOT', 'ad_contact_info.name', null]);
    		} else {
    			$query->andWhere(['ad_contact_info.name' => null]);
    		}
    	}
    	
    	if($this->addressFilter) {
    		if($this->addressFilter == '1') {
    			$query->andWhere(['IS NOT', 'ad_contact_info.address', null]);
    		} else {
    			$query->andWhere(['ad_contact_info.address' => null]);
    		}
    	}
    	
    	if($this->phoneFilter) {
    		if($this->phoneFilter == '1') {
    			$query->andWhere(['IS NOT', 'ad_contact_info.phone', null]);
    		} else {
    			$query->andWhere(['ad_contact_info.phone' => null]);
    		}
    	}
    	
    	if($this->mobileFilter) {
    		if($this->mobileFilter == '1') {
    			$query->andWhere(['IS NOT', 'ad_contact_info.mobile', null]);
    		} else {
    			$query->andWhere(['ad_contact_info.mobile' => null]);
    		}
    	}
    	
    	if($this->emailFilter) {
    		if($this->emailFilter == '1') {
    			$query->andWhere(['IS NOT', 'ad_contact_info.email', null]);
    		} else {
    			$query->andWhere(['ad_contact_info.email' => null]);
    		}
    	}
    	$query->andFilterWhere(['>=', 'area', $this->areaMin]);
    	$query->andFilterWhere(['<=', 'area', $this->areaMax]);
    	
    	if($this->priceFilter != '') {
    		$query->andWhere(['price_type' => $this->priceFilter]);
    	}
    	
    	$query->andFilterWhere(['>=', 'price', $this->priceMin]);
    	$query->andFilterWhere(['<=', 'price', $this->priceMax]);

    	$query->andFilterWhere(['ad_product_addition_info.home_direction' => $this->homeDirection]);
    	$query->andFilterWhere(['ad_product_addition_info.facade_direction' => $this->facadeDirection]);
    	
    	$query->andFilterWhere(['>=', 'ad_product_addition_info.floor_no', $this->floor]);
    	$query->andFilterWhere(['>=', 'ad_product_addition_info.room_no', $this->room]);
    	$query->andFilterWhere(['>=', 'ad_product_addition_info.toilet_no', $this->toilet]);
    
    	return $dataProvider;
    }
}
