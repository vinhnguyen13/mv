<?php
namespace console\controllers;

use console\models\Homefinder;
use yii\console\Controller;
use keltstr\simplehtmldom\SimpleHTMLDom;

/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/8/2015
 * Time: 1:30 PM
 */
class CrawlerController extends Controller
{
    public function beforeAction($action)
    {
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        echo "cron service runnning";
    }

    public function actionHomefinder()
    {
        Homefinder::find()->getListDevelopers();
    }

    public function actionBatdongsan()
    {

    }
}