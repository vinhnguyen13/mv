<?php

namespace common\vendor\vsoft\ad\models\base;

use Yii;

/**
 * This is the model class for table "ad_building_project".
 *
 * @property integer $id
 * @property string $name
 * @property string $location
 * @property string $investment_type
 * @property string $land_area
 * @property string $commercial_leasing_area
 * @property string $apartment_no
 * @property string $floor_no
 * @property string $facilities
 * @property string $hotline
 * @property string $website
 * @property double $lng
 * @property double $lat
 * @property string $gallery
 * @property string $video
 * @property string $progress
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 *
 * @property AdAreaType[] $adAreaTypes
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
            [['name', 'created_at'], 'required'],
            [['lng', 'lat'], 'number'],
            [['gallery', 'video', 'progress'], 'string'],
            [['created_at', 'updated_at', 'status'], 'integer'],
            [['name', 'land_area', 'commercial_leasing_area', 'apartment_no', 'floor_no', 'hotline'], 'string', 'max' => 32],
            [['location', 'investment_type', 'facilities', 'website'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'location' => 'Location',
            'investment_type' => 'Investment Type',
            'land_area' => 'Land Area',
            'commercial_leasing_area' => 'Commercial Leasing Area',
            'apartment_no' => 'Apartment No',
            'floor_no' => 'Floor No',
            'facilities' => 'Facilities',
            'hotline' => 'Hotline',
            'website' => 'Website',
            'lng' => 'Lng',
            'lat' => 'Lat',
            'gallery' => 'Gallery',
            'video' => 'Video',
            'progress' => 'Progress',
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
