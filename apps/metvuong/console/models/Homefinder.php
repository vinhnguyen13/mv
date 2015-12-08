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
    const DOMAIN = 'http://homefinder.vn';
    /**
     * @return mixed
     */
    public static function find()
    {
        return Yii::createObject(Homefinder::className());
    }

    public function parse()
    {
        $this->getListDevelopers();
    }

    public function getListDevelopers()
    {
        $content = SimpleHTMLDom::file_get_html(self::DOMAIN.'/developer/');
        $list = $content->find('.body-cont .img-devs a');
        if (!empty($list)) {
            $start = time();
            foreach ($list as $item) {
                $this->getListProject($item->href);

            }
            $end = time();
        }
        echo "cron service runnning: " . ($end - $start);
    }

    public function getListProject($hrefProject)
    {
        $content = SimpleHTMLDom::file_get_html(self::DOMAIN.$hrefProject);
        $list = $content->find('.menu ul.list_project li');
        if (!empty($list)) {
            $start = time();
            foreach ($list as $item) {
                $this->getListProject($item->href);
            }
            $end = time();
        }
        echo "cron service runnning: " . ($end - $start);
    }

    public function getProjectDetail($hrefProject)
    {

    }
}