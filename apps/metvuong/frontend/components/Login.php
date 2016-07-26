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
    public static function handleAfterLogin(UserEvent $event)
    {
        $event->identity;
        /**
         * test
         * @todo: remove if done
         */
        if(($user = User::findOne($event->identity->id)) !== null){
            $user->updateAttributes(['updated_at' => time()]);
            \Yii::$app->getSession()->setFlash('after_login', true);
        }
        return $event;
    }
}