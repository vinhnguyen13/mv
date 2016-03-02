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
	
	public function rules()
	{
		return [
			[['category_id', 'home_no', 'city_id', 'district_id', 'type', 'content', 'price', 'area'], 'required'],
			[['category_id', 'project_building_id', 'user_id', 'city_id', 'district_id', 'ward_id', 'street_id', 'type', 'price', 'price_type', 'start_date', 'end_date', 'score', 'view', 'verified', 'created_at', 'updated_at', 'status'], 'integer'],
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

	public function getAddress($withoutCity = false) {
		$address = '';
		$street = AdStreet::findOne($this->street_id);
		$ward = AdWard::findOne($this->ward_id);
		$district = AdDistrict::findOne($this->district_id);
		$city = AdCity::findOne($this->city_id);
		if($this->home_no) {
			$address .= "{$this->home_no}, ";
		}
		if($street) {
			$address .= "{$street->pre} {$street->name}, ";
		}
		if($ward) {
			$address .= "{$ward->pre} {$ward->name}, ";
		}
		if($address) {
			$address .= "{$district->pre} {$district->name}";
		} else {
			$address = "{$district->pre} {$district->name}";
		}
		
		if(!$withoutCity) {
			$address .= ", {$city->name}";
		}
		
		return $address;
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

	public function getOwner()
	{
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}
	
	public function getImage($size = false) {
		$image = AdImages::find()->where(['product_id' => $this->id])->one();
		
		if($image) {
			$size = $size ? $size : AdImages::SIZE_MEDIUM;
			$folder = AdImageHelper::makeFolderName(AdImageHelper::$sizes[$size]);
			
			return '/store/' . $image->folder . '/' . $folder . '/' . $image->file_name;
		} else {
			return AdImages::defaultImage();
		}
	}

	public function urlDetail()
	{
		return Url::to(['/ad/detail', 'id' => $this->id, 'slug' => \common\components\Slug::me()->slugify($this->getAddress())]);
	}
}
