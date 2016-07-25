<?php

namespace vsoft\ad\models;

use Yii;
use common\models\AdCategoryGroup as ACG;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ad_category_group".
 *
 * @property integer $id
 * @property string $name
 * @property string $categories_id
 * @property integer $order
 * @property integer $apply_to_type
 * @property integer $status
 */
class AdCategoryGroup extends ACG
{
	public static $mapTrans = [
		'apartments' => 'Căn hộ chung cư',
		'houses' => 'Nhà riêng',
		'lands' => 'Đất',
		'condos/detached houses' => 'Nhà biệt thự, liền kề',
		'other types' => 'Loại BĐS khác'	
	];
	
    public function rules()
    {
    	return [
        		['categories_id', 'safe'],
    			[['name'], 'required'],
    			[['order', 'apply_to_type', 'status'], 'integer'],
    			[['name'], 'string', 'max' => 32]
    	];
    }

	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'name' => 'Loại tin',
			'categories_id' => 'Các loại',
			'apply_to_type' => 'Apply To Type',
			'order' => 'Order',
			'status' => 'Status',
		];
	}
	
	public function search($params)
	{
		$query = self::find();
	
		$query->orderBy(['order' => SORT_ASC]);
		
		$dataProvider = new ActiveDataProvider([
				'query' => $query,
		]);
	
		if ($this->load($params) && !$this->validate()) {
			return $dataProvider;
		}
	
		$query->andFilterWhere([
				'id' => $this->id
		]);
	
		$query->andFilterWhere(['like', 'name', $this->name]);
	
		return $dataProvider;
	}
	
	public function afterFind() {
		if(!empty($this->categories_id)) {
			$this->categories_id = explode(',', $this->categories_id);
		}
		 
		return parent::afterFind();
	}
	
	public function beforeSave($insert) {
	
		if($this->categories_id) {
			$this->categories_id = implode(',', $this->categories_id);
		}
	
		return parent::beforeSave($insert);
	}
	
	public static function slugMap() {
		if(\Yii::$app->language == 'vi-VN') {
			return [
				'can-ho-chung-cu' => '6',
				'nha-rieng' => '7,9',
				'dat' => '10,11',
				'nha-biet-thu-lien-ke' => '8',
				'loai-bds-khac' => '12,13,14,15,16,17,18'
			];
		} else {
			return [
				'apartments' => '6',
				'houses' => '7,9',
				'lands' => '10,11',
				'condos-detached-houses' => '8',
				'other-types' => '12,13,14,15,16,17,18'
			];
		}
	}
	
	public function getNameTranslate() {
		return self::$mapTrans[$this->name];
	}
}
