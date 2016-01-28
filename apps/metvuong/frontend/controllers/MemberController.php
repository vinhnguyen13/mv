<?php
namespace frontend\controllers;

use frontend\components\Finder;
use dektrium\user\helpers\Password;
use frontend\models\LoginForm;
use frontend\models\RegistrationForm;
use frontend\models\ResetPasswordForm;
use frontend\models\Token;
use Yii;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use frontend\components\Controller;
use yii\web\Cookie;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use frontend\models\RecoveryForm;

/**
 * Site controller
 */
class MemberController extends Controller
{
    public $layout = '@app/views/layouts/layout';
    public $_module;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
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

    public function init(){
        $this->_module = Yii::$app->getModule('user');
        parent::init();
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = Yii::createObject(LoginForm::className());
            if ($model->load(Yii::$app->getRequest()->post()) && $model->login()) {
                return ['statusCode'=>200, 'parameters'=>['username'=>!empty(Yii::$app->user->identity->profile->name) ? Yii::$app->user->identity->profile->name : Yii::$app->user->identity->email]];
            } else {
                return ['statusCode'=>404, 'parameters'=>$model->errors];
            }
        }
        return $this->render('login');
        throw new NotFoundHttpException('Not Found');
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = Yii::createObject(RegistrationForm::className());
            $model->load(Yii::$app->request->post());
            $model->validate();
            if (!$model->hasErrors()) {
                $user = $model->register();
                if (!empty($user) && Yii::$app->getUser()->login($user, $this->_module->rememberFor)) {
                    return ['statusCode'=>200, 'parameters'=>['username'=>!empty(Yii::$app->user->identity->profile->name) ? Yii::$app->user->identity->profile->name : Yii::$app->user->identity->email]];
                }
            } else {
                return ['statusCode'=>404, 'parameters'=>$model->errors];
            }
        }
        return $this->render('signup');
        throw new NotFoundHttpException('Not Found');
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionForgot()
    {
        if (!$this->_module->enablePasswordRecovery) {
            throw new NotFoundHttpException();
        }

        /** @var RecoveryForm $model */
        $model = Yii::createObject([
            'class'    => RecoveryForm::className(),
            'scenario' => 'request',
        ]);
        if(Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model->load(Yii::$app->request->post());
            $model->validate();
            if (!$model->hasErrors()) {
                if(($msg =$model->sendRecoveryMessage()) !== false){
                    return ['statusCode'=>200, 'parameters'=>['msg'=>$msg]];
                }
            } else {
                return ['statusCode'=>404, 'parameters'=>$model->errors];
            }
        }

        return $this->render('forgot', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($id, $code)
    {
        if (!$this->_module->enablePasswordRecovery) {
            throw new NotFoundHttpException();
        }

        /** @var Token $token */
        $token = Token::find()->where(['MD5(CONCAT(user_id, code))' => md5($id.$code), 'code' => $code, 'type' => Token::TYPE_RECOVERY])->one();
        if ($token === null || $token->isExpired || $token->user === null) {
            Yii::$app->session->setFlash('danger', Yii::t('user', 'Recovery link is invalid or expired. Please try requesting a new one.'));

            return $this->render('/message', [
                'title'  => Yii::t('user', 'Invalid or expired link'),
                'module' => $this->module,
            ]);
        }

        /** @var RecoveryForm $model */
        $model = Yii::createObject([
            'class'    => RecoveryForm::className(),
            'scenario' => 'reset',
        ]);

        if(Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model->load(Yii::$app->request->post());
            $model->validate();
            if (!$model->hasErrors()) {
                if(($msg = $model->resetPassword($token)) !== false){
                    return ['statusCode'=>200, 'parameters'=>['msg'=>$msg]];
                }
            } else {
                return ['statusCode'=>404, 'parameters'=>$model->errors];
            }
        }
        return $this->render('reset', [
            'model' => $model,
        ]);
    }

}
