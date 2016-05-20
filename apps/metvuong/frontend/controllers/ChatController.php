<?php

namespace frontend\controllers;
use frontend\components\Controller;
use frontend\models\Chat;
use Yii;
use yii\base\Event;
use yii\base\ViewEvent;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii\web\View;

class ChatController extends Controller
{
    public $layout = '@app/views/layouts/layout';

    public function beforeAction($action)
    {
        $this->checkAccess();
        $this->view->params = ['noFooter' => true, 'menuChat' => true, 'isDashboard' => true];
        return parent::beforeAction($action);
    }

    public function actionIndex(){
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('index');
        }else{
            return $this->render('index');
        }
    }

    public function actionList(){
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('_partials/list');
        }
    }

    public function actionIndex2(){
        return $this->render('index2');
    }

    public function actionWith($username){
        if($username == Yii::$app->user->identity->username){
            $this->redirect(Url::to(['/chat/index']));
        }
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('with', ['username'=>$username]);
        }else{
            $this->redirect(Url::to(['/chat/index', 'username'=> Yii::$app->user->identity->username]));
        }
    }

    public function actionConversation(){
        if(Yii::$app->request->isAjax && Yii::$app->request->isPost){
            $word = Yii::$app->request->post('word');
            Yii::$app->response->format = 'json';
            return Chat::find()->searchUserFromConversation(Yii::$app->user->identity->username, $word);
        }
    }
}