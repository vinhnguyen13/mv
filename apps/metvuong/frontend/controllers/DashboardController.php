<?php

namespace frontend\controllers;
use dektrium\user\Mailer;
use frontend\models\User;
use frontend\models\ProfileForm;
use vsoft\express\components\ImageHelper;
use Yii;
use yii\db\mssql\PDO;
use yii\helpers\Url;
use vsoft\news\models\CmsShow;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\web\View;
use frontend\components\Controller;

class DashboardController extends Controller
{
    public $layout = '@app/views/layouts/layout';

    public function beforeAction($action)
    {
        if(Yii::$app->user->isGuest){
            $this->redirect(['/member/login']);
        }
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $this->redirect('/dashboard/statistics');
    }

    public function actionStatistics()
    {
        return $this->render('statistics/index', [
        ]);
    }

    public function actionNotification()
    {
        return $this->render('notification/index', [
        ]);
    }

    public function actionAds()
    {
        return $this->render('ads/index', [
        ]);
    }

    public function actionPassword()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = Yii::createObject([
            'class'    => ProfileForm::className(),
            'scenario' => 'password',
        ]);

        if(Yii::$app->request->isAjax) {
            if(Yii::$app->request->isPost){
                Yii::$app->response->format = Response::FORMAT_JSON;
                $post = Yii::$app->request->post();
                $model->load($post);
                $model->validate();
                if (!$model->hasErrors()) {
                    $res = $model->resetPass();
                    return ['statusCode'=>$res];
                }else{
                    return ['statusCode'=> false, 'parameters' => $model->errors];
                }
            }
            return $this->renderAjax('user/password', [
                'model' => $model
            ]);
        }

        return $this->render('user/password', [
            'model'=>$model
        ]);
    }




}
