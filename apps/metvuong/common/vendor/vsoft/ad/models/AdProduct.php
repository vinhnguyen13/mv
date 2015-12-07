<?php

namespace common\vendor\vsoft\ad\models;

use Yii;
use common\vendor\vsoft\ad\models\base\AdProductBase;


class AdProduct extends AdProductBase
{
	const TYPE_FOR_SELL = 1;
	const TYPE_FOR_RENT = 2;
	
	const PRICE_DEAL = 1;
	const SELL_PRICE_MILLION = 2;
	const SELL_PRICE_BILLION = 3;
	const SELL_PRICE_HUNDRED_THOUSAND_SQUARE = 4;
	const SELL_PRICE_MILLION_SQUARE = 5;
	const RENT_PRICE_HUNDRED_THOUSAND = 6;
	const RENT_PRICE_MILLION = 7;
	const RENT_PRICE_HUNDRED_THOUSAND_SQUARE = 8;
	const RENT_PRICE_MILLION_SQUARE = 9;
	const RENT_PRICE_THOUSAND_SQUARE = 10;
	
	
	public static function priceTypeForSell() {
		return [
			self::PRICE_DEAL => 'Thương lượng',
				self::SELL_PRICE_MILLION => 'Triệu',
				self::SELL_PRICE_BILLION => 'Tỷ',
				self::SELL_PRICE_HUNDRED_THOUSAND_SQUARE => 'Trăm nghìn/m²',
				self::SELL_PRICE_MILLION_SQUARE => 'Triệu/m²'
		];
	}
	
	public static function priceTypeForRent() {
		return [
			self::PRICE_DEAL => 'Thương lượng',
			self::RENT_PRICE_HUNDRED_THOUSAND => 'Trăm nghìn/tháng',
				self::RENT_PRICE_MILLION => 'Triệu/tháng',
				self::RENT_PRICE_HUNDRED_THOUSAND_SQUARE => 'Trăm nghìn/m²/tháng',
				self::RENT_PRICE_MILLION_SQUARE => 'Triệu/m²/tháng',
				self::RENT_PRICE_THOUSAND_SQUARE => 'Nghìn/m²/tháng'
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
		$this->price = $this->calculatePrice($this->price_input);
		
		return parent::beforeSave($insert);
	}
	
	public function calculatePrice($priceInput) {
		$price = null;
		
		if($this->price_type != self::PRICE_DEAL) {
			switch ($this->price_type) {
				case self::SELL_PRICE_MILLION:
				case self::RENT_PRICE_MILLION;
					$price = $priceInput * 1000000;
					break;
				case self::SELL_PRICE_MILLION_SQUARE:
				case self::RENT_PRICE_MILLION_SQUARE:
					$price = $priceInput * 1000000 * $this->area;
					break;
				case self::SELL_PRICE_BILLION:
					$price = $priceInput * 1000000000;
					break;
				case self::SELL_PRICE_HUNDRED_THOUSAND_SQUARE:
				case self::RENT_PRICE_HUNDRED_THOUSAND_SQUARE;
					$price = $priceInput * 100000 * $this->area;
					break;
				case self::RENT_PRICE_THOUSAND_SQUARE:
					$price = $priceInput * 1000 * $this->area;
					break;
				case self::RENT_PRICE_HUNDRED_THOUSAND:
					$price = $priceInput * 100000;
					break;
			}
			
			$price = round($price);
		}
		
		return $price;
	}
}
