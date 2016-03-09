<?php
namespace frontend\controllers;

use frontend\models\ContactForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use funson86\cms\models\Status;
use vsoft\news\models\CmsShow;
use Yii;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use frontend\components\Controller;
use yii\web\Cookie;
use vsoft\ad\models\AdCity;
use vsoft\ad\models\AdDistrict;
use vsoft\ad\models\AdWard;
use vsoft\ad\models\AdStreet;
use yii\helpers\ArrayHelper;
use vsoft\ad\models\AdBuildingProject;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use vsoft\ad\models\AdCategory;
use vsoft\news\models\CmsCatalog;
use yii\helpers\StringHelper;
use frontend\models\Elastic;
use vsoft\ad\models\AdProduct;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $layout = '@app/views/layouts/news';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
			'page' => [
				'class' => 'yii\web\ViewAction',
			],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $this->layout = '@app/views/layouts/layout';
        Yii::$app->meta->add(Yii::$app->request->absoluteUrl);
        $homepage_news = CmsShow::getShowForHomepage();
        $metvuong_news = CmsShow::getShowForMetvuong();
        return $this->render('index',['news' => $homepage_news, 'metvuong_news' => $metvuong_news]);
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionLanguage()
    {
        $language = Yii::$app->request->get('language');
        Yii::$app->language = $language;
        $cookie = new Cookie([
            'name' => 'language',
            'value' => $language,
            'expire' => time() + 60 * 60 * 24 * 30, // 30 days
//            'domain' => '.lancaster.vn' // <<<=== HERE
        ]);
        \Yii::$app->getResponse()->getCookies()->add($cookie);
        $this->redirect(Yii::$app->request->referrer);
    }

    public function actionInfo()
    {
        echo "<pre>";
        print_r(phpinfo());
        echo "</pre>";
        exit;
    }
    
    public function actionMapImage($t, $s) {
    	if(ctype_digit($t) && $t < 10000 && $t > 1) {
    		header('Content-Type: image/png');
    		
    		if(strlen($t) > 3) {
    			$imgSrcName = ($s == 0) ? 'mc-1' : 'mch-1';
    			$offsetX = 16; // image width / 2
    			$offsetY = 17; // image height / 2
    		} else {
    			$imgSrcName = ($s == 0) ? 'mc' : 'mch';
    			$offsetX = 12; // image width / 2
    			$offsetY = 13; // image height / 2
    		}
    		
    		$markerImagesFolder = \Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . 'images';
    		$markerSaveName = $markerImagesFolder . DIRECTORY_SEPARATOR . 'genarate' . DIRECTORY_SEPARATOR . $imgSrcName . '-' . $t . '.png';
    		
    		if(file_exists($markerSaveName)) {
    			$im = @imagecreatefrompng($markerSaveName);
    			imagealphablending($im, true);
    			imagesavealpha($im, true);
    		} else {
    			$im = @imagecreatefrompng($markerImagesFolder . DIRECTORY_SEPARATOR . $imgSrcName . '.png');
    			imagealphablending($im, true);
    			imagesavealpha($im, true);
    			
    			$font = \Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . 'MyriadPro-Regular.otf';
    			$size = 10;
    			$color = ($s == 0) ? imagecolorallocate($im, 0, 0, 0) : imagecolorallocate($im, 255, 255, 255);
    			
    			// calculate center text on image
    			$bbox = imagettfbbox($size, 0, $font, $t);
    			$dx = ($bbox[2]-$bbox[0])/2.0 - ($bbox[2]-$bbox[4])/2.0;
    			$dy = ($bbox[3]-$bbox[1])/2.0 + ($bbox[7]-$bbox[1])/2.0;
    			
    			if(StringHelper::startsWith($t, '1')) {
    				$recalOffsetX = $offsetX - 1;
    			} else if(StringHelper::startsWith($t, '4')) {
    				$recalOffsetX = $offsetX + 1;
    			}
    			$px = StringHelper::startsWith($t, '1') ? $recalOffsetX - $dx : $offsetX - $dx;
    			
    			$py = $offsetY - $dy;
    			
    			imagettftext($im, $size, 0, $px, $py, $color, $font, $t);
    			
    			imagepng($im, $markerSaveName);
    		}
    		
    		imagepng($im);
    		imagedestroy($im);
    		exit();
    	}
    }
    
    public function actionServiceLocationList() {
		$cities = AdCity::find()->indexBy('id')->select('id, name')->asArray(true)->all();
    	$districts = AdDistrict::find()->indexBy('id')->select('id, name, pre, city_id')->asArray(true)->all();
    	$wards = AdWard::find()->indexBy('id')->select('id, name, district_id, pre')->asArray(true)->all();
    	$streets = AdStreet::find()->indexBy('id')->select('id, name, district_id, pre')->asArray(true)->all();
    	$projects = AdBuildingProject::find()->indexBy('id')->with('categories')->select('id, name, district_id')->asArray(true)->all();
    	
    	foreach ($cities as &$city) {
    		unset($city['id']);
    		$city['districts'] = [];
    	}
    	
    	foreach ($districts as &$district) {
    		$district['wards'] = [];
    		$district['streets'] = [];
    		$district['projects'] = [];
    	}
    	
    	foreach ($wards as $k => $ward) {
    		$districtId = $ward['district_id'];
    		unset($ward['district_id']);
    		unset($ward['id']);
    		$districts[$districtId]['wards'][$k] = $ward;
    	}
    	$wards = null;
    	
    	foreach ($streets as $k => $street) {
    		$districtId = $street['district_id'];
    		unset($street['district_id']);
    		unset($street['id']);
    		$districts[$districtId]['streets'][$k] = $street;
    	}
    	$streets = null;
    	
    	foreach ($projects as $k => $project) {
    		$districtId = $project['district_id'];
    		unset($project['district_id']);
    		unset($project['id']);
    		$project['categories'] = ArrayHelper::getColumn($project['categories'], 'id');
    		$districts[$districtId]['projects'][$k] = $project;
    	}
    	$projects = null;

    	foreach ($districts as $k => &$district) {
            $cityId = $district['city_id'];
            unset($district['city_id']);
    		unset($district['id']);
    		$cities[$cityId]['districts'][$k] = $district;
    	}
    	
    	$categories = AdCategory::find()->indexBy('id')->select('id, name, apply_to_type, template, limit_area')->where(['status'=>1])->asArray(true)->all();
    	$templateMap = AdCategory::templateMap();
    	
    	foreach ($categories as $k => &$category) {
    		unset($category['id']);
    		$category['template'] = $templateMap[$category['template']];
    	}
    	

    	$catalogs = CmsCatalog::find()->indexBy('id')->select('id, title')->where(['parent_id'=>Yii::$app->params['newsCatID'], 'status'=>Status::STATUS_ACTIVE])->asArray(true)->all();
    	 
    	foreach ($catalogs as $k => &$catalog) {
    		unset($catalog['id']);
    	}

        $news = [
            1=>['title'=>Yii::t('cms', 'Tin Tức')],
            2=>['title'=>Yii::t('cms', 'Dự Án')],
        ];

		$content = 'var dataCities = ' . json_encode($cities, JSON_UNESCAPED_UNICODE) . ';' .
					'var dataCategories = ' . json_encode($categories, JSON_UNESCAPED_UNICODE) . ';' .
					'var newsCatalogs = ' . json_encode($catalogs, JSON_UNESCAPED_UNICODE) . ';'.
					'var news = ' . json_encode($news, JSON_UNESCAPED_UNICODE) . ';';
    	$file = fopen(Yii::getAlias('@store') . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . "data.js", "w");
    	fwrite($file, $content);
		fclose($file);
    }
    
    public function actionSearch() {
    	$v = \Yii::$app->request->get('v');
    	
    	if(Yii::$app->request->isAjax) {
    		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    		
    		$v = Elastic::transform($v);
    		 
    		$params = [
    		'query' => [
    		'match_phrase_prefix' => ['search_field' => [
    		'query' => $v,
    		'max_expansions' => 100
    		]],
    		],
    		'sort' => ['total' => ['order' => 'desc']],
    		'_source' => ['full_name', 'total']
    		];
    		 
    		$ch = curl_init(Yii::$app->params['elastic']['config']['hosts'][0] . '/term/_search');
    		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    		 
    		$result = json_decode(curl_exec($ch), true);
    		$response = [];
    		 
    		foreach ($result['hits']['hits'] as $k => $hit) {
    			$response[$k] = $hit['_source'];
    			$response[$k]['url'] = Url::to(['/ad/index', $hit['_type'] => $hit['_id']]);
    		}
    		
    		return $response;
    	} else {
    		$id = str_replace('mv', '', strtolower($v));
    		$product = AdProduct::findOne($id);
    		
    		if($product) {
    			return $this->redirect($product->urlDetail());
    		} else {
    			return $this->redirect(Url::to(['/ad/index']));
    		}
    	}
    }
    
	public function actionTest() {
// 		ini_set('xdebug.var_display_max_depth', -1);
// 		ini_set('xdebug.var_display_max_children', -1);
// 		ini_set('xdebug.var_display_max_data', -1);
		
		$files = array_diff(scandir(Yii::getAlias('@store') . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'geometry'), array('.', '..'));
		
		$cities = ArrayHelper::index(\Yii::$app->db->createCommand("SELECT * FROM `ad_city`")->queryAll(), 'slug');
		
		$mapCity = [
			'ho-chi-minh' => 'tp-ho-chi-minh'
		];
		
		$mapDistrict = [
			'Hà Nội' => [
				'Ba Vì' => 'Ba Vi',
				'Bắc Từ Liêm' => 'Từ Liêm',
				'Nam Từ Liêm' => 'Từ Liêm',
				'Ứng Hòa' => 'Ứng Hoà',
			],
			'Bình Dương' => [
				'Dầu Tiếng' => 'Dau Tieng',
				'Dĩ An' => 'Di An'
			],
			'Đà Nẵng' => [
				'Hòa Vang' => 'Hoà Vang',
			],
			'Hải Phòng' => [
				'Bạch Long Vĩ' => 'Bach Long Vi',
			],
			'Long An' => [
				'Tân Thạnh' => 'Tân Thành',
				'Thạnh Hóa' => 'Thanh Hóa',
			],
			'Bà Rịa Vũng Tàu' => [
				'Bà Rịa' => 'Ba Ria'
			],
			'An Giang' => [
				'Long Xuyên' => 'TP. Long Xuyên'
			],
			'Bắc Kạn' => [
				'Bắc Kạn' => 'Bac Kan',
				'Na Rì' => 'Na Ri'
			],
			'Bến Tre' => [
				'Mỏ Cày Bắc' => 'Mỏ Cày',
				'Mỏ Cày Nam' => 'Mỏ Cày'
			],
			'Bình Định' => [
				'Quy Nhơn' => 'Qui Nhơn',
				'Vân Canh' => 'Van Canh',
			],
			'Bình Phước' => [
				'Bù Đốp' => 'Bu Dop',
				'Đồng Phú' => 'Đồng Phù',
				'Đồng Xoài' => 'Dong Xoai',
			],
			'Bình Thuận' => [
				'Đảo Phú Quý' => 'Phú Quý',
				'Tánh Linh' => 'Tanh Linh',
			],
			'Cao Bằng' => [
				'Hòa An' => 'Hoà An',
				'Quảng Uyên' => 'Quảng Yên',
			],
			'Đắk Lắk' => [
				'Buôn Ma Thuột' => 'Buon Ma Thuot',
				"Ea H'Leo" => "Ea H'leo",
				'Krông Pắc' => 'Krông Pắk'
			],
			'Đắk Nông' => [
				'Dăk GLong' => 'Đăk Glong',
				'Dăk Mil' => 'Đăk Mil',
				"Dăk R'Lấp" => "Đăk R'Lấp",
				"Dăk Song"	=> "Dak Song",
			],
			'Điện Biên' => [
				'Điện Biên Phủ' => 'Điên Biên Phủ'
			],
			'Đồng Nai' => [
				'Biên Hòa' => 'Bien Hoa'
			],
			'Đồng Tháp' => [
				'Huyện Cao Lãnh' => 'Cao Lanh',
				'Huyện Hồng Ngự' => 'Hồng Ngự',
				'Tp. Cao Lãnh' => 'Cao Lãnh',
			],
			'Gia Lai' => [
				'AYun Pa' => 'Ayun Pa',
				'ChưPRông' => 'Chư Prông',
				'Đăk Đoa' => 'Đắk Đoa',
				'Đăk Pơ' => 'Đắk Pơ',
				'KBang' => "K'Bang",
				'Plei Ku' => 'Pleiku',
			],
			'Hậu Giang' => [
				'Vị Thủy' => 'Vị Thuỷ'
			],
			'Hòa Bình' => [
				'Lạc Thủy' => 'Lạc Thuỷ',
				'Yên Thủy' => 'Yên Thuỷ'
			],
			'Khánh Hòa' => [
				'Vạn Ninh' => 'Van Ninh'
			],
			'Kiên Giang' => [
				'U minh Thượng' => 'U Minh Thượng'
			],
			'Kon Tum' => [
				'KonTum' => 'Kon Tum'
			],
			'Lai Châu' => [
				'Than Uyên' => 'Thanh Uyen'
			],
			'Lạng Sơn' => [
				'Văn Lãng' => 'Vãn Lãng'
			],
			'Lào Cai' => [
				'Xi Ma Cai' => 'Si Ma Cai'
			],
			'Ninh Thuận' => [
				'Phan Rang - Tháp Chàm' => 'Phan Rang-Tháp Chàm'
			],
			'Phú Thọ' => [
				'Hạ Hòa' => 'Hạ Hoà',
				'Phù Ninh' => 'Phú Ninh',
				'Thanh Thủy' => 'Thanh Thuỷ'
			],
			'Phú Yên' => [
				'Tuy Hòa' => 'Tuy Hoa'
			],
			'Quảng Bình' => [
				'Tuyên Hóa' => 'Tuyen Hoa'
			],
			'Quảng Nam' => [
				'Tây Giang' => 'Tay Giang'
			],
			'Quảng Trị' => [
				'Đăk Rông' => 'Đa Krông',
				'Đảo Cồn cỏ' => 'Cồn Cỏ',
			],
			'Sóc Trăng' => [
				'Thạnh Trị' => 'Thanh Trì',
				
			],
			'Thái Bình' => [
				'Thái Thuỵ' => 'Thái Thụy'
			],
			'Thái Nguyên' => [
				'Định Hóa' => 'Định Hoá'
			],
			'Thanh Hóa' => [
				'Bỉm Sơn' => 'Bim Son',
				'Ngọc Lặc' => 'Ngọc Lạc',
				'Thanh Hóa' => 'Thanh Hóa City'
			],
			'Tiền Giang' => [
				'Gò Công' => 'Go Cong',
				'Huyện Cai Lậy' => 'Cai Lậy'
			],
			'Tuyên Quang' => [
				'Chiêm Hóa' => 'Chiêm Hoá',
				'Na Hang' => 'Nà Hang',
			],
			'Vĩnh Phúc' => [
				'Tam Dương' => 'Tam Đường'
			],
			'Yên Bái' => [
				'Mù Cang Chải' => 'Mù Căng Trai'
			]
		];
		
		$mapWards = [
			'Hồ Chí Minh' => [
				'Bình Chánh' => [
					'Xã Tân Nhựt' => 'Tân Nhùt'
				],
				'Bình Tân' => [
					'Phường Bình Hưng Hòa A' => 'Bình Hưng Hòa A',
					'Phường Bình Hưng Hòa' => 'Bình Hưng Hòa',
				],
				'Cần Giờ' => [
					'Xã An Thới Đông' => 'An Thíi Đông',
					'Phường Cần Thạnh ' => 'Cần Thạnh',
					'Xã Tam Thôn Hiệp' => 'Tam Thôn HiÖp',
				],
				'Củ Chi' => [
					'Xã Hòa Phú' => 'Hoà Phú',
					'Xã An Phú' => 'Ân Phú',
					'Xã Phú Hòa Đông' => 'Phú Hoà Đông'
				],
				'Hóc Môn' => [
					'Phường Hóc Môn' => 'Hóc Môn',
					'Xã Đông Thạnh' => 'Đông Thạnh'
				],
				'Quận 12' => [
					'Phường Thới An' => 'Thíi An',
					'Phường Hiệp Thành' => 'Hiệp Thµnh',
					'Phường Thạnh Xuân' => 'Th¹nh Xuân'
				],
				'Quận 2' => [
					'Phường An Phú' => 'Ân Phú',
					'Phường Thạnh Mỹ Lợi' => 'Thạnh Mỹ Lợi'
				],
			]
		];
		
		
		$limit = 3;
		$count = 0;
		
		foreach($cities as $slug => $city) {
			$slug = isset($mapCity[$slug]) ? $mapCity[$slug] : $slug;
			$cityFile = json_decode(file_get_contents(Yii::getAlias('@store') . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'geometry' . DIRECTORY_SEPARATOR . $slug . '.js'), true);
					
			$districts = ArrayHelper::index(\Yii::$app->db->createCommand("SELECT * FROM `ad_district` WHERE city_id = {$city['id']}")->queryAll(), 'name');
			$districtFiles = ArrayHelper::index($cityFile['geo']['features'], function($e) { return $e['properties']['name']; });
			
			echo $city['name'];
			echo '<div style="margin-left: 40px;">';
			foreach($districts as $districtName => $district) {
				$districtName = isset($mapDistrict[trim($city['name'])][$districtName]) ? $mapDistrict[trim($city['name'])][$districtName] : $districtName;
				
				echo $districtName;
				
				if(isset($districtFiles[$districtName])) {
					$wards = ArrayHelper::index(\Yii::$app->db->createCommand("SELECT * FROM `ad_ward` WHERE district_id = {$district['id']}")->queryAll(), 'name');
					$wardFiles = ArrayHelper::index($districtFiles[$districtName]['wards']['geo']['features'], function($e) { return $e['properties']['name']; });
					
					echo '<div style="margin-left: 80px;">';
					echo count($wards) . ' ' . count($wardFiles) . '<br />';
					foreach ($wards as $wardName => $ward) {
						$wardName = $ward['pre'] ? $ward['pre'] . ' ' . $wardName : $wardName;
						$wardName = isset($mapWards[trim($city['name'])][$districtName][$wardName]) ? $mapWards[trim($city['name'])][$districtName][$wardName] : $wardName;
						if(!isset($wardFiles[$wardName])) {
							echo $wardName . '<br />';
						}
					}
					echo '</div>';
				}
			}
// 			foreach($districts as $name => $district) {
// 				$name = isset($mapDistrict[trim($city['name'])][$name]) ? $mapDistrict[trim($city['name'])][$name] : $name;
// 				if(isset($districtFiles[$name])) {
// 					$wards = ArrayHelper::index(\Yii::$app->db->createCommand("SELECT * FROM `ad_ward` WHERE district_id = {$district['id']}")->queryAll(), 'name');
// 					var_dump($wards);
// // 					$wards = ArrayHelper::index(\Yii::$app->db->createCommand("SELECT * FROM `ad_ward` WHERE district_id = {$district['id']}")->queryAll(), 'name');
					
// // 					var_dump($districtFiles[$name]['wards']);
// // 					echo $name;
// // 					echo '<div style="margin-left: 80px;">';
// // 					echo '</div>';
// 					break;
// 				}
// 				break;
// 			}
			echo '</div>';
			break;
		}
	}
}
