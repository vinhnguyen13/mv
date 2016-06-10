<?php
namespace frontend\controllers;

use frontend\models\ContactForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use funson86\cms\models\Status;
use vsoft\news\models\CmsShow;
use Yii;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\base\UserException;
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
use yii\web\HttpException;
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
				'viewPrefix' => 'pages/'.Yii::$app->language
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
		$this->view->title = Yii::t('meta', 'homepage');
        Yii::$app->meta->add(Yii::$app->request->absoluteUrl);
        return $this->render('index');
    }

	public function actionError2()
	{
		if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
			// action has been invoked not from error handler, but by direct route, so we display '404 Not Found'
			$exception = new HttpException(404, Yii::t('yii', 'Page not found.'));
		}

		if ($exception instanceof HttpException) {
			$code = $exception->statusCode;
		} else {
			$code = $exception->getCode();
		}
		if ($exception instanceof Exception) {
			$name = $exception->getName();
		} else {
			$name = Yii::t('yii', 'Error');
		}
		if ($code) {
			$name .= " (#$code)";
		}

		if ($exception instanceof UserException) {
			$message = $exception->getMessage();
		} else {
			$message = Yii::t('yii', 'An internal server error occurred.');
		}
		if(!empty($exception->statusCode) && $exception->statusCode == 404){
			$pathInfo = Yii::$app->request->pathInfo;
			if(!empty($pathInfo)){
				$c = explode("/", $pathInfo);
				if(!empty($c[0]) && in_array($c[0], Yii::$app->bootstrap['MVBootstrap']['supportedLanguages'])){
					$url = '/'.str_replace($c[0], '', $pathInfo);
					$this->redirect(Url::to([$url]));
					Yii::$app->end();
				}
			}
		}
		if (Yii::$app->getRequest()->getIsAjax()) {
			return "$name: $message";
		} else {
			return $this->render('error', [
				'name' => $name,
				'message' => $message,
				'exception' => $exception,
			]);
		}
	}

	public function actionFeatureListings()
	{
		if(Yii::$app->request->isAjax){
			return $this->renderAjax('_partials/featureListings');
		}
	}

	public function actionNews()
	{
		if(Yii::$app->request->isAjax){
			return $this->renderAjax('_partials/news');
		}
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

	public function actionCommingsoon()
	{
		return $this->renderPartial('commingsoon');
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
    	if(ctype_digit($t) && $t < 100000 && $t > 1) {
    		header('Content-Type: image/png');
    		if(strlen($t) > 4) {
    			$imgSrcName = ($s == 0) ? 'mc-2' : 'mch-2';
    			$offsetX = 21; // image width / 2
    			$offsetY = 20; // image height / 2
    		} else if(strlen($t) > 3) {
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
}
