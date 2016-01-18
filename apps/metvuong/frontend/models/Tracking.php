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
use vsoft\tracking\models\AdProductVisitor;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use vsoft\news\models\CmsShow;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class Tracking extends Component
{
    const INDEX = 'listing';
    const TYPE = 'tracking';
    const DATE_FORMAT = 'd-m-Y';

    protected $elastic = null;
    protected $client = null;
    protected $dataChart = [];

    /**
     * __construct
     */
    public function __construct(){
        $this->elastic = new Elastic();
        if(empty($this->client)){
            $this->client = $this->elastic->connect();
        }
    }
    /**
     * @return mixed
     */
    public static function find()
    {
        return Yii::createObject(Tracking::className());
    }

    private function checkAccess(){
        if(Yii::$app->user->isGuest){
            throw new NotFoundHttpException('You must login !');
        }
        return true;
    }

    public function productVisitor($uid, $pid, $time = null){
        $time = !empty($time) ? $time : time();
        $query = AdProductVisitor::find();
        $query->andFilterWhere(['between', 'time', strtotime(date("d-m-Y 00:00:01", $time)), strtotime(date("d-m-Y 59:59:59", $time))]);
        $query->andWhere(['user_id' => $uid]);
        $query->andWhere(['product_id' => $pid]);
        if(($adProductVisitor = $query->one())===null){
            $adProductVisitor = new AdProductVisitor();
            $adProductVisitor->user_id = $uid;
            $adProductVisitor->product_id = $pid;
            $adProductVisitor->count = 1;
        }else{
            $adProductVisitor->count++;
        }
        $adProductVisitor->time = $time;
        $adProductVisitor->save();
        return $adProductVisitor;




        $this->checkAccess();
        $today = date(self::DATE_FORMAT, $time);
        $time = !empty($time) ? $time : time();
        $params = [
            'index' => self::INDEX,
            'type' => self::TYPE,
            'id' => $uid.'_'.$pid.'_'.$today,
        ];

        try{
            if(!empty($this->client) && $this->client->transport->getConnection()->ping()){
                $user = User::findOne($uid);
                $product = AdProduct::findOne($pid);
                $exist = $this->elastic->findOne(self::INDEX, self::TYPE, $uid.'_'.$pid.'_'.$today);
                if(!empty($exist)) {
                    $body = [
                        'doc' => ['count'=>$exist['count']++]
                    ];
                    $params = ArrayHelper::merge($params, [ 'body' => $body]);
                    $response = $this->client->update($params);
                }else{
                    $body = ['uid'=> $uid, 'u_name'=>!empty($user->profile->name) ? $user->profile->name : $user->email, 'pid'=> $pid, 'p_name'=> $product->getAddress(), 'time'=>$time, 'day'=>$today, 'count'=>1];
                    $params = ArrayHelper::merge($params, [ 'body' => $body]);
                    $response = $this->client->index($params);
                }
                return $response;
            }
            return false;
        }catch(Exception $ex){
            throw new NotFoundHttpException('Service error.');
        }

    }


    public function getProductTracking($from, $to, $pids = []){
        $filtered = array();
        $filtered['filter'] = [
            'range' => [
                'time' => [
                    "gte" => $from,
                    "lte" => $to
                ]
            ],

        ];
        if(!empty($pids)){
            $filtered['query'] = [
                "terms"=> [
                    "pid"=> $pids,
                ]
            ];
        }
        $params = [
            'index' => self::INDEX,
            'type' => self::TYPE,
            "size" => 100,
            "from" => 0,
            'body' => [
                'query' => [
                    'filtered' => $filtered
                ],
            ]
        ];
        try{
            if($this->client->transport->getConnection()->ping()){
                $results = $this->client->search($params);
                if(!empty($results['hits']['hits'])){
                    return $results;
                }
                return false;
            }
        }catch(Exception $ex){
            throw new NotFoundHttpException('Service error.');
        }
    }

    public function parseTracking($from, $to, $pids = []){
        $query = AdProductVisitor::find();
        $query->andFilterWhere(['between', 'time', $from, $to]);
//        $query->andWhere(['>', 'time', $from]);
//        $query->andWhere(['<', 'time', $to]);
        if(!empty($pids)){
            $query->where(['user_id' => ['$in' => $pids],]);
        }
        $adProductVisitors = $query->all();
        $dateRange = Util::me()->dateRange($from, $to, '+1 day', self::DATE_FORMAT);
        $tmpDefaultData = array_map(function ($key, $date) {
            return ['y' => 0,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact', 'date'=>$date])];
        }, array_keys($dateRange), $dateRange);;

        if(!empty($adProductVisitors)){
            $tmpDataByPid = [];
            foreach($adProductVisitors as $k => $item){
                $day = date(self::DATE_FORMAT, $item->time);
                $product = AdProduct::getDb()->cache(function ($db) use ($item) {
                    return AdProduct::find()->where(['id' => $item->product_id])->one();
                });
                $key = $item->product_id;
                if(empty($tmpDataByPid[$key]['data'])){
                    $tmpDataByPid[$key]['data'] = $tmpDefaultData;
                }
                $kDate = array_search($day, $dateRange);
                $tmpDataByPid[$key]['data'][$kDate]['y']++;
                $tmpDataByPid[$key]['name'] = $product->getAddress();
            }
            return ['dataChart'=>$tmpDataByPid, 'categories'=>$dateRange];
        }
        return false;
    }
}