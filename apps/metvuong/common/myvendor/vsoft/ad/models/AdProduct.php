<?php

namespace vsoft\ad\models;

use frontend\models\User;
use Yii;
use yii\helpers\Url;
use common\models\AdProduct as AP;
use vsoft\express\components\AdImageHelper;


class AdProduct extends AP
{
	const TYPE_FOR_SELL = 1;
	const TYPE_FOR_RENT = 2;
	
	const OWNER_HOST = 1;
	const OWNER_AGENT = 2;
	
	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 0;
	
	const EXPIRED = 86400;
	
	const DEFAULT_CITY = 1;
	const DEFAULT_DISTRICT = 10;
	
	public function rules()
	{
		return [
			[['category_id', 'city_id', 'district_id', 'type', 'content', 'price', 'area'], 'required'],
			[['category_id', 'project_building_id', 'user_id', 'city_id', 'district_id', 'ward_id', 'street_id', 'type', 'price', 'price_type', 'start_date', 'end_date', 'score', 'view', 'verified', 'created_at', 'updated_at', 'status', 'show_home_no'], 'integer'],
			[['price_input', 'lng', 'lat'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/'],
			[['area'], 'limitArea'],
			[['home_no'], 'string', 'max' => 32],
			[['content'], 'string', 'max' => 3200]
		];
	}
	
	public function limitArea($attribute, $params) {
		if($this->category->limit_area && $this->$attribute > $this->category->limit_area) {
			$this->addError($attribute, Yii::t('ad', sprintf('Diện tích không được lớn hơn %s.', $this->category->limit_area)));
		}
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'category_id' => 'Loại BĐS',
			'project_building_id' => 'Dự án',
            'home_no' => 'Số nhà',
			'user_id' => 'User ID',
			'city_id' => 'Tỉnh/Thành Phố',
			'district_id' => 'Quận/Huyện',
			'ward_id' => 'Phường/Xã',
			'street_id' => 'Đường',
			'type' => 'Hình thức',
			'content' => 'Nội dung tin đăng',
			'area' => 'Diện tích',
			'price' => 'Giá',
			'price_type' => 'Price Type',
			'lng' => 'Lng',
			'lat' => 'Lat',
			'start_date' => 'Start Date',
			'end_date' => 'End Date',
			'score' => 'Score',
			'view' => 'View',
			'verified' => 'Verified',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
			'status' => 'Status',
            'show_home_no' => 'Hiển thị số nhà đến người xem',
		];
	}
	
	public function beforeSave($insert) {
		if($insert) {
			$now = time();
			
			$this->created_at = $this->created_at ? $this->created_at : $now;
			$this->start_date = $this->start_date ? $this->start_date : $now;
			$this->end_date = $now + self::EXPIRED;
		} else {
			$this->updated_at = time();
		}
		
		if($this->area) {
			$this->area = str_replace(',', '.', $this->area);
		}
		
		return parent::beforeSave($insert);
	}

	public function getAddress($showHomeNo = true, $showCity = true) {
		$address = [];
		
		if(($showHomeNo && $this->home_no)) {
			$address[] = $this->home_no;
		}
		
		if($this->street_id && ($street = AdStreet::findOne($this->street_id))) {
			$address[] = "{$street->pre} {$street->name}";
		}
		
		if(($this->ward_id && ($ward = AdWard::findOne($this->ward_id)))) {
			$address[] = "{$ward->pre} {$ward->name}";
		}
		
		if($district = AdDistrict::findOne($this->district_id)) {
			$address[] = trim("{$district->pre} {$district->name}");
		}
		
		if($showCity && ($city = AdCity::findOne($this->city_id))) {
			$address[] = $city->name;
		}
		
		return implode(", ", $address);
	}

	public function getProductSaved() {
		$query = $this->hasOne(AdProductSaved::className(), ['product_id' => 'id']);
		$query->andOnCondition('`user_id` = :user_id', [':user_id'=>Yii::$app->user->id]);
		return $query;
	}
	
	public static function getAdTypes() {
		return [
			AdProduct::TYPE_FOR_SELL => 'Bán',
			AdProduct::TYPE_FOR_RENT => 'Cho thuê',
		];
	}
	
	public function getAdImages()
	{
		return $this->hasMany(AdImages::className(), ['product_id' => 'id'])->orderBy(['order' => SORT_ASC]);
	}
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getAdContactInfo()
	{
		return $this->hasOne(AdContactInfo::className(), ['product_id' => 'id']);
	}

	public function getCreatedBy()
	{
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}
	
	public function getRepresentImage() {
		$image = AdImages::find()->orderBy('`order` ASC')->where(['product_id' => $this->id])->one();
		
		if($image) {
			return $image->url;
		} else {
			return AdImages::defaultImage();
		}
	}

	public function urlDetail($scheme = false)
	{
		return Url::to(['/ad/detail', 'id' => $this->id, 'slug' => \common\components\Slug::me()->slugify($this->getAddress())], $scheme);
	}
}
