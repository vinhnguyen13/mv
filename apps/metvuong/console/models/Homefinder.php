<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/8/2015
 * Time: 2:19 PM
 */
namespace console\models;

use DateInterval;
use DateTime;
use keltstr\simplehtmldom\SimpleHTMLDom;
use linslin\yii2\curl\Curl;
use vsoft\ad\models\AdCity;
use vsoft\ad\models\AdDistrict;
use vsoft\ad\models\AdProduct;
use vsoft\ad\models\AdStreet;
use vsoft\ad\models\AdWard;
use Yii;
use yii\base\Component;
use yii\helpers\FileHelper;

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
        $content = SimpleHTMLDom::file_get_html(self::DOMAIN . '/developer/');
        $list = $content->find('.body-cont .img-project-inside a');
        if (!empty($list)) {
            $start = time();
            foreach ($list as $idx => $item) {
                if ($item->href && $idx >= 0) {
//                    echo $item->title;
                    $this->getListProject($item->href);
                }
            }
            $end = time();
        }
        echo "cron service runnning: " . ($end - $start);
    }

    public function getListProject($href)
    {
        $content = SimpleHTMLDom::file_get_html(self::DOMAIN . $href);
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
        $content = SimpleHTMLDom::file_get_html(self::DOMAIN . $href);
        $list = $content->find('script');
        if (!empty($list)) {
            $start = time();
            foreach ($list as $item) {
                $varname = "pId";
                preg_match('/' . $varname . '\s*?=\s*?(.*)\s*?(;|$)/msU', $item->innertext(), $matches);
                if (!empty($matches[1])) {
                    $room_id = $matches[1];
                    $room_id = str_replace('"', '', $room_id);
                    $log = $this->loadFileLog();
                    $sequence_id = empty($log["last_id"]) ? 0 : ($log["last_id"]+1);
                    $this->pagingListing($room_id, $this->page_current, $log, $sequence_id);
                }
            }
            $end = time();
            echo "cron service runnning: " . ($end - $start);
        }
    }

    public function pagingListing($pid, $page_current, $log, $sequence_id)
    {
        $url = self::DOMAIN . '/ajax/projecttable/' . $pid . '/ban?draw=1&columns[0][data]=hinh_anh&columns[0][name]=' .
            '&columns[0][searchable]=true&columns[0][orderable]=true&columns[0][search][value]=&columns[0][search][regex]=false' .
            '&columns[1][data]=cost&columns[1][name]=&columns[1][searchable]=true&columns[1][orderable]=true&columns[1][search][value]=' .
            '&columns[1][search][regex]=false&columns[2][data]=dien_tich_quy_hoach&columns[2][name]=&columns[2][searchable]=true&columns[2][orderable]=true' .
            '&columns[2][search][value]=&columns[2][search][regex]=false&columns[3][data]=ngay_dang&columns[3][name]=&columns[3][searchable]=true&columns[3][orderable]=true' .
            '&columns[3][search][value]=&columns[3][search][regex]=false&columns[4][data]=broker.name&columns[4][name]=&columns[4][searchable]=true&columns[4][orderable]=true' .
            '&columns[4][search][value]=&columns[4][search][regex]=false&columns[5][data]=_id&columns[5][name]=&columns[5][searchable]=true&columns[5][orderable]=true' .
            '&columns[5][search][value]=&columns[5][search][regex]=false&order[0][column]=3&order[0][dir]=desc&start=' . ($page_current * 5) . '&length=5&search[value]=&search[regex]=false&_=' . time();
//        $content = SimpleHTMLDom::file_get_html(self::DOMAIN.$url);
        $curl = new Curl();
        $url_broker = self::DOMAIN . '/ajax/projectdata/' . $pid . '/broker';
        $response_broker = $curl->get($url_broker);
        $response_broker = json_decode($response_broker);
        $response_broker_table = $response_broker->table[0];

        $response = $curl->get($url);
        if (($response = json_decode($response)) && !empty($response->data)) {
            $totalItem = count($response->data);

            $project_name = $response_broker_table->du_an;
            $city = $response_broker_table->cname;
            $district = $response_broker_table->dname;
            $ward = $response_broker_table->wname;
            $street = $response_broker_table->sname;
            $description = $response_broker_table->mo_ta_chi_tiet;
            $description = str_replace('<div id="LeftMainContent__productDetail_panelTag" class="tagpanel"></div>', '', $description);
            $home_no = $response_broker_table->so_nha;
            $lat = $response_broker_table->lat;
            $lon = $response_broker_table->lon;

            foreach ($response->data as $item) {
                $checkExists = false;
                if(!empty($log)) {
                    $checkExists = in_array($item->_id, $log["files"]);
                }
                if ($checkExists == false) {
                    $price = $item->gia;
//                    $content = $this->getListingDetail(self::DOMAIN . '/' . $item->_id, $item, $project_name, $city, $district, $ward, $street, $description, $home_no, $lat, $lon, $price);
                    $res = $this->getListingDetail_2(self::DOMAIN . '/' . $item->_id, $item, $project_name, $city, $district, $ward, $street, $description, $home_no, $lat, $lon, $price, $sequence_id);

                    if (!empty($res)) {
                        $log["files"][$sequence_id] = $res;
                        $log["last_id"] = $sequence_id;
                        $this->writeFileLog($log);
                        $sequence_id++;
                    }
                }
            }

            if (!empty($response->recordsTotal) && ($response->recordsTotal > ($totalItem * $page_current))) {
                $page_current++;
                $this->pagingListing($pid, $page_current, $log, $sequence_id);
            }

        }
    }

    public function getListingDetail($url, $item, $project_name, $city, $district, $ward, $street, $description, $home_no, $lat, $lon, $price)
    {
        $arr_detail = array();
        $htmlDetail = SimpleHTMLDom::file_get_html($url);
        $currency_value = 1000000; // 1 trieu
        // item_id
        $arr_detail[$project_name]["item_id"] = trim($item->_id);
        // dien tich
        $arr_detail[$project_name]["dientich"] = trim($item->dien_tich_quy_hoach);

        // tien ich phong ngu , toilet
        $util = $htmlDetail->find('.left .line1', 0);
        if (!empty($util)) {
            $arr_util = explode(',', $util->plaintext);
            if (!empty($arr_util[1])) {
                $room_no = str_replace('Bedroom', '', $arr_util[1]);
                $arr_detail[$project_name]["room_no"] = trim($room_no);
            }
            if (!empty($arr_util[2])) {
                $toilet_no = str_replace('tolet', '', $arr_util[2]);
                $arr_detail[$project_name]["toilet_no"] = trim($toilet_no);
            }
        }
        $arr_detail[$project_name]["lat"] = trim($lat);
        $arr_detail[$project_name]["lng"] = trim($lon);
        $arr_detail[$project_name]["city"] = trim($city);
        $arr_detail[$project_name]["district"] = trim($district);
        $arr_detail[$project_name]["ward"] = trim($ward);
        $arr_detail[$project_name]["street"] = trim($street);
        $arr_detail[$project_name]["description"] = trim($description);
        $arr_detail[$project_name]["home_no"] = trim($home_no);
        $arr_detail[$project_name]["price"] = $price * $currency_value;
        $arr_detail[$project_name]["loai_tai_san"] = trim($item->loai_tai_san);
        $arr_detail[$project_name]["loai_giao_dich"] = trim($item->loai_giao_dich);
        $arr_detail[$project_name]["broker"] = trim($item->broker->name);
        $arr_detail[$project_name]["phone"] = trim($item->broker->phone[0]);
        $start_date = trim($item->ngay_dang);
        $start_date = substr($start_date, 0, 10);
        $arr_detail[$project_name]["start_date"] = strtotime($start_date);
        $nDays = 30;
        $end_date = new DateTime($start_date);
        $end_date->add(new DateInterval('P' . $nDays . 'D'));
        $arr_detail[$project_name]["end_date"] = $end_date->getTimestamp();

        $filename = $item->_id . '.json';
        $path = Yii::getAlias('@console') . '/data/' . $filename;
        $data = json_encode($arr_detail);
        $this->writeFileJson($path, $data);


        return $arr_detail;
    }

    public function getListingDetail_2($url, $item, $project_name, $city, $district, $ward, $street, $description, $home_no, $lat, $lon, $price, $sequence_id)
    {
        $arr_detail = array();
        $htmlDetail = SimpleHTMLDom::file_get_html($url);
        $currency_value = 1000000; // 1 trieu
        // item_id
        $arr_detail[$project_name]["item_id"] = trim($item->_id);
        // dien tich
        $arr_detail[$project_name]["dientich"] = trim($item->dien_tich_quy_hoach);

        // tien ich phong ngu , toilet
        $util = $htmlDetail->find('.left .line1', 0);
        if (!empty($util)) {
            $arr_util = explode(',', $util->plaintext);
            if (!empty($arr_util[1])) {
                $room_no = str_replace('Bedroom', '', $arr_util[1]);
                $arr_detail[$project_name]["room_no"] = trim($room_no);
            }
            if (!empty($arr_util[2])) {
                $toilet_no = str_replace('tolet', '', $arr_util[2]);
                $arr_detail[$project_name]["toilet_no"] = trim($toilet_no);
            }
        }
        $arr_detail[$project_name]["lat"] = trim($lat);
        $arr_detail[$project_name]["lng"] = trim($lon);
        $arr_detail[$project_name]["city"] = trim($city);
        $arr_detail[$project_name]["district"] = trim($district);
        $arr_detail[$project_name]["ward"] = trim($ward);
        $arr_detail[$project_name]["street"] = trim($street);
        $arr_detail[$project_name]["description"] = trim($description);
        $arr_detail[$project_name]["home_no"] = trim($home_no);
        $arr_detail[$project_name]["price"] = $price * $currency_value;
        $arr_detail[$project_name]["loai_tai_san"] = trim($item->loai_tai_san);
        $arr_detail[$project_name]["loai_giao_dich"] = trim($item->loai_giao_dich);
        $arr_detail[$project_name]["broker"] = trim($item->broker->name);
        $arr_detail[$project_name]["phone"] = trim($item->broker->phone[0]);
        $start_date = trim($item->ngay_dang);
        $start_date = substr($start_date, 0, 10);
        $arr_detail[$project_name]["start_date"] = strtotime($start_date);
        $nDays = 30;
        $end_date = new DateTime($start_date);
        $end_date->add(new DateInterval('P' . $nDays . 'D'));
        $arr_detail[$project_name]["end_date"] = $end_date->getTimestamp();

        $filename = $item->_id;
        $path = Yii::getAlias('@console') . '/data/item/' . $filename;
        $data = json_encode($arr_detail);
        $res = $this->writeFileJson($path, $data);
        if($res)
            return $filename;
        else
            return null;
    }

    function getCityId($cityFile, $cityDB)
    {
        foreach ($cityDB as $obj) {
            preg_match('/'.$obj->name.'$/', $cityFile, $match);
            if (!empty($match[0])) {
                return (int)$obj->id;
            }
        }
        return 0;
    }

    function getDistrictId($districtFile, $districtDB, $city_id)
    {
        foreach ($districtDB as $obj) {
            preg_match('/'.$obj->name.'$/', $districtFile, $match);
            if (!empty($match[0]) && $obj->city_id == $city_id) {
                return (int)$obj->id;
            }
        }
        return 0;
    }

    function getWardId($_file, $_data, $_id)
    {
        foreach ($_data as $obj) {
            preg_match('/'.$obj->name.'$/', $_file, $match);
            if (!empty($match[0]) && $obj->district_id == $_id) {
                return (int)$obj->id;
            }
        }
        return 0;
    }

    function getStreetId($_file, $_data, $_id)
    {
        foreach ($_data as $obj) {
            $a = preg_quote($obj->name, '/'); //  / -> \/
            $b = preg_quote($_file);
            preg_match('/'.$a.'$/', $b, $match);
            if (!empty($match[0]) && $obj->district_id == $_id) {
                return (int)$obj->id;
            }
        }
        return 0;
    }

    function loadFileLog(){
        $path = Yii::getAlias('@console') . "/data/file_log.json";
        $data = null;
        if(file_exists($path))
            $data = file_get_contents($path);
        else
        {
            $this->writeFileJson($path, null);
            $data = file_get_contents($path);
        }

        if(!empty($data)){
            $data = json_decode($data, true);
            return $data;
        }
        else
            return null;
    }

    function writeFileLog($log){
        $file_name = Yii::getAlias('@console') . '/data/file_log.json';
        $log_data = json_encode($log);
        $this->writeFileJson($file_name, $log_data);
    }

    public function importData()
    {
        $path = Yii::getAlias('@console') . '/data';
        $files = FileHelper::findFiles($path, ['only' => ['*.json']]);
        if (isset($files[0])) {
            print_r('Prepare data...');
            $cityData = AdCity::find()->all();
            $districtData = AdDistrict::find()->all();
            $wardData = AdWard::find()->all();
            $streetData = AdStreet::find()->all();
            $tableName = AdProduct::tableName();
            $columnNameArray = ['category_id','home_no', 'user_id',
                'city_id', 'district_id', 'ward_id', 'street_id',
                'type', 'content', 'area', 'price', 'lat', 'lng',
                'start_date', 'end_date', 'verified', 'created_at'];
            $bulkInsertArray = array();
            print_r('Insert data...');
            foreach ($files as $file) {
                $data = file_get_contents($file);
                $data = json_decode($data, true);
                foreach ($data as $value) {
                    $city_id = $this->getCityId($value["city"], $cityData);
                    $district_id = $this->getDistrictId($value["district"], $districtData, $city_id);
                    $ward_id = $this->getWardId($value["ward"], $wardData, $district_id);
                    $street_id = $this->getStreetId($value["street"], $streetData, $district_id);
                    $record = [
                        'category_id' => 6,
//                        'project_building_id' => 1,
                        'home_no' => $value["home_no"],
                        'user_id' => 3,
                        'city_id' => $city_id,
                        'district_id' => $district_id,
                        'ward_id' => $ward_id,
                        'street_id' => $street_id,
                        'type' => $value["loai_giao_dich"] == 'Thuê' ? 2 : 1,
                        'content' => ''.$value["description"],
                        'area' => $value["dientich"],
                        'price' => $value["price"],
                        'lat' => $value["lat"],
                        'lng' => $value["lng"],
                        'start_date' => $value["start_date"],
                        'end_date' => $value["end_date"],
                        'verified' => 1,
                        'created_at' => time(),

                    ];
                    $bulkInsertArray[] = $record;
                }
            }
            if(count($bulkInsertArray)>0){
                // below line insert all your record and return number of rows inserted
                $insertCount = Yii::$app->db->createCommand()
                    ->batchInsert(
                        $tableName, $columnNameArray, $bulkInsertArray
                    )
                    ->execute();
                return $insertCount;
            }
        }
        else{
            print_r("x_x File not found !");
        }
    }

    public function importData_2()
    {
        $start_time = time();
        $insertCount = 0;
        $log = $this->loadFileLog();
        $start = empty($log["last_import"]) ? 0 : $log["last_import"];
        $path = Yii::getAlias('@console') . '/data/item';
        $files = $log["files"];
        $counter = count($files);
        if ($counter > $start) {
            print_r('Prepare data...');
            $cityData = AdCity::find()->all();
            $districtData = AdDistrict::find()->all();
            $wardData = AdWard::find()->all();
            $streetData = AdStreet::find()->all();
            $tableName = AdProduct::tableName();
            $columnNameArray = ['category_id','home_no', 'user_id',
                'city_id', 'district_id', 'ward_id', 'street_id',
                'type', 'content', 'area', 'price', 'lat', 'lng',
                'start_date', 'end_date', 'verified', 'created_at'];
            $bulkInsertArray = array();
            print_r('Insert data...');
            for($i = $start; $i < $counter; $i++) {
                $log["last_import"] = $i+1;
                $log["last_import_name"] = $files[$i];
                $log["last_import_time"] = date("d/m/Y H:i:s a");
                $this->writeFileLog($log);

                $filename = $path.'/'.$files[$i];
                $data = file_get_contents($filename);
                $data = json_decode($data, true);
                foreach ($data as $value) {
                    $city_id = $this->getCityId($value["city"], $cityData);
                    $district_id = $this->getDistrictId($value["district"], $districtData, $city_id);
                    $ward_id = $this->getWardId($value["ward"], $wardData, $district_id);
                    $street_id = $this->getStreetId($value["street"], $streetData, $district_id);
                    $record = [
                        'category_id' => 6,
//                        'project_building_id' => 1,
                        'home_no' => $value["home_no"],
                        'user_id' => 3,
                        'city_id' => $city_id,
                        'district_id' => $district_id,
                        'ward_id' => $ward_id,
                        'street_id' => $street_id,
                        'type' => $value["loai_giao_dich"] == 'Thuê' ? 2 : 1,
                        'content' => ''.$value["description"],
                        'area' => $value["dientich"],
                        'price' => $value["price"],
                        'lat' => $value["lat"],
                        'lng' => $value["lng"],
                        'start_date' => $value["start_date"],
                        'end_date' => $value["end_date"],
                        'verified' => 1,
                        'created_at' => time(),

                    ];
                    $bulkInsertArray[] = $record;
                }
            }
            if(count($bulkInsertArray)>0){
                // below line insert all your record and return number of rows inserted
                $insertCount = Yii::$app->db->createCommand()
                    ->batchInsert(
                        $tableName, $columnNameArray, $bulkInsertArray
                    )
                    ->execute();
            }
        }
        else{
            print_r("---------------\n");
            print_r("File imported!\n");
            print_r("---------------");
        }
        $end_time = time();
        print_r("\n"." Time: ");
        print_r($end_time-$start_time);
        print_r("s");
        print_r(" - Record: ". $insertCount);
    }

    public function writeFileJson($filePath, $data)
    {
        $handle = fopen($filePath, 'w') or die('Cannot open file:  ' . $filePath);
        $int = fwrite($handle, $data);
        fclose($handle);
        return $int;
    }

    public function readFileJson($filePath)
    {
        $handle = fopen($filePath, 'r') or die('Cannot open file:  ' . $filePath);
        if (filesize($filePath) > 0) {
            $data = fread($handle, filesize($filePath));
            return $data;
        } else return null;
    }


}