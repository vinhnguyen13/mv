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
use yii\mongodb\ActiveRecord;
use Yii;

class UserActivity extends \vsoft\user\models\base\UserActivity
{
    const ACTION_AD_FAVORITE   = 1;
    const ACTION_AD_SEARCH     = 2;
    const ACTION_AD_CLICK      = 3;
    const ACTION_AD_CHAT       = 4;

    const READ_YES      = 1;
    const READ_NO       = 2;

    public static function me()
    {
        return Yii::createObject(self::className());
    }

    public function saveActivity($action, $message, $params, $object_id){
        if(!isset(Yii::$app->params['activity']['enable']) || Yii::$app->params['activity']['enable'] == false){
            return false;
        }
        if(!Yii::$app->user->isGuest){
            $this->owner_id = Yii::$app->user->id;
            $this->owner_username = Yii::$app->user->identity->username;
            $this->action = $action;
            $this->message = $message;
            $this->params = Json::encode($params);
            $this->ip = Yii::$app->getRequest()->getUserIP();
            $this->object_id = $object_id;
            $object = $this->findObject();
            if(!empty($object)) {
                $this->buddy_id = $object->buddy_id;
                $this->buddy_username = $object->buddy_username;
            }
            $this->parent_id = 0;
            $this->status = 1;
            $this->created = time();
            $this->updated = 0;
            $this->read_status = self::READ_NO;
            $this->read_time = Yii::$app->user->id;
            $this->validate();
            return $this->save();
        }
        return false;
    }

    public function findObject(){
        if(!empty($this->action)){
            switch($this->action){
                case self::ACTION_AD_FAVORITE;
                    if(($product = AdProduct::findOne(['id'=>$this->object_id])) !== null){
                        $object = new \stdClass();
                        $owner = User::findOne($product->user_id);
                        $object->buddy_id = $product->user_id;
                        $object->buddy_username = $owner->username;
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

    public function setRead($action){

    }
}