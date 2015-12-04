<?php
namespace frontend\controllers;

use frontend\models\ContactForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use Yii;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Cookie;
use common\vendor\vsoft\ad\models\AdCity;
use common\vendor\vsoft\ad\models\AdDistrict;
use common\vendor\vsoft\ad\models\AdWard;
use common\vendor\vsoft\ad\models\AdStreet;
use yii\helpers\ArrayHelper;
use common\vendor\vsoft\ad\models\AdBuildingProject;
use yii\web\NotFoundHttpException;
use yii\web\Response;

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
        $this->layout = '@app/views/layouts/main';
        Yii::$app->meta->add(Yii::$app->request->absoluteUrl);
        return $this->render('index');
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
    	
		$content = 'var dataCities = ' . json_encode($cities, JSON_UNESCAPED_UNICODE) . ';';
    	$file = fopen(Yii::$app->view->theme->basePath . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . "data.js", "w");
    	fwrite($file, $content);
		fclose($file);
    }
}
