<?php

namespace vsoft\ad\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use common\models\AdBuildingProject as ABP;
use yii\helpers\Url;
use yii\db\Query;


class AdBuildingProject extends ABP
{
	private static $areaTypes = [];
	
	const STATUS_ENABLED = 1;
	const STATUS_DISABLED = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
	        [['city_id', 'district_id', 'created_at', 'updated_at', 'status', 'is_crawl', 'hot_project', 'click'], 'integer'],
	        [['name'], 'required'],
	        [['location_detail', 'facilities_detail', 'seo_title', 'seo_keywords', 'seo_description', 'gallery', 'video', 'progress',
                'name', 'description', 'file_name', 'data_html'], 'string'],
            [['facade_width'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/', 'max' => 10000],
            [['lift'], 'integer', 'max' => 100],
	        [['lng', 'lat'], 'number'],
	        [['land_area', 'apartment_no', 'floor_no', 'start_time', 'estimate_finished', 'hotline'], 'string', 'max' => 32],
	        [['location', 'investment_type', 'commercial_leasing_area', 'owner_type', 'website', 'logo', 'slug'], 'string', 'max' => 255]
        ];
    }
    
    public static function getAreaTypes() {
    	if(!self::$areaTypes) {
    		self::$areaTypes = [
	    		'bpfApartmentArea' => Yii::t('ad', 'Khu căn hộ'),
	    		'bpfCommercialArea' => Yii::t('ad', 'Khu thương mại'),
	    		'bpfTownhouseArea' => Yii::t('ad', 'Khu nhà phố'),
	    		'bpfOffice' => Yii::t('ad', 'Khu Office - Officetel')
    		];
    	}
    	return self::$areaTypes;
    }
    
    public function attributeLabels()
    {
    	return [
    	'id' => 'ID',
    	'city_id' => 'Tỉnh / Thành Phố',
    	'district_id' => 'Quận / Huyện',
    	'name' => 'Tên dự án',
    	'logo' => 'Logo / Ảnh đại diện',
    	'location' => 'Vị trí',
    	'description' => 'Thông tin mô tả',
    	'investment_type' => 'Loại hình đầu tư',
    	'land_area' => 'Diện tích khu đất',
    	'commercial_leasing_area' => 'Diện tích trung tâm văn phòng dịch vụ',
    	'apartment_no' => 'Số lượng sản phẩm',
    	'floor_no' => 'Số tầng',
    	'location_detail' => 'Bản đồ vị trí',
    	'facilities_detail' => 'Tiện ích',
    	'seo_title' => 'Tiêu đề sử dụng cho SEO',
    	'seo_keywords' => 'Keywords sử dụng cho SEO',
    	'seo_description' => 'Description sử dụng cho SEO',
    	'start_time' => 'Thời gian xây dựng',
    	'estimate_finished' => 'Dự kiến hoàn thành',
    	'owner_type' => 'Hình thức sở hữu',
    	'facilities' => 'Tiện ích',
    	'hotline' => 'Hotline',
    	'website' => 'Website',
    	'lng' => 'Lng',
    	'lat' => 'Lat',
    	'gallery' => 'Thư viện ảnh',
    	'video' => 'Phim 3D dự án',
    	'progress' => 'Tiến độ xây dựng',
    	'slug' => 'Slug',
    	'created_at' => 'Created At',
    	'updated_at' => 'Updated At',
    	'status' => 'Status',
    	'investors' => 'Chủ đầu tư',
    	'categories' => 'Phân loại',
    	'facade_width' => 'Mặt tiền(m)',
    	'lift' => 'Thang máy(cái)',
    	'start_date' => 'Ngày khởi công',
        'hot_project' => 'Dự án nổi bật',
        'click' => 'Lượt xem'
    	];
    }
    
