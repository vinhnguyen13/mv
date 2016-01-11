<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/8/2015
 * Time: 10:14 AM
 */

namespace frontend\models;
use vsoft\ad\models\AdProduct;
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
    protected $elastic = null;
    protected $client = null;

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

    private function checkLogin(){
        if(Yii::$app->user->isGuest){
            throw new NotFoundHttpException('You must login !');
        }
        return true;
    }

    public function productVisitor($uid, $pid, $time = null){
        $this->checkLogin();
        $today = date('d-m-Y', $time);
        $time = !empty($time) ? $time : time();
        $params = [
            'index' => self::INDEX,
            'type' => self::TYPE,
            'id' => $uid.'_'.$pid.'_'.$today,
        ];

        try{
            if($this->client){
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
                /*'aggs' => [
                    'tag' => [
                        'terms' => [
                            'field' => 'tags'
                        ],
                    ],
                ],*/

            ]
        ];
        $results = $this->client->search($params);
        if(!empty($results['hits']['hits'])){
            return $results;
        }
        return false;
    }
}