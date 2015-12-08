<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/8/2015
 * Time: 2:19 PM
 */
namespace console\models;
use keltstr\simplehtmldom\SimpleHTMLDom;
use linslin\yii2\curl\Curl;
use Yii;
use yii\base\Component;

class Homefinder extends Component
{
    const DOMAIN = 'http://homefinder.vn';
    protected $time_start = 0;
    protected $time_end = 0;
    /**
     * @return mixed
     */
    public static function find()
    {
        return Yii::createObject(Homefinder::className());
    }

    public function parse()
    {
        $this->time_start = time();
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

    public function getListProject($href)
    {
        $content = SimpleHTMLDom::file_get_html(self::DOMAIN.$href);
        $list = $content->find('.menu ul.list_project li');
        if (!empty($list)) {
            $start = time();
            foreach ($list as $item) {
                $a = $item->find('a');
                $this->getProjectDetail($a[0]->href);
            }
            $end = time();
        }
        echo "cron service runnning: " . ($end - $start);
    }

    public function getProjectDetail($href)
    {
        $content = SimpleHTMLDom::file_get_html(self::DOMAIN.$href);
        $list = $content->find('script');
        if (!empty($list)) {
            $start = time();
            foreach ($list as $item) {
                $varname = "pId";
                preg_match('/'.$varname.'\s*?=\s*?(.*)\s*?(;|$)/msU',$item->innertext(),$matches);
                if(!empty($matches[1]) ){
                    $room_id = $matches[1];
                    $room_id = str_replace('"', '', $room_id);
                    $this->pagingListing($room_id);
                }
            }
            $end = time();
            echo "cron service runnning: " . ($end - $start);
        }
    }

    public function pagingListing($pid)
    {
        $url = self::DOMAIN.'/ajax/projecttable/'.$pid.'/ban?draw=2&columns[0][data]=hinh_anh&columns[0][name]=&columns[0][searchable]=true&columns[0][orderable]=true&columns[0][search][value]=&columns[0][search][regex]=false&columns[1][data]=cost&columns[1][name]=&columns[1][searchable]=true&columns[1][orderable]=true&columns[1][search][value]=&columns[1][search][regex]=false&columns[2][data]=dien_tich_quy_hoach&columns[2][name]=&columns[2][searchable]=true&columns[2][orderable]=true&columns[2][search][value]=&columns[2][search][regex]=false&columns[3][data]=ngay_dang&columns[3][name]=&columns[3][searchable]=true&columns[3][orderable]=true&columns[3][search][value]=&columns[3][search][regex]=false&columns[4][data]=broker.name&columns[4][name]=&columns[4][searchable]=true&columns[4][orderable]=true&columns[4][search][value]=&columns[4][search][regex]=false&columns[5][data]=_id&columns[5][name]=&columns[5][searchable]=true&columns[5][orderable]=true&columns[5][search][value]=&columns[5][search][regex]=false&order[0][column]=3&order[0][dir]=desc&start=5&length=5&search[value]=&search[regex]=false&_=1449561346330';
//        $content = SimpleHTMLDom::file_get_html(self::DOMAIN.$url);
        $curl = new Curl();
        //get http://example.com/
        $response = $curl->get($url);
        echo "<pre>";
        print_r($response);
        echo "</pre>";
        exit;

    }
}