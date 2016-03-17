<?php
namespace frontend\models;

use yii\data\ActiveDataProvider;
use yii\helpers\Inflector;
use vsoft\ad\models\AdProduct;
use yii\helpers\StringHelper;

class AdProductSearch extends AdProduct {
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
	
	function search($params) {
		$this->load($params);
		$this->fetchValues();
		
		$sort = $this->order_by ? $this->order_by : '-created_at';
		$doa = SORT_ASC;
		if(StringHelper::startsWith($sort, '-')) {
			$sort = str_replace('-', '', $sort);
			$doa = SORT_DESC;
		}
		
		$query = AdProduct::find();
		$dataProvider = new ActiveDataProvider(['query' => $query, 'sort' => ['defaultOrder' => [$sort => $doa]]]);
		
		$where = ['status' => 1];
		
		if($this->street_id) {
			$where['street_id'] = $this->street_id;
		}
		
		if($this->ward_id) {
			$where['ward_id'] = $this->ward_id;
		}
		
		if($this->project_building_id) {
			$where['project_building_id'] = $this->project_building_id;
		}
		
		if(count($where) == 1 && $this->district_id) {
			$where['district_id'] = $this->district_id;
		}
		
		if(count($where) == 1 && $this->city_id) {
			$where['city_id'] = $this->city_id;
		}
		
		if($this->category_id) {
			$where['category_id'] = $this->category_id;
		}
		
		if($this->owner) {
			$where['owner'] = $this->owner;
		}
		
		$query->where($where);
		
		if($this->price_min) {
			$query->andWhere(['>=', 'ad_product.price', $this->price_min]);
		}
		 
		if($this->price_max) {
			$query->andWhere(['<=', 'ad_product.price', $this->price_max]);
		}
		
		if($this->size_min) {
			$query->andWhere(['>=', 'ad_product.area', $this->size_min]);
		}
		
		if($this->size_max) {
			$query->andWhere(['<=', 'ad_product.area', $this->size_max]);
		}
		
		if($this->created_before) {
			$query->andWhere(['>=', 'ad_product.created_at', strtotime($this->created_before)]);
		}
		
		if($this->room_no) {
			$query->andWhere(['>=', 'ad_product_addition_info.room_no', $this->room_no]);
		}
		
		if($this->toilet_no) {
			$query->andWhere(['>=', 'ad_product_addition_info.toilet_no', $this->toilet_no]);
		}
		
		$query->leftJoin('ad_product_addition_info', '`ad_product_addition_info`.`product_id` = `ad_product`.`id`');
		
		return $dataProvider;
	}
	
	function fetchValues() {
		if($this->district_id === null) {
			if($this->street_id) {
				$this->district_id = $this->street->district->id;
			} else if($this->ward_id) {
				$this->district_id = $this->ward->district->id;
			} else if($this->project_building_id) {
				if($this->projectBuilding->district) {
					$this->district_id = $this->projectBuilding->district->id;
				}
			} else {
				$this->district_id = 10;
			}
		}
		
		if(!$this->city_id) {
			if($this->district_id) {
				$this->city_id = $this->district->city->id;
			} else {
				$this->city_id = 1;
			}
		}
	}
}