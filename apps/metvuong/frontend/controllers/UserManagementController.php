<?php

namespace frontend\controllers;
use dektrium\user\Mailer;
use frontend\models\User;
use frontend\models\userManagement\ProfileForm;
use Yii;
use yii\db\mssql\PDO;
use yii\helpers\Url;
use vsoft\news\models\CmsShow;
use yii\web\Response;
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
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $user = User::findIdentity(Yii::$app->user->id);
        $profile = $user->profile;

        $model = Yii::createObject([
            'class'    => ProfileForm::className(),
            'scenario' => 'updateprofile',
        ]);

        $model->name = $profile->name;
        $model->public_email = $profile->public_email;
        $model->phone = $profile->phone;
        $model->mobile = $profile->mobile;
        $model->address = $profile->address;

        if(Yii::$app->request->isAjax) {
            if(Yii::$app->request->isPost){
                Yii::$app->response->format = Response::FORMAT_JSON;
                $post = Yii::$app->request->post();
                $model->load($post);
                $model->validate();
                if (!$model->hasErrors()) {
                    $res = $model->updateProfile();
                    return ['statusCode'=>true];
                }else{
                    return ['statusCode'=> false, 'parameters' => $model->errors];
                }
            }
            return $this->renderAjax('user/profile', [
                'model' => $model
            ]);
        }
        return $this->render('user/profile', [
            'model' => $model
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
