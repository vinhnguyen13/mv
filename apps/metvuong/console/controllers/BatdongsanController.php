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
        Listing::find()->parse();
    }
    // get listing nha-dat-cho-thue
    public function actionRentListing()
    {
        Listing::find()->parseRent();
    }

    public $valid;
    public function options()
    {
        return ['valid'];
    }
    public function optionAliases()
    {
        return ['valid' => 'valid'];
    }
    // php yii batdongsan/copy-listing -valid=1
    public function actionCopyListing()
    {
        $validate = intval($this->valid);
        CopyListing::find()->copyToMainDB($validate);
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