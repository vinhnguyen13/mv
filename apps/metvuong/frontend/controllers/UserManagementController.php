<?php

namespace frontend\controllers;
use dektrium\user\Mailer;
use vsoft\user\models\User;
use Yii;
use yii\db\mssql\PDO;
use yii\helpers\Url;
use vsoft\news\models\CmsShow;
use vsoft\ad\models\AdBuildingProject;

class UserManagementController extends \yii\web\Controller
{
    public $layout = '@app/views/user-management/layouts/main';
    public function actionIndex()
    {
        $this->redirect('ads');
    }
    public function actionAds()
    {
        return $this->render('ads/index', [
        ]);
    }
    public function actionChart()
    {
        return $this->render('chart/ads', [
        ]);
    }
    public function actionProfile()
    {
        return $this->render('user/profile', [
        ]);
    }
}
