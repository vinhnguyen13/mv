<?php

namespace vsoft\ad\models\base;

use Yii;

/**
 * This is the model class for table "ad_building_project".
 *
 * @property integer $id
 * @property integer $city_id
 * @property integer $district_id
 * @property string $name
 * @property string $logo
 * @property string $location
 * @property string $investment_type
 * @property string $land_area
 * @property string $commercial_leasing_area
 * @property string $apartment_no
 * @property string $floor_no
 * @property string $location_detail
 * @property string $facilities_detail
 * @property string $seo_title
 * @property string $seo_keywords
 * @property string $seo_description
 * @property string $start_time
 * @property string $estimate_finished
 * @property string $owner_type
 * @property string $facilities
 * @property string $hotline
 * @property string $website
 * @property double $lng
 * @property double $lat
 * @property string $gallery
 * @property string $video
 * @property string $progress
 * @property string $slug
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 *
 * @property AdAreaType[] $adAreaTypes
 * @property AdCity $city
 * @property AdDistrict $district
 * @property AdBuildingProjectCategory[] $adBuildingProjectCategories
 * @property AdInvestorBuildingProject[] $adInvestorBuildingProjects
 * @property AdProduct[] $adProducts
 */
class AdBuildingProjectBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_building_project';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city_id', 'district_id', 'created_at', 'updated_at', 'status'], 'integer'],
            [['name', 'slug', 'created_at'], 'required'],
            [['location_detail', 'facilities_detail', 'seo_title', 'seo_keywords', 'seo_description', 'gallery', 'video', 'progress', 'description', 'name', 'logo', 'slug'], 'string'],
            [['lng', 'lat'], 'number'],
            [['land_area', 'apartment_no', 'floor_no', 'start_time', 'estimate_finished', 'hotline'], 'string', 'max' => 32],
            [['location', 'investment_type', 'commercial_leasing_area', 'owner_type', 'facilities', 'website'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'city_id' => 'City ID',
            'district_id' => 'District ID',
            'name' => 'Name',
            'logo' => 'Logo',
            'location' => 'Location',
            'investment_type' => 'Investment Type',
            'land_area' => 'Land Area',
            'commercial_leasing_area' => 'Commercial Leasing Area',
            'apartment_no' => 'Apartment No',
            'floor_no' => 'Floor No',
            'location_detail' => 'Location Detail',
            'facilities_detail' => 'Facilities Detail',
            'seo_title' => 'Seo Title',
            'seo_keywords' => 'Seo Keywords',
            'seo_description' => 'Seo Description',
            'start_time' => 'Start Time',
            'estimate_finished' => 'Estimate Finished',
            'owner_type' => 'Owner Type',
            'facilities' => 'Facilities',
            'hotline' => 'Hotline',
            'website' => 'Website',
            'lng' => 'Lng',
            'lat' => 'Lat',
            'gallery' => 'Gallery',
            'video' => 'Video',
            'progress' => 'Progress',
            'slug' => 'Slug',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdAreaTypes()
    {
        return $this->hasMany(AdAreaType::className(), ['building_project_id' => 'id']);
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
    public function getAdBuildingProjectCategories()
    {
        return $this->hasMany(AdBuildingProjectCategory::className(), ['building_project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdInvestorBuildingProjects()
    {
        return $this->hasMany(AdInvestorBuildingProject::className(), ['building_project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdProducts()
    {
        return $this->hasMany(AdProduct::className(), ['project_building_id' => 'id']);
    }
}
