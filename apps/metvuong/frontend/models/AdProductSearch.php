<?php
namespace frontend\models;

use yii\data\ActiveDataProvider;
use yii\helpers\Inflector;
use vsoft\ad\models\AdProduct;
use yii\helpers\StringHelper;

class AdProductSearch extends AdProduct {
	const DEFAULT_CITY = 1;
	const DEFAULT_DISTRICT = 10; 
	
	public $price_min;
	public $price_max;
	
	public $size_min;
	public $size_max;
	
	public $created_before;
	public $order_by;
	
	public $room_no;
	public $toilet_no;
	
	function rules() {
		return array_merge(parent::rules(), [
			[['order_by'], 'string'],
			[['price_min', 'price_max', 'size_min', 'size_max', 'created_before', 'room_no', 'toilet_no'], 'number']
		]);
	}
	
	function formName() {
		return '';
	}
	
	function search($params) {
		$this->load($params);
		
		$query = AdProduct::find();
		$query->select('ad_product.id, ad_product.city_id, ad_product.district_id, ad_product.ward_id, ad_product.street_id, ad_product.lat, ad_product.lng,
						ad_product.home_no, ad_product.category_id, ad_product.price, ad_product.area, ad_product.created_at, ad_product.type,
						ad_product_addition_info.floor_no, ad_product_addition_info.room_no, ad_product_addition_info.toilet_no, ad_images.file_name as image_file_name, ad_images.folder as image_folder');
		$query->from('ad_product');
		$query->innerJoin('ad_product_addition_info', 'ad_product_addition_info.product_id = ad_product.id');
		$query->leftJoin('ad_images', 'ad_images.order = 0 AND ad_images.product_id = ad_product.id');
		$query->groupBy('ad_product.id');
		
		$where = ['status' => 1];
		
		if($this->street_id) {
			$where['street_id'] = intval($this->street_id);
		}
		
		if($this->ward_id) {
			$where['ward_id'] = intval($this->ward_id);
		}
		
		if($this->project_building_id) {
			$where['project_building_id'] = intval($this->project_building_id);
		}
		
		if(count($where) == 1 && $this->district_id) {
			$where['district_id'] = intval($this->district_id);
		}
		
		if(count($where) == 1) {
			if(!$this->city_id) {
				$this->city_id = 1;
			}
			
			$where['city_id'] = intval($this->city_id);
		}

		if($this->type) {
			$where['type'] = intval($this->type);
		}
		
		if($this->category_id) {
			$where['category_id'] = intval($this->category_id);
		}
		
		if($this->owner) {
			$where['owner'] = intval($this->owner);
		}
		
		$query->where($where);
		
		if($this->price_min) {
			$query->andWhere(['>=', 'ad_product.price', intval($this->price_min)]);
		}
		 
		if($this->price_max) {
			$query->andWhere(['<=', 'ad_product.price', intval($this->price_max)]);
		}
		
		if($this->size_min) {
			$query->andWhere(['>=', 'ad_product.area', intval($this->size_min)]);
		}
		
		if($this->size_max) {
			$query->andWhere(['<=', 'ad_product.area', intval($this->size_max)]);
		}
		
		if($this->created_before) {
			$query->andWhere(['>=', 'ad_product.created_at', strtotime($this->created_before)]);
		}
		
		if($this->room_no) {
			$query->andWhere(['>=', 'ad_product_addition_info.room_no', intval($this->room_no)]);
		}
		
		if($this->toilet_no) {
			$query->andWhere(['>=', 'ad_product_addition_info.toilet_no', intval($this->toilet_no)]);
		}
		
		$sort = $this->order_by ? $this->order_by : '-created_at';
		$doa = 'ASC';
		if(StringHelper::startsWith($sort, '-')) {
			$sort = str_replace('-', '', $sort);
			$doa = 'DESC';
		}
		
		$query->orderBy("$sort $doa");
		
		return $query;
	}
	
	function fetchValues() {
		if(!$this->district_id) {
			if($this->street_id) {
				$this->district_id = $this->street->district->id;
			} else if($this->ward_id) {
				$this->district_id = $this->ward->district->id;
			} else if($this->project_building_id) {
				if($this->projectBuilding->district) {
					$this->district_id = $this->projectBuilding->district->id;
				}
			}
		}
		
		if(!$this->city_id) {
			if($this->district_id) {
				$this->city_id = $this->district->city->id;
			}
		}
	}
}