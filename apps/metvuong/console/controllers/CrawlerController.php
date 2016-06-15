<?php
namespace console\controllers;

use console\models\Batdongsan;
use console\models\BatdongsanV2;
use console\models\Homefinder;
use console\models\Muaban_net;
use vsoft\ad\models\AdCity;
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

    // Sale Batdongsan
    public function actionBatdongsan()
    {
        BatdongsanV2::find()->parse();
    }
    public function actionImportbatdongsan()
    {
//        BatdongsanV2::find()->importData(1);
    }
    public function actionImportbatdongsan2()
    {
        BatdongsanV2::find()->importDataForTool(1);
    }
    public function actionUpdatebatdongsan()
    {
        // update address from Google API Geocode
        BatdongsanV2::find()->updateData();
    }

    public function actionUpdateAddressByProject(){
        // update address from project address
        BatdongsanV2::find()->updateAddressByProject();
    }

    // price = 1 get products have price  > 0
    // build = true build elastic
    public function actionCopytomain($price=1, $build='build=false')
    {
        BatdongsanV2::find()->copyToMainDb($price, $build);
    }

    // Agent Batdongsan
    public function actionAgentbatdongsan()
    {
        BatdongsanV2::find()->getAgents();
    }
    public function actionImportagentbds()
    {
        BatdongsanV2::find()->importAgent();
    }
    // Rent Batdongsan
    public function actionRentbatdongsan()
    {
        BatdongsanV2::find()->parseRent();
    }

    public function actionImportrentbds()
    {
        BatdongsanV2::find()->importDataForTool(2);
    }

    // Get Projects
    public function actionProjectbds(){
        BatdongsanV2::find()->getProjects();
    }
    public function actionImportprojectbds(){
        BatdongsanV2::find()->importProjects();
    }
    public function actionCopyProject(){
        BatdongsanV2::find()->copyProjects();
    }

    // db chinh
    public function actionImportProjectPrimary(){
        BatdongsanV2::find()->importProjectPrimary();
    }
    public function actionUpdateProjectPrimary(){
        BatdongsanV2::find()->updateProjects();
    }

    // Get Contractor
    public function actionContractorbds(){
        BatdongsanV2::find()->getContractors();
    }
    public function actionImportcontractorbds(){
//        BatdongsanV2::find()->importProjects();
    }

    // Muaban.net
    public function actionMuaban()
    {
        Muaban_net::find()->parse();
    }

}