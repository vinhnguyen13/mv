<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/8/2015
 * Time: 2:19 PM
 */
namespace console\models;

use Collator;
use DateInterval;
use DateTime;
use keltstr\simplehtmldom\SimpleHTMLDom;
use linslin\yii2\curl\Curl;
use vsoft\ad\models\AdCity;
use vsoft\ad\models\AdContactInfo;
use vsoft\ad\models\AdDistrict;
use vsoft\ad\models\AdImages;
use vsoft\ad\models\AdProduct;
use vsoft\ad\models\AdProductAdditionInfo;
use vsoft\ad\models\AdStreet;
use vsoft\ad\models\AdWard;
use Yii;
use yii\base\Component;
use yii\helpers\FileHelper;

class Batdongsan extends Component
{
    const DOMAIN = 'http://batdongsan.com.vn';
    protected $time_start = 0;
    protected $time_end = 0;
    protected $page_current = 1;

    /**
     * @return mixed
     */
    public static function find()
    {
        return Yii::createObject(Batdongsan::className());
    }

    public function parse()
    {
        ob_start();
        $this->time_start = time();
        $this->getPages();
        $this->time_end = time();
        print_r("\nTime: ");
        print_r($this->time_end - $this->time_start);
    }

    public function getPages()
    {
        $url = self::DOMAIN . '/nha-dat-ban/';
        $page = $this->getUrlContent($url);
        if(!empty($page)) {
            $html = SimpleHTMLDom::str_get_html($page, true, true, DEFAULT_TARGET_CHARSET, false);
            $pagination = $html->find('.container-default .background-pager-right-controls a');
            $count_page = count($pagination);
            if($count_page > 0) {
//                $last_page = str_replace("/nha-dat-ban/p", "", $pagination[$count_page-1]->href);
                $last_page = 3;
                $log = $this->loadFileLog();
                $sequence_id = empty($log["last_id"]) ? 0 : ($log["last_id"]+1);
                for($i = 1; $i <= $last_page; $i++){
                    $page_link = "/nha-dat-ban/p" . $i;
                    $log = $this->getListProject($page_link, $sequence_id, $log);
                    if(!empty($log)) {
                        $this->writeFileLog($log);
                    }
                    echo "Scraping done: ".self::DOMAIN.$page_link;
                    echo "\n";
                    sleep(1);
                }
            } else {
                echo "Cannot find pagination of _".self::DOMAIN;
            }
        } else {
            echo "Cannot access in get pages of ".self::DOMAIN;
        }
    }

    public function getListProject($href, $sequence_id, $log)
    {
        $page = $this->getUrlContent(self::DOMAIN . $href);
        if(!empty($page)) {
            $html = SimpleHTMLDom::str_get_html($page, true, true, DEFAULT_TARGET_CHARSET, false);
            $list = $html->find('div.p-title a');
            if (!empty($list)) {
                // about 20 listing
                foreach ($list as $item) {
                    $startIndex = strripos($item->href, '-pr');
                    $productId = substr($item->href, $startIndex, strlen($item->href));
                    $productId = str_replace('-pr', '', $productId);
                    $checkExists = false;
                    if(!empty($log)) {
                        $checkExists = in_array($productId, $log["files"]);
                    }
                    if ($checkExists == false) {
                        $res = $this->getProjectDetail($item->href);
                        if (!empty($res)) {
                            $log["files"][$sequence_id] = $res;
                            $log["last_id"] = $sequence_id;
                            $sequence_id++;
                        }
                    }
                }
                return $log;
            } else {
                echo "Cannot find get title link of _".self::DOMAIN;
            }

        } else {
            echo "Cannot access in get List Project of ".self::DOMAIN;
        }
        return null;
    }

