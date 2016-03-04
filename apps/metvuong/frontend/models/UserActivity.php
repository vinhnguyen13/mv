<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 1/14/2016
 * Time: 1:58 PM
 */
namespace frontend\models;
use vsoft\ad\models\AdProduct;
use yii\helpers\Json;
use Yii;

class UserActivity extends \vsoft\user\models\base\UserActivity
{
    const ACTION_AD_FAVORITE   = 1;
    const ACTION_AD_SEARCH     = 2;
    const ACTION_AD_CLICK      = 3;
    const ACTION_AD_CHAT       = 4;

    const READ_YES      = 1;
    const READ_NO       = 0;

    public static function me()
    {
        return Yii::createObject(self::className());
    }

    public function saveActivity($action, $params, $object_id){
        $app = Yii::$app;
        if(!isset($app->params['activity']['enable']) || $app->params['activity']['enable'] == false){
            return false;
        }
        if(!$app->user->isGuest){
            $activity = $this;
            if($action == self::ACTION_AD_FAVORITE){
                if(($activityExist = self::findOne(['action'=>$action, 'owner_id'=>$app->user->id, 'object_id'=>$object_id])) !== null){
                    $activity = $activityExist;
                }
            }elseif($action == self::ACTION_AD_CLICK){
                if(($activityExist = self::findOne(['action'=>$action, 'owner_id'=>$app->user->id, 'object_id'=>$object_id])) !== null){
                    $activity = $activityExist;
                }
            }

            $activity->action = $action;
            $activity->owner_id = $app->user->id;
            $activity->owner_username = $app->user->identity->username;
            $activity->message = $activity->getMessage();
            $activity->params = $params;
            $activity->ip = $app->getRequest()->getUserIP();
            $activity->object_id = $object_id;
            $object = $activity->findObject();
            if(!empty($object['user'])) {
                $activity->buddy_id = $object['user']->id;
                $activity->buddy_username = $object['user']->username;
            }else{
                return false;
            }
            $activity->parent_id = 0;
            $activity->status = 1;
            $activity->read_status = self::READ_NO;
            $activity->read_time = 0;
            $activity->validate();
            if(!$activity->hasErrors()){
                if($activity->isNewRecord){
                    $activity->created = $activity->updated = time();
                }else{
                    $activity->updated = time();
                }
                if($activity->save()){
                    if(!empty($object['user'])) {
                        return $activity->setUserData($object['user']);
                    }
                }
            }
        }
        return false;
    }

    public function getMessage(){
        if(!empty($this->action)){
            switch($this->action){
                case self::ACTION_AD_FAVORITE;
                    return "{owner} favorite {product}";
                    break;
                case self::ACTION_AD_CLICK;
                    return "{owner} view {product}";
                    break;
            }
        }
    }

    public function findObject(){
        if(!empty($this->action)){
            switch($this->action){
                case self::ACTION_AD_FAVORITE;
                case self::ACTION_AD_CLICK;
                    if(($product = AdProduct::findOne(['id'=>$this->object_id])) !== null && !empty($product->user_id)){
                        $object['user'] = User::findOne($product->user_id);
                        return $object;
                    }
                    break;
            }
        }
    }

    public function getOwner(){
        if(($owner = User::findOne($this->owner_id)) !== null) {
            return $owner;
        }
        return false;
    }

    public function getBuddy(){
        if(($buddy = User::findOne($this->buddy_id)) !== null) {
            return $buddy;
        }
        return false;
    }

    public function read(){
        return $this->updateAttributes(['read_status'=>self::READ_YES, 'read_time' => time()]);
    }

    public function setUserData($user){
        $id = (string) $this->_id ;
        if(!empty($id)){
            return UserData::me()->saveAlert($user, UserData::ALERT_OTHER, [trim($id)]);
        }
    }

    public function isAction($action){
        if(!empty($this->action) && $this->action == $action){
            return true;
        }
        return false;
    }
}