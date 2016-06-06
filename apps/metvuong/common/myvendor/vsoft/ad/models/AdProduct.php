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
	
	const TYPE_FOR_SELL_TOTAL = 'total_sell';
	const TYPE_FOR_RENT_TOTAL = 'total_rent';
	
	private $oldAttr = [];
	private static $elasticUpdateFields = ['city', 'district', 'ward', 'street', 'project_building'];
	
	public $image_file_name;
	public $image_folder;
	
	public function rules()
	{
		return [
			[['category_id', 'city_id', 'district_id', 'type', 'content', 'price', 'area'], 'required'],
			[['category_id', 'project_building_id', 'user_id', 'city_id', 'district_id', 'ward_id', 'street_id', 'type', 'price', 'price_type', 'start_date', 'end_date', 'score', 'view', 'verified', 'created_at', 'updated_at', 'status', 'owner', 'show_home_no'], 'integer'],
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
			'category_id' => Yii::t('ad', 'Property Types'),
			'project_building_id' => \Yii::t('ad', 'Project'),
            'home_no' => Yii::t('ad', 'Address'),
			'user_id' => 'User ID',
			'city_id' => Yii::t('ad', 'City'),
			'district_id' => Yii::t('ad', 'District'),
			'ward_id' => Yii::t('ad', 'Ward'),
			'street_id' => Yii::t('ad', 'Street'),
			'type' => Yii::t('ad', 'Type of transaction'),
			'content' => Yii::t('ad', 'Content'),
			'area' => Yii::t('ad', 'Home size'),
			'price' => Yii::t('ad', 'Price'),
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
            'show_home_no' => Yii::t('ad', 'Show address'),
		];
	}
	
	public function beforeSave($insert) {
		if($insert) {
			$now = time();
			
			$this->created_at = $this->created_at ? $this->created_at : $now;
			$this->updated_at = $this->updated_at ? $this->updated_at : $now;
			$this->start_date = $this->start_date ? $this->start_date : $now;
			$this->end_date = $now + (self::EXPIRED * 30);
		} else {
			$this->updated_at = time();
		}
		
		if($this->area) {
			$this->area = str_replace(',', '.', $this->area);
		}
		
		// Cast to int to detect changedAttribute in afterSave EVENT
		$this->oldAttr = $this->oldAttributes;
		
		return parent::beforeSave($insert);
	}

	public function getAddress($showHomeNo = true, $showCity = true) {
		$address = [];
		
		if(($showHomeNo && $this->home_no)) {
			$address[] = $this->home_no;
		}
		
		if($this->street) {
			$address[] = "{$this->street->pre} {$this->street->name}";
		}
		
		if($this->ward) {
			$address[] = "{$this->ward->pre} {$this->ward->name}";
		}
		
		if($this->district) {
			$address[] = trim("{$this->district->pre} {$this->district->name}");
		}
		
		if($showCity && $this->city) {
			$address[] = $this->city->name;
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
			AdProduct::TYPE_FOR_SELL => \Yii::t('ad', 'Sell'),
			AdProduct::TYPE_FOR_RENT => \Yii::t('ad', 'Rent'),
		];
	}
	
	public static function getAdOwners() {
		return [
			AdProduct::OWNER_HOST => Yii::t('ad', 'owner'),
			AdProduct::OWNER_AGENT => Yii::t('ad', 'agent'),
		];
	}
	
	public function getOwnerString() {
		$owners = self::getAdOwners();
		
		return $owners[$this->owner];
	}
	
	public function getAdImages()
	{
		return $this->hasMany(AdImages::className(), ['product_id' => 'id'])->orderBy(['order' => SORT_ASC])->indexBy('id');
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

	public function getProjectBuilding()
	{
		return $this->hasOne(AdBuildingProject::className(), ['id' => 'project_building_id']);
	}
	
    public function getAdProductAdditionInfo()
    {
        return $this->hasOne(AdProductAdditionInfo::className(), ['product_id' => 'id']);
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
		return Url::to(['/ad/detail' . $this->type, 'id' => $this->id, 'slug' => \common\components\Slug::me()->slugify($this->getAddress($this->show_home_no))], $scheme);
	}

    public function getExpired(){
        $d = $this->end_date - time();
        return ceil($d / (60 * 60 * 24));
    }
	
	public function afterSave($insert, $changedAttributes) {
		$totalType = ($this->type == self::TYPE_FOR_SELL) ? self::TYPE_FOR_SELL_TOTAL : self::TYPE_FOR_RENT_TOTAL;
		if($insert) {
			foreach(self::$elasticUpdateFields as $field) {
				$attr = $field . '_id';
				if($this->attributes[$attr]) {
					$this->updateElasticCounter($field, $this->attributes[$attr], $totalType);
				}
			}
		} else {
			if($this->oldAttr['type'] != $this->attributes['type']) {
				if($this->oldAttr['type'] == self::TYPE_FOR_SELL) {
					$oldTotalType = self::TYPE_FOR_SELL_TOTAL;
					$newTotalType = self::TYPE_FOR_RENT_TOTAL;
				} else {
					$oldTotalType = self::TYPE_FOR_RENT_TOTAL;
					$newTotalType = self::TYPE_FOR_SELL_TOTAL;
				}
				
				foreach(self::$elasticUpdateFields as $field) {
					$attr = $field . '_id';
					$this->updateElasticCounter($field, $this->oldAttr[$attr], $oldTotalType, false);
					$this->updateElasticCounter($field, $this->attributes[$attr], $newTotalType);
				}
			} else {
				foreach(self::$elasticUpdateFields as $field) {
					$attr = $field . '_id';
					if($this->oldAttr[$attr] != $this->attributes[$attr]) {
						if($this->attributes[$attr]) {
							$this->updateElasticCounter($field, $this->attributes[$attr], $totalType);
						}
						
						if($this->oldAttr[$attr]) {
							$this->updateElasticCounter($field, $this->oldAttr[$attr], $totalType, false);
						}
					}
				}
			}
		}
		
		parent::afterSave($insert, $changedAttributes);
	}
	
	public static function updateElasticCounter($type, $id, $totalType, $increase = true) {
		$sign = $increase ? '+' : '-';
		$script = '{"script" : "ctx._source.' . $totalType . $sign . '=1"}';
		$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . "/term/$type/$id/_update");
			
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $script);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_exec($ch);
		curl_close($ch);
	}

	public static function calcScore($product, $additionInfo = false, $contactInfo = false, $totalImage = false) {
		
		if(is_object($product)) {
			if($additionInfo === false) {
				$additionInfo = $product->adProductAdditionInfo;
			}
			if($contactInfo === false) {
				$contactInfo = $product->adContactInfo;
			}
			if($totalImage === false) {
				$totalImage = count($product->adImages);
			}
			
			$product = $product->getAttributes();
		}
		
		if(is_object($additionInfo)) {
			$additionInfo = $additionInfo->getAttributes();
		}
		
		if(is_object($contactInfo)) {
			$contactInfo = $contactInfo->getAttributes();
		}
		
		$score = 0;
		
		$score += empty($product['type']) ? 0 : 3;
		
		if(!empty($product['category_id'])) {
			if($product['category_id'] == AdCategory::CATEGORY_CHCK) {
				$score += 2;
				$score += empty($product['project_building_id']) ? 0 : 3;
			} else {
				$score += 5;
			}
		}
		
		$score += empty($product['city_id']) ? 0 : 2;
		$score += empty($product['district_id']) ? 0 : 2;
		$score += empty($product['ward_id']) ? 0 : 2;
		$score += empty($product['street_id']) ? 0 : 2;
		
		if(!empty($product['category_id']) && $product['category_id'] != 10 && $product['category_id'] != 11) {
			$score += empty($product['home_no']) ? 0 : 4;
			
			$score += empty($additionInfo['room_no']) ? 0 : 5;
			$score += empty($additionInfo['toilet_no']) ? 0 : 5;
		}
		
		$score += empty($product['area']) ? 0 : 5;
		$score += empty($product['price']) ? 0 : 5;
		
		if(!empty($product['content'])) {
			$words = preg_split('/\s+/', $product['content']);
			
			if(count($words) >= 30) {
				$score += 15;
			} else if(count($words) >= 20) {
				$score += 10;
			} else if(count($words) >= 10) {
				$score += 5;
			}
		}
		
		$score += empty($additionInfo['floor_no']) ? 0 : 2;
		$score += empty($additionInfo['facade_width']) ? 0 : 2;
		$score += empty($additionInfo['land_width']) ? 0 : 2;
		$score += empty($additionInfo['home_direction']) ? 0 : 2;
		$score += empty($additionInfo['facade_direction']) ? 0 : 2;
		
		if(!empty($additionInfo['facility'])) {
			if (is_string($additionInfo['facility'])) {
				$additionInfo['facility'] = explode(',', $additionInfo['facility']);
			}
			$facility = count($additionInfo['facility']);
			$score += ($facility > 5) ? 5 : $facility;
		}
			
		if($totalImage > 2) {
			$score += 10;
		} else if($totalImage > 0) {
			$score += 5;
		}

		$score += empty($contactInfo['name']) ? 0 : 5;
		$score += empty($contactInfo['mobile']) ? 0 : 5;
		$score += empty($contactInfo['email']) ? 0 : 5;
		$score += empty($product['owner']) ? 0 : 5;
		
		return $score;
	}
}
