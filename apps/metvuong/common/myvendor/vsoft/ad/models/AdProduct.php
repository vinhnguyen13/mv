<?php

namespace vsoft\ad\models;

use Yii;
use vsoft\ad\models\base\AdProductBase;


class AdProduct extends AdProductBase
{
	const TYPE_FOR_SELL = 1;
	const TYPE_FOR_RENT = 2;
	
	public function rules()
	{
		return [
			[['category_id', 'home_no', 'city_id', 'district_id', 'type', 'content', 'start_date', 'end_date', 'created_at'], 'required'],
			[['category_id', 'project_building_id', 'user_id', 'city_id', 'district_id', 'ward_id', 'street_id', 'type', 'price', 'price_type', 'start_date', 'end_date', 'score', 'view', 'verified', 'created_at', 'updated_at', 'status'], 'integer'],
			[['area', 'price_input', 'lng', 'lat'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/'],
			[['home_no'], 'string', 'max' => 32],
			[['content'], 'string', 'max' => 3200]
		];
	}
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

	public function getAddress() {
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
			$address .= "{$district->pre} {$district->name}, {$city->name}";
		} else {
			$address = "{$district->pre} {$district->name}, {$city->name}";
		}
		return $address;
	}
}
