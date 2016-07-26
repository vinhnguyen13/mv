<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tracking_search".
 *
 * @property string $id
 * @property integer $user_id
 * @property string $session
 * @property string $ip
 * @property integer $type
 * @property string $location
 * @property integer $city_id
 * @property integer $district_id
 * @property integer $ward_id
 * @property integer $street_id
 * @property integer $project_building_id
 * @property string $category_id
 * @property integer $room_no
 * @property integer $toilet_no
 * @property string $price_min
 * @property string $price_max
 * @property integer $size_min
 * @property integer $size_max
 * @property string $order_by
 * @property integer $created_at
 */
class TrackingSearch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tracking_search';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'type', 'city_id', 'district_id', 'ward_id', 'street_id', 'project_building_id', 'room_no', 'toilet_no', 'price_min', 'price_max', 'size_min', 'size_max', 'created_at'], 'integer'],
            [['session', 'category_id', 'order_by'], 'string', 'max' => 32],
            [['ip', 'location'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
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
            'room_no' => 'Room No',
            'toilet_no' => 'Toilet No',
            'price_min' => 'Price Min',
            'price_max' => 'Price Max',
            'size_min' => 'Size Min',
            'size_max' => 'Size Max',
            'order_by' => 'Order By',
            'created_at' => 'Created At',
        ];
    }
}
