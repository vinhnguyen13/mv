<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/8/2015
 * Time: 10:14 AM
 */

namespace frontend\models;
use kartik\helpers\Enum;
use vsoft\ad\models\AdProductSaved;
use vsoft\express\models\SysEmail;
use vsoft\tracking\models\base\AdProductFinder;
use vsoft\tracking\models\base\AdProductShare;
use vsoft\tracking\models\base\AdProductVisitor;
use vsoft\tracking\models\base\ChartStats;
use vsoft\tracking\models\base\CompareStats;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use vsoft\news\models\CmsShow;
use yii\web\NotFoundHttpException;

class Tracking extends Component
{
    const INDEX = 'listing';
    const TYPE = 'tracking';
    const DATE_FORMAT = 'd-m-Y';
    const DESKTOP = 0;
    const MOBILE = 1;
    const TABLET = 2;

    protected $dataChart = [];

    /**
     * __construct
     */
    public function __construct(){
    }
    /**
     * @return mixed
     */
    public static function find()
    {
        return Yii::createObject(Tracking::className());
    }

    private function checkAccess(){
        if($this->isEnable()) {
            if (Yii::$app->user->isGuest) {
                return false;
            }
            return true;
        }
        return false;
    }

    private function isEnable(){
        if(isset(Yii::$app->params['tracking']['all']) && Yii::$app->params['tracking']['all'] == false){
            return false;
        }
        return true;
    }

    public function getMobileDetect(){
        if(Yii::$app->mobileDetect->isMobile()){
            return self::MOBILE;
        }
        if(Yii::$app->mobileDetect->isTablet()){
            return self::TABLET;
        }
        return self::DESKTOP;
    }

    public function productVisitor($uid, $pid, $time = null, $return = false){
        if($this->checkAccess()) {
            $time = !empty($time) ? $time : time();
            $query = AdProductVisitor::find();
            $query->andFilterWhere(['between', 'time', strtotime(date("d-m-Y 00:00:00", $time)), strtotime(date("d-m-Y 23:59:59", $time))]);
            $query->andWhere(['user_id' => (int)$uid]);
            $query->andWhere(['product_id' => (int)$pid]);
            $query->orderBy('time DESC');
            $adProductVisitor = $query->one();
            if (count($adProductVisitor) > 0) {
                $adProductVisitor->count++;
                $adProductVisitor->save();
            } else {
                $adProductVisitor = new AdProductVisitor();
                $adProductVisitor->user_id = $uid;
                $adProductVisitor->product_id = (int)$pid;
                $adProductVisitor->count = 1;
                $adProductVisitor->time = $time;
                $adProductVisitor->device = $this->getMobileDetect();
                if($adProductVisitor->save()){
                    // save chart_stats
                    $this->saveChartStats($pid, date("d-m-Y", $time), 'visit');
                }
            }
            return !empty($return) ? $adProductVisitor : true;
        }
        return false;
    }
    public function productShare($uid, $pid, $time = null, $type, $return = false){
        if($this->checkAccess()) {
            $time = !empty($time) ? $time : time();
            $query = AdProductShare::find();
            $query->andFilterWhere(['between', 'time', strtotime(date("d-m-Y 00:00:00", $time)), strtotime(date("d-m-Y 23:59:59", $time))]);
            $query->andWhere(['user_id' => (int)$uid, 'product_id' => (int)$pid, 'type'=>$type]);
            $query->orderBy('time DESC');
            $adProductShare = $query->one();
            if (count($adProductShare) > 0) {
                $adProductShare->count++;
                $adProductShare->save();
            } else {
                $adProductShare = new AdProductShare();
                $adProductShare->user_id = (int)$uid;
                $adProductShare->product_id = (int)$pid;
                $adProductShare->count = 1;
                $adProductShare->time = $time;
                $adProductShare->device = $this->getMobileDetect();
                $adProductShare->type = $type;
                if($adProductShare->save()){
                    // save chart_stats
                    $this->saveChartStats($pid, date("d-m-Y", $time), 'share');
                }

            }

            return !empty($return) ? $adProductShare : true;
        }
        return false;
    }

