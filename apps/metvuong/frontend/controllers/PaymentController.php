<?php

namespace frontend\controllers;
use frontend\components\Controller;
use frontend\models\Chat;
use frontend\models\Profile;
use Yii;
use yii\base\Event;
use yii\base\ViewEvent;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii\web\View;
use frontend\models\Payment;

class PaymentController extends Controller
{
    public $layout = '@app/views/layouts/layout';

    public function beforeAction($action)
    {
        $this->view->params['noFooter'] = true;
        return parent::beforeAction($action);
    }

    public function actionIndex(){
        $this->checkAccess();
        if(Yii::$app->request->isPost) {
            Payment::me()->payWithNganLuong();
        }
        return $this->render('index');
    }

    public function actionPackage(){
        $this->view->title = Yii::t('meta', 'services');
        $this->view->params['menuPricing'] = true;
        return $this->render('package/index');
    }

    public function actionSuccess(){
        $token = Yii::$app->request->get('token');
        $error_code = Yii::$app->request->get('error_code');
        if(!empty($token)){
            Payment::me()->success($token);
            $this->redirect('/');
            /**
             * redirect to
             * 1.referer url
             * 2.payment history
             * 3.home page if not found 1 & 2
             */
        }
    }

    public function actionCancel(){
        /**
         * transaction cancel by user, show layout cancel
         */
    }

}
