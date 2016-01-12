<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/8/2015
 * Time: 2:19 PM
 */
namespace console\models;

use Collator;
use frontend\models\User;
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

class BatdongsanV2 extends Component
{
    const DOMAIN = 'http://batdongsan.com.vn';
    const TYPE = ['nha-dat-ban-quan-1','nha-dat-ban-quan-2','nha-dat-ban-quan-3','nha-dat-ban-quan-4','nha-dat-ban-quan-5','nha-dat-ban-quan-6',
                'nha-dat-ban-quan-7','nha-dat-ban-quan-8', 'nha-dat-ban-quan-9','nha-dat-ban-quan-10','nha-dat-ban-quan-11','nha-dat-ban-quan-12',
                'nha-dat-ban-binh-chanh','nha-dat-ban-binh-tan','nha-dat-ban-binh-thanh','nha-dat-ban-can-gio','nha-dat-ban-cu-chi','nha-dat-ban-go-vap',
                'nha-dat-ban-hoc-mon','nha-dat-ban-nha-be','nha-dat-ban-tan-binh','nha-dat-ban-tan-phu','nha-dat-ban-phu-nhuan','nha-dat-ban-thu-duc'];
    protected $time_start = 0;
    protected $time_end = 0;

    /**
     * @return mixed
     */
    public static function find()
    {
        return Yii::createObject(BatdongsanV2::className());
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
        $bds_log = $this->loadBdsLog();
        if(empty($bds_log["type"])){
            $bds_log["type"] = array();
        }
        $last_type = empty($bds_log["last_type_index"]) ? 0 : ($bds_log["last_type_index"] + 1);
        $count_type = count(self::TYPE) - 1;
        if($last_type > $count_type) $last_type = 0;
        foreach (self::TYPE as $key_type => $type) {
            if ($key_type >= $last_type) {
                $url = self::DOMAIN . '/' . $type;
                $page = $this->getUrlContent($url);
                if (!empty($page)) {
                    $html = SimpleHTMLDom::str_get_html($page, true, true, DEFAULT_TARGET_CHARSET, false);
                    $pagination = $html->find('.container-default .background-pager-right-controls a');
                    $count_page = count($pagination);
                    $last_page = (int)str_replace("/" . $type . "/p", "", $pagination[$count_page - 1]->href);
                    if ($count_page > 0) {
                        $log = $this->loadFileLog($type);
                        $current_page = empty($log["current_page"]) ? 1 : ($log["current_page"] + 1);

                        $current_page_add = $current_page + 4; // +4 => total page to run are 5.
                        if($current_page_add > $last_page)
                            $current_page_add = $last_page;

                        if ($current_page <= $last_page) {
                            for ($i = $current_page; $i <= $current_page_add; $i++) {
                                $log = $this->loadFileLog($type);
                                $sequence_id = empty($log["last_id"]) ? 0 : ($log["last_id"] + 1);
                                $list_return = $this->getListProject($type, $i, $sequence_id, $log);
                                if (!empty($list_return["data"])) {
                                    $list_return["data"]["current_page"] = $i;
                                    $this->writeFileLog($type, $list_return["data"]);
                                    print_r("\n{$type}-page " . $i . " done!\n");
                                }
                                sleep(1);
                                ob_flush();
                            }
                            break;
                        } else {
                            $log = $this->loadFileLog($type);
                            if(!empty($log["data"]["current_page"]))
                                $log["data"]["current_page"] = 0;
                            $this->writeFileLogFail($type, "\nPaging end: Current:$current_page_add , last:$last_page" . "\n");
                        }
                    } else {
                        echo "\nCannot find listing. End page!" . self::DOMAIN;
                        $this->writeFileLogFail($type, "\nCannot find listing: $url" . "\n");
                    }
                } else {
                    echo "\nCannot access in get pages of " . self::DOMAIN;
                    $this->writeFileLogFail($type, "\nCannot access: $url" . "\n");
                }

                if(!in_array($type, $bds_log["type"])) {
                    array_push($bds_log["type"], $type);
                }
                $bds_log["last_type_index"] = $key_type;
                $this->writeBdsLog($bds_log);
                print_r("\nTYPE: {$type} DONE!\n");
            }
        }
    }

