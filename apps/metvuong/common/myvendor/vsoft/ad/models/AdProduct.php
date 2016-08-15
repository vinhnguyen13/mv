<?php

namespace vsoft\ad\models;

use frontend\models\User;
use Yii;
use yii\helpers\Url;
use common\models\AdProduct as AP;
use vsoft\express\components\AdImageHelper;
use frontend\models\Elastic;
use yii\helpers\ArrayHelper;
use yii\db\Query;
use yii\db\yii\db;

class AdProduct extends AP
{
	const CHARGE_POST = 5;
	const CHARGE_BOOST_1 = 3;
	const CHARGE_BOOST_3 = 5;
	
	
	const TYPE_FOR_SELL = 1;
	const TYPE_FOR_RENT = 2;
	
	const OWNER_HOST = 1;
	const OWNER_AGENT = 2;

	/*
	 * STATUS_DELETE: Tin sẽ không hiển thị trong dashboard và search
	 * STATUS_PENDING: Tin hiển thị trong dashboard nhưng không hiển thị ở search, trạng thái này khi người dùng đăng tin nhưng không có key để active
	 * STATUS_INACTIVE: Tin hiển thị trong dashboard nhưng không hiển thị ở search, trạng thái này khi người dùng đã bán được nhà và muốn ẩn khỏi search để khỏi bị gọi điện làm phiền
	 * STATUS_ACTIVE: Tin sẽ được hiển thị trong dashboard và search
	 */
	const STATUS_DELETE = -2;
	const STATUS_PENDING = -1;
	const STATUS_INACTIVE = 0;
	const STATUS_ACTIVE = 1;
	
	const EXPIRED = 5184000;
	const BOOST_LIMIT = 2592000;
	
	const DEFAULT_CITY = 1;
	const DEFAULT_DISTRICT = 10;
	
	const TYPE_FOR_SELL_TOTAL = 'total_sell';
	const TYPE_FOR_RENT_TOTAL = 'total_rent';
	