    public function productFinder($uid, $pid, $time = null, $return = false){
        if($this->checkAccess()) {
            $time = !empty($time) ? $time : time();
            $query = AdProductFinder::find();
            $query->andFilterWhere(['between', 'time', strtotime(date("d-m-Y 00:00:00", $time)), strtotime(date("d-m-Y 23:59:59", $time))]);
            $query->andWhere(['user_id' => (int)$uid]);
            $query->andWhere(['product_id' => (int)$pid]);
            $query->orderBy('time DESC');
            $adProductFinder = $query->one();
            if (count($adProductFinder) > 0) {
                $adProductFinder->count++;
                $adProductFinder->save();
            } else {
                $adProductFinder = new AdProductFinder();
                $adProductFinder->user_id = (int)$uid;
                $adProductFinder->product_id = (int)$pid;
                $adProductFinder->count = 1;
                $adProductFinder->time = $time;
                $adProductFinder->device = $this->getMobileDetect();
                if($adProductFinder->save()){
                    // save chart_stats
                    $this->saveChartStats($pid, date("d-m-Y", $time), 'search');
                }
            }
            return !empty($return) ? $adProductFinder : true;
        }
        return false;
    }

    // count all visitor to show in Listings page of user
    public function countVisitors($pid){
    	if($this->isEnable()) {
	        $query = AdProductVisitor::find()->where(['product_id' => (int)$pid])->count();
	        return $query;
    	}
    }

    public function countFinders($pid){
    	if($this->isEnable()) {
	        $query = AdProductFinder::find()->where(['product_id' => (int)$pid])->count();
	        return $query;
    	}
    }

    public function countFavourites($pid){
    	if($this->isEnable()) {
	        $query = (int)AdProductSaved::find()->where(['product_id' => (int)$pid])->andWhere('saved_at > :sa',[':sa' => 0])->count();
	        return $query;
    	}
    }

    public function countShares($pid){
        if($this->isEnable()) {
            $count_share = 0;
            $shares = AdProductShare::find()->where(['product_id' => (int)$pid])->all();
            foreach($shares as $share){
                $count_share = $count_share + $share["count"];
            }
            return $count_share;
        }
        return 0;
    }

    public function saveEmailLog($data){
        $sysEmail = new SysEmail();
        if(!empty($data) && is_array($data)){
            foreach($data as $key=>$value){
                $sysEmail->setAttribute($key, $value);
            }
        }
        $sysEmail->send_time = time();
        $sysEmail->send_ip = Yii::$app->request->userIP;
        $sysEmail->save();
        return $sysEmail;
    }

    public function fromLogo($tr, $tp){
        if(!empty($tr)){
            $sysEmail = SysEmail::findOne($tr);
            if(!empty($sysEmail)){
                $sysEmail->read_time = time();
                $sysEmail->read_ip = Yii::$app->request->userIP;
                $sysEmail->save(false);
                return true;
            }
        }
    }

