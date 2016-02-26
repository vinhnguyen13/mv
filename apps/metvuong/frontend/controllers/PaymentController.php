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

class PaymentController extends Controller
{
    public $layout = '@app/views/layouts/layout';

    public function beforeAction($action)
    {
        $this->view->params['noFooter'] = true;
        return parent::beforeAction($action);
    }

    public function actionPackage(){
        return $this->render('package/index');
    }

}