    public function getProjectDetail($href)
    {
        $json = array();
        $page = $this->getUrlContent(self::DOMAIN . $href);
        if(!empty($page)) {
            $detail = SimpleHTMLDom::str_get_html($page, true, true, DEFAULT_TARGET_CHARSET, false);
            if (!empty($detail)) {
//                $title = $detail->find('h1', 0)->innertext;
                $lat = $detail->find('#hdLat', 0)->value;
                $long = $detail->find('#hdLong', 0)->value;
                $product_id = $detail->find('.pm-content', 0)->cid;
                $content = $detail->find('.pm-content', 0)->innertext;

                $gia = trim($detail->find('.gia-title', 0)->plaintext);
                $price = 0;
                if(strpos($gia, ' triệu')){
                    $gia = str_replace('Giá:', '', $gia);
                    $gia = str_replace(' triệu&nbsp;', '', $gia);
                    $gia = trim($gia);
                    $price = $gia * 1000000;
                }
                else if(strpos($gia, ' tỷ')){
                    $gia = str_replace('Giá:', '', $gia);
                    $gia = str_replace(' tỷ&nbsp;', '', $gia);
                    $gia = trim($gia);
                    $price = $gia * 1000000000;
                }

                $dientich = trim($detail->find('.gia-title', 1)->plaintext);
                $dt = 0;
                if(strpos($dientich, 'm²')){
                    $dientich = str_replace('m²', '', $dientich);
                    $dientich = str_replace('Diện tích:', '', $dientich);
                    $dientich = trim($dientich);
                    $dt = $dientich;
                }

                $imgs = $detail->find('#thumbs li img');
                $thumbs = array();
                if(count($imgs) > 0) {
                    foreach ($imgs as $img) {
                        $img_link = str_replace('80x60', '745x510', $img->src);
                        array_push($thumbs, $img_link);
                    }
                }

                $left_detail = $detail->find('.pm-content-detail .left-detail', 0);
                $div_info = $left_detail->find('div div');
                $left = '';
                $arr_info = [];
                foreach ($div_info as $div) {
                    $class = $div->class;
                    if (!(empty($class))) {
                        if ($class == 'left')
                            $left = trim($div->innertext);
                        else if ($class == 'right') {
                            if(array_key_exists($left, $arr_info)){
                                $right = $left.'_1';
                            }
                            $arr_info[$left] = trim($div->plaintext);
                        }
                    }
                }

                $address = mb_split(',', $arr_info["Địa chỉ"]);
                $city = $address[count($address)-1];
                $district = $address[count($address)-2];
//                $ward = $address[count($address)-3];
//                $street = $address[count($address)-4];

                $startdate = trim($arr_info["Ngày đăng tin"]);
                $startdate = strtotime($startdate);
                $startdate = date('Y-m-d', $startdate);

                $endate = trim($arr_info["Ngày hết hạn"]);
                $endate = strtotime($endate);
                $endate = date('Y-m-d', $endate);

                $loai_tin = trim($arr_info["Loại tin rao"]);
                $loai_tai_san = 6;
                if($loai_tin == "Bán căn hộ chung cư"){
                    $loai_tai_san = 6;
                }
                else if($loai_tin == "Bán nhà riêng"){
                    $loai_tai_san = 7;
                }

                $contact = $detail->find('.pm-content-detail #divCustomerInfo', 0);
                $div_contact = $contact->find('div.right-content div');
                $right = '';
                $arr_contact = [];
                foreach ($div_contact as $div) {
                    $class = $div->class;
                    if (!(empty($class))) {
                        if (strpos($class,'left') == true) {
                            $right = (string)$div->plaintext;
                            $right = trim($right);
                        }
                        else if($class == 'right') {
                            if(array_key_exists($right, $arr_contact)){
                                $right = $right.'_1';
                            }
                            $value = (string)$div->innertext;
                            $arr_contact[$right] = trim($value);
                        }
                    }
                }
                $json["nha-dat-ban"][$product_id] = [
                    'lat' => trim($lat),
                    'lng' => trim($long),
                    'description' => trim($content),
                    'thumbs' => $thumbs,
                    'info' => $arr_info,
                    'contact' => $arr_contact,
                    'city' => trim($city),
                    'district' => trim($district),
//                    'ward' => null,
//                    'street' => null,
                    'loai_tai_san' => $loai_tai_san,
                    'loai_giao_dich' => 1,
                    'price' => $price,
                    'dientich' => $dt,
                    'start_date' => $startdate,
                    'end_date' => $endate
                ];

                $path = Yii::getAlias('@console') . '/data/bds/';
                if(!is_dir($path)){
                    mkdir($path , 0777);
                    echo "Directory {$path} was created";
                }
                $data = json_encode($json);
                $res = $this->writeFileJson($path.$product_id, $data);
                if($res)
                    return $product_id;
                else
                    return null;
            }
            else {
                echo "Cannot find detail at " .self::DOMAIN.$href;
            }
        }
    }

