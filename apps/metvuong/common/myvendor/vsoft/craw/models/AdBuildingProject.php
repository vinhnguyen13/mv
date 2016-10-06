<?php

namespace vsoft\craw\models;

use Yii;
use frontend\models\ElasticCraw;

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
 */
class AdBuildingProject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_building_project';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('dbCraw');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city_id', 'district_id', 'created_at', 'updated_at', 'status', 'is_crawl', 'hot_project', 'click', 'project_main_id'], 'integer'],
            [['name', 'slug', 'created_at'], 'required'],
            [['location_detail', 'facilities_detail', 'seo_title', 'seo_keywords', 'seo_description', 'gallery', 'video', 'progress',
                'name', 'description', 'file_name', 'data_html'], 'string'],
            [['lng', 'lat'], 'number'],
            [['land_area', 'apartment_no', 'floor_no', 'start_time', 'estimate_finished', 'hotline'], 'string', 'max' => 32],
            [['location', 'investment_type', 'commercial_leasing_area', 'owner_type', 'facilities', 'website', 'logo', 'slug'], 'string', 'max' => 255]
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
            'project_main_id' => 'Main ID',
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
    
    public function afterSave($insert, $changedAttributes) {
    	$indexName = \Yii::$app->params['indexName']['craw'];
    	$type = 'project_building';
    
    	if($insert) {
    		$cityAcronym = ElasticCraw::acronym($this->city->name);
    		$districtAcronym = ctype_digit($this->district->name) ? ElasticCraw::acronym($this->district->pre) . $this->district->name . ' ' . $this->district->name : ElasticCraw::acronym($this->district->name);
    			
    		$fullName = $this->name . ', ' . $this->district->pre . ' ' . $this->district->name . ', ' . $this->city->name;
    		$nameWithPrefix = 'Dự án ' . $this->name;
    		$acronym = ElasticCraw::acronym($this->name);
    		$acronymFullName1 = $acronym . ' ' . $cityAcronym;
    		$acronymFullName = $acronym . ' ' . $districtAcronym . ' ' . $cityAcronym;
    		$nameFulltext = ElasticCraw::standardSearch($nameWithPrefix);
    		$fullName1 = $nameFulltext . ' ' . $this->city->name;
    		$fullNameSearch = $nameFulltext . ' ' . ElasticCraw::standardSearchDistrict($this->district->pre . ' ' . $this->district->name) . ' ' . $this->city->name;
    			
    		$document = [
    				'full_name' => $fullName,
    				'slug' => '',
    				'total_sell' => 0,
    				'total_rent' => 0,
    				'city_id' => $this->city->id,
    				'district_id' => $this->district->id,
    				's1' => $this->name,
    				's2' => $this->name,
    				's3' => $nameWithPrefix,
    				's4' => $acronym,
    				's5' => $acronymFullName1,
    				's6' => $acronymFullName,
    				's7' => $nameFulltext,
    				's8' => $fullName1,
    				's9' => $fullNameSearch,
    				's10' => $fullNameSearch
    		];
    			
    		$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . "/$indexName/$type/" . $this->id);
    		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($document));
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    		curl_exec($ch);
    		curl_close($ch);
    	} else {
    		$dif = array_diff_assoc($this->attributes, $this->oldAttrs);
    			
    		if(isset($dif['city_id']) || isset($dif['district_id']) || isset($dif['name'])) {
    			$cityAcronym = ElasticCraw::acronym($this->city->name);
    			$districtAcronym = ctype_digit($this->district->name) ? ElasticCraw::acronym($this->district->pre) . $this->district->name . ' ' . $this->district->name : ElasticCraw::acronym($this->district->name);
    
    			$fullName = $this->name . ', ' . $this->district->pre . ' ' . $this->district->name . ', ' . $this->city->name;
    			$nameWithPrefix = 'Dự án ' . $this->name;
    			$acronym = ElasticCraw::acronym($this->name);
    			$acronymFullName1 = $acronym . ' ' . $cityAcronym;
    			$acronymFullName = $acronym . ' ' . $districtAcronym . ' ' . $cityAcronym;
    			$nameFulltext = ElasticCraw::standardSearch($nameWithPrefix);
    			$fullName1 = $nameFulltext . ' ' . $this->city->name;
    			$fullNameSearch = $nameFulltext . ' ' . ElasticCraw::standardSearchDistrict($this->district->pre . ' ' . $this->district->name) . ' ' . $this->city->name;
    				
    			$update = ["doc" => [
    					'full_name' => $fullName,
    					'slug' => $this->slug,
    					'city_id' => $this->city->id,
    					'district_id' => $this->district->id,
    					's1' => $this->name,
    					's2' => $this->name,
    					's3' => $nameWithPrefix,
    					's4' => $acronym,
    					's5' => $acronymFullName1,
    					's6' => $acronymFullName,
    					's7' => $nameFulltext,
    					's8' => $fullName1,
    					's9' => $fullNameSearch,
    					's10' => $fullNameSearch
    			]];
    				
    			$ch = curl_init(\Yii::$app->params['elastic']['config']['hosts'][0] . "/$indexName/$type/" . $this->id . "/_update");
    			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($update));
    			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    			curl_exec($ch);
    			curl_close($ch);
    		}
    	}
    }
}
