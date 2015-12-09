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
    protected $page_current = 1;
    /**
     * @return mixed
     */
    public static function find()
    {
        return Yii::createObject(Homefinder::className());
    }

    public function parse()
    {
        ob_start();
        $this->time_start = time();
        $this->getListDevelopers();
    }

    public function getListDevelopers()
    {
        $content = SimpleHTMLDom::file_get_html(self::DOMAIN.'/developer/');
        $list = $content->find('.body-cont .img-project-inside a');
        if (!empty($list)) {
            $start = time();
            foreach ($list as $idx => $item) {
                if($item->href && $idx > 0){
                    $this->getListProject($item->href);
                    echo $item->title;
                }
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
                echo $a[0]->innertext();
                echo "\n";
                sleep(1);
                ob_flush();
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
                    $this->pagingListing($room_id, $this->page_current);
                }
            }
            $end = time();
            echo "cron service runnning: " . ($end - $start);
        }
    }

    public function pagingListing($pid, $page_current)
    {
        $url = self::DOMAIN.'/ajax/projecttable/'.$pid.'/ban?draw=1&columns[0][data]=hinh_anh&columns[0][name]=' .
            '&columns[0][searchable]=true&columns[0][orderable]=true&columns[0][search][value]=&columns[0][search][regex]=false' .
            '&columns[1][data]=cost&columns[1][name]=&columns[1][searchable]=true&columns[1][orderable]=true&columns[1][search][value]=' .
            '&columns[1][search][regex]=false&columns[2][data]=dien_tich_quy_hoach&columns[2][name]=&columns[2][searchable]=true&columns[2][orderable]=true' .
            '&columns[2][search][value]=&columns[2][search][regex]=false&columns[3][data]=ngay_dang&columns[3][name]=&columns[3][searchable]=true&columns[3][orderable]=true' .
            '&columns[3][search][value]=&columns[3][search][regex]=false&columns[4][data]=broker.name&columns[4][name]=&columns[4][searchable]=true&columns[4][orderable]=true' .
            '&columns[4][search][value]=&columns[4][search][regex]=false&columns[5][data]=_id&columns[5][name]=&columns[5][searchable]=true&columns[5][orderable]=true' .
            '&columns[5][search][value]=&columns[5][search][regex]=false&order[0][column]=3&order[0][dir]=desc&start='.($page_current*5).'&length=5&search[value]=&search[regex]=false&_='.time();
//        $content = SimpleHTMLDom::file_get_html(self::DOMAIN.$url);
        $curl = new Curl();
        //get http://example.com/
        $response = $curl->get($url);
        if(($response = json_decode($response)) && !empty($response->data)){
            $totalItem = count($response->data);
            foreach($response->data as $item){
                if($page_current==2){
                    
                }
                $content = $this->getListingDetail(self::DOMAIN.'/'.$item->_id, $item->_id);
            }
            if(!empty($response->recordsTotal) && ($response->recordsTotal > ($totalItem*$page_current))){
                $page_current++;
                $this->pagingListing($pid, $page_current);
            }
        }
    }

    public function getListingDetail($url, $id)
    {
        $arr_detail = array();
        $htmlDetail = SimpleHTMLDom::file_get_html($url);

        $title = $htmlDetail->find('title', 0);
        $title = trim($title->plaintext);
        $project_name = substr($title, 0, strpos($title, '-')-1);

        // tien ich m2, phong ngu , toilet
        $util = $htmlDetail->find('.left .line1', 0);
        if(!empty($util)) {
            $arr_util = explode(',', $util->plaintext);
            $dientich = trim($arr_util[0]);
            $tienich = null;
            for($i=1; $i < count($arr_util); $i++){
                $tienich = $tienich . ' ' . trim($arr_util[$i]);
            }
            $arr_detail[$project_name]["dientich"] = $dientich;
            $arr_detail[$project_name]["tienich"] = $tienich;
        }

        // thong tin mo ta
        $description = $htmlDetail->find('.description-list .desc_content', 0);
        if(!empty($description)){
            $arr_detail[$project_name]["mota"] = $description->innertext;
        }

        $arr_hidden = array();
        // lat, lon, loai_tai_san, loai_giao_dich(ban/thue)
        $detail = $htmlDetail->find('.detail input');
        if(!empty($detail)){
            foreach($detail as $input){
                $arr_hidden[$input->name] = $input->value;
            }
//            print_r($arr_hidden);
            $arr_detail[$project_name]["lat"] = $arr_hidden["lat"];
            $arr_detail[$project_name]["lng"] = $arr_hidden["lon"];
            $arr_detail[$project_name]["loai_tai_san"] = $arr_hidden["loai_tai_san"];
            $arr_detail[$project_name]["loai_giao_dich"] = $arr_hidden["loai_giao_dich"];
        }


        $str_date = $htmlDetail->find('.desc_broker', 0);
        if(!empty($str_date)){
            $num_date = trim($str_date->innertext);
            $start_pos = strpos($num_date, '-') + 1;
            $end_pos = strlen($num_date) - 1;
            $start_date = trim(substr($num_date, $start_pos, $end_pos));

            $arr_detail[$project_name]["start_date"] = $start_date;
        }

        $broker = $htmlDetail->find('.broker-info .name', 0)->plaintext;
        if(!empty($broker)) {
            $broker = trim($broker);
            $broker = str_replace('</i>', '', $broker);
            $arr_detail[$project_name]["broker"] = $broker;
        }
        $phone = $htmlDetail->find('.broker-info .phone', 0)->plaintext;
        if(!empty($phone))
            $arr_detail[$project_name]["phone"] = trim($phone);


        $filename = $id.'.json';
        $path = Yii::getAlias('@console').'/data/'.$filename;

        $data = json_encode($arr_detail);


        $this->writeFileJson($path, $data);

        return $arr_detail;
    }

    public function writeFileJson($filePath, $data){
        $handle = fopen($filePath, 'w') or die('Cannot open file:  '.$filePath);
        $int = fwrite($handle, $data);
        fclose($handle);
        return $int;
    }

}