    function getCityId($cityFile, $cityDB)
    {
        foreach ($cityDB as $obj) {
            $c = new Collator('vi_VN');
            $check = $c->compare($cityFile, $obj->name);
            if ($check == 0) {
                return (int)$obj->id;
            }
        }
        return 0;
    }

    function getDistrictId($districtFile, $districtDB, $city_id)
    {
        foreach ($districtDB as $obj) {
            $c = new Collator('vi_VN');
            $check = $c->compare($districtFile, $obj->name);
            if ($check == 0 && $obj->city_id == $city_id) {
                return (int)$obj->id;
            }
        }
        return 0;
    }

    function getWardId($_file, $_data, $_id)
    {
        foreach ($_data as $obj) {
            $c = new Collator('vi_VN');
            $check = $c->compare($_file, $obj->name);
            if ($check == 0 && $obj->district_id == $_id) {
                return (int)$obj->id;
            }
        }
        return 0;
    }

    function getStreetId($_file, $_data, $_id)
    {
        foreach ($_data as $obj) {
            $c = new Collator('vi_VN');
            $check = $c->compare($_file, $obj->name);
            if ($check == 0 && $obj->district_id == $_id) {
                return (int)$obj->id;
            }
        }
        return 0;
    }

    function loadFileLog(){
        $path = Yii::getAlias('@console') . "/data/bds_log.json";
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
        $file_name = Yii::getAlias('@console') . '/data/bds_log.json';
        $log_data = json_encode($log);
        $this->writeFileJson($file_name, $log_data);
    }

