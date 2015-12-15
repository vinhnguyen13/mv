<?php

namespace frontend\controllers;
use dektrium\user\Mailer;
use frontend\models\userManagement\ProfileForm;
use Yii;
use yii\db\mssql\PDO;
use yii\helpers\Url;
use vsoft\news\models\CmsShow;
use yii\web\View;

class UserManagementController extends \yii\web\Controller
{
    public $layout = '@app/views/user-management/layouts/main';
    public function actionIndex()
    {
        $this->redirect('/user-management/ads');
    }

    public function actionAds()
    {
        if(Yii::$app->request->isAjax){
            return $this->renderPartial('ads/index', [
            ]);
        }
        return $this->render('ads/index', [
        ]);
    }

    public function actionAdsMostSearch()
    {
        if(Yii::$app->request->isAjax){
            return $this->renderPartial('ads/most-search', [
            ]);
        }
        return $this->render('ads/most-search', [
        ]);
    }

    public function actionAdsSuggest()
    {
        if(Yii::$app->request->isAjax){
            return $this->renderPartial('ads/suggest', [
            ]);
        }
        return $this->render('ads/suggest', [
        ]);
    }

    public function actionChart()
    {
        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('chart/ads', [
            ]);
        }
        return $this->render('chart/ads', [
        ]);
    }

    public function actionProfile()
    {
        if(Yii::$app->request->isAjax) {
            return $this->renderPartial('user/profile', [
            ]);
        }
        return $this->render('user/profile', [
        ]);
    }

    public function actionPassword()
    {
        $model = Yii::createObject([
            'class'    => ProfileForm::className(),
            'scenario' => 'password',
        ]);
        if(Yii::$app->request->isAjax) {
            return $this->renderPartial('user/password', [
                'model'=>$model
            ]);
        }
        return $this->render('user/password', [
            'model'=>$model
        ]);
    }
}
