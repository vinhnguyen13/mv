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
use vsoft\coupon\models\CouponCode;
use vsoft\coupon\models\CouponHistory;
use vsoft\ec\models\EcTransactionHistory;
use vsoft\express\components\ImageHelper;
use vsoft\news\models\Status;
use vsoft\tracking\models\base\AdProductFinder;
use vsoft\tracking\models\base\AdProductVisitor;
use Yii;
use yii\data\Pagination;
use yii\db\mssql\PDO;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use vsoft\news\models\CmsShow;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\web\View;
use frontend\components\Controller;
use vsoft\ad\models\AdProduct;
use frontend\models\Transaction;

class DashboardController extends Controller
{
    public $layout = '@app/views/layouts/layout';

    public function beforeAction($action)
    {
        $this->checkAccess();
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $this->redirect(Url::to(['dashboard/ad']));
    }

    public function actionStatistics()
    {
        $this->view->params = ArrayHelper::merge(['noFooter' => true, 'menuDashboard' => true, 'isDashboard' => true], $this->view->params);
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
        $shares = null;

        if(Yii::$app->user->id != $product->user_id)
            $this->goHome();

        $filter = Yii::$app->request->get("filter");
        if(empty($filter))
            $filter = "week";

        $date = Yii::$app->request->get("date");
        if($date == "undefined-undefined-")
            $date = null;

        $finders = Chart::find()->getFinderWithLastTime($id, $date, $filter);
        $visitors = Chart::find()->getVisitorWithLastTime($id, $date, $filter);
        $shares = Chart::find()->getShareWithLastTime($id, $date, $filter);
        $favourites = Chart::find()->getSavedWithLastTime($id, $date, $filter);

        if(empty($finders) && empty($visitors) && empty($shares) && empty($favourites)) {
            Yii::$app->session->setFlash('danger', Yii::t('statistic', '<div class="text-center">Not found data with filter.</div>'));
            return $this->render('/_systems/_alert', [
                'title'  => Yii::t('statistic', 'Invalid or expired link'),
                'module' => $this->module,
            ]);
        }
//        if(Yii::$app->request->isAjax){
//            return $this->renderAjax('statistics/index', [
//                'product' => $product,
//                'visitors' => $visitors,
//                'finders' => $finders,
//                'favourites' => $favourites,
//                'shares' => $shares,
//                'view' => '_partials/finder'
//            ]);
//        }else{
            if (($search = \frontend\models\Tracking::find()->countFinders($product->id)) === null) {
                $search = 0;
            }
            if (($click = \frontend\models\Tracking::find()->countVisitors($product->id)) === null) {
                $click = 0;
            }
            if (($fav = \frontend\models\Tracking::find()->countFavourites($product->id)) === null) {
                $fav = 0;
            }
            if (($share = \frontend\models\Tracking::find()->countShares($product->id)) === null) {
                $share = 0;
            }
            return $this->render('statistics/index', [
                'product' => $product,
                'visitors' => $visitors,
                'finders' => $finders,
                'favourites' => $favourites,
                'shares' => $shares,
                'view' => '_partials/finder',
                'search_count' => $search,
                'click_count' => $click,
                'fav_count' => $fav,
                'share_count' => $share,
                'from' => $finders["from"],
                'to' => $finders["to"],
                'filter' => $filter
            ]);
//        }
    }

    public function actionUpgrade()
    {
        if(Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $id = (int)Yii::$app->request->get("id");
            $days = (int)Yii::$app->request->get("upgrade-time");
            $total_budget = (int)Yii::$app->request->get("total_budget");
            $product = AdProduct::findOne($id);
            $time = time();
            if(!empty($product)){
                $product->start_date = $time;
                $product->updated_at = $time;
                $end = strtotime("+$days days", $time);
                $product->end_date = $end;
                $product->is_expired = 0;
                $product->save(false);

                // update elastic counter
                $totalType = ($product->type == AdProduct::TYPE_FOR_SELL) ? AdProduct::TYPE_FOR_SELL_TOTAL : AdProduct::TYPE_FOR_RENT_TOTAL;

                AdProduct::updateElasticCounter('city', $product->city_id, $totalType);
                AdProduct::updateElasticCounter('district', $product->district_id, $totalType);

                if($product->ward_id) {
                    AdProduct::updateElasticCounter('ward', $product->ward_id, $totalType);
                }
                if($product->street_id) {
                    AdProduct::updateElasticCounter('street', $product->street_id, $totalType);
                }
                if($product->project_building_id) {
                    AdProduct::updateElasticCounter('project_building', $product->project_building_id, $totalType);
                }
                // end update elastic

                return ['expired' => $product->expired];
            }
        }
        return false;
    }

