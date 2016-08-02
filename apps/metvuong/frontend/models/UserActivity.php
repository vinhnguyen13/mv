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
    const ACTION_USER_LOGIN    = 5;

    const READ_YES      = 1;
    const READ_NO       = 0;

    public $total;
    public static function me()
    {
        return Yii::createObject(self::className());
    }

    public function saveActivity($action, $params, $object_id, $identity = null){
        $app = Yii::$app;
        if(!isset($app->params['activity']['enable']) || $app->params['activity']['enable'] == false){
            return false;
        }
        $_identity = !empty($identity) ? $identity : $app->user->identity;
        if(!empty($_identity)){
            $activity = $this->getActivity($action, $params, $object_id, $_identity);
            $activity->action = $action;
            $activity->owner_id = $_identity->id;
            $activity->owner_username = $_identity->username;
            $activity->message = $activity->getMessage($action);
            $activity->params = $params;
            $activity->ip = $app->getRequest()->getUserIP();
            $object = $activity->findObject($action, $object_id);
            if(!empty($object)) {
                $activity->object_id = $object_id;
                if (!empty($object['user'])) {
                    $activity->buddy_id = $object['user']->id;
                    $activity->buddy_username = $object['user']->username;
                }
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

    public function getActivity($action, $params, $object_id, $_identity){
        $activity = $this;
        if(!empty($action)){
            switch($action){
                case self::ACTION_AD_FAVORITE;
                case self::ACTION_AD_CLICK;
                case self::ACTION_AD_SEARCH;
                    if(($activityExist = UserActivity::findOne(['action'=>$action, 'owner_id'=>$_identity->id, 'object_id'=>$object_id])) !== null){
                        return $activityExist;
                    }
                    break;
                case self::ACTION_AD_CHAT;
                    if(($activityExist = UserActivity::findOne(['action'=>$action, 'owner_id'=>$_identity->id, 'object_id'=>$object_id])) !== null){
                        return $activityExist;
                    }
                    break;
                case self::ACTION_USER_LOGIN;
                    $now = time();
                    $from = strtotime(date('d-m-Y 00:00:00', $now));
                    $to = strtotime(date('d-m-Y 23:59:59', $now));
                    $activityExist = UserActivity::find()->where(['action'=>$action, 'owner_id'=>$_identity->id])->andFilterWhere(['between', 'created', $from, $to])->one();
                    if(($activityExist) !== null){
                        return $activityExist;
                    }
                    break;
            }
        }
        return $activity;
    }

    public function getMessage($action){
        if(!empty($action)){
            switch($action){
                case self::ACTION_AD_FAVORITE;
                    Yii::t('activity', '{owner} favorite {product}');
                    return "{owner} favorite {product}";
                    break;
                case self::ACTION_AD_CLICK;
                    Yii::t('activity', '{owner} view {product}');
                    return "{owner} view {product}";
                    break;
                case self::ACTION_AD_SEARCH;
                    Yii::t('activity', '{owner} find {product}');
                    return "{owner} find {product}";
                    break;
                case self::ACTION_AD_CHAT;
                    Yii::t('activity', '{owner} chat with {buddy}');
                    return "{owner} chat with {buddy}";
                    break;
                case self::ACTION_USER_LOGIN;
                    Yii::t('activity', '{owner} login at {time}');
                    return "{owner} login at {time}";
                    break;
            }
        }
    }

    public function findObject($action, $object_id){
        if(!empty($action)){
            switch($action){
                case self::ACTION_AD_FAVORITE;
                case self::ACTION_AD_CLICK;
                case self::ACTION_AD_SEARCH;
                    if(($product = AdProduct::findOne(['id'=>$object_id])) !== null && !empty($product->user_id)){
                        $object['user'] = User::findOne($product->user_id);
                        return $object;
                    }
                    break;
                case self::ACTION_AD_CHAT;
                    break;
                case self::ACTION_USER_LOGIN;
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
        if($this->updateAttributes(['read_status'=>self::READ_YES, 'read_time' => time()])){
            Cache::me()->delete(Cache::PRE_NOTIFICATION.$this->buddy_id);
            return true;
        }
    }

    public function setUserData($user){
        $id = (string) $this->_id ;
        if(!empty($id)){
            if(UserData::me()->saveAlert($user, UserData::ALERT_OTHER, [trim($id)])){
                return true;
            }
        }
    }

    public function isAction($action){
        if(!empty($this->action) && $this->action == $action){
            return true;
        }
        return false;
    }
}