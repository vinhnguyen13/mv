<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 7/12/2016 10:18 AM
 */

namespace console\controllers;

use console\models\batdongsan\CopyListing;
use console\models\batdongsan\CopyProject;
use console\models\batdongsan\ImportProject;
use console\models\batdongsan\Listing;
use console\models\batdongsan\Project;
use yii\console\Controller;

class BatdongsanController extends Controller
{
    // get listing nha-dat-ban
    public function actionListing()
    {
        if($this->city == 'hcm')
            $this->city = 'ho-chi-minh';
        Listing::find()->parse($this->city);
    }
    // get listing nha-dat-cho-thue
    public function actionRentListing()
    {
        if($this->city == 'hcm')
            $this->city = 'ho-chi-minh';
        Listing::find()->parseRent($this->city);
    }

    public $valid;
    public $city;
    public $limit;
    public function options()
    {
        return ['valid','city','limit'];
    }
    public function optionAliases()
    {
        return ['valid' => 'valid', 'city' => 'city', 'limit' => 'limit'];
    }
    // php yii batdongsan/copy-listing -valid=1
    public function actionCopyListing()
    {
        $validate = intval($this->valid);
        $copy_limit = $this->limit == null ? 300 : (intval($this->limit) > 300 ? 300 : intval($this->limit));
        if($copy_limit > 0)
            CopyListing::find()->copyToMainDB($validate,$copy_limit);
        else {
            print_r("\n How many listing to copy? \n Ex: php yii crawler/copytomain -valid=1 -limit=300\n");
        }
    }

    // get project
    public function actionProject()
    {
        Project::find()->getProjects();
    }

    public function actionImportProject()
    {
        ImportProject::find()->importProjects();
    }

    public function actionCopyProject()
    {
        CopyProject::find()->copyProjects();
    }
}