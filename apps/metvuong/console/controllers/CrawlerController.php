<?php
namespace console\controllers;

use console\models\Batdongsan;
use console\models\BatdongsanV2;
use console\models\Homefinder;
use console\models\Muaban_net;
use yii\console\Controller;

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

    // Homefinder
    public function actionHomefinder()
    {
        Homefinder::find()->parse();
    }
    public function actionImporthomefinder()
    {
        Homefinder::find()->importData_2();
    }

    // Batdongsan
    public function actionBatdongsan()
    {
        BatdongsanV2::find()->parse();
    }
    public function actionImportbatdongsan()
    {
        BatdongsanV2::find()->importData();
    }
    public function actionImportbatdongsan2()
    {
        BatdongsanV2::find()->importDataForTool();
    }
    public function actionUpdatebatdongsan()
    {
        BatdongsanV2::find()->updateData();
    }
    public function actionAgentbatdongsan()
    {
        BatdongsanV2::find()->getAgents();
    }
    public function actionImportagentbds()
    {
        BatdongsanV2::find()->importAgent();
    }

    // Muaban.net
    public function actionMuaban()
    {
        Muaban_net::find()->parse();
    }

}