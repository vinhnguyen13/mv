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
use yii\data\Pagination;
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
        $this->checkAccess();
        $this->view->params['noFooter'] = true;
        $this->view->params['menuDashboard'] = true;
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
        $finders = null;
        $visitors = null;
        $favourites = null;

        if(Yii::$app->user->id != $product->user_id)
            $this->goHome();

        $date = Yii::$app->request->get("date");
        if($date == "undefined-undefined-")
            $date = null;

        $finders = Chart::find()->getFinderWithLastTime($id, $date);
        $visitors = Chart::find()->getVisitorWithLastTime($id, $date);
        $favourites = Chart::find()->getSavedWithLastTime($id, $date);

        return $this->render('statistics/index', [
            'product' => $product,
            'visitors' => $visitors,
            'finders' => $finders,
            'favourites' => $favourites,
            'view' => '_partials/finder'
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
                        $classPopupUser = 'popup_enable';
                        if($key_finder == Yii::$app->user->identity->username)
                            $classPopupUser = '';
                        $li = '<li><a class="'.$classPopupUser.'" href="#popup-user-inter" data-email="'.$finder["email"].'" data-ava="'.Url::to($finder['avatar'], true).'">'.
                                    '<img src="'.$finder['avatar'].'"> '.$key_finder.'</a>'.
                                    '<span class="pull-right">'.$finder['count'].'</span></li>';
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
                        $classPopupUser = 'popup_enable';
                        if($key_visitor == Yii::$app->user->identity->username)
                            $classPopupUser = '';
                        $li = '<li><a class="'.$classPopupUser.'" href="#popup-user-inter" data-email="'.$val_visitor["email"].'" data-ava="'.Url::to($val_visitor['avatar'], true).'">'.
                            '<img src="'.$val_visitor['avatar'].'"> '.$key_visitor.'</a>'.
                            '<span class="pull-right">'.$val_visitor['count'].'</span></li>';
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
                        $classPopupUser = 'popup_enable';
                        $li = '<li><a class="'.$classPopupUser.'" href="#popup-user-inter" data-email="'.$val["email"].'" data-ava="'.Url::to($val['avatar'], true).'">'.
                            '<img src="'.$val['avatar'].'"> '.$key.'</a>'.
                            '<span class="pull-right">'.$val['count'].'</span></li>';
                        $html .= $li;
                    }
                }
                return $html;
            }
        }
        return false;
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

        $products = array();
        $last_id = empty(Yii::$app->request->get('last_id')) ? 0 : (int)Yii::$app->request->get('last_id');
        $sell = 0;
        $rent = 0;
    	$query = AdProduct::find()->where(['user_id' => Yii::$app->user->id])->with('projectBuilding');
        if($last_id != 0){
            $query->andWhere('id < :id', [':id' => $last_id]);
        }
        $totalProducts = $query->orderBy(['id' => SORT_DESC])->all();
        $count_total = count($totalProducts);
        if($count_total > 0){
            foreach($totalProducts as $key => $product){
                if($product->type == 1) {
                    $sell += 1;
                }
                else {
                    $rent += 1;
                }
                if($key < 6){
                    array_push($products, $product);
                    $last_id = $product->id;
                }
            }
        }
        return $this->render('ad/index', ['products' => $products, 'last_id' => $last_id, 'total' => $count_total, 'sell' => $sell, 'rent' => $rent
        ]);
    }

    public function actionAdList(){
        if(Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_HTML;
            $type = (int)Yii::$app->request->get('type');
            $products = array();
            $last_id = empty(Yii::$app->request->get('last_id')) ? 0 : (int)Yii::$app->request->get('last_id');
            $query = AdProduct::find()->where(['user_id' => Yii::$app->user->id])->with('projectBuilding');
            if($type > 0){
                $query->andWhere(['type' => $type]);
            }
            if($last_id != 0){
                $query->andWhere('id < :id', [':id' => $last_id]);
            }
            $totalProducts = $query->orderBy(['id' => SORT_DESC])->all();
            $count_total = count($totalProducts);
            if($count_total > 0){
                foreach($totalProducts as $key => $product){
                    if($key < 6){
                        array_push($products, $product);
                        $last_id = $product->id;
                    }
                }
            }
            return $this->renderAjax('/dashboard/ad/list', ['products' => $products, 'type' => $type, 'last_id' => $last_id]);
        }
        return false;
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
            $address = Yii::$app->request->get('address');
            $urlDetail = Yii::$app->request->get('urlDetail');

            return $this->renderAjax('chart/'.$view, ['id' => $id, 'from' => $from, 'to' => $to, 'address' => $address, 'urlDetail' => $urlDetail]);
        }
        return false;
    }


}