    public function actionUp()
    {
        if(Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $id = (int)Yii::$app->request->get("id");
            $product = AdProduct::findOne($id);
            if(!empty($product)){
                if($product->updated_at/* < strtotime("-5 days")*/) {
                    $product->updated_at = time();
                    $product->save();
                }
                return $product->attributes;
            }
        }
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

            if($view == "finders") {
                $data = Chart::find()->getDataFinder($id, $from, $to);
                $infoData = empty($data) ? null : $data["infoData"];
                $favourites = empty($infoData["finders"]) ? null : $infoData["finders"];
                $html = "<li>finders</li>";
            } else if ($view == "visitors"){
                $data = Chart::find()->getDataVisitor($id, $from, $to);
                $infoData = empty($data) ? null : $data["infoData"];
                $favourites = empty($infoData["visitors"]) ? null : $infoData["visitors"];
            } else if ($view == "saved"){
                $data = Chart::find()->getDataSaved($id, $from, $to);
                $infoData = empty($data) ? null : $data["infoData"];
                $favourites = empty($infoData["saved"]) ? null : $infoData["saved"];
            }else if ($view == "shares"){
                $data = Chart::find()->getDataShare($id, $from, $to);
                $infoData = empty($data) ? null : $data["infoData"];
                $favourites = empty($infoData["shares"]) ? null : $infoData["shares"];
            }
            return $this->renderAjax('chart/_partials/listContact',['view'=>$view, 'favourites'=>$favourites ]);
        }
        return false;
    }

    public function actionAd()
    {
        $this->view->params = ArrayHelper::merge(['noFooter' => true, 'menuDashboard' => true, 'isDashboard' => true], $this->view->params);
        $this->checkIsMe();
        $products = array();
        $search = array();
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
                $thumb = $product->representImage;
                if($product->projectBuilding) {
                    if(strpos($thumb, "default")){
                        $thumb = $product->projectBuilding->logoUrl;
                    }
                    $tempProduct["label"] = Yii::$app->params['listing_prefix_id'] . $product->id." - ".$product->projectBuilding->name." - ".$product->address;
                    $tempProduct["url"] = Url::to(['/dashboard/statistics', 'id' => $product->id], true);
                    $tempProduct["thumb"] = $thumb;
                } else {
                    $tempProduct["label"] = Yii::$app->params['listing_prefix_id'] . $product->id." - ".$product->address;
                    $tempProduct["url"] = Url::to(['/dashboard/statistics', 'id' => $product->id], true);
                    $tempProduct["thumb"] = $thumb;
                }
                array_push($search, $tempProduct);

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
        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('ad/index', ['products' => $products, 'search' => $search, 'last_id' => $last_id, 'total' => $count_total, 'sell' => $sell, 'rent' => $rent]);
        }else{
            return $this->render('ad/index', ['products' => $products, 'search' => $search, 'last_id' => $last_id, 'total' => $count_total, 'sell' => $sell, 'rent' => $rent]);
        }
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
            return $this->renderAjax('/dashboard/ad/list', ['products' => $products,
                'type' => $type,
                'last_id' => $last_id
            ]);
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

    public function actionPayment()
    {
        $this->view->params = ArrayHelper::merge(['noFooter' => true, 'menuPayment' => true, 'isDashboard' => true], $this->view->params);
        $this->checkAccess();
        $query = EcTransactionHistory::find()->where('user_id = :_uid',[':_uid' => Yii::$app->user->id]);
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count]);
        $pagination->defaultPageSize = 10;
        $transactions = $query->offset($pagination->offset)->limit($pagination->limit)
            ->orderBy(['id' => SORT_DESC])->all();
        return $this->render('payment/index', ['transactions' => $transactions, 'pagination' => $pagination]);
    }

    public function actionCreateTransaction()
    {
        $this->checkAccess();
        $user_id = Yii::$app->user->id;
        $objs = [501,503,516,517,518,520,521,522];
        $obj_id = array_rand(array_flip($objs), 1);
        $obj_type = EcTransactionHistory::OBJECT_TYPE_PRODUCT;
        $amount = array_rand(array_flip([100,200,500,10,20,50]), 1);

//        $act_types = array_keys(EcTransactionHistory::getActionType());
        $action_type = 1;//array_rand(array_flip($act_types), 1);

        $act_details = array_keys(EcTransactionHistory::getActionDetail());
        $action_detail= array_rand(array_flip($act_details), 1);
        if($action_detail == 3) {
            $obj_id = $user_id;
            $obj_type = 3;
        }
        $status = 1;

        $transaction = EcTransactionHistory::createTransaction($user_id, $obj_id, $obj_type, $amount, $action_type, $action_detail, null, $status, 'json');
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['result' => EcTransactionHistory::getObjectType($transaction->object_type)." ".Yii::t('ec', 'Transaction').EcTransactionHistory::getTransactionStatus($transaction->status) ];
    }

    public function actionUpdateExpired($id) {
    	if(Yii::$app->user->identity && Yii::$app->request->isAjax) {
    		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    		
    		$product = AdProduct::findOne($id);
    		if($product && $product->user_id == Yii::$app->user->identity->id) {
    			if($product->is_expired == 1) {
    				$balance = Yii::$app->user->identity->balance;
    
    				if($balance->amount >= AdProduct::CHARGE_POST) {
    					$balance->amount -= AdProduct::CHARGE_POST;
    					$balance->save(false);
    
    					$product->status = AdProduct::STATUS_ACTIVE;
    					$product->end_date = time() + (AdProduct::EXPIRED * 30);
    					$product->is_expired = 0;
    					$product->save(false);

    					$transaction_code = md5(uniqid(rand(), true));
    					Transaction::me()->saveTransaction($transaction_code, [
    							'code'=>$transaction_code,
    							'user_id'=>Yii::$app->user->identity->id,
    							'object_id'=>$product->id,
    							'object_type'=>Transaction::OBJECT_TYPE_UPDATE_EXPIRED,
    							'amount'=>AdProduct::CHARGE_POST,
    							'balance'=>$balance->amount,
    							'status'=>Transaction::STATUS_SUCCESS,
    					]);
    						
    					$template = $this->renderPartial('/dashboard/ad/list', ['products' => [$product]]);
						return ['success' => true, 'message' => \Yii::t("listing", "Tin đã được gia hạn thành công."), 'template' => $template];
    				} else {
    					return ['success' => false, 'message' => \Yii::t("listing", "Bạn không đủ keys để thực hiện thao tác này, vui lòng nạp thêm keys.")];
    				}
    			}
    		}
    	}
    }

    public function actionInvite()
    {
        return $this->render('invite/index');
    }
}