	const BOOST_SORT_LIMIT = 4;
	
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
			[['home_no'], 'validateHomeNo'],
			[['home_no'], 'string', 'max' => 32],
			[['content'], 'string', 'max' => 3200]
		];
	}
	
	public function limitArea($attribute, $params) {
		if($this->category->limit_area && $this->$attribute > $this->category->limit_area) {
			$this->addError($attribute, Yii::t('ad', sprintf('Diện tích không được lớn hơn %s.', $this->category->limit_area)));
		}
	}
	
	public function validateHomeNo($attribute, $params) {
		if($this->$attribute && (preg_match_all("/[a-z]/", Elastic::transform($this->$attribute)) > 6 || !preg_match("/[0-9]/", $this->$attribute))) {
			$this->addError($attribute, Yii::t('ad', 'Số nhà không hợp lệ.'));
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
		if (parent::beforeSave($insert)) {
			
			if($insert) {
				if($this->area) {
					$this->area = str_replace(',', '.', $this->area);
				}
			} else {
				$this->updated_at = time();
				
				if(empty($this->oldAttributes['area'])) {
					if($this->area) {
						$this->area = str_replace(',', '.', $this->area);
					}
				} else {
					if($this->area != $this->oldAttributes['area']) {
						$this->area = str_replace(',', '.', $this->area);
					}
				}
			}
			
			$this->oldAttr = $this->oldAttributes;
			
			return true;
		} else {
			return false;
		}
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
		$this->oldAttr = $this->attributes;
		
		return parent::afterSave($insert, $changedAttributes);
		/*
		$totalType = ($this->type == self::TYPE_FOR_SELL) ? self::TYPE_FOR_SELL_TOTAL : self::TYPE_FOR_RENT_TOTAL;
		
		if($insert) {
			if($this->status == self::STATUS_ACTIVE) {
				foreach(self::$elasticUpdateFields as $field) {
					$attr = $field . '_id';
					if($this->attributes[$attr]) {
						$this->updateElasticCounter($field, $this->attributes[$attr], $totalType);
					}
				}
					
				$this->insertEsProduct();
			}
		} else {
			$before = ($this->oldAttr['status'] == self::STATUS_ACTIVE && !$this->oldAttr['is_expired']);
			$after = ($this->status == self::STATUS_ACTIVE && !$this->is_expired);
			
			if($after && !$before) {
				foreach(self::$elasticUpdateFields as $field) {
					$attr = $field . '_id';
					if($this->attributes[$attr]) {
						$this->updateElasticCounter($field, $this->attributes[$attr], $totalType);
					}
				}
					
				$this->insertEsProduct();
			} else if(!$after && $before) {
				foreach(self::$elasticUpdateFields as $field) {
					$attr = $field . '_id';
					if($this->attributes[$attr]) {
						$this->updateElasticCounter($field, $this->attributes[$attr], $totalType, false);
					}
				}
				
				$this->removeEsProduct();
			} else if($after && $before) {
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
				
				$this->updateEsProduct();
			}
		}
		
		$this->oldAttr = $this->attributes;
		
		parent::afterSave($insert, $changedAttributes);
		*/
	}
	
	public function updateEsProduct() {
		$change = array_diff($this->attributes, $this->oldAttr);
		
		$query = Elastic::buildQueryProduct();
		$query->where(['`ad_product`.`id`' => $this->id]);
		
		$product = $query->one();
		
		$bulk = Elastic::buildProductDocument($product);
		
		$newAttrs = $bulk[1];
		
		$indexName = \Yii::$app->params['indexName']['product'];
		
		$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . "/$indexName/" . Elastic::$productEsType . "/" . $this->id . "?pretty");
		
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$result = json_decode(curl_exec($ch), true);

		if($result['found']) {
			$newLocation = $newAttrs['location'];
			unset($newAttrs['location']);
			$oldLocation = $result['_source']['location'];
			unset($result['_source']['location']);
			
			$changedAttrs = array_diff($newAttrs, $result['_source']);
			
			if(array_diff($newLocation, $oldLocation)) {
				$changedAttrs['location'] = $newLocation;
			}

			if(isset($changedAttrs['boost_time'])) {
				if($changedAttrs['boost_time'] == 0) {
					$changedAttrs['boost_sort'] = 0;
				} else {
					$changedAttrs['boost_sort'] = time();
					/*
					$params = [
						"query" => [
							"filtered" => [
								"filter" => [
									"bool" => [
										"must" => [
											[
												"range" => [
														"boost_start_time" => ["gt" => 0]
												]
											],
											[
												"term" => [
													"type" => $this->type
												]
											]
										]
									]
								]
							]
						],
						"size" => self::BOOST_SORT_LIMIT,
						"sort" => ["boost_sort"]
					];
					*/
					$params = [
						"query" => [
							"filtered" => [
								"filter" => [
									"range" => [
										"boost_sort" => ["gt" => 0]
									]
								]
							]
						],
						"size" => self::BOOST_SORT_LIMIT,
						"sort" => ["boost_sort"]
					];
					
					$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . '/' . \Yii::$app->params['indexName']['product'] . '/_search');
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
					curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						
					$boostResult = json_decode(curl_exec($ch), true);
					
					$hits = $boostResult['hits']['hits'];
					
					if($boostResult['hits']['total'] == self::BOOST_SORT_LIMIT && !in_array($this->id, ArrayHelper::getColumn($hits, '_id'))) {
						$indexName = \Yii::$app->params['indexName']['product'];
						$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . "/$indexName/" . Elastic::$productEsType . "/" . $hits[0]['_id'] . "/_update");
						$update = [
							"doc" => ["boost_sort" => 0]
						];
						curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($update));
						curl_exec($ch);
						curl_close($ch);
					}
				}
			}
			
			$update = [
				"doc" => $changedAttrs
			];
			
			$indexName = \Yii::$app->params['indexName']['product'];
			$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . "/$indexName/" . Elastic::$productEsType . "/" . $this->id . "/_update");
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($update));
			curl_exec($ch);
			curl_close($ch);
		} else {
			$this->insertEsProduct();
		}
	}
	/*
	public function removeEsProduct() {
		$indexName = \Yii::$app->params['indexName']['product'];
		
		$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . "/$indexName/" . Elastic::$productEsType . "/" . $this->id . "?pretty");
		
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_exec($ch);
		curl_close($ch);
	}
	*/
	/*
	public function insertEsProduct() {
		$query = Elastic::buildQueryProduct();
		$query->where(['`ad_product`.`id`' => $this->id]);
		
		$product = $query->one();
			
		$indexName = \Yii::$app->params['indexName']['product'];
		$bulk = Elastic::buildProductDocument($product);
		
		Elastic::insertProducts($indexName, Elastic::$productEsType, $bulk);
	}
	*/
	public static function updateElasticCounter($type, $id, $totalType, $increase = true) {
		$sign = $increase ? '+' : '-';
		$script = '{"script" : "ctx._source.' . $totalType . $sign . '=1"}';
		$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . "/" . \Yii::$app->params['indexName']['countTotal'] . "/$type/$id/_update?retry_on_conflict=" . Elastic::RETRY_ON_CONFLICT);
			
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
/*
 * New -----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
 */	
	public function updateExpired() {
		if($this->canUpdateExpired()) {
			$this->is_expired = 0;
			$this->start_date = time();
			$this->end_date = $this->start_date + self::EXPIRED;
			$this->save();
			
			$this->insertEs();
			
			return true;
		}
		
		return false;
	}
	
	public function canUpdateExpired() {
		return $this->is_expired == 1 && $this->status == AdProduct::STATUS_ACTIVE;
	}
	
	public function insertEs() {
		/*
		 * Insert To product Es
		 */
		$query = Elastic::buildQueryProduct();
		$query->where(['`ad_product`.`id`' => $this->id]);
		$product = $query->one();
		$bulk = Elastic::buildProductDocument($product);
		
		$document = $bulk[1];
		$indexName = \Yii::$app->params['indexName']['product'];
		$type = Elastic::$productEsType;
		
		$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . "/$indexName/$type/" . $this->id);
		
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($document));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_exec($ch);
		curl_close($ch);
		
		/*
		 * Increase Counter
		 */
		$totalType = ($this->type == self::TYPE_FOR_SELL) ? self::TYPE_FOR_SELL_TOTAL : self::TYPE_FOR_RENT_TOTAL;
		
		self::updateElasticCounters($this->attributes, $totalType);
	}
	
	public static function updateElasticCounters($attributes, $totalType, $increase = true) {
		foreach(self::$elasticUpdateFields as $field) {
			$attr = $field . '_id';
			
			if(!empty($attributes[$attr])) {
				self::updateElasticCounter($field, $attributes[$attr], $totalType, $increase);
			}
		}
	}
	
	public function removeEs() {
		$indexName = \Yii::$app->params['indexName']['product'];
		
		$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . "/$indexName/" . Elastic::$productEsType . "/" . $this->id);
		
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_exec($ch);
		curl_close($ch);
		
		/*
		 * Decrease Counter
		 */
		$totalType = ($this->type == self::TYPE_FOR_SELL) ? self::TYPE_FOR_SELL_TOTAL : self::TYPE_FOR_RENT_TOTAL;
		
		self::updateElasticCounters($this->attributes, $totalType, false);
	}
	
	public function boost($boostTime) {
		if($this->canBoost($boostTime)) {
			$this->boost_start_time = time();
			$currentBoostEnd = $this->boost_time ? $this->boost_time : $this->boost_start_time;
			$this->boost_time = $currentBoostEnd + $boostTime;
			
			if($this->boost_time > $this->end_date) {
				$this->end_date = $this->boost_time;
			}
			
			$this->save();
			
			$changes = [
				'boost_start_time' => $this->boost_start_time,
				'boost_time' => $this->boost_time,
				'boost_sort' => $this->boost_start_time
			];
			
			$this->updateEs($changes);
			$this->reSortBoost($this->type);
			
			return true;
		}
		
		return false;
	}
	
	public function canBoost($boostTime) {
		$now = time();
		$currentBoostEnd = $this->boost_time ? $this->boost_time : $now;
		
		return $this->status == self::STATUS_ACTIVE && $currentBoostEnd + $boostTime <= $now + AdProduct::BOOST_LIMIT;
	}
	
	public function updateEs($changes) {
		self::_updateEs($this->id, $changes);
	}
	
	public static function _updateEs($id, $changes) {
		$indexName = \Yii::$app->params['indexName']['product'];
		$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . "/$indexName/" . Elastic::$productEsType . "/" . $id . "/_update");
		$update = ["doc" => $changes];
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($update));
		curl_exec($ch);
		curl_close($ch);
	}
	
	public static function reSortBoost($type) {
		$params = [
			"query" => [
				"filtered" => [
					"filter" => [
						"bool" => [
							"must" => [
								["range" => ["boost_sort" => ["gt" => 0]]],
								["term" => ["type" => $type]]
							]
						]
					]
				]
			]
		];
			
		$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . '/' . \Yii::$app->params['indexName']['product'] . '/_search');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		$boostResult = json_decode(curl_exec($ch), true);
		$boostSortproducts = ArrayHelper::getColumn($boostResult['hits']['hits'], "_id");

		$boostProductInDb = (new Query())->select('`id`, `boost_start_time`')->from('ad_product')->where(['is_expired' => 0, 'status' => self::STATUS_ACTIVE, 'type' => $type])
										->andWhere(['>', 'boost_start_time', 0])->orderBy('`boost_start_time` DESC')->limit(self::BOOST_SORT_LIMIT)->indexBy('id')->all();

		$boostProductInDbId = ArrayHelper::getColumn($boostProductInDb, 'id');
		
		$addBoost = array_diff($boostProductInDbId, $boostSortproducts);
		$removeBoost = array_diff($boostSortproducts, $boostProductInDbId);
		
		if($addBoost) {
			foreach ($addBoost as $id) {
				self::_updateEs($id, ['boost_sort' => $boostProductInDb[$id]['boost_start_time']]);
			}
		}
		
		if($removeBoost) {
			foreach ($removeBoost as $id) {
				self::_updateEs($id, ['boost_sort' => 0]);
			}
		}
	}
	
	public function updateStatus($status) {
		$currentStatus = $this->status;
		
		$this->status = $status;
		$this->save();
		
		if($currentStatus == self::STATUS_ACTIVE && $status != self::STATUS_ACTIVE) {
			$this->removeEs();
		} else if($currentStatus != self::STATUS_ACTIVE && $status == self::STATUS_ACTIVE) {
			$this->insertEs();
		}
		
		if($currentStatus != self::STATUS_PENDING && $this->boost_start_time > 0) {
			self::reSortBoost($this->type);
		}
	}
	
	public function updateWithEs() {
		/*
		 * Update Elastic
		 */
		$indexName = \Yii::$app->params['indexName']['product'];
		$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . "/$indexName/" . Elastic::$productEsType . "/" . $this->id);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = json_decode(curl_exec($ch), true);
		
		if($result['found']) {
			$query = Elastic::buildQueryProduct();
			$query->where(['`ad_product`.`id`' => $this->id]);
			$product = $query->one();
			$bulk = Elastic::buildProductDocument($product);
			$newAttrs = $bulk[1];
			$ollAttrs = $result['_source'];
			
			$newLocation = $newAttrs['location'];
			unset($newAttrs['location']);
			$oldLocation = $ollAttrs['location'];
			unset($ollAttrs['location']);
				
			$changes = array_diff_assoc($newAttrs, $ollAttrs);
			
			if(isset($changes['boost_sort'])) {
				unset($changes['boost_sort']);
			}
				
			if(array_diff($newLocation, $oldLocation)) {
				$changes['location'] = $newLocation;
			}
			
			$this->updateEs($changes);
			
			if(isset($changes['type']) && $ollAttrs['boost_sort'] != 0) {
				self::reSortBoost(self::TYPE_FOR_RENT);
				self::reSortBoost(self::TYPE_FOR_SELL);
			}
			
			/*
			 * Update Counter
			 */
			
			if($this->status == self::STATUS_ACTIVE && $this->is_expired == 0) {
				if(isset($changes['type'])) {
					$oldTotalType = ($ollAttrs['type'] == self::TYPE_FOR_SELL) ? self::TYPE_FOR_SELL_TOTAL : self::TYPE_FOR_RENT_TOTAL;
					$newTotalType = ($newAttrs['type'] == self::TYPE_FOR_SELL) ? self::TYPE_FOR_SELL_TOTAL : self::TYPE_FOR_RENT_TOTAL;
				
					self::updateElasticCounters($ollAttrs, $oldTotalType, false);
					self::updateElasticCounters($newAttrs, $newTotalType);
				} else {
					$totalType = ($this->type == self::TYPE_FOR_SELL) ? self::TYPE_FOR_SELL_TOTAL : self::TYPE_FOR_RENT_TOTAL;
				
					self::updateElasticCounters(array_diff_assoc($ollAttrs, $newAttrs), $totalType, false);
					self::updateElasticCounters($changes, $totalType);
				}
			}
		}
	}
	
	public function getEs() {
		$indexName = \Yii::$app->params['indexName']['product'];
		$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . "/$indexName/" . Elastic::$productEsType . "/" . $this->id);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = json_decode(curl_exec($ch), true);
		
		if($result['found']) {
			return $result['_source'];
		} else {
			return null;
		}
	}
}
