<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/8/2015
 * Time: 10:14 AM
 */

namespace frontend\models;
use vsoft\chat\models\TigUsers;
use Yii;
use yii\base\Component;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class Chat extends Component
{
    const DOMAIN = 'metvuong.com';

    public static function find()
    {
        return Yii::createObject(Chat::className());
    }

    public function getTigUser(){
        if (!Yii::$app->user->isGuest) {
            $user_id = $this->getJid(Yii::$app->user->identity->username);
            if(($tigUser = TigUsers::findOne(['user_id'=>$user_id])) === null){
                $tigUser = new TigUsers();
                $tigUser->user_id = $user_id;
                $tigUser->sha1_user_id = sha1($user_id);
                $tigUser->user_pw = $this->generateKey();
                $tigUser->save();
            }
            return $tigUser;
        }
    }

    public function getJid($username){
        return $username.'@'.self::DOMAIN;
    }

    public function getUsername($jid){
        return str_replace('@'.self::DOMAIN, '', $jid);
    }

    public function getKey(){
        $tigUser = $this->getTigUser();
        return $tigUser->user_pw;
    }

    public function getDomain(){
        return self::DOMAIN;
    }

    public function generateKey(){
        if (!Yii::$app->user->isGuest) {
            $key = Yii::$app->user->identity->getAuthKey();
            return md5($key);
        }
        return false;
    }
}