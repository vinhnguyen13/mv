<?php

namespace frontend\controllers;
use common\components\Util;
use dektrium\user\Mailer;
use Elasticsearch\ClientBuilder;
use frontend\components\Controller;
use frontend\models\Elastic;
use frontend\models\Tracking;
use GuzzleHttp\Ring\Client\CurlHandler;
use vsoft\tracking\models\base\AdProductVisitor;
use vsoft\user\models\User;
use Yii;
use yii\db\mssql\PDO;
use yii\helpers\Url;
use vsoft\news\models\CmsShow;
use vsoft\ad\models\AdBuildingProject;

class ChatController extends Controller
{
    public $layout = '@app/views/layouts/layout';

    public function actionIndex(){
        return $this->render('index');
    }
}
