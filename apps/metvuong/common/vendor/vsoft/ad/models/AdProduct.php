<?php

namespace common\vendor\vsoft\ad\models;

use Yii;
use common\vendor\vsoft\ad\models\base\AdProductBase;


class AdProduct extends AdProductBase
{
	const TYPE_FOR_SELL = 1;
	const TYPE_FOR_RENT = 2;
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'category_id' => 'Category ID',
			'project_building_id' => 'Project Building ID',
			'user_id' => 'User ID',
			'city_id' => 'City ID',
			'district_id' => 'District ID',
			'ward_id' => 'Ward ID',
			'street_id' => 'Street ID',
			'type' => 'Hình thức',
			'title' => 'Title',
			'content' => 'Content',
			'area' => 'Area',
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
}
