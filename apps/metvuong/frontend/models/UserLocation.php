<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 3/7/2016 10:28 AM
 */

namespace frontend\models;

use Yii;

class UserLocation extends \vsoft\user\models\base\UserLocation
{
    const DEFAULT_CITY = 1;
    const DEFAULT_DISTRICT = 10;

    public function getUser()
    {
        return $this->hasOne($this->module->modelMap['User'], ['id' => 'user_id']);
    }

    public function getCity(){
        $db = $this->getDb();
        $city_id = $this->city_id;
        $city = $db->cache(function($db) use ($city_id){
            return \vsoft\ad\models\AdCity::find()->where(['id' => $city_id])->one();
        });
//        $city = \vsoft\ad\models\AdCity::find()->where(['id' => $this->city_id])->one();
        return empty($city) ? "" : $city->name;
    }

}