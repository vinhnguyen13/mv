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
        return Yii::createObject(Ad::className());
    }

    private function checkLogin(){
        if(Yii::$app->user->isGuest){
            throw new NotFoundHttpException('You must login !');
        }
        return true;
    }

    public function productVisitor(){
        $this->checkLogin();
        if(Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            $elastic = new Elastic();
            $client = $elastic->connect();
            if($client){
                $params = [
                    'index' => 'listing',
                    'type' => 'store',
                    'id' => '28',
                    'body' => [
                        'doc' => [
                            'title' => 'Ứng dụng công nghệ Holongram vào trình diễn dự án tại Việt Nam'
                        ]
                    ]
                ];
            }
        }
    }
}