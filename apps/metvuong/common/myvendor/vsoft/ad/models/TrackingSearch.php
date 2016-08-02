<?php
namespace vsoft\ad\models;

use common\models\TrackingSearch as TS;
use yii\data\ActiveDataProvider;
use common\models\User;

class TrackingSearch extends TS {
	
	const DELAY_TRACKING = 3;
	const FROM_HOME = 1;
	const FROM_IN_PAGE = 2;
	const FROM_QUICK_SEARCH = 3;
	const FROM_OTHER_PAGE = 4;
	const FROM_OTHER_SITE = 5;
	const FROM_DIRECT = 6;
	
	public $category_search;
	public $finder_search;
	public $finder_filter;
	public $from_filter;
	
	public function rules()
	{
		return array_merge(parent::rules(), [
			[['category_search', 'finder_search', 'from_filter', 'finder_filter'], 'safe']
		]);
	}
	
	public function getCategory()
	{
		return $this->hasOne(AdCategoryGroup::className(), ['categories_id' => 'category_id']);
	}
	
	public function getUser()
	{
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}
	
	public function formName() {
		return '';
	}
	
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'user_id' => 'User ID',
			'session' => 'Session',
			'ip' => 'Ip',
			'type' => 'Type',
			'location' => 'Location',
			'city_id' => 'City ID',
			'district_id' => 'District ID',
			'ward_id' => 'Ward ID',
			'street_id' => 'Street ID',
			'project_building_id' => 'Project Building ID',
			'category_id' => 'Category ID',
			'room_no' => 'Phòng ngủ',
			'toilet_no' => 'Phòng tắm',
			'created_at' => 'Thời gian',
			'price_min' => 'Giá thấp nhất',
			'price_max' => 'Giá cao nhất',
			'size_min' => 'Diện tích nhỏ nhất',
			'size_max' => 'Diện tích lớn nhất',
			'category' => 'Loại BĐS',
			'alias' => 'Người tìm',
			'order_by' => 'Sắp xếp',
			'is_mobile' => 'Di động',
			'from' => 'Nguồn đến'
		];
	}
	
	public function search($params)
	{
		$query = self::find();
		$query->joinWith(['user']);
		$query->leftJoin('ad_category_group', 'categories_id = category_id');
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]]
		]);
		
		if ($this->load($params) && !$this->validate()) {
			return $dataProvider;
		}
    	
    	$dataProvider->sort->attributes['category'] = [
    		'asc' => ['ad_category_group.name' => SORT_ASC],
    		'desc' => ['ad_category_group.name' => SORT_DESC]
    	];
    	
    	$dataProvider->sort->attributes['finder'] = [
    		'asc' => ['user.username' => SORT_ASC],
    		'desc' => ['user.username' => SORT_DESC]
    	];

    	if($this->finder_filter !== null && $this->finder_filter !== '') {
    		if($this->finder_filter == 0) {
    			$query->andWhere("user_id IS NOT NULL");
    		} else if($this->finder_filter == 1) {
    			$query->andWhere("user_id IS NULL");
    		} else {
    			$query->andFilterWhere(['like', 'alias', $this->alias]);
    		}
    	}
    	
		$query->andFilterWhere(['=', 'category_id', $this->category_search]);
		$query->andFilterWhere(['like', 'location', $this->location]);
		$query->andFilterWhere(['=', 'room_no', $this->room_no]);
		$query->andFilterWhere(['=', 'toilet_no', $this->toilet_no]);
		$query->andFilterWhere(['=', 'price_min', $this->price_min]);
		$query->andFilterWhere(['=', 'price_max', $this->price_max]);
		$query->andFilterWhere(['=', 'size_min', $this->size_min]);
		$query->andFilterWhere(['=', 'size_max', $this->size_max]);
		$query->andFilterWhere(['=', 'order_by', $this->order_by]);
		$query->andFilterWhere(['=', 'type', $this->type]);
		$query->andFilterWhere(['=', 'is_mobile', $this->is_mobile]);
		
		if($this->from_filter) {
			$query->andFilterWhere(['=', 'from', $this->from_filter]);
		}

		return $dataProvider;
	}
}