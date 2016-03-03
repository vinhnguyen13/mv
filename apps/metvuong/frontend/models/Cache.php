<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/8/2015
 * Time: 10:14 AM
 */

namespace frontend\models;
use common\components\Util;
use vsoft\chat\models\TigUsers;
use vsoft\user\models\UserJid;
use Yii;
use yii\base\Component;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class Cache extends Component
{
    const NOTIFICATION = 'NOTIFICATION';
    public static function me()
    {
        return Yii::createObject(self::className());
    }

    public function set($key, $value, $duration = 0, $dependency = null)
    {
        return Yii::$app->cache->set($key, $value, $duration, $dependency);
    }

    public function get($key)
    {
        return Yii::$app->cache->get($key);
    }

    public function delete($key)
    {
        return Yii::$app->cache->delete($key);
    }
}