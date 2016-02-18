<?php

namespace frontend\controllers;
use common\components\Util;
use dektrium\user\Mailer;
use frontend\models\Tracking;
use frontend\models\User;
use frontend\models\ProfileForm;
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

    public function actionStatistics($id=1)
    {
        $visitors = Tracking::find()->getVisitors($id);
        $finders = Tracking::find()->getFinders($id);

//        $uids = [5,4,3,2,1];
//        $pids = [1, 2, 4, 3, 5, 6];
//        $times = Util::me()->dateRange(strtotime('-30 days'), strtotime('0 days'), '+1 day', 'd-m-Y H:i:s');
//        foreach($pids as $pid){
//            $uid = array_rand(array_flip($uids), 1);
//            $time = array_rand(array_flip($times), 1);
//            $time = strtotime($time);
//            $ck = Tracking::find()->productVisitor($uid, $pid, $time);
//        }

        return $this->render('statistics/index', [
            'visitors' => $visitors,
            'finders' => $finders,
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
