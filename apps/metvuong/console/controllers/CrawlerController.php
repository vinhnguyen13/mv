<?php
namespace console\controllers;

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

    public function actionIndex() {
        echo "cron service runnning";
    }

    public function actionHomefinder() {
        $content = SimpleHTMLDom::file_get_html('http://homefinder.vn/developer/');
        $list = $content->find('.body-cont .img-devs a');
        if(!empty($list)){
            foreach($list as $item){
                $link = $item->find('a');
                $hrefProject = $item->href;
                echo "<pre>";
                print_r($hrefProject);
                echo "</pre>";
                echo "\n";

            }
        }
        echo "cron service runnning";
    }

    public function actionBatdongsan() {

    }
}