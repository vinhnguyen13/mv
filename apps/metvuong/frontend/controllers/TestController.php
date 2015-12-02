<?php

namespace frontend\controllers;
use dektrium\user\Mailer;
use vsoft\user\models\User;
use Yii;
use yii\helpers\Url;
use vsoft\news\models\CmsShow;
use common\vendor\vsoft\ad\models\AdBuildingProject;

class TestController extends \yii\web\Controller
{
    public $layout = '@app/views/layouts/search';
    public function actionMail()
    {
        $user = User::findOne(5);
        $mailer = new Mailer();
        $chk = $mailer->sendWelcomeMessage($user, null, true);
        echo "<pre>";
        print_r($chk);
        echo "</pre>";
        exit;
    }

}
