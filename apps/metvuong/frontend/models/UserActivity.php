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
            $this->user_id = Yii::$app->user->id;
            $this->username = Yii::$app->user->identity->username;
            $this->action = $action;
            $this->message = $message;
            $this->params = Json::encode($params);
            $this->ip = Yii::$app->getRequest()->getUserIP();
            $object = $this->findObject($action, $object_id);
            if(!empty($object)) {
                $this->object_id = $object_id;
                $this->object_owner_id = $object->object_owner_id;
                $this->object_owner_username = $object->object_owner_username;
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

    public function findObject($action, $object_id){
        if(!empty($action)){
            switch($action){
                case self::ACTION_AD_FAVORITE;
                    if(($product = AdProduct::findOne(['id'=>$object_id])) !== null){
                        $object = new \stdClass();
                        $object->object_id = $object_id;
                        $owner = User::findOne($product->user_id);
                        $object->object_owner_id = $product->user_id;
                        $object->object_owner_username = $owner->username;
                        return $object;
                    }
                    break;
            }
        }
    }

    public function getMessage(){
        if(!empty($this->action)) {
            switch ($this->action) {
                case self::ACTION_AD_FAVORITE;
                    $params = Json::decode($this->params);
                    $params['product'] = (($product = AdProduct::findOne(['id'=>$params['product']])) !== null) ? $product->getAddress() : '';
                    $message = Yii::t('activity', $this->message, $params);
                    return $message;
                    break;
            }
        }
    }
}