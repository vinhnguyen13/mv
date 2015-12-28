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
        $start = time();
        $content = SimpleHTMLDom::file_get_html(self::DOMAIN . '/developer/');
        if(!empty($content)) {
            $list = $content->find('.body-cont .img-project-inside a');
            if (!empty($list)) {
                $path = Yii::getAlias('@console') . "/data/homefinder/";
                if (!is_dir($path)) {
                    mkdir($path, 0777);
                    echo "\nDirectory {$path} was created";
                }

                foreach ($list as $idx => $item) {
                    if ($item->href && $idx >= 0) {
//                    echo $item->title;
                        $this->getListProject($item->href, $item->title);
                    }
                }
            }
        }
        $end = time();
        echo "\nDeveloper runnning: " . ($end - $start);
    }

    public function getListProject($href, $developer)
    {
        $content = SimpleHTMLDom::file_get_html(self::DOMAIN . $href);
        if(!empty($content)) {
            $list = $content->find('.menu ul.list_project li');
            if (!empty($list)) {
                foreach ($list as $item) {
                    $start = time();
                    $log = $this->loadFileLog();
                    if(empty($log) == true ) $log["projects"] = array();

                    $projectId = null;
                    $attr = $item->getAttribute("ng-class");
                    if (!empty($attr)) {
                        $projectId = str_replace("{active : projectId == '", "", $attr);
                        $projectId = str_replace("'}", "", $projectId);
                    }

                    $a = $item->find('a');
                    if (!empty($projectId) && count($a) > 0) {
                        $project_href = $a[0]->href;
                        $project_name = str_replace("/project/", "", $project_href);
                        if(!in_array($project_name, $log["projects"])) {
                            $path = Yii::getAlias('@console') . "/data/homefinder/{$project_name}/";
                            if (!is_dir($path)) {
                                mkdir($path, 0777);
                            }
                            $this->getProjectInfo($projectId, $log, $project_name, $path, $project_href);
                        }
                    }
                    $end = time();
                    print_r("\n");
                    print_r("{$developer}: ".$a[0]->innertext() . " ... Done! ".($end - $start)."s");
                    print_r("\n");
                    sleep(1);
                    ob_flush();
                }
            }
        }

    }

    // Ghi thong tin cua Du an
    public function getProjectInfo($projectId, $log, $project_name, $path, $project_href){
        $curl = new Curl();
        $curl->setOption(CURLOPT_REFERER, self::DOMAIN."/project/".$project_name);
        $curl->setOption(CURLOPT_USERAGENT, "homefinder.vn");
        $lat = null;
        $lon = null;
        $home_no = 0;
        $url = "http://homefinder.vn/ajax/projectrender/".$projectId;
        $projectInfo = $curl->get($url);
        if(!empty($projectInfo)) {
            $filename = $project_name . '-info.json';
            if ($this->writeFileJson($path . $filename, $projectInfo) > 0) {
                $log[$project_name]['info'] = $filename;
            }
            $projectInfo = json_decode($projectInfo, true);
            $gioi_thieu = $projectInfo["gioi_thieu"];
            if(!empty($gioi_thieu)){
                $gioi_thieu = $projectInfo["displayname"];
            }
            $home_no = $projectInfo["so_nha"];
            $pos_home_no = strpos($home_no,"-");
            if($pos_home_no > 0){
                $home_no = trim(substr($home_no, 0, $pos_home_no));
            }
            if(!empty($projectInfo["location"])){
                $lat = !empty($projectInfo["location"]["lat"]) ? $projectInfo["location"]["lat"] : null;
                $lon = !empty($projectInfo["location"]["lon"]) ? $projectInfo["location"]["lon"] : null;
                if(!empty($lat) && !empty($lon)){
                    $url_poi = "http://homefinder.vn/place/atm,school,bus_station,hospital,grocery_or_supermarket/gd:{$lat},{$lon}_1/500";
                    $response_poi = $curl->get($url_poi);
                    if(!empty($response_poi)) {
                        $poi = json_decode($response_poi, true);
                        if(!empty($poi["data"])) {
                            $poi["projectId"] = $projectId;
                            $data_response_poi = json_encode($poi);
                            $filePOI = $project_name . '-poi.json';
                            if ($this->writeFileJson($path . $filePOI, $data_response_poi) > 0) {
                                $log[$project_name]['poi'] = $filePOI;
                            }
                        }
                    }
                }

            }
        }

        $url_phone_broker = self::DOMAIN . '/ajax/projecttopbroker/' . $projectId;
        $response_phone_broker = $curl->get($url_phone_broker);
        if(!empty($response_phone_broker)) {
            $phone_broker = json_decode($response_phone_broker, true);
            if(!empty($phone_broker["data"])) {
                $filename = $project_name . '-phone-broker.json';
                $phone_broker["projectId"] = $projectId;
                $data_phone_broker = json_encode($phone_broker);
                if ($this->writeFileJson($path . $filename, $data_phone_broker) > 0) {
                    $log[$project_name]["phone-broker"] = $filename;
                }
            }
        }

        $url_cost = self::DOMAIN . '/ajax/projectdata/' . $projectId . '/cost';
        $response_cost = $curl->get($url_cost);
        if(!empty($response_cost)) {
            $response_cost = json_decode($response_cost, true);
            if (!empty($response_cost["aggs"]["ban"]["buckets"])) {
                $data_response_cost = json_encode($response_cost);
                $filename = $project_name . '-cost.json';
                if ($this->writeFileJson($path . $filename, $data_response_cost) > 0) {
                    $log[$project_name]["cost"] = $filename;
                }
            }
        }

        $not_info = true;
        $url_broker = self::DOMAIN . '/ajax/projectdata/' . $projectId . '/broker';
        $response_broker = $curl->get($url_broker);
        if(!empty($response_broker)) {
            $response_broker = json_decode($response_broker, true);
            if (!empty($response_broker["aggs"]["tsp"]["buckets"])) {
                $data_response_broker = json_encode($response_broker);
                $filename = $project_name . '-broker.json';
                if ($this->writeFileJson($path . $filename, $data_response_broker) > 0) {
                    $log[$project_name]["broker"] = $filename;
                }

                if (!empty($response_broker["table"][0])) {
                    $response_broker_table = $response_broker["table"][0];
                    $city = trim($response_broker_table["cname"]);
                    $district = trim($response_broker_table["dname"]);
                    $ward = trim($response_broker_table["wname"]);
                    $street = trim($response_broker_table["sname"]);
                    $description = trim($response_broker_table["mo_ta_chi_tiet"]);
                    $description = str_replace('<div id="LeftMainContent__productDetail_panelTag" class="tagpanel"></div>', '', $description);
                    $description = str_replace('<div id="LeftMainContent__productDetail_panelTag" class="tagpanel">', '', $description);
//                    $home_no = trim($response_broker_table["so_nha"]);
                    $lat = $response_broker_table["lat"];
                    $lon = $response_broker_table["lon"];
                    $this->getProjectDetail($project_href, $project_name, $city, $district, $ward, $street, $description, $home_no, $lat, $lon);
                    $log = $this->loadFileLog();
                    array_push($log["projects"], $project_name);
                    $this->writeFileLog($log);
                    $not_info = false;
                }
            } else {
                $url_info = "http://homefinder.vn/projectinfo/{$projectId}";
                $response_info = $curl->get($url_info);
                if(!empty($response_info)) {
                    $response_info = json_decode($response_info, true);
                    if(!empty($response_info["proj_address"])) {
                        $address = $response_info["proj_address"];
                        $arr_address = split(',', $address);
                        if(count($arr_address)>= 3) {
                            $city = trim($arr_address[3]);
                            $district = trim($arr_address[2]);
                            $ward = trim($arr_address[1]);
                            $street = trim($arr_address[0]);
                            $this->getProjectDetail($project_href, $project_name, $city, $district, $ward, $street, $gioi_thieu, $home_no, $lat, $lon);
                            $log = $this->loadFileLog();
                            if(empty($log["projects"])) $log["projects"] = array();
                            array_push($log["projects"], $project_name);
                            $this->writeFileLog($log);
                            $not_info = false;
                        }
                    }
                }
            }
        }

        if($not_info){
            array_push($log["projects"], $project_name);
            $this->writeFileLog($log);
        }
    }

    // Chi tiet cac Bai dang tin
    public function getProjectDetail($href, $project_name, $city, $district, $ward, $street, $description, $home_no, $lat, $lon)
    {
        $content = SimpleHTMLDom::file_get_html(self::DOMAIN . $href);
        $list = $content->find('script');
        if (!empty($list)) {
            foreach ($list as $item) {
                $varname = "pId";
                preg_match('/' . $varname . '\s*?=\s*?(.*)\s*?(;|$)/msU', $item->innertext(), $matches);
                if (!empty($matches[1])) {
                    $room_id = $matches[1];
                    $room_id = str_replace('"', '', $room_id);
                    $project_log = $this->loadProjectFileLog($project_name);
                    if(empty($project_log[$project_name]))
                        $project_log[$project_name]["files"] = array();
                    $this->pagingListing($room_id, $this->page_current, $project_log, $project_name, $city, $district, $ward, $street, $description, $home_no, $lat, $lon);
                }
            }
        }
    }
    // Xu ly lay tin trong trang chi tiet
    public function pagingListing($pid, $page_current, $log, $project_name, $city, $district, $ward, $street, $description, $home_no, $lat, $lon)
    {
        $url = self::DOMAIN . '/ajax/projecttable/' . $pid . '/ban?draw=1&columns[0][data]=hinh_anh&columns[0][name]=' .
            '&columns[0][searchable]=true&columns[0][orderable]=true&columns[0][search][value]=&columns[0][search][regex]=false' .
            '&columns[1][data]=cost&columns[1][name]=&columns[1][searchable]=true&columns[1][orderable]=true&columns[1][search][value]=' .
            '&columns[1][search][regex]=false&columns[2][data]=dien_tich_quy_hoach&columns[2][name]=&columns[2][searchable]=true&columns[2][orderable]=true' .
            '&columns[2][search][value]=&columns[2][search][regex]=false&columns[3][data]=ngay_dang&columns[3][name]=&columns[3][searchable]=true&columns[3][orderable]=true' .
            '&columns[3][search][value]=&columns[3][search][regex]=false&columns[4][data]=broker.name&columns[4][name]=&columns[4][searchable]=true&columns[4][orderable]=true' .
            '&columns[4][search][value]=&columns[4][search][regex]=false&columns[5][data]=_id&columns[5][name]=&columns[5][searchable]=true&columns[5][orderable]=true' .
            '&columns[5][search][value]=&columns[5][search][regex]=false&order[0][column]=3&order[0][dir]=desc&start=' . ($page_current * 5) . '&length=5&search[value]=&search[regex]=false&_=' . time();

        $curl = new Curl();
        $response = $curl->get($url);
        if (($response = json_decode($response)) && !empty($response->data)) {
            $totalItem = count($response->data);
            foreach ($response->data as $item) {
                $checkExists = in_array($item->_id, $log[$project_name]["files"]);
                if($checkExists) {
                    continue;
                }
                else {
                    $price = $item->gia;
                    // lay chi tiet trong danh sach bai dang tin
//                    $res = $this->getListingDetail(self::DOMAIN . '/' . $item->_id, $item, $project_name, $city, $district, $ward, $street, $description, $home_no, $lat, $lon, $price);
                    $arr_detail = array();
//                    $htmlDetail = SimpleHTMLDom::file_get_html($url);
                    $currency_value = 1000000; // 1 trieu
                    // item_id
                    $arr_detail[$project_name]["item_id"] = trim($item->_id);
                    // dien tich
                    $arr_detail[$project_name]["dientich"] = trim($item->dien_tich_quy_hoach);

//                    // tien ich phong ngu , toilet
//                    $util = $htmlDetail->find('.left .line1', 0);
//                    if (!empty($util)) {
//                        $arr_util = explode(',', $util->plaintext);
//                        if (!empty($arr_util[1])) {
//                            $room_no = str_replace('Bedroom', '', $arr_util[1]);
//                            $arr_detail[$project_name]["room_no"] = trim($room_no);
//                        }
//                        if (!empty($arr_util[2])) {
//                            $toilet_no = str_replace('tolet', '', $arr_util[2]);
//                            $arr_detail[$project_name]["toilet_no"] = trim($toilet_no);
//                        }
//                    }
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
                    if(!empty($item->broker->name))
                        $arr_detail[$project_name]["broker"] = $item->broker->name;
                    if(!empty($item->broker->phone[0]))
                        $arr_detail[$project_name]["phone"] = str_replace(" ", "", $item->broker->phone[0]);
                    $start_date = trim($item->ngay_dang);
                    $start_date = substr($start_date, 0, 10);
                    $arr_detail[$project_name]["start_date"] = strtotime($start_date);
                    $nDays = 30;
                    $end_date = new DateTime($start_date);
                    $end_date->add(new DateInterval('P' . $nDays . 'D'));
                    $arr_detail[$project_name]["end_date"] = $end_date->getTimestamp();

                    $filename = $item->_id;
                    $path = Yii::getAlias('@console') . "/data/homefinder/{$project_name}/files/";
                    if(!is_dir($path)){
                        mkdir($path , 0777);
                    }
                    $path = $path . $filename;
                    $data = json_encode($arr_detail);
                    $res = $this->writeFileJson($path, $data);
                    if ($res > 0 && !empty($filename)) {
                        array_push($log[$project_name]["files"],$filename);
                    }
                }
            }
            $this->writeProjectFileLog($project_name, $log);

            if (!empty($response->recordsTotal) && ($response->recordsTotal > ($totalItem * $page_current))) {
                $page_current++;
                $log = $this->loadProjectFileLog($project_name);
                $this->pagingListing($pid, $page_current, $log, $project_name, $city, $district, $ward, $street, $description, $home_no, $lat, $lon);
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
        if(!empty($item->broker->name))
            $arr_detail[$project_name]["broker"] = $item->broker->name;
        if(!empty($item->broker->phone[0]))
            $arr_detail[$project_name]["phone"] = str_replace(" ", "", $item->broker->phone[0]);
        $start_date = trim($item->ngay_dang);
        $start_date = substr($start_date, 0, 10);
        $arr_detail[$project_name]["start_date"] = strtotime($start_date);
        $nDays = 30;
        $end_date = new DateTime($start_date);
        $end_date->add(new DateInterval('P' . $nDays . 'D'));
        $arr_detail[$project_name]["end_date"] = $end_date->getTimestamp();

        $filename = $item->_id;
        $path = Yii::getAlias('@console') . "/data/homefinder/{$project_name}/files/";
        if(!is_dir($path)){
            mkdir($path , 0777);
        }
        $path = $path . $filename;
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

//        $path = Yii::getAlias('@console') . "/data/file_log.json";
        $path = Yii::getAlias('@console') . "/data/homefinder/homefinder_log.json";
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
        $file_name = Yii::getAlias('@console') . '/data/homefinder/homefinder_log.json';
        $log_data = json_encode($log);
        $this->writeFileJson($file_name, $log_data);
    }

    function loadProjectFileLog($project_name){
        $path = Yii::getAlias('@console') . "/data/homefinder/{$project_name}/{$project_name}.json";
        $data = null;
        if(file_exists($path)) {
            $data = file_get_contents($path);
        }
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

    function writeProjectFileLog($project_name, $log){
        $file_name = Yii::getAlias('@console') . "/data/homefinder/{$project_name}/{$project_name}.json";
        $log_data = json_encode($log);
        $this->writeFileJson($file_name, $log_data);
    }

    public function importData_2()
    {
        $start_time = time();
        $insertCount = 0;
        $log = $this->loadFileLog();
        $start_project = empty($log["last_project_index"]) ? 0 : $log["last_project_index"];
        $counter_project = count($log["projects"]);

        if ($counter_project > $start_project) {
            print_r('Prepare data...');
            $cityData = AdCity::find()->all();
            $districtData = AdDistrict::find()->all();
            $wardData = AdWard::find()->all();
            $streetData = AdStreet::find()->all();
            $tableName = AdProduct::tableName();
            $columnNameArray = ['category_id', 'home_no', 'user_id',
                'city_id', 'district_id', 'ward_id', 'street_id',
                'type', 'content', 'area', 'price', 'lat', 'lng',
                'start_date', 'end_date', 'verified', 'created_at'];
            $bulkInsertArray = array();
            print_r('Insert data...');
            for ($i = $start_project; $i < $counter_project; $i++) {
                $project_name = $log["projects"][$i];
                if (!empty($project_name)) {
//                    $path = Yii::getAlias('@console') . "/data/homefinder/{$project_name}";
//                    $project_files = $this->loadProjectFileLog($project_name);
                    $path = Yii::getAlias('@console') . "/data/homefinder/{$project_name}/files";
                    $project_files = scandir($path, 1);
                    $start_file = empty($log["last_file_index"]) ? 0 : $log["last_file_index"];
                    $counter_file = count($project_files) - 3; // last file except .. and .
                    if ($counter_file > $start_file) {
                        for ($j = $start_file; $j < 500 ; $j++) {
                            $log["last_project_index"] = $i;
                            $log["last_file_index"] = $j;
                            $log["last_import_time"] = date("d-m-Y H:i");
                            $this->writeFileLog($log);

                            $filename = $path . '/' . $project_files[$j];
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
                                    'content' => '' . $value["description"],
                                    'area' => $value["dientich"],
                                    'price' => $value["price"],
                                    'lat' => $value["lat"],
                                    'lng' => $value["lng"],
                                    'start_date' => $value["start_date"],
                                    'end_date' => $value["end_date"],
                                    'verified' => 1,
                                    'created_at' => $value["start_date"],

                                ];
                                $bulkInsertArray[] = $record;
                            }
                        } // end items loop
                    } // end project["files"];
                } // end projects loop
                if (count($bulkInsertArray) > 0) {
                    // below line insert all your record and return number of rows inserted
                    $insertCount = Yii::$app->db->createCommand()
                        ->batchInsert($tableName, $columnNameArray, $bulkInsertArray)
                        ->execute();
                }
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