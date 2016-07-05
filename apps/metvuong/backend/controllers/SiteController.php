<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;

use yii\base\ExitException;
use yii\base\Model;
use yii\base\Module as Module2;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete'  => ['post'],
                    'confirm' => ['post'],
                    'block'   => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return (Yii::$app->user->can('/'.$this->id.'/*') || Yii::$app->user->can('/'.$this->id.'/'.$this->action->id)) ;
                        },
                    ],
                ],
            ],
//            'as access' => [
//                'class' => 'mdm\admin\components\AccessControl',
//                'allowActions' => [/*'*'*/]
//            ],
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
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionClearCache(){
        Yii::$app->session->setFlash(
            'info',
            Yii::t('user', 'Your account has been created and a message with further instructions has been sent to your email')
        );
        Yii::$app->cache->flush();
        $this->redirect(Url::home());
    }
}
