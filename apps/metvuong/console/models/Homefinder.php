<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/8/2015
 * Time: 2:19 PM
 */
namespace console\models;
use keltstr\simplehtmldom\SimpleHTMLDom;
use Yii;
use yii\base\Component;

class Homefinder extends Component
{
    /**
     * @return mixed
     */
    public static function find()
    {
        return Yii::createObject(Homefinder::className());
    }

    public function getListProject()
    {
        $content = SimpleHTMLDom::file_get_html('http://homefinder.vn/developer/');
        $list = $content->find('.body-cont .img-devs a');
        if (!empty($list)) {
            $start = time();
            foreach ($list as $item) {
                $link = $item->find('a');
                $hrefProject = $item->href;
                echo "<pre>";
                print_r($hrefProject);
                echo "</pre>";
                echo "\n";

            }
            $end = time();
        }
        echo "cron service runnning: " . ($end - $start);
    }
}