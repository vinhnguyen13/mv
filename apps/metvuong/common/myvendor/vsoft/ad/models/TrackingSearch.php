<?php
namespace vsoft\ad\models;

use common\models\TrackingSearch as TS;
use yii\data\ActiveDataProvider;
use common\models\User;

class TrackingSearch extends TS {
	
	const DELAY_TRACKING = 3;
	
	public $category_search;
	public $finder;
	public $finder_search;
	
	public function rules()
	{
		return array_merge(parent::rules(), [
			[['category_search', 'finder_search'], 'safe']
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
			'finder' => 'Người tìm',
			'order_by' => 'Sắp xếp',
			'is_mobile' => 'Di động',
			'referer' => 'Nguồn đến'
		];
	}
	
	public static function track($data) {
		$ip = \Yii::$app->request->userIP;
		$now = time();
		
		$previousTracking = TrackingSearch::find()->asArray(true)->select(['MAX(created_at) `created_at`'])->where(['ip' => $ip])->one();
	
		if(!$previousTracking || $now - $previousTracking['created_at'] > self::DELAY_TRACKING) {
			$trackingSearch = new TrackingSearch();
			$trackingSearch->load($data, '');
			if(!\Yii::$app->user->isGuest) {
				$trackingSearch->user_id = \Yii::$app->user->id;
			}
			$trackingSearch->ip = $ip;
			$trackingSearch->session = \Yii::$app->session->id;
			$trackingSearch->created_at = $now;
			$trackingSearch->save(false);
		}
	}
	
	public function search($params)
	{
		$query = self::find();
		$query->joinWith(['user']);
		$query->leftJoin('ad_category_group', 'categories_id = category_id');
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
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

    	$query->andFilterWhere([
    		'or',
    		['like', 'username', $this->finder_search],
    		['like', 'ip', $this->finder_search],
    	]);
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
		
		if($this->referer) {
			if($this->referer == '2') {
				$query->andWhere("`referer` IS NULL");
			} else if($this->referer == '3') {
				$query->andWhere("`referer` LIKE '/%'");
			} else if($this->referer == '4') {
				$query->andWhere("`referer` LIKE 'http%'");
			} else {
				$query->andFilterWhere(['=', 'referer', $this->referer]);
			}
		}

		return $dataProvider;
	}
}