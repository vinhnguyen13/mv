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
        if (Yii::$app->user->isGuest) {
            return $this->redirect(Url::to(['member/login']));
        }

        $id = (int)Yii::$app->request->get("id");
        $product = AdProduct::findOne($id);

        if(Yii::$app->user->id != $product->user_id)
            $this->goHome();

        $date = Yii::$app->request->get("date");
        if($date == "undefined-undefined-")
            $date = null;

        if($date) { // truong hop chon calendar
            $useDate = new \DateTime($date);
        }
        else { // vao thong ke cua 1 tin dang
            $finder = AdProductFinder::find()->where((['product_id' => $id]))->orderBy('time DESC')->one();
            if(count($finder) > 0)
                $useDate = new \DateTime(date('Y-m-d', $finder->time));
            else
                $useDate = new \DateTime(date('Y-m-d', time()));
        }

        $f = date_format($useDate, 'Y-m-d 00:00:00');
        $dateFrom = new \DateTime($f);
        $from = strtotime('-6 days', $dateFrom->getTimestamp());

        $t = date_format($useDate, 'Y-m-d 23:59:59');
        $dateTo = new \DateTime($t);
        $to = $dateTo->getTimestamp();

        $dataFinders = Chart::find()->getDataFinder($id, $from, $to);
        $infoDataFinders = empty($dataFinders) ? null : $dataFinders["infoData"];
        $finders = empty($infoDataFinders["finders"]) ? null : $infoDataFinders["finders"];

        $dataVisitors = Chart::find()->getDataVisitor($id, $from, $to);
        $infoDataVisitors = empty($dataVisitors) ? null : $dataVisitors["infoData"];
        $visitors = empty($infoDataVisitors["visitors"]) ? null : $infoDataVisitors["visitors"];

        $dataSaved = Chart::find()->getDataSaved($id, $from, $to);
        $infoDataFavourites = empty($dataSaved) ? null : $dataSaved["infoData"];
        $favourites = empty($infoDataFavourites["saved"]) ? null : $infoDataFavourites["saved"];

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

    public function actionClickchart(){
        if(Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_HTML;

            $id = (int)Yii::$app->request->get("id");
            $dateParam = Yii::$app->request->get("date");
            $dateArr = explode('/', $dateParam);
            $date = $dateArr[2]."-".$dateArr[1]."-".$dateArr[0];
            $view = Yii::$app->request->get('view', '_partials/finder');

            $useDate = new \DateTime($date);

            $f = date_format($useDate, 'Y-m-d 00:00:00');
            $dateFrom = new \DateTime($f);
            $from = $dateFrom->getTimestamp();

            $t = date_format($useDate, 'Y-m-d 23:59:59');
            $dateTo = new \DateTime($t);
            $to = $dateTo->getTimestamp();

            if($view == "_partials/finder") {
                $data = Chart::find()->getDataFinder($id, $from, $to);
                $infoData = empty($data) ? null : $data["infoData"];
                $finders = empty($infoData["finders"]) ? null : $infoData["finders"];
                $html = "";
                if(count($finders) > 0)
                    foreach($finders as $key_finder => $finder) {
                        $li = "<li><img src=\"".$finder['avatar']."\" alt=\"".$key_finder."\"><a href=\"/".$key_finder."\">".$key_finder."</a><span class=\"pull-right\">".$finder['count']."</span>";
                        $li .= "</li>";
                        $html .= $li;
                    }
                return $html;
            } else if ($view == "_partials/visitor"){
                $data = Chart::find()->getDataVisitor($id, $from, $to);
                $infoData = empty($data) ? null : $data["infoData"];
                $visitors = empty($infoData["visitors"]) ? null : $infoData["visitors"];
                $html = "";
                if(count($visitors) > 0)
                    foreach ($visitors as $key_visitor => $val_visitor) {
                        $li = "<li><img src=\"" . $val_visitor['avatar'] . "\" alt=\"" . $key_visitor . "\"><a href=\"/" . $key_visitor . "\">" . $key_visitor . "</a><span class=\"pull-right\">" . $val_visitor['count'] . "</span>";
                        $li .= "</li>";
                        $html .= $li;
                    }
                return $html;
            } else if ($view == "_partials/saved"){
                $data = Chart::find()->getDataSaved($id, $from, $to);
                $infoData = empty($data) ? null : $data["infoData"];
                $favourites = empty($infoData["saved"]) ? null : $infoData["saved"];
                $html = "";
                if(count($favourites) > 0) {
                    foreach ($favourites as $key => $val) {
                        $li = "<li><img src=\"".$val['avatar']."\" alt=\"" . $key . "\"><a href=\"/" . $key . "\">" . $key . "</a><span class=\"pull-right\">" . $val['count'] . "</span>";
                        $li .= "</li>";
                        $html .= $li;
                    }
                }
                return $html;
            }
        }
        return false;
    }

    public function actionNotification()
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
                $output = $this->render('notification/index', []);
                Cache::me()->set(Cache::PRE_NOTIFICATION.Yii::$app->user->id, $output);
                return $output;
            }
        }
    }

    public function actionAd()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(Url::to(['member/login']));
        }

        $requestUrl = Yii::$app->request->absoluteUrl;
        $username = Yii::$app->user->identity->username;
        $checkUsername = strpos($requestUrl, $username);
        if($checkUsername == false)
            return $this->goHome();

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
            $view = Yii::$app->request->get('view', '_partials/finder');
            $id = (int)Yii::$app->request->get('id');
            $from = (int)Yii::$app->request->get('from');
            $to = (int)Yii::$app->request->get('to');

            return $this->renderAjax('chart/'.$view, ['id' => $id, 'from' => $from, 'to' => $to]);
        }
        return false;
    }


}
