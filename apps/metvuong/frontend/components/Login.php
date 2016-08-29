<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/7/2015
 * Time: 11:16 AM
 */

namespace frontend\components;
use frontend\models\User;
use frontend\models\UserActivity;
use frontend\models\UserData;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\base\Component;
use yii\web\UserEvent;

class Login extends Component
{
    public static function me()
    {
        return Yii::createObject(self::className());
    }

    public static function handleAfterLogin(UserEvent $event)
    {
        $event->identity;
        /**
         * test
         * @todo: remove if done
         */
        self::me()->afterLogin($event->identity);
        return $event;
    }

    public function afterLogin($user)
    {
        if(!empty($user) && is_numeric($user)){
            $user = User::findOne($user);
        }
        if(!empty($user->id)){
            \Yii::$app->getSession()->setFlash('after_login', true);
            if($user->confirmed_at <= $user->created_at){
                $user->confirm();
            }
        }
    }
}