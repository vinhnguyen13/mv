<?php

namespace frontend\controllers;
use dektrium\user\Mailer;
use vsoft\user\models\User;
use Yii;
use yii\db\mssql\PDO;
use yii\helpers\Url;
use vsoft\news\models\CmsShow;
use vsoft\ad\models\AdBuildingProject;
use yii\web\View;

class UserManagementController extends \yii\web\Controller
{
    public $layout = '@app/views/user-management/layouts/main';
    public function actionIndex()
    {
        $this->redirect('ads');
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
    public function actionChart()
    {
        if(Yii::$app->request->isAjax) {
            return $this->renderPartial('chart/ads', [
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
}
