<?php

namespace vsoft\craw\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;

class AdProductSearch2 extends AdProduct
{
	public $project_name;
	public $city_name;
	public $district_name;
	public $ward_name;
	public $street_name;
	public $home_no_filter;
	public $area_filter;
	public $room_no_filter;
	public $toilet_no_filter;
	public $floor_no_filter;
	public $facade_width_filter;
	public $land_width_filter;
	public $home_direction_filter;
	public $facade_direction_filter;
	public $interior_filter;
	public $content_filter;
	public $contact_name_filter;
	public $contact_address_filter;
	public $phone_filter;
	public $mobile_filter;
	public $email_filter;
	public $project_name_filter;
	public $project_name_mask;
	public $ward_name_filter;
	public $ward_name_mask;
	public $street_name_filter;
	public $street_name_mask;
	public $city_name_mask;
	public $district_name_mask;
	public $price_min;
	public $price_max;
	public $price_mask;
	public $price_unit;
	public $area_mask;
	public $area_min;
	public $area_max;
	public $room_no_mask;
	public $toilet_no_mask;
	public $floor_no_mask;
	
	public $columns;
	
	public static $_columns = [
    	"apid" => "ad_product.id",
    	"apt" => "ad_product.type",
    	"apcid" => "ad_product.category_id",
    	"abpn" => "ad_building_project.name `project_name`",
    	"acn" => "ad_city.name `city_name`",
    	"adn" => "CONCAT(ad_district.pre, ' ', ad_district.name) `district_name`",
    	"awn" => "CONCAT(ad_ward.pre, ' ', ad_ward.name) `ward_name`",
    	"asn" => "CONCAT(ad_street.pre, ' ', ad_street.name) `street_name`",
    	"aphn" => "ad_product.home_no",
    	"app" => "ad_product.price",
    	"apa" => "ad_product.area",
    	"apairn" => "ad_product_addition_info.room_no",
    	"apaitn" => "ad_product_addition_info.toilet_no",
    	"apaifn" => "ad_product_addition_info.floor_no",
    	"apaifw" => "ad_product_addition_info.facade_width",
    	"apailw" => "ad_product_addition_info.land_width",
    	"apaihd" => "ad_product_addition_info.home_direction",
    	"apaifd" => "ad_product_addition_info.facade_direction",
    	"apaii" => "ad_product_addition_info.interior",
    	"apc" => "ad_product.content",
    	"acin" => "ad_contact_info.name `contact_name`",
    	"acia" => "ad_contact_info.address `contact_address`",
    	"acip" => "ad_contact_info.phone",
    	"acim" => "ad_contact_info.mobile",
    	"acie" => "ad_contact_info.email",
    	"apca" => "ad_product.created_at"
    ];
	
	public static $_columnsName = [
    	"ad_product.id" => "ID",
    	"ad_product.type" => "Hình thức",
    	"ad_product.category_id" => "Phân loại",
    	"ad_building_project.name `project_name`" => "Thuộc dự án",
    	"ad_city.name `city_name`" => "Tỉnh/Thành",
    	"CONCAT(ad_district.pre, ' ', ad_district.name) `district_name`" => "Quận/Huyện",
    	"CONCAT(ad_ward.pre, ' ', ad_ward.name) `ward_name`" => "Phường/Xã",
    	"CONCAT(ad_street.pre, ' ', ad_street.name) `street_name`" => "Đường/Phố",
    	"ad_product.home_no" => "Số nhà",
    	"ad_product.content" => "Nội dung",
    	"ad_product.area" => "Diện tích (m)",
    	"ad_product.price" => "Giá",
    	"ad_product.created_at" => "Ngày đăng",
    	"ad_product_addition_info.facade_width" => "Mặt tiền(m)",
    	"ad_product_addition_info.land_width" => "Đường vào(m)",
    	"ad_product_addition_info.home_direction" => "Hướng nhà",
    	"ad_product_addition_info.facade_direction" => "Hướng ban công",
    	"ad_product_addition_info.floor_no" => "Số tầng",
    	"ad_product_addition_info.room_no" => "Phòng ngủ",
    	"ad_product_addition_info.toilet_no" => "Phòng tắm",
    	"ad_product_addition_info.interior" => "Nội thất",
    	"ad_contact_info.name `contact_name`" => "Tên liên hệ",
    	"ad_contact_info.address `contact_address`" => "Địa chỉ",
    	"ad_contact_info.phone" => "Số Điện thoại",
    	"ad_contact_info.mobile" => "Số di động",
    	"ad_contact_info.email" => "Email"
    ];

