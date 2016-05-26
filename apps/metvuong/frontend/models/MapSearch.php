<?php
namespace frontend\models;

use yii\db\Query;
use vsoft\ad\models\AdProduct;
use yii\data\Pagination;

class MapSearch extends AdProduct {
	
	public $price_min;
	public $price_max;
	
	public $size_min;
	public $size_max;
	
	public $created_before;
	public $order_by;
	
	public $room_no;
	public $toilet_no;
	
	public $rect;
	public $rm;
	public $ra;
	public $ra_k;
	public $rl;
	
	function rules() {
		return array_merge(parent::rules(), [
			[['order_by', 'rect', 'ra', 'ra_k'], 'string'],
			[['price_min', 'price_max', 'size_min', 'size_max', 'created_before', 'room_no', 'toilet_no', 'rm', 'rl'], 'number']
		]);
	}
	
	function formName() {
		return '';
	}
	
	public function search($args) {
		$this->load($args);

		$query = new Query();

		$query->select('ad_product.id, area, price, lng, lat, room_no, toilet_no');
		$query->from('ad_product');
		$query->innerJoin('ad_product_addition_info', 'ad_product_addition_info.product_id = ad_product.id');
		
		$where = ['status' => 1, 'is_expired' => 0, 'verified' => 1];
		$totalInitWhere = count($where);
		
		if($this->street_id) {
			$where['street_id'] = intval($this->street_id);
		}
		
		if($this->ward_id) {
			$where['ward_id'] = intval($this->ward_id);
		}
		
		if($this->project_building_id) {
			$where['project_building_id'] = intval($this->project_building_id);
		}
		
		if(count($where) == $totalInitWhere && $this->district_id) {
			$where['district_id'] = intval($this->district_id);
		}
		
		if(count($where) == $totalInitWhere) {
			if($this->city_id) {
				$where['city_id'] = intval($this->city_id);
			} else {
				$where['city_id'] = AdProduct::DEFAULT_CITY;
			}
		}
		
		if($this->type) {
			$where['type'] = intval($this->type);
		} else {
			$where['type'] = AdProduct::TYPE_FOR_SELL;
		}
		
		if($this->category_id) {
			$where['category_id'] = explode(',', $this->category_id);
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
		
		if($this->rect) {
			$rect = explode(',', $this->rect);
			
			$query->andWhere(['>=', 'lat', $rect[0]]);
			$query->andWhere(['<=', 'lat', $rect[2]]);
			$query->andWhere(['>=', 'lng', $rect[1]]);
			$query->andWhere(['<=', 'lng', $rect[3]]);
		}
		
		return $query;
	}
	
	public function getList($query) {
		$listQuery = clone $query;
			
		$countQuery = clone $listQuery;
		$pages = new Pagination(['totalCount' => $countQuery->count()]);
		$pages->setPageSize(\Yii::$app->params['listingLimit']);
				
		$listQuery->offset($pages->offset);
		$listQuery->limit($pages->limit);
		
		return ['products' => $listQuery->all(), 'pages' => $pages];
	}
}