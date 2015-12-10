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
        Homefinder::find()->parse();
    }

    public function actionImporthomefinder()
    {
        $begin = time();
        $num = Homefinder::find()->importData();
        $end = time();
        print_r("\n"." Time: ");
        print_r($end-$begin);
        print_r("s");
        print_r(" - Record: ". $num);
    }

    public function actionBatdongsan()
    {

    }
}