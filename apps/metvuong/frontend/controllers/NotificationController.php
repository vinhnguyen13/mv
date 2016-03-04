<?php

namespace frontend\controllers;
use common\components\Util;
use dektrium\user\Mailer;
use frontend\models\Cache;
use frontend\models\Chart;
use frontend\models\Tracking;
use frontend\models\User;
use frontend\models\ProfileForm;
use frontend\models\UserActivity;
use vsoft\express\components\ImageHelper;
use vsoft\tracking\models\base\AdProductFinder;
use vsoft\tracking\models\base\AdProductVisitor;
use Yii;
use yii\db\mssql\PDO;
use yii\helpers\Url;
use vsoft\news\models\CmsShow;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\web\View;
use frontend\components\Controller;
use vsoft\ad\models\AdProduct;

class NotificationController extends Controller
{
    public $layout = '@app/views/layouts/layout';

    public function beforeAction($action)
    {
        $this->view->params['noFooter'] = true;
        if(Yii::$app->user->isGuest){
            $this->redirect(['/member/login']);
        }
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        if(Yii::$app->request->isAjax){
            if(Yii::$app->request->isPost){
                $_id = Yii::$app->request->post('id');
                if(($userActivity = UserActivity::findOne(['_id'=>$_id])) !== null){
                    $userActivity->read();
                }
            }
        }else {
            if($output = Cache::me()->get(Cache::PRE_NOTIFICATION.Yii::$app->user->id)){
                return $output;
            }else{
                $output = $this->render('index', []);
                Cache::me()->set(Cache::PRE_NOTIFICATION.Yii::$app->user->id, $output);
                return $output;
            }
        }
    }
}
