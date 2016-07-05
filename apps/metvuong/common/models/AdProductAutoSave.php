<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ad_product_auto_save".
 *
 * @property integer $id
 * @property integer $category_id
 * @property integer $project_building_id
 * @property string $home_no
 * @property integer $user_id
 * @property integer $city_id
 * @property integer $district_id
 * @property integer $ward_id
 * @property integer $street_id
 * @property integer $type
 * @property string $content
 * @property double $area
 * @property string $price
 * @property double $lng
 * @property double $lat
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $owner
 * @property integer $show_home_no
 * @property double $facade_width
 * @property double $land_width
 * @property integer $home_direction
 * @property integer $facade_direction
 * @property integer $floor_no
 * @property integer $room_no
 * @property integer $toilet_no
 * @property string $interior
 * @property string $facility
 * @property integer $stay_time
 * @property string $ip
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string $mobile
 * @property string $email
 */
class AdProductAutoSave extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_product_auto_save';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'project_building_id', 'user_id', 'city_id', 'district_id', 'ward_id', 'street_id', 'type', 'price', 'created_at', 'updated_at', 'owner', 'show_home_no', 'home_direction', 'facade_direction', 'floor_no', 'room_no', 'toilet_no', 'stay_time'], 'integer'],
            [['area', 'lng', 'lat', 'facade_width', 'land_width'], 'number'],
            [['home_no', 'name', 'phone', 'mobile', 'email'], 'string', 'max' => 32],
            [['content', 'interior'], 'string', 'max' => 3200],
            [['facility', 'ip', 'address'], 'string', 'max' => 255]
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
            'home_no' => 'Home No',
            'user_id' => 'User ID',
            'city_id' => 'City ID',
            'district_id' => 'District ID',
            'ward_id' => 'Ward ID',
            'street_id' => 'Street ID',
            'type' => 'Type',
            'content' => 'Content',
            'area' => 'Area',
            'price' => 'Price',
            'lng' => 'Lng',
            'lat' => 'Lat',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'owner' => 'Owner',
            'show_home_no' => 'Show Home No',
            'facade_width' => 'Facade Width',
            'land_width' => 'Land Width',
            'home_direction' => 'Home Direction',
            'facade_direction' => 'Facade Direction',
            'floor_no' => 'Floor No',
            'room_no' => 'Room No',
            'toilet_no' => 'Toilet No',
            'interior' => 'Interior',
            'facility' => 'Facility',
            'stay_time' => 'Stay Time',
            'ip' => 'Ip',
            'name' => 'Name',
            'address' => 'Address',
            'phone' => 'Phone',
            'mobile' => 'Mobile',
            'email' => 'Email',
        ];
    }
}
