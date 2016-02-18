<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/8/2015
 * Time: 10:14 AM
 */

namespace frontend\models;
use vsoft\tracking\models\base\AdProductFinder;
use vsoft\tracking\models\base\AdProductVisitor;
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
    const DESKTOP = 0;
    const MOBILE = 1;
    const TABLET = 2;

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
        $this->checkAccess();
        $time = !empty($time) ? $time : time();
        $query = AdProductVisitor::find();
        $query->andFilterWhere(['between', 'time', strtotime(date("d-m-Y 00:00:01", $time)), strtotime(date("d-m-Y 23:59:59", $time))]);
        $query->andWhere(['user_id' => $uid]);
        $query->andWhere(['product_id' => $pid]);
        $query->orderBy('time DESC');
        if(($adProductVisitor = $query->one())===null){
            $adProductVisitor = new AdProductVisitor();
            $adProductVisitor->user_id = $uid;
            $adProductVisitor->product_id = $pid;
            $adProductVisitor->count = 1;
        }else{
            $adProductVisitor->count++;
        }
        $adProductVisitor->time = $time;
        $adProductVisitor->device = $this->getMobileDetect();
        $adProductVisitor->save();
        return !empty($return) ? $adProductVisitor : true;
    }

    public function productFinder($uid, $pid, $time = null, $return = false){
        $this->checkAccess();
        $time = !empty($time) ? $time : time();
        $query = AdProductFinder::find();
        $query->andFilterWhere(['between', 'time', strtotime(date("d-m-Y 00:00:01", $time)), strtotime(date("d-m-Y 23:59:59", $time))]);
        $query->andWhere(['user_id' => $uid]);
        $query->andWhere(['product_id' => $pid]);
        $query->orderBy('time DESC');
        if(($adProductFinder = $query->one())===null){
            $adProductFinder = new AdProductFinder();
            $adProductFinder->user_id = $uid;
            $adProductFinder->product_id = $pid;
            $adProductFinder->count = 1;
        }else{
            $adProductFinder->count++;
        }
        $adProductFinder->time = $time;
        $adProductFinder->device = $this->getMobileDetect();
        $adProductFinder->save();
        return !empty($return) ? $adProductFinder : true;
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

    public function getVisitors($pid){
        $query = AdProductVisitor::find()->where(['product_id' => $pid])->all();
        $visitors = array();
        foreach($query as $k => $q){
            $username = User::findIdentity($q->user_id)->username;
            if(array_key_exists($username, $visitors)){
                $visitors[$username]++;
            } else
                $visitors[$username] = 1;
        }
        return $visitors;
    }

    public static function getFinders($pid){
        $query = AdProductFinder::find()->where(['product_id' => $pid])->all();
        $finders = array();
        foreach($query as $k => $q){
            $username = User::findIdentity($q->user_id)->username;
            if(array_key_exists($username, $finders)){
                $finders[$username]++;
            } else
                $finders[$username] = 1;
        }
        return $finders;
    }

}