<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/8/2015
 * Time: 10:14 AM
 */

namespace frontend\models;
use common\components\Util;
use vsoft\ad\models\AdProduct;
use vsoft\ad\models\AdProductSaved;
use vsoft\ad\models\AdProductSavedSearch;
use vsoft\tracking\models\base\AdProductFinder;
use vsoft\tracking\models\base\AdProductShare;
use vsoft\tracking\models\base\AdProductVisitor;
use vsoft\tracking\models\AdProductVisitorSearch;
use vsoft\tracking\models\base\ChartStats;
use Yii;
use yii\base\Component;
use yii\helpers\Url;
use yii\mongodb\Query;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class Chart extends Component
{
    const DATE_FORMAT = 'd-m-Y';
    const TYPE_VISITOR = 1;
    const TYPE_FINDER = 2;
    const TYPE_SAVED = 3;

    public static function filter()
    {
        return [
            'week' => -6,
            '2week' => -13,
            'month' => -30,
            'quarter' => -90,
        ];
    }

    public static function find()
    {
        return Yii::createObject(Chart::className());
    }

    // finder
    public function getDataFinder($pid, $from, $to, $limit=null, $last_id=null){
//        echo "<pre>";
//        print_r(date('d-m-Y H:i:s', $from));
//        print_r(date('d-m-Y H:i:s', $to));
//        echo "<pre>";
//        exit();
        $query = new Query;
        $query->from(AdProductFinder::collectionName())
            ->where(['between', 'time', $from, $to])
            ->andWhere(['product_id' => $pid]);
        if(!empty($last_id)) {
            $oid = new \MongoId($last_id);
            $query = $query->andWhere(['<', '_id', $oid]);
        }
        if(!empty($limit)){
            $query = $query->limit($limit);
        }
        return $query->orderBy('_id DESC')->all();
//        $dateRange = Util::me()->dateRange($from, $to, '+1 day', self::DATE_FORMAT);
//        $defaultData = array_map(function ($key, $date) {
//            return ['y' => 0];
//        }, array_keys($dateRange), $dateRange);
//
//        $pushDataToChart = $this->pushDataToChart($adProductFinders, $defaultData, $dateRange, 'finders', $pid);
//        $result = array_merge($pushDataToChart, ['from' => $from, 'to' => $to, 'pid' => $pid]);
//        return $result;
//        if(count($adProductFinders) > 0){
//            return $this->pushDataToChart($adProductFinders, $defaultData, $dateRange, 'finders');
//        }
//        return false;
    }

    // visitor
    public function getDataVisitor($pid, $from, $to, $limit=null, $last_id=null){
        $query = new Query;
        $query->from(AdProductVisitor::collectionName())
            ->where(['between', 'time', $from, $to])
            ->andWhere(['product_id' => $pid]);
        if(!empty($last_id)) {
            $oid = new \MongoId($last_id);
            $query = $query->andWhere(['<', '_id', $oid]);
        }
        if(!empty($limit)){
            $query = $query->limit($limit);
        }
        return $query->orderBy('_id DESC')->all();

//        $adProductVisitors = AdProductVisitor::find()->where(['between', 'time', $from, $to])->andWhere(['product_id' => (int)$pid])->orderBy('time DESC')->all();
//        $dateRange = Util::me()->dateRange($from, $to, '+1 day', self::DATE_FORMAT);
//        $defaultData = array_map(function ($key, $date) {
//            return ['y' => 0];
//        }, array_keys($dateRange), $dateRange);
//
//        return $this->pushDataToChart($adProductVisitors, $defaultData, $dateRange, 'visitors', $pid);
//        if(count($adProductVisitors) > 0){
//            return $this->pushDataToChart($adProductVisitors, $defaultData, $dateRange, 'visitors', $pid);
//        }
//        return false;
    }

    public function getDataShare($pid, $from, $to, $limit=null, $last_id=null){
        $query = new Query;
        $query->from(AdProductShare::collectionName())
            ->where(['between', 'time', $from, $to])
            ->andWhere(['product_id' => $pid]);
        if(!empty($last_id)) {
            $oid = new \MongoId($last_id);
            $query = $query->andWhere(['<', '_id', $oid]);
        }
        if(!empty($limit)){
            $query = $query->limit($limit);
        }
        return $query->orderBy('_id DESC')->all();
//        $adProductShares = AdProductShare::find()->where(['between', 'time', $from, $to])->andWhere(['product_id' => (int)$pid])->orderBy('time DESC')->all();
//        $dateRange = Util::me()->dateRange($from, $to, '+1 day', self::DATE_FORMAT);
//        $defaultData = array_map(function ($key, $date) {
//            return ['y' => 0];
//        }, array_keys($dateRange), $dateRange);
//
//        return $this->pushDataToChart($adProductShares, $defaultData, $dateRange, 'shares', $pid);
//        if(count($adProductShares) > 0){
//            return $this->pushDataToChart($adProductShares, $defaultData, $dateRange, 'shares', $pid);
//        }
//        return false;
    }

    // saved
    public function getDataSaved($pid, $from, $to, $limit=null, $last_id=null){
        $query = AdProductSaved::find()->where(['between', 'saved_at', $from, $to])
            ->andWhere(['product_id' => $pid])
            ->andWhere('saved_at > :sa',[':sa' => 0]);
        if(!empty($last_id)) {
            $oid = new \MongoId($last_id);
            $query = $query->andWhere(['<', '_id', $oid]);
        }
        if(!empty($limit)){
            $query = $query->limit($limit);
        }
        return $query->orderBy('saved_at DESC')->all();

//        $dateRange = Util::me()->dateRange($from, $to, '+1 day', self::DATE_FORMAT);
//        $defaultData = array_map(function ($key, $date) {
//            return ['y' => 0];
//        }, array_keys($dateRange), $dateRange);
//
//        return $this->pushDataToChart($adProductSaveds, $defaultData, $dateRange, 'saved', $pid);
//
//        if(count($adProductSaveds) > 0){
//            return $this->pushDataToChart($adProductSaveds, $defaultData, $dateRange, 'saved', $pid);
//        }
//        return false;
    }

    private function pushDataToChart($adProductTypes, $defaultData, $dateRange, $view, $pid){
        $last_id = null;
        if(count($adProductTypes) > 0){
            $tmpDataByPid = [];
            $infoData = [];
            $infoSaved = array();
            foreach($adProductTypes as $k => $item){
                $day = date(self::DATE_FORMAT, $item->time);
                $color = '#00a769';
                $typeChart = 'column';

                if($view == 'finders'){
                    $last_id = $item->_id->{'$id'};
                    $color = '#337ab7';
                    $typeChart = 'column';
                }elseif($view == 'visitors'){
                    $last_id = $item->_id->{'$id'};
                    $color = '#a94442';
                    $typeChart = 'line';
                }elseif($view == 'saved'){
                    $day = date(self::DATE_FORMAT, $item->saved_at);
                    $color = '#00a769';
                    $typeChart = 'line';
                }elseif($view == 'shares'){
                    $last_id = $item->_id->{'$id'};
                    $color = '#8a6d3b';
                    $typeChart = 'line';
                }
                $key = $pid;
                if(empty($tmpDataByPid[$key]['data'])){
                    $tmpDataByPid[$key]['data'] = $defaultData;
                }
                $kDate = array_search($day, $dateRange);
                $counting = $tmpDataByPid[$key]['data'][$kDate]['y'];
                $tmpDataByPid[$key]['data'][$kDate]['y'] = $counting + 1;
                $tmpDataByPid[$key]['data'][$kDate]['url'] = Url::to(['/dashboard/clickchart', 'id'=>$pid, 'date'=>$dateRange[$kDate], 'view'=>$view]);
                $tmpDataByPid[$key]['color'] = $color;
                $tmpDataByPid[$key]['type'] = $typeChart;

                $user_id = $item->user_id;
                $user = Yii::$app->db->cache(function() use($user_id){
                    return User::findIdentity($user_id);
                });
                if($user) {
                    $username = $user->username;
                    $email = empty($user->profile->public_email) ? $user->email : $user->profile->public_email;
                    $avatar = $user->profile->getAvatarUrl();

                    if (array_key_exists($username, $infoSaved)) {
                        $cc = $infoSaved[$username]['count'];
                        $cc = $cc + 1;
                        $infoSaved[$username] = [
                            'count' => $cc,
                            'avatar' => $avatar,
                            'email' => $email
                        ];
                    } else {
                        $infoSaved[$username] = [
                            'count' => 1,
                            'avatar' => $avatar,
                            'email' => $email
                        ];
                    }
                }

                $infoData[$view] = $infoSaved;
            }

        } else {
            foreach($dateRange as $key => $day){
                $key = $pid;
                if(empty($tmpDataByPid[$key]['data'])){
                    $tmpDataByPid[$key]['data'] = $defaultData;
                }
                $kDate = array_search($day, $dateRange);
                $tmpDataByPid[$key]['data'][$kDate]['y'] = 0;
//                $tmpDataByPid[$key]['data'][$kDate]['color'] = '#00a769';

                $infoData[$view] = array();
            }
        }
        return ['dataChart'=>$tmpDataByPid, 'categories'=>$dateRange, 'infoData' => $infoData, 'last_id' => $last_id];
    }

    public function getContacts(){
        $date = Yii::$app->request->get('date');
        $type = Yii::$app->request->get('type');
        $pid = Yii::$app->request->get('pid');
        $from = strtotime($date);
        $to = strtotime('+1 days', strtotime($date));
        if(empty($type)){
            throw new NotFoundHttpException('Not found');
        }
        $provider = [
            1 => [
                'title' => 'Nguyễn Trung Ngạn',
                'phone' => '090903xxxx',
                'time' => date('H:i:s d-m-Y', strtotime('-2days')),
            ],
            2 => [
                'title' => 'Quách Tuấn Lệnh',
                'phone' => '090903xxxx',
                'time' => date('H:i:s d-m-Y', strtotime('-3days')),
            ],
            3 => [
                'title' => 'Quách Tuấn Du',
                'phone' => '090903xxxx',
                'time' => date('H:i:s d-m-Y', strtotime('-5days')),
            ],

        ];
        switch($type){
            case self::TYPE_VISITOR:
                $searchModel = new AdProductVisitorSearch();
                $searchModel->product_id = $pid;
                $provider = $searchModel->search2(Yii::$app->request->queryParams, $from, $to);
                break;
            case self::TYPE_FINDER:
                break;
            case self::TYPE_SAVED:
                $searchModel = new AdProductSavedSearch();
                $searchModel->product_id = $pid;
                $provider = $searchModel->search2(Yii::$app->request->queryParams, $pid, $from, $to);
                break;
        }
        return $provider;

    }

    public function getFinderWithLastTime($id, $filter){
        /*if(empty($useDate)) {
            $finder = AdProductFinder::find()->where(['product_id' => $id])->orderBy('time DESC')->one();
            if (count($finder) > 0)
                $useDate = new \DateTime(date('Y-m-d', $finder->time));
            else
                $useDate = new \DateTime(date('Y-m-d', time()));
        } else {
            $useDate = new \DateTime($useDate);
        }*/
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

        $dateRange = Util::me()->dateRange($from, $to, '+1 day', self::DATE_FORMAT);
        $query = new Query;
        $query->from(ChartStats::collectionName())
            ->where(['product_id' => $id])
            ->andWhere(['IN', 'date', $dateRange]);
        $chart_stats = $query->orderBy('created_at')->all();
        if(count($chart_stats) > 0){
            return $chart_stats;

        }


//        $to = new \DateTime(date('Y-m-d', $from));
//        $t = date_format($to, 'Y-m-d 23:59:59');
//        $dateTo = new \DateTime($t);
//        $to = $dateTo->getTimestamp();
//        echo "<pre>";
//        print_r(date('d-m-Y H:i:s', $from));
//        print_r(date('d-m-Y H:i:s', $to));
//        echo "<pre>";
//        exit();
//        $dataFinders = $this->getDataFinder($id, $from, $to);
//
//        if($dataFinders != false) {
//            $infoDataFinders = empty($dataFinders) ? null : $dataFinders["infoData"];
//            if (!empty($infoDataFinders) && isset($infoDataFinders["finders"])) {
//                $infoDataFinders["from"] = $from;
//                $infoDataFinders["to"] = $to;
//            }
//            return $infoDataFinders;
//        }
        return null;
    }

    public function getVisitorWithLastTime($id, $filter){
        /*if(empty($useDate)) {
            $visitor = AdProductVisitor::find()->where(['product_id' => $id])->orderBy('time DESC')->one();
            if (count($visitor) > 0)
                $useDate = new \DateTime(date('Y-m-d', $visitor->time));
            else
                $useDate = new \DateTime(date('Y-m-d', time()));
        } else {
            $useDate = new \DateTime($useDate);
        }*/
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

        $dataVisitors = $this->getDataVisitor($id, $from, $to);
        $infoDataVisitors = empty($dataVisitors) ? null : $dataVisitors["infoData"];
        if(!empty($infoDataVisitors) && isset($infoDataVisitors["visitors"])){
            $infoDataVisitors["from"] = $from;
            $infoDataVisitors["to"] = $to;
        }
        return $infoDataVisitors;
    }
    public function getShareWithLastTime($id, $filter){
        /*if(empty($useDate)) {
            $share = AdProductShare::find()->where(['product_id' => $id])->orderBy('time DESC')->one();
            if (count($share) > 0)
                $useDate = new \DateTime(date('Y-m-d', $share->time));
            else
                $useDate = new \DateTime(date('Y-m-d', time()));
        } else {
            $useDate = new \DateTime($useDate);
        }*/
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

        $dataShares = $this->getDataShare($id, $from, $to);
        $infoDataShares = empty($dataShares) ? null : $dataShares["infoData"];
        if(!empty($infoDataShares) && isset($infoDataShares["shares"])){
            $infoDataShares["from"] = $from;
            $infoDataShares["to"] = $to;
        }
        return $infoDataShares;
    }

    public function getSavedWithLastTime($id, $filter )
    {
        /*if(empty($useDate)) {
            $saved = AdProductSaved::find()->where(['product_id' => $id])->orderBy('saved_at DESC')->one();
            if (count($saved) > 0)
                $useDate = new \DateTime(date('Y-m-d', $saved->saved_at));
            else
                $useDate = new \DateTime(date('Y-m-d', time()));
        } else {
            $useDate = new \DateTime($useDate);
        }*/
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

        $dataSaved = Chart::find()->getDataSaved($id, $from, $to);
        $infoDataFavourites = empty($dataSaved) ? null : $dataSaved["infoData"];
        if(!empty($infoDataFavourites) && isset($infoDataFavourites["saved"])){
            $infoDataFavourites["from"] = $from;
            $infoDataFavourites["to"] = $to;
        }
        return $infoDataFavourites;
    }
}