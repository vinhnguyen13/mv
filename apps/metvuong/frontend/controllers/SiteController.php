<?php
namespace frontend\controllers;

use frontend\components\MetaExt;
use frontend\models\Avg;
use frontend\models\ContactForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\Tracking;
use funson86\cms\models\Status;
use vsoft\ad\models\AdCategoryGroup;
use vsoft\news\models\CmsShow;
use Yii;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\base\UserException;
use yii\db\Query;
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
                'view' => 'error',
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

    public function actionViewDistrictFooter()
    {
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('_partials/viewDistrict');
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

	public function actionAvg() {
		if(Yii::$app->request->isAjax){
			$type = Yii::$app->request->post('type');
			$query = new Query();
			$query->from('ad_product');
			$query->where('true');
			if(!empty($type)){
				$query->andWhere(['type' => $type]);
			}
			$category = Yii::$app->request->post('category');
			if(!empty($category)){
				$categoryGroup = AdCategoryGroup::findOne($category);
				if(!empty($categoryGroup->categories_id) && is_array($categoryGroup->categories_id)) {
					$query->andWhere(['category_id' => $categoryGroup->categories_id]);
				}
			}
			$project_building_id = Yii::$app->request->post('project_building_id');
			if(!empty($project_building_id)){
				$query->andWhere(['project_building_id' => $project_building_id]);
			}
			$city = Yii::$app->request->post('city');
			if(!empty($city)){
				$query->andWhere(['city_id' => $city]);
				$district = Yii::$app->request->post('district');
				if(!empty($district)){
					$query->andWhere(['district_id' => $district]);
				}
				$wards = Yii::$app->request->post('wards');
				if(!empty($wards)){
					$query->andWhere(['ward_id' => $wards]);
				}
				$streets = Yii::$app->request->post('streets');
				if(!empty($streets)){
					$query->andWhere(['street_id' => $streets]);
				}
			}
			$size_min = Yii::$app->request->post('size_min');
			$size_max = Yii::$app->request->post('size_max');
			if(!empty($size_min) && !empty($size_max)){
				$query->andWhere(['BETWEEN', 'area', $size_min, $size_max]);
			}
			$bathroom = Yii::$app->request->post('bathroom');
			$bedroom = Yii::$app->request->post('bedroom');
			if(!empty($bathroom) || !empty($bedroom)){
				$query->innerJoin('ad_product_addition_info', 'ad_product_addition_info.product_id = ad_product.id');
				if(!empty($bathroom)){
					$query->andWhere(['>','ad_product_addition_info.room_no', $bathroom]);
				}
				if(!empty($bedroom)){
					$query->andWhere(['>','ad_product_addition_info.toilet_no', $bedroom]);
				}
			}

			Yii::$app->dbCraw->createCommand('SET group_concat_max_len = 5000000')->execute();
			$resultTotal = $query->select('COUNT(*) as totalListing')->one(Yii::$app->dbCraw);
			$result = $query->select([
				'SUM(price) as sum_price',
				'SUM(area) as sum_area',
				'COUNT(*) as total',
				'GROUP_CONCAT(area ORDER BY price ASC) as listarea',
				'GROUP_CONCAT(CAST(price/1000000 AS UNSIGNED) ORDER BY price ASC) as listprice',
				'GROUP_CONCAT(CAST(price/area AS UNSIGNED) ORDER BY price ASC) as listpricem2',
				])
				->andWhere('price != 0')->one(Yii::$app->dbCraw);

			if(!empty($result['listprice']) && $result['total'] >= 3) {
				$arrPrice = explode(',', $result['listprice']);
				$arrArea = explode(',', $result['listarea']);
				$dataBoxplot = \frontend\models\Avg::me()->calculation_boxplot($arrPrice, YII_DEBUG);
				$exclude_outlier = 3 * $dataBoxplot['IQR'];
				$keyEliminate = $arrAreaM2 = $newArrAreaM2 = [];
				$newArrPrice = array_filter($arrPrice, function($element, $key) use ($exclude_outlier, $dataBoxplot, $arrArea, &$keyEliminate, &$arrAreaM2, &$newArrAreaM2) {
					$arrAreaM2[$key] = $element/$arrArea[$key];
					if($element > ($dataBoxplot['q1']-$exclude_outlier) && $element < ($dataBoxplot['q3']+$exclude_outlier)) {
						$newArrAreaM2[$key] = $element/$arrArea[$key];
						return $element;
					}else{
						$keyEliminate[] = $key;
					}
				}, ARRAY_FILTER_USE_BOTH);

				$dataBoxplotNew = \frontend\models\Avg::me()->calculation_boxplot($newArrPrice, YII_DEBUG);
				$data['total'] = $result['total'];
				$data['totalListing'] = $resultTotal['totalListing'];
				$data['list_price'] = $arrPrice;
				$data['list_price_new'] = $newArrPrice;
				$data['dataChart'] = $dataBoxplotNew;
				$data['average_old'] = (array_sum($arrPrice) / count($arrPrice))*1000000;
				$data['average_m2_old'] = (array_sum($arrAreaM2) / count($arrAreaM2))*1000000;
				$data['average_new'] = (array_sum($newArrPrice) / count($newArrPrice))*1000000;
				$data['average_m2_new'] = (array_sum($newArrAreaM2) / count($newArrAreaM2))*1000000;


				/**
				 * chart for price/m2
				 */
				$dataBoxplotNewPM2 = \frontend\models\Avg::me()->calculation_boxplot($newArrAreaM2);

				$data['dataChartPM2'] = $dataBoxplotNewPM2;
				$data['list_price_new_PM2'] = $newArrAreaM2;
				$data['list_price_PM2'] = $arrAreaM2;
				$data['average_m2_old_PM2'] = (array_sum($arrAreaM2) / count($arrAreaM2)) * 1000000;
				$data['average_m2_new_PM2'] = (array_sum($newArrAreaM2) / count($newArrAreaM2)) * 1000000;
				/**
				 * remove if release
				 */

				$output = $this->renderAjax('/site/pages/vi-VN/_partials/avg-result', ['data'=>$data]);
				return $output;
			}
			return 'Not yet data !';
		}
	}

    public function actionSetCookie($name='Homepage'){
        if(Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $cookies = Yii::$app->request->cookies;
            $value = $cookies->getValue('tutorial', []);
            $value = array_merge($value, [
                $name => true,
            ]);

            Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => 'tutorial',
                'value' => $value,
                'expire' => time() + (10 * 365 * 24 * 60 * 60)
            ]));
            return true;
        }
        return false;
    }

	public function actionLogo($tr, $tp=1)
	{
		Tracking::find()->fromLogo($tr, $tp);
		$avatarPath = Yii::getAlias('@webroot').( '/images/logo-white.png');
		$pathinfo = pathinfo($avatarPath);
		$response = Yii::$app->getResponse();
		$response->headers->set('Content-Type', 'image/'.$pathinfo['extension']);
		$response->format = Response::FORMAT_RAW;
		if ( !is_resource($response->stream = fopen($avatarPath, 'r')) ) {
			throw new \yii\web\ServerErrorHttpException('file access failed: permission deny');
		}
		return $response->send();
	}
}
