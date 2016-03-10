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
use vsoft\tracking\models\base\AdProductVisitor;
use vsoft\tracking\models\AdProductVisitorSearch;
use Yii;
use yii\base\Component;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class Chart extends Component
{
    const DATE_FORMAT = 'd/m/Y';
    const TYPE_VISITOR = 1;
    const TYPE_FINDER = 2;
    const TYPE_SAVED = 3;

    public static function find()
    {
        return Yii::createObject(Chart::className());
    }

    // finder
    public function getDataFinder($pid, $from, $to){
//        echo "<pre>";
//        print_r(date('d-m-Y H:i:s', $from));
//        print_r(date('d-m-Y H:i:s', $to));
//        echo "<pre>";
//        exit();
        $adProductFinders = AdProductFinder::find()->where(['between', 'time', $from, $to])->andWhere(['product_id' => (int)$pid])->orderBy('time DESC')->all();
        $dateRange = Util::me()->dateRange($from, $to, '+1 day', self::DATE_FORMAT);
        $defaultData = array_map(function ($key, $date) {
            return ['y' => 0];
        }, array_keys($dateRange), $dateRange);
        if(count($adProductFinders) > 0){
            return $this->pushDataToChart($adProductFinders, $defaultData, $dateRange, 'finders');
        }
        return false;
    }

    // visitor
    public function getDataVisitor($pid, $from, $to){
        $adProductVisitors = AdProductVisitor::find()->where(['between', 'time', $from, $to])->andWhere(['product_id' => (int)$pid])->orderBy('time DESC')->all();
        $dateRange = Util::me()->dateRange($from, $to, '+1 day', self::DATE_FORMAT);
        $defaultData = array_map(function ($key, $date) {
            return ['y' => 0];
        }, array_keys($dateRange), $dateRange);
        if(count($adProductVisitors) > 0){
            return $this->pushDataToChart($adProductVisitors, $defaultData, $dateRange, 'visitors');
        }
        return false;
    }

    // saved
    public function getDataSaved($pid, $from, $to){
        $adProductSaveds = AdProductSaved::find()->where(['between', 'saved_at', $from, $to])
            ->andWhere(['product_id' => $pid])
            ->andWhere('saved_at > :sa',[':sa' => 0])
            ->orderBy('saved_at DESC')->all();

        $dateRange = Util::me()->dateRange($from, $to, '+1 day', self::DATE_FORMAT);
        $defaultData = array_map(function ($key, $date) {
            return ['y' => 0];
        }, array_keys($dateRange), $dateRange);
        if(count($adProductSaveds) > 0){
            return $this->pushDataToChart($adProductSaveds, $defaultData, $dateRange, 'saved');
        }
        return false;
    }

    private function pushDataToChart($adProductTypes, $defaultData, $dateRange, $view){
        if(!empty($adProductTypes)){
            $tmpDataByPid = [];
            $infoData = [];
            $infoSaved = array();
            foreach($adProductTypes as $k => $item){
                $day = date(self::DATE_FORMAT, $item->time);
                if($view == "saved")
                    $day = date(self::DATE_FORMAT, $item->saved_at);
//                $product = AdProduct::getDb()->cache(function ($db) use ($item) {
//                    return AdProduct::find()->where(['id' => $item->product_id])->one();
//                });
                $key = $item->product_id;
                if(empty($tmpDataByPid[$key]['data'])){
                    $tmpDataByPid[$key]['data'] = $defaultData;
                }
                $kDate = array_search($day, $dateRange);
                $tmpDataByPid[$key]['data'][$kDate]['y']++;
                $tmpDataByPid[$key]['data'][$kDate]['color'] = '#00a769';
//                $tmpDataByPid[$key]['data'][$kDate]['url'] = Url::to(['/user-management/chart', 'view'=>'_partials/listContact', 'date'=>$day, 'pid'=>$key, 'type'=>$type]);
//                $tmpDataByPid[$key]['name'] = $product->getAddress();

                $user = User::findIdentity($item->user_id);
                $username = $user->username;
                $email = empty($user->profile->public_email) ? $user->email : $user->profile->public_email;
                $avatar = $user->profile->getAvatarUrl();
                if(array_key_exists($username, $infoSaved)){
                    $c =  $infoSaved[$username]['count'];
                    $c = $c+1;
                    $infoSaved[$username] = [
                        'count' => $c,
                        'avatar' => $avatar,
                        'email' => $email
                    ];
                } else
                    $infoSaved[$username] = [
                        'count' => 1,
                        'avatar' => $avatar,
                        'email' => $email
                    ];

                $infoData[$view] = $infoSaved;
            }
            return ['dataChart'=>$tmpDataByPid, 'categories'=>$dateRange, 'infoData' => $infoData];
        }
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

    public function getFinderWithLastTime($id, $useDate){
        if(empty($useDate)) {
            $finder = AdProductFinder::find()->where((['product_id' => $id]))->orderBy('time DESC')->one();
            if (count($finder) > 0)
                $useDate = new \DateTime(date('Y-m-d', $finder->time));
            else
                $useDate = new \DateTime(date('Y-m-d', time()));
        } else {
            $useDate = new \DateTime($useDate);
        }

        $f = date_format($useDate, 'Y-m-d 00:00:00');
        $dateFrom = new \DateTime($f);
        $from = strtotime('-6 days', $dateFrom->getTimestamp());

        $t = date_format($useDate, 'Y-m-d 23:59:59');
        $dateTo = new \DateTime($t);
        $to = $dateTo->getTimestamp();

        $dataFinders = $this->getDataFinder($id, $from, $to);
        if($dataFinders != false) {
            $infoDataFinders = empty($dataFinders) ? null : $dataFinders["infoData"];
            if (!empty($infoDataFinders) && isset($infoDataFinders["finders"])) {
                $infoDataFinders["from"] = $from;
                $infoDataFinders["to"] = $to;
            }
            return $infoDataFinders;
        }
        return null;
    }

    public function getVisitorWithLastTime($id, $useDate){
        if(empty($useDate)) {
            $visitor = AdProductVisitor::find()->where((['product_id' => $id]))->orderBy('time DESC')->one();
            if (count($visitor) > 0)
                $useDate = new \DateTime(date('Y-m-d', $visitor->time));
            else
                $useDate = new \DateTime(date('Y-m-d', time()));
        } else {
            $useDate = new \DateTime($useDate);
        }

        $f = date_format($useDate, 'Y-m-d 00:00:00');
        $dateFrom = new \DateTime($f);
        $from = strtotime('-6 days', $dateFrom->getTimestamp());

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

    public function getSavedWithLastTime($id, $useDate)
    {
        if(empty($useDate)) {
            $saved = AdProductSaved::find()->where((['product_id' => $id]))->orderBy('saved_at DESC')->one();
            if (count($saved) > 0)
                $useDate = new \DateTime(date('Y-m-d', $saved->saved_at));
            else
                $useDate = new \DateTime(date('Y-m-d', time()));
        } else {
            $useDate = new \DateTime($useDate);
        }

        $f = date_format($useDate, 'Y-m-d 00:00:00');
        $dateFrom = new \DateTime($f);
        $from = strtotime('-6 days', $dateFrom->getTimestamp());

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