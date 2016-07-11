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
use vsoft\express\components\ImageHelper;
use vsoft\news\models\Status;
use vsoft\tracking\models\base\AdProductFinder;
use vsoft\tracking\models\base\AdProductVisitor;
use vsoft\tracking\models\base\ChartStats;
use Yii;
use yii\data\Pagination;
use yii\db\mssql\PDO;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use vsoft\news\models\CmsShow;
use yii\mongodb\Query;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\web\View;
use frontend\components\Controller;
use vsoft\ad\models\AdProduct;
use frontend\models\Transaction;
use vsoft\ec\models\EcStatisticView;

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
    
    public function actionAcceptViewStatistics() {
    	$statisticView = Yii::$app->user->identity->statisticView;
    	$now = time();
    	
    	if(!($redirect = \Yii::$app->request->get('redirect'))) {
    		$redirect = Url::to(['/dashboard/ad', 'username'=> Yii::$app->user->identity->getUsername()]);
    	}
    	
    	if($statisticView) {
    		if($statisticView->end_at < $now) {
    			$balance = Yii::$app->user->identity->balance;
    			
    			if($balance->amount >= EcStatisticView::CHARGE) {
    				$transaction = $balance->getDb()->beginTransaction();
    				try {
    					$statisticView->start_at = $now;
    					$statisticView->end_at = $now + (EcStatisticView::LIMIT_DAY * 86400);
    					$statisticView->save();
    					
    					$balance->amount -= EcStatisticView::CHARGE;
    					$balance->save();
    					
    					$transaction_code = md5(uniqid(rand(), true));
    					Transaction::me()->saveTransaction($transaction_code, [
    							'code'=>$transaction_code,
    							'user_id'=>Yii::$app->user->identity->id,
    							'object_id'=>$statisticView->id,
    							'object_type'=>Transaction::OBJECT_TYPE_DASHBOARD,
    							'amount'=>-EcStatisticView::CHARGE,
    							'balance'=>$balance->amount,
    							'status'=>Transaction::STATUS_SUCCESS,
    					]);
    					 
    					$transaction->commit();
    				} catch(Exception $e) {
    					$transaction->rollback();
    				}
    			}
    		}
    	} else {
    		$balance = Yii::$app->user->identity->balance;
    		
    		if($balance->amount >= EcStatisticView::CHARGE) {
    			$transaction = $balance->getDb()->beginTransaction();
    			
    			try {
    				$statisticView = new EcStatisticView();
	    			$statisticView->start_at = $now;
	    			$statisticView->end_at = $now + (EcStatisticView::LIMIT_DAY * 86400);
	    			$statisticView->user_id = Yii::$app->user->identity->id;
    				$statisticView->save();
    				
    				$balance->amount -= EcStatisticView::CHARGE;
    				$balance->save();
    				
    				$transaction_code = md5(uniqid(rand(), true));
    				Transaction::me()->saveTransaction($transaction_code, [
    						'code'=>$transaction_code,
    						'user_id'=>Yii::$app->user->identity->id,
    						'object_id'=>$statisticView->id,
    						'object_type'=>Transaction::OBJECT_TYPE_DASHBOARD,
    						'amount'=>-EcStatisticView::CHARGE,
    						'balance'=>$balance->amount,
    						'status'=>Transaction::STATUS_SUCCESS,
    				]);
    			
    				$transaction->commit();
    			} catch(Exception $e) {
    				$transaction->rollback();
    			}
    		} else {
    			return $this->redirect(Url::to(['/payment/index', 'redirect' => $redirect]));
    		}
    	}
    	
    	return $this->redirect($redirect);
    }

    public function actionStatistics()
    {
        $this->view->params = ArrayHelper::merge(['noFooter' => true, 'menuDashboard' => true, 'isDashboard' => true], $this->view->params);

        if (Yii::$app->user->isGuest) {
            return $this->redirect(Url::to(['member/login']));
        }

        $id = (int)Yii::$app->request->get("id");
        $product = Yii::$app->db->cache(function() use($id){
            return AdProduct::findOne($id);
        });

        if($product && Yii::$app->user->id != $product->user_id)
            $this->goHome();
        
        $statisticView = Yii::$app->user->identity->statisticView;
        $balance = Yii::$app->user->identity->balance;
        $message = '';
        
        if(!$statisticView) {
        	$message = 'Sử dụng trình thống kê thông minh của MetVuong sẽ giúp bạn biết được những khách hàng tiềm năng và có thể liên hệ trực tiếp với họ. Giúp bạn theo dõi được tính hiệu quả của tin đăng từ đó có thể cải thiện tin đăng và nhanh chóng bán được sản phẩm.';
        } else if($statisticView->end_at < time()) {
        	$message = 'Thời gian xem thống kê đã hết hạn, bạn cần phải nạp phí để tiếp tục.';
        }
        
        if($message) {
        	return $this->render('statistics/notify', [
        		'balance' => $balance,
        		'statisticView' => $statisticView,
        		'message' => $message
        	]);
        }

        $filter = Yii::$app->request->get("filter");
        if(empty($filter) || !isset(Chart::filter()[$filter]))
            $filter = "week";

        $useDate = new \DateTime(date('Y-m-d', time()));
        if(!isset(Chart::filter()[$filter]))
            return null;

        $days = Chart::filter()[$filter]." days";
        $f = date_format($useDate, 'Y-m-d 00:00:00');
        $dateFrom = new \DateTime($f);
        $from = strtotime($days, $dateFrom->getTimestamp());

        $t = date_format($useDate, 'Y-m-d 23:59:59');
        $dateTo = new \DateTime($t);
        $to = $dateTo->getTimestamp();

        $dateRange = Util::me()->dateRange($from, $to, '+1 day', Chart::DATE_FORMAT);
        $chart_stats = Chart::find()->getChartStats($id, $dateRange);

        $finders = null;
        $visitors = null;
        $favorites = null;
        $shares = null;

        $search_count = 0;
        $click_count = 0;
        $fav_count = 0;
        $share_count= 0;

        foreach($dateRange as $kDate => $d) {
            $finders['data'][$kDate]['y'] = 0;
            $visitors['data'][$kDate]['y'] = 0;
            $favorites['data'][$kDate]['y'] = 0;
            $shares['data'][$kDate]['y'] = 0;
            if (count($chart_stats) > 0) {
                foreach ($chart_stats as $stats) {
                    if (isset($stats['date']) && $stats['date'] == $d) {
                        if (isset($stats['search'])) {
                            $finders['data'][$kDate]['y'] = $stats['search'];
                            $finders['data'][$kDate]['url'] = Url::to(['/dashboard/clickchart', 'id' => $id, 'date' => $d, 'view' => 'finders']);
                            $finders['color'] = '#337ab7';
                            $finders['type'] = 'column';
                            $search_count = $search_count + $stats['search'];
                        }
                        if (isset($stats['visit'])) {
                            $visitors['data'][$kDate]['y'] = $stats['visit'];
                            $visitors['data'][$kDate]['url'] = Url::to(['/dashboard/clickchart', 'id' => $id, 'date' => $d, 'view' => 'visitors']);
                            $visitors['color'] = '#a94442';
                            $visitors['type'] = 'line';
                            $click_count = $click_count + $stats['visit'];
                        }
                        if (isset($stats['favorite'])) {
                            $favorites['data'][$kDate]['y'] = $stats['favorite'];
                            $favorites['data'][$kDate]['url'] = Url::to(['/dashboard/clickchart', 'id' => $id, 'date' => $d, 'view' => 'saved']);
                            $favorites['color'] = '#00a769';
                            $favorites['type'] = 'line';
                            $fav_count = $fav_count + $stats['favorite'];

                        }
                        if (isset($stats['share'])) {
                            $shares['data'][$kDate]['y'] = $stats['share'];
                            $shares['data'][$kDate]['url'] = Url::to(['/dashboard/clickchart', 'id' => $id, 'date' => $d, 'view' => 'shares']);
                            $shares['color'] = '#8a6d3b';
                            $shares['type'] = 'line';
                            $share_count = $share_count + $stats['share'];
                        }
                    }
                }
            }
        }

        return $this->render('statistics/index', [
            'product' => $product,
            'visitors' => $visitors,
            'finders' => $finders,
            'favorites' => $favorites,
            'shares' => $shares,
            'search_count' => $search_count,
            'click_count' => $click_count,
            'fav_count' => $fav_count,
            'share_count' => $share_count,
            'from' => $from,
            'to' => $to,
            'filter' => $filter,
            'categories' => $dateRange
        ]);
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
            $view = Yii::$app->request->get('view', 'finders');
            $total = Yii::$app->request->get('total');

//            $dateArr = explode('/', $dateParam);
//            $date = $dateArr[2]."-".$dateArr[1]."-".$dateArr[0];
//            $view = Yii::$app->request->get('view', '_partials/finder');
            $useDate = new \DateTime($dateParam);
            $f = date_format($useDate, 'Y-m-d 00:00:00');
            $dateFrom = new \DateTime($f);
            $from = $dateFrom->getTimestamp();

            $t = date_format($useDate, 'Y-m-d 23:59:59');
            $dateTo = new \DateTime($t);
            $to = $dateTo->getTimestamp();

            $data = null;
            if($view == "finders") {
                $data = Chart::find()->getDataFinder($id, $from, $to, Chart::LIMIT_ITEM);
            }
            if ($view == "visitors"){
                $data = Chart::find()->getDataVisitor($id, $from, $to, Chart::LIMIT_ITEM);
            }
            if ($view == "saved"){
                $data = Chart::find()->getDataSaved($id, $from, $to, Chart::LIMIT_ITEM);
            }
            if ($view == "shares"){
                $data = Chart::find()->getDataShare($id, $from, $to, Chart::LIMIT_ITEM);
            }
            return $this->renderAjax('chart/_partials/listContact',['view' => $view, 'data' => $data, 'totalUser' => $total, 'dateParam' => $dateParam, 'from' => $from, 'to' => $to, 'pid' => $id]);
        }
        return false;
    }

    public function actionClickchartLoadMore()
    {
        if(Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_HTML;
            $view = Yii::$app->request->get('view', 'finders');
            $pid = (int)Yii::$app->request->get('pid');
            $from = (int)Yii::$app->request->get('from');
            $to = (int)Yii::$app->request->get('to');
            $last_id = Yii::$app->request->get('last_id');
            $data = null;
            if($view == "finders") {
                $data = Chart::find()->getDataFinder($pid, $from, $to, Chart::LIMIT_ITEM, $last_id);
            }
            if ($view == "visitors"){
                $data = Chart::find()->getDataVisitor($pid, $from, $to, Chart::LIMIT_ITEM, $last_id);
            }
            if ($view == "saved"){
                $data = Chart::find()->getDataSaved($pid, $from, $to, Chart::LIMIT_ITEM, $last_id);
            }
            if ($view == "shares"){
                $data = Chart::find()->getDataShare($pid, $from, $to, Chart::LIMIT_ITEM, $last_id);
            }
            $html = null;
            $count_data = count($data) > 0 ? count($data) : 0;
            if($count_data > 0){
                return $this->renderAjax('chart/_partials/listContact_item',['view' => $view, 'data' => $data]);
            }
            return $html;
        }
        return null;
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
        $query = Transaction::find()->where('user_id = :_uid',[':_uid' => Yii::$app->user->id]);
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count]);
        $pagination->defaultPageSize = 10;
        $transactions = $query->offset($pagination->offset)->limit($pagination->limit)
            ->orderBy(['id' => SORT_DESC])->all();
        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('payment/index', ['transactions' => $transactions, 'pagination' => $pagination]);
        }else{
            return $this->render('payment/index', ['transactions' => $transactions, 'pagination' => $pagination]);
        }
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
    							'amount'=>-AdProduct::CHARGE_POST,
    							'balance'=>$balance->amount,
    							'status'=>Transaction::STATUS_SUCCESS,
    					]);
    						
    					$template = $this->renderPartial('/dashboard/ad/list', ['products' => [$product]]);
						return ['success' => true, 'amount' => $balance->amount, 'message' => \Yii::t("listing", "Tin đã được gia hạn thành công."), 'template' => $template];
    				} else {
    					return ['success' => false, 'message' => \Yii::t("listing", "Bạn không đủ keys để thực hiện thao tác này, vui lòng nạp thêm keys.")];
    				}
    			}
    		}
    	}
    }

    public function actionInvite()
    {
        $this->view->params = ArrayHelper::merge(['noFooter' => true, 'menuInvite' => true, 'isDashboard' => true], $this->view->params);
        $this->checkAccess();
        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('invite/index');
        } else {
            return $this->render('invite/index');
        }
    }

    public function actionFavorite()
    {
        $this->view->params = ArrayHelper::merge(['noFooter' => true, 'menuFavorite' => true, 'isDashboard' => true], $this->view->params);
        $this->checkAccess();
        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('favorite/index');
        } else {
            return $this->render('favorite/index');
        }
    }

    public function actionSyncFavorite($pid){
        return Tracking::syncFavorite($pid);
    }

}