    public function behaviors()
    {
    	return [
	    	'slug' => [
		    	'class' => 'common\components\Slug',
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
	    			ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
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
	
	public function getInvestors() {
		return $this->hasMany(AdInvestor::className(), ['id' => 'investor_id'])->viaTable('ad_investor_building_project', ['building_project_id' => 'id']);
	}
	
	public function getCategories() {
		return $this->hasMany(AdCategory::className(), ['id' => 'category_id'])->viaTable('ad_building_project_category', ['building_project_id' => 'id']);
	}

    public function getArchitects() {
		return $this->hasMany(AdArchitect::className(), ['id' => 'architect_id'])->viaTable('ad_architect_building_project', ['building_project_id' => 'id']);
	}

	public function getContractors() {
		return $this->hasMany(AdContractor::className(), ['id' => 'contractor_id'])->viaTable('ad_contractor_building_project', ['building_project_id' => 'id']);
	}
	
	public function saveMultiple($data, $relationModels, $field) {
		$postIds = $data[$field] ? $data[$field] : [];
		$ids = ArrayHelper::getColumn($this->$field, 'id');
		$link = array_diff($postIds, $ids);
		$unlink = array_diff($ids, $postIds);
		
		foreach ($relationModels as $relationModel) {
			if(in_array($relationModel->id, $link)) {
				$this->link($field, $relationModel);
			} else if(in_array($relationModel->id, $unlink)) {
				$this->unlink($field, $relationModel, true);
			}
		}
	}

    public static function mb_ucfirst($string, $encoding='UTF-8')
    {
        $strlen = mb_strlen($string, $encoding);
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $then = mb_substr($string, 1, $strlen - 1, $encoding);
        return mb_strtoupper($firstChar, $encoding) . $then;
    }
    
    public static function getListByDistrict($districtId) {
    	$items = [];
    
    	if($districtId) {
    		$projects = self::find()->where('`district_id` = :district_id', [':district_id' => $districtId])->all();
    
    		usort($projects, function($a, $b) {
    			return strnatcmp($a['name'], $b['name']);
    		});
    		
    		foreach($projects as $project) {
    			$items[] = [
					'id' => $project['id'],
					'name' => $project['name']
    			];
    		}
    	}
    
    	return $items;
    }

    public static function getProjectBySlug($slug){
        if($slug) {
            $project = self::find()->where('`slug` = :slug', [':slug' => $slug])->one();
            return $project;
        }
        return null;
    }

    public function getLogoUrl(){
        $image = '/themes/metvuong2/resources/images/default-ads.jpg';
        $logo = $this->logo;
        if($logo) {
            $checkLogo = Yii::getAlias('@store'). "/building-project-images/" . $logo;
            if(file_exists($checkLogo) === TRUE){
                $image = "/store/building-project-images/" . $logo;
            } else
                $image = $logo;
        }
        else {
            if ($this->gallery) {
                $gallery = explode(',', $this->gallery);
                if (count($gallery) > 0) {
                    $imageUrl = Yii::getAlias('@store') . "/building-project-images/" . $gallery[0];
                    if (file_exists($imageUrl)) {
                        $image = Url::to('/store/building-project-images/' . $gallery[0]);
                    }
                }
            }
        }
        return $image;
    }

//    public static function getHotProject(){
//        $projects = self::find()->where('hot_project = :hot', [':hot' => 1])
//            ->andWhere('status = :s', [':s' => 1])
//            ->orderBy(['id' => SORT_DESC])->limit(4)
//            ->all();
//        return $projects;
//    }

    public static function getTopProject($limit = 10){
        $projects = self::find()->where('status = :s', [':s' => 1])
            ->orderBy(['click' => SORT_DESC])->limit($limit)
            ->all();
        return $projects;
    }
    
    public function getAdFacilities() {
    	$facilities = array_filter(explode(',', $this->facilities));
    	
    	if($facilities) {
    		$query = new Query();
    		$query->from('ad_facility')->where(['id' => $facilities])->all();
    		return $query->all();
    	}
    	
    	return [];
    }
}