    public function getListProject($type, $current_page, $sequence_id, $log)
    {
        $href = "/".$type."/p".$current_page;
        $page = $this->getUrlContent(self::DOMAIN . $href);
        if(!empty($page)) {
            $html = SimpleHTMLDom::str_get_html($page, true, true, DEFAULT_TARGET_CHARSET, false);
            $list = $html->find('div.p-title a');
            if (count($list) > 0) {
                // about 20 listing
                foreach ($list as $item) {
                    if (preg_match('/pr(\d+)/', self::DOMAIN . $item->href, $matches)) {
                        if(!empty($matches[1])){
                            $productId = $matches[1];
                        }
                    }
                    $checkExists = false;
                    if(!empty($productId) && !empty($log["files"])) {
                        $checkExists = in_array($productId, $log["files"]);
                    }

                    if ($checkExists == false) {
                        $res = $this->getProjectDetail($type, $item->href);
                        if (!empty($res)) {
                            $log["files"][$sequence_id] = $res;
                            $log["last_id"] = $sequence_id;
                            $sequence_id = $sequence_id + 1;
                        }
                    } else {
                        if(empty($log["p".$current_page]["duplicate"])){
                            $log["p".$current_page]["duplicate"] = array();
                        }
                        array_unshift($log["p".$current_page]["duplicate"], $productId);
                        $log["p".$current_page]["total"] = count($log["p".$current_page]["duplicate"]);
                        var_dump($productId);
                    }
                }
                return ['data' => $log];
            } else {
                echo "Cannot find listing. End page!".self::DOMAIN;
                $this->writeFileLogFail($type, "Cannot find listing: $href"."\n");
            }

        } else {
            echo "Cannot access in get List Project of ".self::DOMAIN;
            $this->writeFileLogFail($type, "Cannot access: $href"."\n");
        }
        return null;
    }

    public function getProjectDetail($type, $href)
    {
        $page = $this->getUrlContent(self::DOMAIN . $href);
        $matches = array();
        if (preg_match('/pr(\d+)/', self::DOMAIN . $href, $matches)) {
            if(!empty($matches[1])){
                $product_id = $matches[1];
            }
        }

        if(!empty($product_id)) {
                $path = Yii::getAlias('@console') . "/data/bds_html/{$type}/files/";
                if(!is_dir($path)){
                    mkdir($path , 0777, true);
                    echo "Directory {$path} was created";
                }
                $res = $this->writeFileJson($path.$product_id, $page);
                if($res){
                    $this->writeFileLogUrlSuccess($type, self::DOMAIN.$href."\n");
                    return $product_id;
                } else {
                    return null;
                }
        }
        else {
            echo "Error go to detail at " .self::DOMAIN.$href;
            $this->writeFileLogFail($type, "Cannot find detail: ".self::DOMAIN.$href."\n");
        }
    }

    function getCityId($cityFile, $cityDB)
    {
        foreach ($cityDB as $obj) {
            $c = new Collator('vi_VN');
            $cityFile = trim($cityFile);
            $check = $c->compare($cityFile, $obj->name);
            if ($check == 0) {
                return (int)$obj->id;
            }
        }
        return null;
    }

    function getDistrictId($districtFile, $districtDB, $city_id)
    {
        foreach ($districtDB as $obj) {
            $c = new Collator('vi_VN');
            $districtFile = trim($districtFile);
            $check = $c->compare($districtFile, $obj->name);
            if ($check == 0 && $obj->city_id == $city_id) {
                return (int)$obj->id;
            }
        }
        return null;
    }

    function getWardId($_file, $_data, $_id)
    {
        foreach ($_data as $obj) {
            $c = new Collator('vi_VN');
            $_file = trim($_file);
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
            $_file = trim($_file);
            $check = $c->compare($_file, $obj->name);
            if ($check == 0 && $obj->district_id == $_id) {
                return (int)$obj->id;
            }
        }
        return 0;
    }