    public function importData()
    {
        $start_time = time();
        $insertCount = 0;
        $log = $this->loadFileLog();
        $start = empty($log["last_import"]) ? 0 : $log["last_import"];
        $path = Yii::getAlias('@console') . '/data/bds';
        $files = $log["files"];
        $counter = count($files);
        if ($counter > $start) {
            print_r('Prepare data...');
            $cityData = AdCity::find()->all();
            $districtData = AdDistrict::find()->all();
//            $wardData = AdWard::find()->all();
//            $streetData = AdStreet::find()->all();
            $tableName = AdProduct::tableName();
            $columnNameArray = ['category_id','home_no', 'user_id',
                'city_id', 'district_id',
                'type', 'content', 'area', 'price', 'lat', 'lng',
                'start_date', 'end_date', 'verified', 'created_at'];
            $bulkInsertArray = array();
            $imageArray = array();
            $infoArray = array();
            $contactArray = array();
            print_r('Insert data...');
            for($i = $start; $i < $counter; $i++) {
                $log["last_import"] = $i+1;
                $log["last_import_name"] = $files[$i];
                $log["last_import_time"] = date("d/m/Y H:i:s a");
                $this->writeFileLog($log);

                $filename = $files[$i];
                $filePath = $path.'/'.$filename;
                $data = file_get_contents($filePath);
                $data = json_decode($data, true);
                foreach ($data as $value) {
                    $imageArray[$i] = $value[$filename]["thumbs"];
                    $infoArray[$i] = $value[$filename]["info"];
                    $contactArray[$i] = $value[$filename]["contact"];

                    $city_id = $this->getCityId($value[$filename]["city"], $cityData);
                    $district_id = $this->getDistrictId($value[$filename]["district"], $districtData, $city_id);
//                    $ward_id = $this->getWardId($value[$filename]["ward"], $wardData, $district_id);
//                    $street_id = $this->getStreetId($value[$filename][$filename]["street"], $streetData, $district_id);
                    $record = [
                        'category_id' => $value[$filename]["loai_tai_san"],
//                        'project_building_id' => 1,
                        'home_no' => null,
                        'user_id' => 3,
                        'city_id' => $city_id,
                        'district_id' => $district_id,

                        'type' => $value[$filename]["loai_giao_dich"],
                        'content' => ''.$value[$filename]["description"],
                        'area' => $value[$filename]["dientich"],
                        'price' => $value[$filename]["price"],
                        'lat' => $value[$filename]["lat"],
                        'lng' => $value[$filename]["lng"],
                        'start_date' => $value[$filename]["start_date"],
                        'end_date' => $value[$filename]["end_date"],
                        'verified' => 1,
                        'created_at' => $value[$filename]["start_date"],

                    ];
                    $bulkInsertArray[] = $record;
                }
            }
            if(count($bulkInsertArray)>0){
                // below line insert all your record and return number of rows inserted
                $insertCount = Yii::$app->db->createCommand()
                    ->batchInsert(
                        $tableName, $columnNameArray, $bulkInsertArray
                    )->execute();

                $ad_image_columns = ['user_id', 'product_id', 'file_name', 'uploaded_at'];
                $ad_info_columns = ['product_id', 'floor_no', 'room_no', 'toilet_no'];
                $ad_contact_columns = ['product_id', 'name', 'phone', 'mobile', 'address'];

                $bulkImage = array();
                $bulkInfo = array();
                $bulkContact = array();

                $fromProductId = Yii::$app->db->getLastInsertID();
                $toProductId = $fromProductId + $insertCount - 1;

                $index = $start;
                for($i = $fromProductId; $i <= $toProductId; $i++){

                    foreach($imageArray[$index] as $imageValue){
                        $imageRecord = [
                            'user_id' => 3,
                            'product_id' => $i,
                            'file_name' => $imageValue,
                            'upload_at' => time()
                        ];
                        $bulkImage[] = $imageRecord;
                    }

                    $floor_no = empty($infoArray[$index]["Số tầng"]) == false ? trim(str_replace('(tầng)','', $infoArray[$index]["Số tầng"])) : 0;
                    $room_no = empty($infoArray[$index]["Số phòng ngủ"]) == false ? trim(str_replace('(phòng)','', $infoArray[$index]["Số phòng ngủ"])) : 0;
                    $toilet_no = empty($infoArray[$index]["Số toilet"]) == false ? trim($infoArray[$index]["Số toilet"]) : 0 ;
                    $infoRecord = [
                        'product_id' => $i,
                        'floor_no' => $floor_no,
                        'room_no' => $room_no,
                        'toilet_no' => $toilet_no
                    ];
                    $bulkInfo[] = $infoRecord;

                    $name = empty($contactArray[$index]["Tên liên lạc"]) == false ? trim($contactArray[$index]["Tên liên lạc"]) : null;
                    $phone = empty($contactArray[$index]["Điện thoại"]) == false ? trim($contactArray[$index]["Điện thoại"]) : null;
                    $mobile = empty($contactArray[$index]["Mobile"]) == false ? trim($contactArray[$index]["Mobile"]) : null;
                    $address = empty($contactArray[$index]["Địa chỉ"]) == false ? trim($contactArray[$index]["Địa chỉ"]) : null;
                    $contactRecord = [
                        'product_id' => $i,
                        'name' => $name,
                        'phone' => $phone,
                        'mobile' => $mobile,
                        'address' => $address
                    ];
                    $bulkContact[] = $contactRecord;

                    $index = $index + 1;
                }

                // execute image, info, contact
                $imageCount = Yii::$app->db->createCommand()
                    ->batchInsert(AdImages::tableName(), $ad_image_columns, $bulkImage)
                    ->execute();
                if($imageCount > 0)
                    print_r("\nInser image done");

                $infoCount = Yii::$app->db->createCommand()
                    ->batchInsert(AdProductAdditionInfo::tableName(), $ad_info_columns, $bulkInfo)
                    ->execute();
                if($infoCount > 0)
                    print_r("\nInser product addition info done");

                $contactCount = Yii::$app->db->createCommand()
                    ->batchInsert(AdContactInfo::tableName(), $ad_contact_columns, $bulkContact)
                    ->execute();
                if($contactCount > 0)
                    print_r("\nInser contact info done");
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

    function getUrlContent($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($httpcode >= 200 && $httpcode < 300) ? $data : null;
    }

}