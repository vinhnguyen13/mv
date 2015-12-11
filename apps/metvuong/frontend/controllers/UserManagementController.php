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
    public $layout = '@app/views/layouts/layout';
    public function actionIndex()
    {
        return $this->render('index', [
        ]);
    }
}
