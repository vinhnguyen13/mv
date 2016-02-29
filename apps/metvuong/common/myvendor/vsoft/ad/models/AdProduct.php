<?php

namespace vsoft\ad\models;

use Yii;
use common\models\AdProduct as AP;


class AdProduct extends AP
{
	const TYPE_FOR_SELL = 1;
	const TYPE_FOR_RENT = 2;
	
	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 0;
	
// 	public function rules()
// 	{
// 		return [
// 			[['category_id', 'home_no', 'city_id', 'district_id', 'type', 'content', 'start_date', 'end_date', 'created_at', 'price', 'area'], 'required'],
// 			[['category_id', 'project_building_id', 'user_id', 'city_id', 'district_id', 'ward_id', 'street_id', 'type', 'price', 'price_type', 'start_date', 'end_date', 'score', 'view', 'verified', 'created_at', 'updated_at', 'status'], 'integer'],
// 			[['price_input', 'lng', 'lat'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/'],
// 			[['area'], 'number', 'max' => $this->category->limit_area, 'when' => function ($model) {
//         		return $model->category->limit_area;
//     		}],
// 			[['home_no'], 'string', 'max' => 32],
// 			[['content'], 'string', 'max' => 3200]
// 		];
// 	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'category_id' => 'Category ID',
			'project_building_id' => 'Project Building ID',
            'home_no' => 'Số nhà',
			'user_id' => 'User ID',
			'city_id' => 'City ID',
			'district_id' => 'District ID',
			'ward_id' => 'Ward ID',
			'street_id' => 'Street ID',
			'type' => 'Hình thức',
			'content' => 'Nội dung tin đăng',
			'area' => 'Diện tích',
			'price' => 'Price',
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
			$this->created_at = time();
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
	
	public function getImage($size = false) {
		$image = AdImages::find()->where(['product_id' => $this->id])->one();
		
		if($image) {
			if(!$size) {
				$size = AdImages::SIZE_THUMB;
			}
			
			return AdImages::getImageUrl($image->file_name, $size);
		} else {
			return AdImages::defaultImage();
		}
	}
}
