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
use common\models\SlugSearch;
use common\models\common\models;
use common\components\Slug;
use frontend\models\Elastic;


class AdBuildingProject extends ABP
{
	private static $areaTypes = [];
	private $oldAttrs = [];
	
	const STATUS_ENABLED = 1;
	const STATUS_DISABLED = 0;

    public $inv_type = [
        'Khu căn hộ' => 'Khu căn hộ',
        'Cao ốc văn phòng' => 'Cao ốc văn phòng',
        'Khu đô thị mới' => 'Khu đô thị mới',
        'Khu dân cư' => 'Khu dân cư',
        'Khu thương mại dịch vụ' => 'Khu thương mại dịch vụ',
        'Khu công nghiệp' => 'Khu công nghiệp',
        'Khu du lịch nghỉ dưỡng' => 'Khu du lịch nghỉ dưỡng',
        'Khu phức hợp' => 'Khu phức hợp',
        'Dự án khác' => 'Dự án khác'
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        // GFA, Building Density, Construction Start, Complettion, Developer, Architect, Contractor, # of Buildings, # of Units, # of 1bed, SQM of 1bed, #2bed, SQM of 2bed, # of 3bed, SQM of 3bed)
        return [
	        [['city_id', 'district_id', 'created_at', 'updated_at', 'status', 'is_crawl', 'hot_project', 'click', 'ward_id', 'street_id',
                'apartment_no', 'floor_no', 'units_no', 'no_1_bed', 'no_2_bed', 'no_3_bed'], 'integer'],
	        [['name', 'district_id', 'city_id'], 'required'],
	        [['location_detail', 'facilities_detail', 'seo_title', 'seo_keywords', 'seo_description', 'gallery', 'video', 'progress',
                'description', 'file_name', 'data_html'], 'string'],
            [['facade_width'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/', 'max' => 10000],
            [['lift'], 'integer', 'max' => 100],
            [['name', 'bds_name'], 'string', 'max' => 100],
	        [['lng', 'lat', 'land_area', 'gfa', 'building_density'], 'number'],
	        [['start_time', 'estimate_finished', 'hotline', 'home_no'], 'string', 'max' => 32],
	        [['location', 'investment_type', 'commercial_leasing_area', 'owner_type', 'website', 'logo', 'slug', 'sqm_1_bed', 'sqm_2_bed', 'sqm_3_bed'], 'string', 'max' => 255]
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
            'logo' => 'Ảnh đại diện',
            'location' => 'Địa chỉ',
            'description' => 'Thông tin mô tả',
            'investment_type' => 'Loại hình đầu tư',
            'land_area' => Yii::t('project', 'Land Area'),
            'commercial_leasing_area' => 'Diện tích trung tâm văn phòng dịch vụ',
            'apartment_no' => Yii::t('project', '# of Building'),
            'floor_no' => 'Số tầng',
            'location_detail' => 'Bản đồ vị trí',
            'facilities_detail' => 'Tiện ích',
            'seo_title' => 'Tiêu đề sử dụng cho SEO',
            'seo_keywords' => 'Keywords sử dụng cho SEO',
            'seo_description' => 'Description sử dụng cho SEO',
            'start_time' => Yii::t('project', 'Construction Start'),
            'estimate_finished' => Yii::t('project', 'Completion'),
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
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'investors' => 'Chủ đầu tư',
            'architects' => Yii::t('project', 'Architects'),
            'contractors' => Yii::t('project', 'Contractors'),
            'categories' => 'Phân loại',
            'facade_width' => 'Mặt tiền(m)',
            'lift' => Yii::t('project', 'Lift'),
            'start_date' => 'Ngày khởi công',
            'hot_project' => 'Dự án nổi bật',
            'click' => 'Lượt xem',
            'home_no' => 'Số nhà',
            'ward_id' => 'Phường / xã',
            'street_id' => 'Đường',
            'units_no' => Yii::t('project','Units No'),
            'building_density' => Yii::t('project','Building Density'),
            'gfa' => Yii::t('project','GFA'),
            'no_1_bed' => Yii::t('project','# 1 Bed'),
            'no_2_bed' => Yii::t('project','# 2 Bed'),
            'no_3_bed' => Yii::t('project','# 3 Bed'),
            'sqm_1_bed' => Yii::t('project','SQM 1 Bed'),
            'sqm_2_bed' => Yii::t('project','SQM 2 Bed'),
            'sqm_3_bed' => Yii::t('project','SQM 3 Bed'),
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
	
	public function afterFind() {
		$this->oldAttrs = $this->oldAttributes;
	}
	
	public function beforeSave($insert) {
		if (parent::beforeSave($insert)) {
			if($this->progress) {
				$this->progress = json_encode($this->progress, JSON_UNESCAPED_UNICODE);
			}
			
			if($insert) {
				$this->name = Elastic::standardUnicodeCase($this->name);
				
				$this->uniqueSlug();
			} else {
				if(isset($this->oldAttrs['name']) && $this->oldAttrs['name'] != $this->name) {
					$this->name = Elastic::standardUnicodeCase($this->name);
				}
				
				if($this->oldAttrs['slug'] != $this->attributes['slug']) {
					$this->uniqueSlug();
					
					\Yii::$app->db->createCommand("UPDATE `slug_search` SET `slug` = '{$this->slug}' WHERE `table` = 'ad_building_project' AND `value` = {$this->id}")->execute();
				}
			}
		
			return true;
		} else {
			return false;
		}
	}
	
	public function uniqueSlug() {
		if(SlugSearch::find()->where(['slug' => $this->slug])->one()) {
			$slug = new Slug();
			
			$this->slug = $this->slug . '-' . $slug->slugify($this->city->name);
			
			if(SlugSearch::find()->where(['slug' => $this->slug])->one()) {
				$this->slug = $this->slug . '-' . $slug->slugify($this->district->name);
			
				if(SlugSearch::find()->where(['slug' => $this->slug])->one()) {
					$this->slug = $this->slug . uniqid();
				}
			}
		}
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
        $image = '/images/default-ads.jpg';
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

    public static function getTopProject($limit = 10, $current_id = null){
        $projects = self::find()->where('status = :s', [':s' => 1]);
        if($current_id){
            $projects = $projects->andWhere(['!=', 'id', $current_id]);
        }
        $projects = $projects->orderBy(['click' => SORT_DESC])->limit($limit)->all();
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
	
	public function afterSave($insert, $changedAttributes) {
		$indexName = \Yii::$app->params['indexName']['countTotal'];
		$type = 'project_building';
		
		if($insert) {
			$slugSearch = new SlugSearch();
			$slugSearch->slug = $this->slug;
			$slugSearch->table = 'ad_building_project';
			$slugSearch->value = $this->id;
			$slugSearch->save(false);
			
			
			
			$cityAcronym = Elastic::acronym($this->city->name);
			$districtAcronym = ctype_digit($this->district->name) ? Elastic::acronym($this->district->pre) . $this->district->name . ' ' . $this->district->name : Elastic::acronym($this->district->name);
			
			$fullName = $this->name . ', ' . $this->district->pre . ' ' . $this->district->name . ', ' . $this->city->name;
			$nameWithPrefix = 'Dự án ' . $this->name;
			$acronym = Elastic::acronym($this->name);
			$acronymFullName1 = $acronym . ' ' . $cityAcronym;
			$acronymFullName = $acronym . ' ' . $districtAcronym . ' ' . $cityAcronym;
			$nameFulltext = Elastic::standardSearch($nameWithPrefix);
			$fullName1 = $nameFulltext . ' ' . $this->city->name;
			$fullNameSearch = $nameFulltext . ' ' . Elastic::standardSearchDistrict($this->district->pre . ' ' . $this->district->name) . ' ' . $this->city->name;
			
			$document = [
				'full_name' => $fullName,
				'slug' => $this->slug,
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
				$cityAcronym = Elastic::acronym($this->city->name);
				$districtAcronym = ctype_digit($this->district->name) ? Elastic::acronym($this->district->pre) . $this->district->name . ' ' . $this->district->name : Elastic::acronym($this->district->name);
				
				$fullName = $this->name . ', ' . $this->district->pre . ' ' . $this->district->name . ', ' . $this->city->name;
				$nameWithPrefix = 'Dự án ' . $this->name;
				$acronym = Elastic::acronym($this->name);
				$acronymFullName1 = $acronym . ' ' . $cityAcronym;
				$acronymFullName = $acronym . ' ' . $districtAcronym . ' ' . $cityAcronym;
				$nameFulltext = Elastic::standardSearch($nameWithPrefix);
				$fullName1 = $nameFulltext . ' ' . $this->city->name;
				$fullNameSearch = $nameFulltext . ' ' . Elastic::standardSearchDistrict($this->district->pre . ' ' . $this->district->name) . ' ' . $this->city->name;
					
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
