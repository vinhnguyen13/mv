<?php

namespace frontend\controllers;
use frontend\components\Controller;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

class ChatController extends Controller
{
    public $layout = '@app/views/layouts/layout';

    public function beforeAction($action)
    {
        $this->view->params['noFooter'] = true;
        return parent::beforeAction($action);
    }

    public function actionIndex(){
        if(Yii::$app->user->isGuest) {
            return $this->render('/_systems/require_login');
        }
        return $this->render('index');
    }

    public function actionIndex2(){
        if(Yii::$app->user->isGuest) {
            return $this->render('/_systems/require_login');
        }
        return $this->render('index2');
    }

    public function actionWith($username){
        return $this->render('with');
    }
}
