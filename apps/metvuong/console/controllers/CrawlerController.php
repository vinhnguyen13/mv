<?php
namespace console\controllers;

use console\models\Batdongsan;
use console\models\batdongsan\CopyListing;
use console\models\batdongsan\CopyProject;
use console\models\batdongsan\ImportListing;
use console\models\batdongsan\ImportProject;
use console\models\batdongsan\Listing;
use console\models\batdongsan\Project;
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

    // Sale Batdongsan
    public function actionBatdongsan()
    {
//        BatdongsanV2::find()->parse();
        if(!empty($this->city) && isset(Listing::find()->sale_types[$this->city])) {
            Listing::find()->parse($this->city);
        }
        else {
            print_r("\nParam: city undefined ! \nEx: php yii crawler/batdongsan -city=ho-chi-minh\n");
        }

    }
    public function actionImportbatdongsan2()
    {
        if(!empty($this->city) && isset(Listing::find()->sale_types[$this->city])) {
            $import_limit = $this->limit == null ? 300 : ((intval($this->limit) <= 300 && intval($this->limit) > 0) ? intval($this->limit) : 0);
            if($import_limit > 0)
                ImportListing::find()->importDataForTool(1, $this->city, $import_limit);
            else {
                print_r("\nParam: city undefined ! \nEx: php yii crawler/importbatdongsan -city=ho-chi-minh -limit=1\n");
            }
        }
        else {
            print_r("\nParam: city undefined ! \nEx: php yii crawler/importbatdongsan -city=ho-chi-minh -limit=1\n");
        }
    }


    // Rent Batdongsan
    public function actionRentbatdongsan()
    {
//        BatdongsanV2::find()->parseRent();
        if(!empty($this->city) && isset(Listing::find()->rent_types[$this->city])) {
            Listing::find()->parseRent($this->city);
        }
        else {
            print_r("\nParam: city undefined ! \nEx: php yii crawler/rentbatdongsan -city=ho-chi-minh\n");
        }
    }

    public function actionImportrentbds()
    {
//        BatdongsanV2::find()->importDataForTool(2);
        if(!empty($this->city) && isset(Listing::find()->sale_types[$this->city])) {
            $import_limit = $this->limit == null ? 300 : ((intval($this->limit) <= 300 && intval($this->limit) > 0) ? intval($this->limit) : 0);
            if($import_limit > 0)
                ImportListing::find()->importDataForTool(2, $this->city, $import_limit);
            else {
                print_r("\nParam: city undefined ! \nEx: php yii crawler/importrentbds -city=ho-chi-minh -limit=1\n");
            }
        }
        else {
            print_r("\nParam: city undefined ! \nEx: php yii crawler/importrentbds -city=ho-chi-minh -limit=1\n");
        }
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
//    public function actionCopytomain($price=1, $build='build=false')
//    {
//        BatdongsanV2::find()->copyToMainDb($price, $build);
//    }

    public $valid;
    public $type;
    public $city;
    public $district;
    public $limit;
    public $check_expired;
    public function options()
    {
        return ['valid','type','city','district','limit','check_expired'];
    }
    public function optionAliases()
    {
        return ['valid' => 'valid', 'type' => 'type', 'city' => 'city', 'district' => 'district', 'limit' => 'limit', 'check_expired' => 'check_expired'];
    }

    public function actionImportListing()
    {
        $import_limit = $this->limit == null ? 300 : ((intval($this->limit) <= 300 && intval($this->limit) > 0) ? intval($this->limit) : 0);
        ImportListing::find()->importDataForTool($import_limit);
    }

    // php yii crawler/copytomain -valid=1 -limit=300 -check_expired=1 -type=1 -city=1 -district=16
    public function actionCopytomain()
    {
        $validate = intval($this->valid);
        $check_expired = $this->check_expired == null ? 0 : 1;
        $copy_limit = $this->limit == null ? 300 : ((intval($this->limit) <= 300 && intval($this->limit) > 0) ? intval($this->limit) : 0);
        if($copy_limit > 0) {
            CopyListing::find()->copyToMainDB($validate, $copy_limit, $check_expired, $this->type, $this->city, $this->district);
        }
        else {
            print_r("\n How many listing to copy? \n Ex: php yii crawler/copytomain -valid=1 -limit=1\n");
        }
    }
    public function actionUpdateProductTool(){
        $limit = $this->limit == null ? 1000 : ((intval($this->limit) <= 1000 && intval($this->limit) > 0) ? intval($this->limit) : 0);
        ImportListing::find()->updateProductTool($limit);
    }

    public function actionUpdateProductInfo()
    {
        $limit = $this->limit == null ? 1000 : ((intval($this->limit) <= 1000 && intval($this->limit) > 0) ? intval($this->limit) : 0);
        ImportListing::find()->updateProductInfo($limit);
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

    // Project Batdongsan
    public function actionProject()
    {
        Project::find()->getProjects();
    }
    public function actionImportProject()
    {
        $limit = $this->limit == null ? 300 : ((intval($this->limit) <= 300 && intval($this->limit) > 0) ? intval($this->limit) : 0);
        ImportProject::find()->importProjects($limit);
    }
    public function actionCopyProject()
    {
        $limit = $this->limit == null ? 300 : ((intval($this->limit) <= 300 && intval($this->limit) > 0) ? intval($this->limit) : 0);
        CopyProject::find()->copyProjects($limit);
    }
    public function actionUpdateProjectTool(){
        ImportProject::find()->updateProjectTool();
    }

    // db chinh
    public function actionImportProjectPrimary(){
        BatdongsanV2::find()->importProjectPrimary();
    }
    public function actionUpdateProjectPrimary(){
        $limit = $this->limit == null ? 300 : ((intval($this->limit) <= 300 && intval($this->limit) > 0) ? intval($this->limit) : 0);
//        BatdongsanV2::find()->updateProjects($limit);
        Project::find()->updateProject($limit, true, true);
    }

    // Get Contractor
    public function actionContractorbds(){
        BatdongsanV2::find()->getContractors();
    }
    public function actionImportcontractorbds(){
//        BatdongsanV2::find()->importProjects();
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
    
    // Muaban.net
    public function actionMuaban()
    {
        Muaban_net::find()->parse();
    }

}