    public function saveChartStats($pid, $date, $view, $no=1)
    {
        $chart_stats = ChartStats::find()->where(['product_id' => (int)$pid, 'date' => $date])->one();
        if(count($chart_stats) > 0){
            $chart_stats->created_at = strtotime($date);
            switch($view){
                case 'search':
                    $chart_stats->search = $chart_stats->search + $no;
                    $chart_stats->save();
                    break;
                case 'visit':
                    $chart_stats->visit = $chart_stats->visit + $no;
                    $chart_stats->save();
                    break;
                case 'favorite':
                    $chart_stats->favorite = $chart_stats->favorite + $no;
                    $chart_stats->save();
                    break;
                case 'share':
                    $chart_stats->share = $chart_stats->share + $no;
                    $chart_stats->save();
                    break;
                default:
                    break;
            }
        } else {
            $chart_stats = new ChartStats();
            $chart_stats->date = $date;
            $chart_stats->product_id = (int)$pid;
            $chart_stats->created_at = strtotime($date);
            switch($view){
                case 'search':
                    $chart_stats->search = $no;
                    $chart_stats->visit = 0;
                    $chart_stats->share = 0;
                    $chart_stats->save();
                    break;
                case 'visit':
                    $chart_stats->search = 0;
                    $chart_stats->visit = $no;
                    $chart_stats->share = 0;
                    $chart_stats->save();
                    break;
                case 'favorite':
                    $chart_stats->search = 0;
                    $chart_stats->visit = 0;
                    $chart_stats->share = 0;
                    $chart_stats->favorite = $no;
                    $chart_stats->save();
                    break;
                case 'share':
                    $chart_stats->search = 0;
                    $chart_stats->visit = 0;
                    $chart_stats->share = $no;
                    $chart_stats->save();
                    break;
                default:
                    break;
            }

        }
    }

    public static function syncFavorite($pid){
        $date_range = array();
        $adProSaveds = AdProductSaved::find()->where(['product_id'=>(int)$pid])->orderBy(['saved_at' => SORT_ASC])->asArray()->all();
        if(count($adProSaveds) > 0) {
            foreach ($adProSaveds as $ads) {
                $saved_at = (int)$ads['saved_at'];
                $date = date(Chart::DATE_FORMAT, $saved_at);
                if($saved_at == 0)
                    $date = date(Chart::DATE_FORMAT, time());

                if (array_key_exists($date, $date_range)) {
                    $in = $date_range[$date];
                    $date_range[$date] = $in + 1;
                }
                else {
                    $date_range[$date] = ($saved_at == 0 ? 0 : 1);
                }
            }
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if(count($date_range) > 0){
                foreach ($date_range as $key => $val) {
                    $chart_stats = ChartStats::find()->where(['product_id' => (int)$pid, 'date' => $key])->one();
                    if(is_object($chart_stats) && count($chart_stats) > 0) {
                        $chart_stats->favorite = $val;
                        $chart_stats->save();
                    } else {
                        $chart_stats = new ChartStats();
                        $chart_stats->date = $key;
                        $chart_stats->product_id = (int)$pid;
                        $chart_stats->created_at = strtotime($key);
                        $chart_stats->favorite = $val;
                        $chart_stats->save();
                    }
                }
            }
            $transaction->commit();
            return 'synchronized';

        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        return 'failed';
    }

    /**
     * @param null $uid
     * @param null $ip
     * @param $products
     * @param null $time
     * @return array|bool|null|CompareStats|\yii\mongodb\ActiveRecord
     */
    public function compareStats($uid = null, $ip = null, $products, $time = null){
        $time = !empty($time) ? $time : time();
        $query = CompareStats::find();
        $query->andFilterWhere(['between', 'time', strtotime(date("d-m-Y 00:00:00", $time)), strtotime(date("d-m-Y 23:59:59", $time))]);
        $query->andFilterWhere(['or',
            ['=','user_id',$uid],
            ['=','ip',$ip]]);
        $query->orderBy('time DESC');
        $compareStats = $query->one();
        if (!empty($compareStats)) {
            $compareStats->products = $products;
            $compareStats->count++;
            $compareStats->save();
            return $compareStats;
        } else {
            $alias = $ip;
            if(!empty($uid)){
                $user = Yii::$app->db->cache(function() use($uid){
                    return User::findIdentity($uid);
                });
                $alias = $user->username;
            }
            $compareStats = new CompareStats();
            $compareStats->user_id = $uid;
            $compareStats->ip = $ip;
            $compareStats->alias = $alias;
            $compareStats->products = $products;
            $compareStats->time = $time;
            $compareStats->count = 1;
            $compareStats->device = $this->getMobileDetect();
            if($compareStats->save()){
                return $compareStats;
            }
        }

        return false;
    }

}