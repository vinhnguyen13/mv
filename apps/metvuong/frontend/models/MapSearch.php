<?php
namespace frontend\models;

use yii\db\Query;
use vsoft\ad\models\AdProduct;
use yii\data\Pagination;
use vsoft\express\components\StringHelper;
use vsoft\ad\models\AdStreet;
use vsoft\ad\models\AdWard;
use vsoft\ad\models\AdBuildingProject;
use vsoft\ad\models\AdDistrict;

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
	public $iz;
	public $z;
	public $c;
	public $did;
	public $page;
	
	public $streets;
	public $wards;
	public $districts;
	
	function rules() {
		return array_merge(parent::rules(), [
			[['order_by', 'rect', 'ra', 'ra_k', 'z', 'c'], 'string'],
			[['price_min', 'price_max', 'size_min', 'size_max', 'created_before', 'room_no', 'toilet_no', 'rm', 'rl', 'page', 'iz', 'did'], 'number']
		]);
	}
	
	function formName() {
		return '';
	}
	
	public function search($args) {
		$this->load($args);

		$query = new Query();

		$query->select('ad_product.boost_time, ad_product.show_home_no, ad_product.home_no, ad_product.street_id, ad_product.ward_id, ad_product.district_id, ad_product.id, ad_product.area, ad_product.price, ad_product.lng, ad_product.lat, ad_product_addition_info.room_no, ad_product_addition_info.toilet_no');
		$query->from('ad_product');
		$query->innerJoin('ad_product_addition_info', 'ad_product_addition_info.product_id = ad_product.id');
		
		$where = [
			'ad_product.status' => 1,
			'ad_product.is_expired' => 0,
			//'ad_product.verified' => 1
		];
		$totalInitWhere = count($where);
		
		if($this->street_id) {
			$where['ad_product.street_id'] = intval($this->street_id);
		}
		
		if($this->ward_id) {
			$where['ad_product.ward_id'] = intval($this->ward_id);
		}
		
		if($this->project_building_id) {
			$where['ad_product.project_building_id'] = intval($this->project_building_id);
		}
		
		if(count($where) == $totalInitWhere) {
			if(!$this->district_id && !$this->city_id) {
				$this->district_id = self::DEFAULT_DISTRICT;
				$where['ad_product.district_id'] = self::DEFAULT_DISTRICT;
			} else if($this->district_id) {
				$where['ad_product.district_id'] = intval($this->district_id);
			}
		}

		if(count($where) == $totalInitWhere) {
			if($this->city_id) {
				$where['ad_product.city_id'] = intval($this->city_id);
			} else {
				$where['ad_product.city_id'] = AdProduct::DEFAULT_CITY;
			}
		}
		
		if($this->type) {
			$where['ad_product.type'] = intval($this->type);
		} else {
			$where['ad_product.type'] = AdProduct::TYPE_FOR_SELL;
		}
		
		if($this->category_id) {
			$where['ad_product.category_id'] = explode(',', $this->category_id);
		}
		
		if($this->owner) {
			$where['ad_product.owner'] = intval($this->owner);
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
		
		return $query;
	}
	
	public function getAutoFillValue() {
		$fill = [];
		
		if($this->street_id) {
			$fill[] = $this->street->pre . ' ' . $this->street->name;
		} else if($this->ward_id) {
			$fill[] = $this->ward->pre . ' ' . $this->ward->name;
		} else if($this->project_building_id) {
			$fill[] = $this->projectBuilding->name;
		}
		
		if($this->district_id) {
			$fill[] = trim($this->district->pre . ' ' . $this->district->name);
		}
		
		$fill[] = $this->city->name;
		
		return implode(', ', $fill);
	}
	
	public function getList($query) {
		$listQuery = clone $query;
			
		$countQuery = clone $listQuery;
		$pages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize' => \Yii::$app->params['listingLimit'], 'route' => '/ad/index' . $this->type]);
		$pages->setPageSize(\Yii::$app->params['listingLimit']);
				
		$listQuery->offset($pages->offset);
		$listQuery->limit($pages->limit);
		
		$listQuery->addSelect([
			"ad_product.user_id",
			"ad_product.score",
			"ad_product.start_date",
			"ad_product.category_id",
			"ad_product.type",
		]);
		
		$products = $listQuery->all();
		
		if(\Yii::$app->params['tracking']['all']) {
			$tracking = !empty($this->project_building_id) || ((!empty($this->ward_id) || !empty($this->street_id)) && (!empty($this->room_no) || !empty($this->price_min) || !empty($this->price_max)));
			
			if($tracking && \Yii::$app->user->identity) {
				$userId = \Yii::$app->user->id;
				
				foreach ($products as $product) {
					if($product['user_id'] != $userId) {
						Tracking::find()->productFinder($userId, $product['id'], time());
					}
				}
			}
		}
		
		return ['products' => $products, 'pages' => $pages];
	}
	
	public function sort($query) {
		$sort = $this->order_by ? $this->order_by : '-score';
		$doa = StringHelper::startsWith($sort, '-') ? 'DESC' : 'ASC';
		$sort = str_replace('-', '', $sort);
		
		$query->orderBy("boost_time DESC");
		$query->addOrderBy("$sort $doa");
	}
	
	function fetchValues() {
		if(!$this->district_id) {
			if($this->street_id) {
				$this->district_id = $this->street->district_id;
			} else if($this->ward_id) {
				$this->district_id = $this->ward->district_id;
			} else if($this->project_building_id) {
				if($this->projectBuilding->district) {
					$this->district_id = $this->projectBuilding->district_id;
				}
			}
		}
	
		if(!$this->city_id) {
			if($this->district_id) {
				$this->city_id = $this->district->city_id;
			} else {
				$this->city_id = self::DEFAULT_CITY;
			}
		}
	
		if(!$this->type) {
			$this->type = AdProduct::TYPE_FOR_SELL;
		}
		
		if($this->street_id) {
			$this->streets = [$this->street_id => $this->street->getAttributes()];
		} else if($this->district_id) {
			$this->streets = AdStreet::find()->asArray(true)->where(['district_id' => $this->district_id])->indexBy('id')->all();
		} else if($this->city_id) {
			$this->streets = AdStreet::find()->asArray(true)->where(['city_id' => $this->city_id])->indexBy('id')->all();
		}
		
		if($this->ward_id) {
			$this->wards = [$this->ward_id => $this->ward->getAttributes()];
		} else if($this->district_id) {
			$this->wards = AdWard::find()->asArray(true)->where(['district_id' => $this->district_id])->indexBy('id')->all();
		} else if($this->city_id) {
			$this->wards = AdWard::find()->asArray(true)->where(['city_id' => $this->city_id])->indexBy('id')->all();
		}
		
		if($this->district_id) {
			$this->districts = [$this->district_id => $this->district->getAttributes()];
		} else if($this->city_id) {
			$this->districts = AdDistrict::find()->asArray(true)->where(['city_id' => $this->city_id])->indexBy('id')->all();
		}
	}
}