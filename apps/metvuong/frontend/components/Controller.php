<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/7/2015
 * Time: 11:16 AM
 */

namespace frontend\components;
use Yii;
use yii\helpers\Url;

class Controller extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        Yii::$app->getUser()->setReturnUrl(Url::current());
        return parent::beforeAction($action);
    }
}