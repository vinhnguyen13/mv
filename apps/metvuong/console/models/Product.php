<?php
namespace console\models;
use yii\base\Component;
use Yii;

/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 8/10/2016
 * Time: 5:12 PM
 */
class Product extends Component
{
    public static function me()
    {
        return Yii::createObject(self::className());
    }

}