    public function rules()
    {
        return [
            [['type', 'category_id', 'project_name', 'city_name', 'district_name', 'ward_name', 'street_name', 'home_no_filter',
            		'price_type', 'area_filter', 'room_no_filter', 'toilet_no_filter', 'floor_no_filter', 'facade_width_filter', 'land_width_filter',
            		'home_direction_filter', 'facade_direction_filter', 'interior_filter', 'content_filter', 'contact_name_filter', 'contact_address_filter',
            		'phone_filter', 'mobile_filter', 'email_filter', 'project_name_filter', 'project_name_mask', 'project_building_id', 'ward_id', 'ward_name_filter', 'ward_name_mask',
            		'street_name_filter', 'street_name_mask', 'street_id', 'city_name_mask', 'city_id', 'district_name_mask', 'district_id',
            		'price_min', 'price_max', 'price_mask', 'price_unit', 'area_mask', 'area_min', 'area_max', 'room_no_mask', 'toilet_no_mask', 'floor_no_mask'
            ], 'safe'],
        ];
    }
    
   	public function formName() {
   		return '';
   	}
    
    public function search($params)
    {
    	$excludeColumns = isset($_COOKIE['columns']) ? explode(",", $_COOKIE['columns']) : [];
    	$columns = self::$_columns;
    	
    	foreach ($excludeColumns as $excludeColumn) {
    		unset($columns[$excludeColumn]);
    	}
    	
    	$this->columns = $columns;
	
    	$query = new Query();
    	$query->select(array_values($this->columns));
    	$query->from('ad_product');
    	$query->leftJoin('ad_city', 'ad_product.city_id = ad_city.id');
    	$query->leftJoin('ad_district', 'ad_product.district_id = ad_district.id');
    	$query->leftJoin('ad_ward', 'ad_product.ward_id = ad_ward.id');
    	$query->leftJoin('ad_street', 'ad_product.street_id = ad_street.id');
    	$query->leftJoin('ad_building_project', 'ad_product.project_building_id = ad_building_project.id');
    	$query->leftJoin('ad_product_addition_info', 'ad_product.id = ad_product_addition_info.product_id');
    	$query->leftJoin('ad_contact_info', 'ad_product.id = ad_contact_info.product_id');
    	
    	$dataProvider = new ActiveDataProvider([
    		'query' => $query,
    		'db' => \Yii::$app->dbCraw
    	]);

    	$this->load($params);
    	
    	$sorts = [
    		'id' => 'ad_product.id',
    		'type' => 'ad_product.type',
    		'category_id' => 'ad_product.category_id',
    		'project_name' => 'project_name',
    		'city_name' => 'city_name',
    		'district_name' => 'district_name',
    		'ward_name' => 'ward_name',
    		'street_name' => 'street_name',
    		'home_no' => 'home_no',
    		'price' => 'price',
    		'area' => 'area',
    		'room_no' => 'room_no',
    		'toilet_no' => 'toilet_no',
    		'floor_no' => 'floor_no',
    		'facade_width' => 'facade_width',
    		'land_width' => 'land_width',
    		'home_direction' => 'home_direction',
    		'facade_direction' => 'facade_direction',
    		'interior' => 'interior',
    		'content' => 'content',
    		'contact_name' => 'contact_name',
    		'contact_address' => 'contact_address',
    		'phone' => 'phone',
    		'mobile' => 'mobile',
    		'email' => 'email',
    		'created_at' => 'created_at'
    	];
    	
    	foreach ($sorts as $field => $column) {
    		$dataProvider->sort->attributes[$field] = [
    			'asc' => [$column => SORT_ASC],
    			'desc' => [$column => SORT_DESC]
    		];
    	}
    	
    	$query->andFilterWhere(['=', 'ad_product.type', $this->type]);
    	$query->andFilterWhere(['=', 'ad_product.category_id', $this->category_id]);
    	
    	if($this->price_type == 2) {
    		$unit = $this->price_unit == 1 ? 1000000000 : 1000000;
    		
    		if($this->price_min) {
    			$query->andFilterWhere(['>=', 'price', $this->price_min * $unit]);
    		}
    		if($this->price_max) {
    			$query->andFilterWhere(['<=', 'price', $this->price_max * $unit]);
    		}
    	} else {
    		$query->andFilterWhere(['=', 'ad_product.price_type', $this->price_type]);
    	}
    	
    	if($this->area_filter) {
    		if($this->area_filter == 1) {
    			$query->andWhere(['!=', 'area', 0]);
    			$query->andWhere(['IS NOT', 'area', NULL]);
    		} else if($this->area_filter == 2) {
    			$query->andWhere("(`area` = 0 OR `area` IS NULL)");
    		} else {
    			$query->andFilterWhere(['>=', 'area', $this->area_min]);
    			$query->andFilterWhere(['<=', 'area', $this->area_max]);
    		}
    	}
    	
    	$filterYesNoNull = [
    		'home_no_filter' => 'ad_product.home_no',
    		'facade_width_filter' => 'ad_product_addition_info.facade_width',
    		'land_width_filter' => 'ad_product_addition_info.land_width',
    		'home_direction_filter' => 'ad_product_addition_info.home_direction',
    		'facade_direction_filter' => 'ad_product_addition_info.facade_direction',
    		'interior_filter' => 'ad_product_addition_info.interior',
    		'contact_name_filter' => 'ad_contact_info.name',
    		'contact_address_filter' => 'ad_contact_info.address',
    		'phone_filter' => 'ad_contact_info.phone',
    		'mobile_filter' => 'ad_contact_info.mobile',
    		'email_filter' => 'ad_contact_info.email',
    		'project_name_filter' => 'ad_product.project_building_id',
    		'ward_name_filter' => 'ad_product.ward_id',
    		'street_name_filter' => 'ad_product.street_id'
    	];
    	foreach ($filterYesNoNull as $field => $column) {
    		if($this->$field) {
    			if($this->$field == 1) {
    				$query->andWhere(['IS NOT', $column, NULL]);
    			} else if($this->$field == 2) {
    				$query->andWhere(['IS', $column, NULL]);
    			}
    		}
    	}
    	
    	$filterYesNoZero = [
    		'room_no_filter' => 'ad_product_addition_info.room_no',
    		'toilet_no_filter' => 'ad_product_addition_info.toilet_no',
    		'floor_no_filter' => 'ad_product_addition_info.floor_no'
    	];
    	foreach ($filterYesNoZero as $field => $column) {
    		if($this->$field == 1) {
    			$query->andWhere(['!=', $column, 0]);
    		} else if($this->$field == 2) {
    			$query->andWhere(['=', $column, 0]);
    		} else if($this->$field == 3) {
    			$mask = str_replace('_filter', '_mask', $field);
    			$mask = $this->$mask;
    			if($mask) {
    				if(strpos($mask, "+") !== FALSE) {
    					$query->andFilterWhere(['>=', $column, $mask]);
    				} else if(strpos($this->room_no_mask, "-") !== FALSE) {
    					$query->andFilterWhere(['<=', $column, $mask]);
    					$query->andWhere(['!=', $column, 0]);
    				} else {
    					$query->andFilterWhere(['=', $column, $mask]);
    				}
    			}
    		}
    	}

    	if($this->content_filter) {
    		$query->andWhere([($this->content_filter == 1 ? '!=' : '='), 'ad_product.content', '']);
    	}
    	
    	$query->andFilterWhere(['=', 'ad_product.project_building_id', $this->project_building_id]);
    	$query->andFilterWhere(['=', 'ad_product.ward_id', $this->ward_id]);
    	$query->andFilterWhere(['=', 'ad_product.street_id', $this->street_id]);
    	$query->andFilterWhere(['=', 'ad_product.city_id', $this->city_id]);
    	$query->andFilterWhere(['=', 'ad_product.district_id', $this->district_id]);
    	
    	return $dataProvider;
    	/*
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

        $dataProvider->sort->attributes['file_name'] = [
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
    	$query->andFilterWhere(['like', 'email', $this->emailSearch]);
    	$query->andFilterWhere(['like', 'mobile', $this->mobileSearch]);
    	
    	if($this->homeNoFilter) {
    		if($this->homeNoFilter == '1') {
    			$query->andWhere(['IS NOT', 'ad_product.home_no', null]);
    		} else {
    			$query->andWhere(['ad_product.home_no' => null]);
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
    			$query->andWhere(['IS NOT', 'ad_product.ward_id', null]);
    		} else {
    			$query->andWhere(['ad_product.ward_id' => null]);
    		}
    	}

    	if($this->streetFilter) {
    		if($this->streetFilter == '1') {
    			$query->andWhere(['IS NOT', 'ad_product.street_id', null]);
    		} else {
    			$query->andWhere(['ad_product.street_id' => null]);
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
    			$query->andWhere(['!=', 'content', '']);
    		} else {
    			$query->andWhere(['content' => '']);
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
    	*/
    }
}
