<?php

namespace common\vendor\vsoft\ad\models\base;

use Yii;
use common\vendor\vsoft\ad\models\AdContactInfo;
use common\vendor\vsoft\ad\models\AdImages;
use common\vendor\vsoft\ad\models\AdCategory;
use common\vendor\vsoft\ad\models\AdCity;
use common\vendor\vsoft\ad\models\AdDistrict;
use common\vendor\vsoft\ad\models\AdBuildingProject;
use common\vendor\vsoft\ad\models\AdStreet;
use common\vendor\vsoft\ad\models\AdProductAdditionInfo;
use common\vendor\vsoft\ad\models\AdWard;
use vsoft\ad\models\base\AdProductAdditionInfoBase;

/**
 * This is the model class for table "ad_product".
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
 * @property integer $price
 * @property double $price_input
 * @property integer $price_type
 * @property double $lng
 * @property double $lat
 * @property integer $start_date
 * @property integer $end_date
 * @property integer $score
 * @property integer $view
 * @property integer $verified
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 *
 * @property AdContactInfo $adContactInfo
 * @property AdImages[] $adImages
 * @property AdCategory $category
 * @property AdCity $city
 * @property AdDistrict $district
 * @property AdBuildingProject $projectBuilding
 * @property AdStreet $street
 * @property AdWard $ward
 * @property AdProductAdditionInfo $adProductAdditionInfo
 */
class AdProductBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'home_no', 'city_id', 'district_id', 'type', 'content', 'start_date', 'end_date', 'created_at'], 'required'],
            [['category_id', 'project_building_id', 'user_id', 'city_id', 'district_id', 'ward_id', 'street_id', 'type', 'price', 'price_type', 'start_date', 'end_date', 'score', 'view', 'verified', 'created_at', 'updated_at', 'status'], 'integer'],
            [['area', 'price_input', 'lng', 'lat'], 'number'],
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
            'price_input' => 'Price Input',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdContactInfo()
    {
        return $this->hasOne(AdContactInfo::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdImages()
    {
        return $this->hasMany(AdImages::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(AdCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(AdCity::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrict()
    {
        return $this->hasOne(AdDistrict::className(), ['id' => 'district_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectBuilding()
    {
        return $this->hasOne(AdBuildingProject::className(), ['id' => 'project_building_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStreet()
    {
        return $this->hasOne(AdStreet::className(), ['id' => 'street_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWard()
    {
        return $this->hasOne(AdWard::className(), ['id' => 'ward_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdProductAdditionInfo()
    {
        return $this->hasOne(AdProductAdditionInfo::className(), ['product_id' => 'id']);
    }
}
