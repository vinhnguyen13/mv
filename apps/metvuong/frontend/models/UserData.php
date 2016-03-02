<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 1/14/2016
 * Time: 1:58 PM
 */
namespace frontend\models;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class UserData extends \vsoft\user\models\base\UserData
{
    const ALERT_OTHER = 'other';
    const ALERT_CHAT = 'chat';

    public static function me()
    {
        return Yii::createObject(self::className());
    }

    public function saveAlert(User $for_user, $type, $data = array()){
        if(empty($for_user)){
            return false;
        }
        $userData = $this;
        if(($userDataExist = self::findOne(['user_id'=>$for_user->id])) !== null){
            $userData = $userDataExist;
        };

        $oldAlert = !empty($userData->alert) ? Json::decode($userData->alert) : [];
        $alert[$type] = !empty($oldAlert[$type]) ? ArrayHelper::merge($oldAlert[$type], $data) : $data;
        /*if($type == self::ALERT_OTHER){

        }elseif($type == self::ALERT_CHAT){

        }*/
        $userData->user_id = $for_user->id;
        $userData->username = $for_user->username;
        $userData->alert = Json::encode($alert);
        $userData->validate();
        if(!$userData->hasErrors()){
            $userData->save();
        }
    }
    public function removeAlert($user_id, $type){
        if(($userData = self::findOne(['user_id'=>$user_id])) !== null){
            $oldAlert = !empty($userData->alert) ? Json::decode($userData->alert) : [];
            if(!empty($oldAlert[$type])){
                unset($oldAlert[$type]);
            }
            $userData->alert = Json::encode($oldAlert);
            $userData->save();
        };
    }


    public function saveSearch($search){
        $this->search = $search;
        $this->validate();
        if(!$this->hasErrors()){
            $this->save();
        }
    }

}