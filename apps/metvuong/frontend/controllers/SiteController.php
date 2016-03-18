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
    public $layout = '@app/views/layouts/layout';

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
        Yii::$app->meta->add(Yii::$app->request->absoluteUrl);
        $homepage_news = CmsShow::getShowForHomepage();
//        $metvuong_news = CmsShow::getShowForMetvuong();
        return $this->render('index',['news' => $homepage_news]);
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
    	

    	$catalogs = CmsCatalog::find()->indexBy('id')->select('id, title')->where(['parent_id'=>Yii::$app->params['newsCatID'], 'status'=>Status::STATUS_ACTIVE 
		] )->asArray ( true )->all ();
		
		foreach ( $catalogs as $k => &$catalog ) {
			unset ( $catalog ['id'] );
		}
		
		$news = [ 
				1 => [ 
						'title' => Yii::t ( 'cms', 'Tin Tức' ) 
				],
				2 => [ 
						'title' => Yii::t ( 'cms', 'Dự Án' ) 
				] 
		];
		
		$content = 'var dataCities = ' . json_encode ( $cities, JSON_UNESCAPED_UNICODE ) . ';' . 'var dataCategories = ' . json_encode ( $categories, JSON_UNESCAPED_UNICODE ) . ';' . 'var newsCatalogs = ' . json_encode ( $catalogs, JSON_UNESCAPED_UNICODE ) . ';' . 'var news = ' . json_encode ( $news, JSON_UNESCAPED_UNICODE ) . ';';
		$file = fopen ( Yii::getAlias ( '@store' ) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . "data.js", "w" );
		fwrite ( $file, $content );
		fclose ( $file );
	}
	public function actionSearch() {
    	$v = \Yii::$app->request->get('v');
    	
    	if(Yii::$app->request->isAjax) {
    		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    		
    		$v = Elastic::transform($v);
    		 
    		$params = [
				'query' => [
					'match_phrase_prefix' => [
						'search_field' => [
    						'query' => $v,
    						'max_expansions' => 100
    					]
					],
    			],
    			'sort' => [
    				'total_sell' => [
    					'order' => 'desc',
    					'mode'	=> 'sum'
					],
    				'total_rent' => [
    					'order' => 'desc',
    					'mode'	=> 'sum'
					],
				],
				'size' => 6,
    			'_source' => ['full_name', 'total_sell', 'total_rent']
    		];
    		 
    		$ch = curl_init(Yii::$app->params['elastic']['config']['hosts'][0] . '/term/_search');
    		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    		 
    		$result = json_decode(curl_exec($ch), true);
    		$response = [];
    		 
    		foreach ($result['hits']['hits'] as $k => $hit) {
    			$response[$k] = $hit['_source'];
    			$response[$k]['url'] = Url::to(['/ad/index', $hit['_type'] . '_id' => $hit['_id']]);
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
}