    function loadBdsLog(){
        $path_folder = Yii::getAlias('@console') . "/data/bds_html/";
        $path = $path_folder."bds_log.json";
        if(!is_dir($path_folder)){
            mkdir($path_folder , 0777, true);
            echo "Directory {$path_folder} was created\n";
        }
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

    function writeBdsLog($log){
        $file_name = Yii::getAlias('@console') . "/data/bds_html/bds_log.json";
        $log_data = json_encode($log);
        $this->writeFileJson($file_name, $log_data);
    }

    function loadFileLog($type){
        $path_folder = Yii::getAlias('@console') . "/data/bds_html/{$type}/";
        $path = $path_folder."bds_log_{$type}.json";
        if(!is_dir($path_folder)){
            mkdir($path_folder , 0777, true);
            echo "Directory {$path_folder} was created\n";
        }
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

    function writeFileLog($type, $log){
        $file_name = Yii::getAlias('@console') . "/data/bds_html/{$type}/bds_log_{$type}.json";
        $log_data = json_encode($log);
        $this->writeFileJson($file_name, $log_data);
    }


    function writeFileLogFail($type, $log){
        $file_name = Yii::getAlias('@console') . "/data/bds_html/{$type}/bds_log_fail";
        if(!file_exists($file_name)){
            fopen($file_name, "w");
        }
        if( strpos(file_get_contents($file_name),$log) === false) {
            $this->writeToFile($file_name, $log, 'a');
        }
    }

    function writeFileLogUrlSuccess($type, $log){
        $file_name = Yii::getAlias('@console') . "/data/bds_html/{$type}/bds_log_urls";
        if(!file_exists($file_name)){
            fopen($file_name, "w");
        }
        if( strpos(file_get_contents($file_name),$log) === false) {
            $this->writeToFile($file_name, $log, 'a');
        }
    }


    public function writeFileJson($filePath, $data)
    {
        $handle = fopen($filePath, 'w') or die('Cannot open file:  ' . $filePath);
        $int = fwrite($handle, $data);
        fclose($handle);
        return $int;
    }

    public function writeToFile($filePath, $data, $mode = 'a')
    {
        $handle = fopen($filePath, $mode) or die('Cannot open file:  ' . $filePath);
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
        curl_setopt($ch, CURLOPT_REFERER, self::DOMAIN . '/nha-dat-ban-tp-hcm/');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($httpcode >= 200 && $httpcode < 300) ? $data : null;
    }

    function loadImportLog(){
        $path_folder = Yii::getAlias('@console') . "/data/bds_html/";
        $path = $path_folder."bds_log_import.json";
        if(!is_dir($path_folder)){
            mkdir($path_folder , 0777, true);
            echo "Directory {$path_folder} was created";
        }
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

    function writeImportLog($log){
        $file_name = Yii::getAlias('@console') . '/data/bds_html/bds_log_import.json';
        $log_data = json_encode($log);
        $this->writeFileJson($file_name, $log_data);
    }

    public function importData()
    {
        $start_time = time();
        $insertCount = 0;
        $log_import = $this->loadImportLog();
        if(empty($log_import["files"])) $log_import["files"] = array();

//        $start = empty($log["last_file_index"]) ? 0 : $log["last_file_index"];
        $path = Yii::getAlias('@console') . "/data/bds_html/";
        $files = scandir($path."files", 1);
        $counter = count($files) - 3;
        if ($counter > 0) {
            print_r("Prepare data...\n");
            $cityData = AdCity::find()->all();
            $districtData = AdDistrict::find()->all();
//            $wardData = AdWard::find()->all();
//            $streetData = AdStreet::find()->all();
            $tableName = AdProduct::tableName();
            $columnNameArray = ['category_id', 'user_id',
                'city_id', 'district_id',
                'type', 'content', 'area', 'price', 'lat', 'lng',
                'start_date', 'end_date', 'verified', 'created_at', 'source'];
            $bulkInsertArray = array();
            $imageArray = array();
            $infoArray = array();
            $contactArray = array();
            print_r("Insert data...\n");
            $count_file = 1;
            for($i = 0; $i <= $counter; $i++) {
                if($count_file > 500)
                    break;

                $filename = $files[$i];
                if(in_array($filename, $log_import["files"])) {
                    print_r("\n".$filename." imported.");
                    continue;
                } else {
                    $filePath = $path . "files/" . $filename;
                    if (file_exists($filePath)) {
                        $data = $this->parseDetail($filePath);
                        foreach ($data as $value) {
                            $imageArray[$count_file] = $value[$filename]["thumbs"];
                            $infoArray[$count_file] = $value[$filename]["info"];
                            $contactArray[$count_file] = $value[$filename]["contact"];

                            $city_id = $this->getCityId($value[$filename]["city"], $cityData);
                            if (empty($city_id))
                                continue;
                            $district_id = $this->getDistrictId($value[$filename]["district"], $districtData, $city_id);
                            if (empty($district_id))
                                continue;

                            $area = $value[$filename]["dientich"];
                            $price = $value[$filename]["price"];
                            if ($price == 0)
                                continue;

                            $desc = $value[$filename]["description"];
                            $content = null;
                            if (!empty($desc)) {
                                $content = strip_tags($desc, '<br>');
                                $pos = strpos($content, 'Tìm kiếm theo từ khóa');
                                if($pos) {
                                    $content = substr($content, 0, $pos);
                                    $content = str_replace('Tìm kiếm theo từ khóa', '', $content);
                                }
                                $content = str_replace('<br/>', PHP_EOL, $content);
                                $content = trim($content);
                            }

                            $record = [
                                'category_id' => $value[$filename]["loai_tai_san"],
                                'user_id' => null,
                                'city_id' => $city_id,
                                'district_id' => $district_id,
                                'type' => $value[$filename]["loai_giao_dich"],
                                'content' => $content,
                                'area' => $area,
                                'price' => $price,
                                'lat' => $value[$filename]["lat"],
                                'lng' => $value[$filename]["lng"],
                                'start_date' => $value[$filename]["start_date"],
                                'end_date' => $value[$filename]["end_date"],
                                'verified' => 1,
                                'created_at' => $value[$filename]["start_date"],
                                'source' => 1
                            ];


                            $bulkInsertArray[] = $record;
                        }
                        print_r("\n".$filename." added.");
                        array_push($log_import["files"], $filename);
                        $log_import["total_import_name"] = $count_file;
                        $log_import["last_import_time"] = date("d-m-Y H:i");
                        $this->writeImportLog($log_import);
                        $count_file++;
                    }
                }
            }
            if(count($bulkInsertArray)>0){
                // below line insert all your record and return number of rows inserted
                $insertCount = Yii::$app->db->createCommand()
                    ->batchInsert( $tableName, $columnNameArray, $bulkInsertArray )->execute();
                print_r("\n Done!");

                if($insertCount > 0) {
                    $ad_image_columns = ['user_id', 'product_id', 'file_name', 'uploaded_at'];
                    $ad_info_columns = ['product_id', 'floor_no', 'room_no', 'toilet_no'];
                    $ad_contact_columns = ['product_id', 'name', 'phone', 'mobile', 'address'];

                    $bulkImage = array();
                    $bulkInfo = array();
                    $bulkContact = array();

                    $fromProductId = Yii::$app->db->getLastInsertID();
                    $toProductId = $fromProductId + $insertCount - 1;

                    $index = 1;
                    for ($i = $fromProductId; $i <= $toProductId; $i++) {
                        $ad_product = AdProduct::findOne($i);
                        if(!empty($ad_product)) {
                            if (count($imageArray) > 0) {
                                foreach ($imageArray[$index] as $imageValue) {
                                    if (!empty($imageValue)) {
                                        $imageRecord = [
                                            'user_id' => null,
                                            'product_id' => $i,
                                            'file_name' => $imageValue,
                                            'upload_at' => time()
                                        ];
                                        $bulkImage[] = $imageRecord;
                                    }
                                }
                            }

                            if (count($infoArray) > 0) {
                                $floor_no = empty($infoArray[$index]["Số tầng"]) == false ? trim(str_replace('(tầng)', '', $infoArray[$index]["Số tầng"])) : 0;
                                $room_no = empty($infoArray[$index]["Số phòng ngủ"]) == false ? trim(str_replace('(phòng)', '', $infoArray[$index]["Số phòng ngủ"])) : 0;
                                $toilet_no = empty($infoArray[$index]["Số toilet"]) == false ? trim($infoArray[$index]["Số toilet"]) : 0;
                                $infoRecord = [
                                    'product_id' => $i,
                                    'floor_no' => $floor_no,
                                    'room_no' => $room_no,
                                    'toilet_no' => $toilet_no
                                ];
                                $bulkInfo[] = $infoRecord;
                            }
                            if (count($contactArray) > 0) {
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
                            }
                            $index = $index + 1;
                        }
                    }

                    // execute image, info, contact
                    if (count($bulkImage) > 0) {
                        $imageCount = Yii::$app->db->createCommand()
                            ->batchInsert(AdImages::tableName(), $ad_image_columns, $bulkImage)
                            ->execute();
                        if ($imageCount > 0)
                            print_r("\nInser image done");
                    }
                    if (count($bulkInfo) > 0) {
                        $infoCount = Yii::$app->db->createCommand()
                            ->batchInsert(AdProductAdditionInfo::tableName(), $ad_info_columns, $bulkInfo)
                            ->execute();
                        if ($infoCount > 0)
                            print_r("\nInser product addition info done");
                    }
                    if (count($bulkContact) > 0) {
                        $contactCount = Yii::$app->db->createCommand()
                            ->batchInsert(AdContactInfo::tableName(), $ad_contact_columns, $bulkContact)
                            ->execute();
                        if ($contactCount > 0)
                            print_r("\nInser contact info done");
                    }
                } else{
                    $log_import = $this->loadImportLog();
                    $log_import["last_import_time"] = date("d-m-Y H:i");
                    $this->writeImportLog($log_import);
                    print_r("\nCannot insert ad_product");
                }
            }
        }
        print_r("\n\n");
        print_r("Files have been imported!\n");
        print_r("------------------------------");
        $end_time = time();
        print_r("\n"."Time: ");
        print_r($end_time-$start_time);
        print_r("s");
        print_r(" - Total Record: ". $insertCount);
    }

    public function parsDetail($filename)
    {
        $json = array();
        $page = file_get_contents($filename);
        $detail = SimpleHTMLDom::str_get_html($page, true, true, DEFAULT_TARGET_CHARSET, false);
        if (!empty($detail)) {
//                $title = $detail->find('h1', 0)->innertext;
            $href = $detail->find('#form1', 0)->action;
            $lat = $detail->find('#hdLat', 0)->value;
            $long = $detail->find('#hdLong', 0)->value;
            $product_id = $detail->find('.pm-content', 0)->cid;
            $content = $detail->find('.pm-content', 0)->innertext;

            $dientich = trim($detail->find('.gia-title', 1)->plaintext);
            $dt = 0;
            if (strpos($dientich, 'm²')) {
                $dientich = str_replace('m²', '', $dientich);
                $dientich = str_replace('Diện tích:', '', $dientich);
                $dientich = trim($dientich);
                $dt = $dientich;
            }

            $gia = trim($detail->find('.gia-title', 0)->plaintext);
            $price = 0;
            if (strpos($gia, ' triệu')) {
                $gia = str_replace('Giá:', '', $gia);
                if (strpos($gia, ' triệu/m²')) {
                    $gia = str_replace(' triệu/m²&nbsp;', '', $gia);
                    $gia = $gia * $dt;
                }
                else
                    $gia = str_replace(' triệu&nbsp;', '', $gia);

                $gia = trim($gia);
                $price = $gia * 1000000;
            } else if (strpos($gia, ' tỷ')) {
                $gia = str_replace('Giá:', '', $gia);
                $gia = str_replace(' tỷ&nbsp;', '', $gia);
                $gia = trim($gia);
                $price = $gia * 1000000000;
            }

            $imgs = $detail->find('.pm-middle-content .img-map #thumbs li img');
            $thumbs = array();
            if (count($imgs) > 0) {
                foreach ($imgs as $img) {
                    $img_link = str_replace('80x60', '745x510', $img->src);
                    array_push($thumbs, $img_link);
                }
            } else {
                print_r("\n" . self::DOMAIN . $href . " --> No images\n");
            }

            $left_detail = $detail->find('.pm-content-detail .left-detail', 0);
            $div_info = $left_detail->find('div div');
            $left = '';
            $city = null;
            $district = null;
            $startdate = time();
            $endate = time();
            $loai_tai_san = 6;
            $arr_info = [];
            if (count($div_info) > 0) {
                foreach ($div_info as $div) {
                    $class = $div->class;
                    if (!(empty($class))) {
                        if ($class == 'left')
                            $left = trim($div->innertext);
                        else if ($class == 'right') {
                            if (array_key_exists($left, $arr_info)) {
                                $left = $left . '_1';
                            }
                            $arr_info[$left] = trim($div->plaintext);
                        }
                    }
                }
            }

            if (count($arr_info) > 0) {
                if (!empty($arr_info["Địa chỉ"])) {
                    $address = mb_split(',', $arr_info["Địa chỉ"]);
                    $count_address = count($address);
                    if ($count_address >= 3) {
                        $city = !empty($address[$count_address - 1]) ? $address[$count_address - 1] : null;
                        $district = !empty($address[$count_address - 2]) ? $address[$count_address - 2] : null;
                    }
                }

                $startdate = empty($arr_info["Ngày đăng tin"]) ? time() : trim($arr_info["Ngày đăng tin"]);
                $startdate = strtotime($startdate);

                $endate = empty($arr_info["Ngày hết hạn"]) ? time() : trim($arr_info["Ngày hết hạn"]);
                $endate = strtotime($endate);

                $loai_tin = empty($arr_info["Loại tin rao"]) ? "Bán căn hộ chung cư" : trim($arr_info["Loại tin rao"]);
                if ($loai_tin == "Bán căn hộ chung cư") {
                    $loai_tai_san = 6;
                } else if (strpos($loai_tin, "Bán nhà")) {
                    $loai_tai_san = 7;
                } else if (strpos($loai_tin, "Bán đất")) {
                    $loai_tai_san = 10;
                }
            }

            $contact = $detail->find('.pm-content-detail #divCustomerInfo', 0);
            $div_contact = $contact->find('div.right-content div');
            $right = '';
            $arr_contact = [];
            if (count($div_contact) > 0) {
                foreach ($div_contact as $div) {
                    $class = $div->class;
                    if (!(empty($class))) {
                        if (strpos($class, 'left') == true) {
                            $right = $div->plaintext;
                            $right = trim($right);
                        } else if ($class == 'right') {
                            if (array_key_exists($right, $arr_contact)) {
                                $right = $right . '_1';
                            }
                            $value = $div->innertext;
                            $arr_contact[$right] = trim($value);
                        }
                    }
                }
            }
            $json['nha-dat-ban-tp-hcm'][$product_id] = [
                'lat' => trim($lat),
                'lng' => trim($long),
                'description' => trim($content),
                'thumbs' => $thumbs,
                'info' => $arr_info,
                'contact' => $arr_contact,
                'city' => $city,
                'district' => $district,
                'loai_tai_san' => $loai_tai_san,
                'loai_giao_dich' => 1,
                'price' => $price,
                'dientich' => $dt,
                'start_date' => $startdate,
                'end_date' => $endate,
                'link' => self::DOMAIN . $href
            ];
        }
        return $json;
    }

}