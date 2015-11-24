<?php

namespace common\vendor\vsoft\ad\models;

use Yii;
use common\vendor\vsoft\ad\models\base\AdBuildingProjectBase;
use common\vendor\vsoft\ad\models\base\AdAreaTypeBase;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;


class AdBuildingProject extends AdBuildingProjectBase
{
	const STATUS_ENABLED = 1;
	const STATUS_DISABLED = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['location_detail', 'facilities_detail', 'seo_title', 'seo_keywords', 'seo_description', 'gallery', 'video'], 'string'],
            [['lng', 'lat'], 'number'],
            [['created_at', 'updated_at', 'status'], 'integer'],
            [['name', 'logo', 'land_area', 'apartment_no', 'floor_no', 'start_time', 'estimate_finished', 'hotline', 'slug'], 'string', 'max' => 32],
            [['location', 'investment_type', 'commercial_leasing_area', 'owner_type', 'facilities', 'website'], 'string', 'max' => 255]
        ];
    }
    
    public function behaviors()
    {
    	return [
	    	'slug' => [
		    	'class' => 'Zelenin\yii\behaviors\Slug',
		    	'slugAttribute' => 'slug',
		    	'attribute' => 'name',
		    	// optional params
		    	'ensureUnique' => true,
		    	'replacement' => '-',
		    	'lowercase' => true,
		    	'immutable' => false,
		    	// If intl extension is enabled, see http://userguide.icu-project.org/transforms/general.
		    	'transliterateOptions' => 'Russian-Latin/BGN; Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC;'
			],
			[
				'class' => TimestampBehavior::className(),
				'attributes' => [
	    			ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
	    			ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
				],
				'value' => new Expression('UNIX_TIMESTAMP()'),
			],
		];
    }

	public function getAdAreaTypes()
	{
		return $this->hasMany(AdAreaType::className(), ['building_project_id' => 'id']);
	}
	
	public function getAdAreaType($type) {
		$areaTypes = $this->adAreaTypes;
	
		foreach ($areaTypes as $areaType) {
			if($areaType->type == $type) {
				return $areaType;
			}
		}
		
		$areaType = new AdAreaType();
		$areaType->type = $type;
	
		return $areaType;
	}
	
	function loadDefaultValues($skipIfSet = true) {
		$this->lat = '10.783233';
		$this->lng = '106.704479';
	
		return parent::loadDefaultValues($skipIfSet);
	}
	
	public function formName() {
		return 'BuildingProject';
	}
	
	public function beforeSave($insert) {
		if($this->progress) {
			$this->progress = json_encode($this->progress, JSON_UNESCAPED_UNICODE);
		}
		
		return parent::beforeSave($insert);
	}
	
	public function formatMultiline($field) {
		$values = explode(PHP_EOL, $this->$field);
		$string = '';
		 
		if(count($values) > 1) {
			$string .= '<ul class="list">';
			foreach ($values as $value) {
				$string .= '<li>' . $value . '</li>';
			}
			$string .= '</ul>';
		} else {
			$string = $this->$field;
		}
		 
		return $string;
	}
	
	public function getVideos() {
		$bpVideos = array_filter(explode(PHP_EOL, $this->video));
		$string = '<ul class="videos">';
		foreach($bpVideos as $bpVideo) {
			parse_str(parse_url($bpVideo, PHP_URL_QUERY), $videoParams);
			$videoId = $videoParams['v'];
			$string .= '<li><a class="video" href="https://www.youtube.com/embed/' . $videoId . '?autoplay=1"><span class="play"></span><img src="http://img.youtube.com/vi/' . $videoId . '/1.jpg" /></a></li>';
		}
		$string .= '</ul>';
		 
		return $string;
	}
}
