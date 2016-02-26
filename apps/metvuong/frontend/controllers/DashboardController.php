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
use vsoft\ad\models\AdProduct;

class DashboardController extends Controller
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
        $this->redirect('/dashboard/statistics');
    }

    public function actionStatistics()
    {
//        $uids = [1,2,3,4,5];
//        $pids = [1,1,1,1,1,1,1,1,1];
//        $times = Util::me()->dateRange(strtotime('-30 days'), strtotime('+1 days'), '+1 day', 'd-m-Y H:i:s');
//        foreach($pids as $pid){
//            $uid = array_rand(array_flip($uids), 1);
//            $time = array_rand(array_flip($times), 1);
//            $time = strtotime($time);
//            $ck = Tracking::find()->productVisitor($uid, $pid, $time);
//            var_dump($ck);
//        }
        $id = (int)Yii::$app->request->get("id");
        $product = AdProduct::findOne($id);
        $date = Yii::$app->request->get("date");
        if ($date) {
            $useDate = new \DateTime($date);
            $from = strtotime('-7 days', $useDate->getTimestamp());

            $t = date_format($useDate, 'Y-m-d 23:59:59');
            $dateTo = new \DateTime($t);
            $to = $dateTo->getTimestamp();

            $visitors = Tracking::find()->getVisitors($id, $from, $to);
            $finders = Tracking::find()->getFinders($id, $from, $to);
            $favourites = Tracking::find()->getFavourites($id, $from, $to);

            return $this->render('statistics/index', [
                'product' => $product,
                'visitors' => $visitors,
                'finders' => $finders,
                'favourites' => $favourites,
                'view' => '_partials/finder',
                'from' => $from,
                'to' => $to
            ]);
        }


        $useDate = new \DateTime(date('Y-m-d'));
        $from = strtotime('-7 days', $useDate->getTimestamp());

        $t = date_format($useDate, 'Y-m-d 23:59:59');
        $dateTo = new \DateTime($t);
        $to = $dateTo->getTimestamp();

        $visitors = Tracking::find()->getVisitors($id, $from, $to);
        $finders = Tracking::find()->getFinders($id, $from, $to);
        $favourites = Tracking::find()->getFavourites($id, $from, $to);

        return $this->render('statistics/index', [
            'visitors' => $visitors,
            'finders' => $finders,
            'favourites' => $favourites,
            'view' => '_partials/finder',
            'product' => $product,
            'from' => $from,
            'to' => $to
        ]);

    }

    public function actionNotification()
    {
        return $this->render('notification/index', [
        ]);
    }

    public function actionAds()
    {
    	$products = AdProduct::find()->where(['user_id' => Yii::$app->user->id])->with('projectBuilding')->orderBy('`created_at` DESC')->all();
    	
        return $this->render('ad/index', ['products' => $products]);
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

    public function actionChart()
    {
        if(Yii::$app->request->isAjax) {
            $view = Yii::$app->request->get('view', 'index');
            $id = Yii::$app->request->get('id');
            $from = Yii::$app->request->get('from');
            $to = Yii::$app->request->get('to');

            switch($view){
                case '_partials/visitor';

                    break;
                case '_partials/finder';

                    break;
                case '_partials/listContact';

                    break;
            }
            if(!empty($view)){
                return $this->renderAjax('chart/'.$view, ['id' => $id, 'from' => $from, 'to' => $to]);
            }
            return false;
        }
        return false;
    }


}
