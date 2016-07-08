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
    const LIMIT_ITEM = 12;

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
    }

    // saved
    public function getDataSaved($pid, $from, $to, $limit=null, $last_id=null){
        $query = AdProductSaved::find()->where(['between', 'saved_at', $from, $to])
            ->andWhere(['product_id' => $pid])
            ->andWhere('saved_at > :sa',[':sa' => 0]);
        if(!empty($last_id)) {
            $query = $query->andWhere(['<', 'saved_at', $last_id]);
        }
        if(!empty($limit)){
            $query = $query->limit($limit);
        }
        return $query->orderBy('saved_at DESC')->asArray()->all();
    }

    public function getChartStats($id, $dateRange)
    {
        Tracking::syncFavorite($id);
        $query = new Query;
        $query->from(ChartStats::collectionName())
            ->where(['product_id' => $id])
            ->andWhere(['IN', 'date', $dateRange]);
        $chart_stats = $query->orderBy('created_at')->all();
        return $chart_stats;
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

}