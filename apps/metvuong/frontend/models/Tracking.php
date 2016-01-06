<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/8/2015
 * Time: 10:14 AM
 */

namespace frontend\models;
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

    public function productVisitor($product_id){
        $this->checkLogin();
        $user_id = Yii::$app->user->id;
        $elastic = new Elastic();
        $client = $elastic->connect();
        $params = [
            'index' => 'listing',
            'type' => 'tracking',
            'id' => $product_id,
        ];


        /****/
        /*
        // Delete doc at /my_index/my_type/my_id
        $response = $client->delete($params);
        echo "<pre>";
        print_r($response);
        echo "</pre>";
        exit;*/
        /****/

        try{
            if($client){
                $exist = $this->productVisitorExist($product_id);
                if(!empty($exist)){
                    $users[] = $user_id;
                    if(!empty($exist[date('d-m-Y')])){
                        array_push($exist[date('d-m-Y')]['data']['users'], $user_id);
                        $users = $exist[date('d-m-Y')]['data']['users'];
                        $users = array_unique($users);
                    }
                    $body = [
                        'doc' => [
                            date('d-m-Y') => [
                                'total'=>count($users),
                                'data' => [
                                    'users' => $users,
                                    'guest' => [$user_id],
                                ]
                            ]
                        ]
                    ];
                }else{
                    $body = [
                        date('d-m-Y') => [
                            'total'=>1,
                            'data' => [
                                'users' => [$user_id],
                                'guest' => [$user_id],
                            ]
                        ]
                    ];

                }
                $params = ArrayHelper::merge($params, [ 'body' => $body]);
                if(!empty($exist)){
                    $response = $client->update($params);
                }else{
                    $response = $client->index($params);
                }
                return $response;
            }
        }catch(Exception $ex){
            throw new NotFoundHttpException('Service error.');
        }

    }

    private function productVisitorExist($product_id){
        $elastic = new Elastic();
        $client = $elastic->connect();
        $params = [
            'index' => 'listing',
            'type' => 'tracking',
            'id' => $product_id,
        ];
        $chk = $client->exists($params);
        if(!empty($chk)){
//            $result = $client->get($params);
            $result = $client->getSource($params);
            return $result;
        }
    }
}