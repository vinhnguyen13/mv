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

class ChatController extends Controller
{
    public $layout = '@app/views/layouts/layout';

    public function beforeAction($action)
    {
        $this->view->params['noFooter'] = true;
        return parent::beforeAction($action);
    }

    public function init()
    {
        parent::init();
        $this->checkAccess();
    }

    public function actionIndex(){
        Chat::find()->searchUserFromConversation(Yii::$app->user->identity->username, 'v');
        return $this->render('index');
    }

    public function actionIndex2(){
        return $this->render('index2');
    }

    public function actionWith($username){
        if($username == Yii::$app->user->identity->username){
            $this->redirect(Url::to(['/chat/index']));
        }
        return $this->render('with', ['username'=>$username]);
    }

    public function actionConversation(){
        if(Yii::$app->request->isAjax && Yii::$app->request->isPost){
            $word = Yii::$app->request->post('word');
            Yii::$app->response->format = 'json';
            return Chat::find()->searchUserFromConversation(Yii::$app->user->identity->username, $word);
        }
    }
}
