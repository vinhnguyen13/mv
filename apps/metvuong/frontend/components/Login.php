<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/7/2015
 * Time: 11:16 AM
 */

namespace frontend\components;
use frontend\models\UserData;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\base\Component;
use yii\web\UserEvent;

class Login extends Component
{
    public static function handleAfterLogin(UserEvent $event)
    {
        $event->identity;
        /**
         * test
         * @todo: remove if done
         */
        if(($userData = UserData::findOne(['user_id'=>$event->identity->id])) !== null){
            $alert = $userData->alert;
            if(!empty($alert[UserData::ALERT_OTHER])){
                Yii::$app->session->set("notifyOther",count($alert[UserData::ALERT_OTHER]));
            }
            if(!empty($alert[UserData::ALERT_CHAT])){
                Yii::$app->session->set("notifyChat",count($alert[UserData::ALERT_CHAT]));
            }
        }
        return $event;
    }
}