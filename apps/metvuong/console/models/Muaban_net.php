<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 1/19/2016
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

class Muaban_net extends Component
{
    const DOMAIN = 'https://muaban.net';
    protected $domain = 'https://muaban.net';
    protected $link_hcm = 'https://muaban.net/ban-nha-can-ho-ho-chi-minh-l59-c32?display=simple';
    protected $types = ['ban-nha-can-ho-quan-1-l5906-c32','ban-nha-can-ho-quan-2-l5910-c32','ban-nha-can-ho-quan-3-l5911-c32','ban-nha-can-ho-quan-4-l5912-c32','ban-nha-can-ho-quan-5-l5913-c32','ban-nha-can-ho-quan-6-l5914-c32','ban-nha-can-ho-quan-7-l5915-c32','ban-nha-can-ho-quan-8-l5916-c32','ban-nha-can-ho-quan-9-l5917-c32','ban-nha-can-ho-quan-10-l5907-c32','ban-nha-can-ho-quan-11-l5908-c32','ban-nha-can-ho-quan-12-l5909-c32','ban-nha-can-ho-quan-binh-tan-l5918-c32','ban-nha-can-ho-quan-binh-thanh-l5919-c32','ban-nha-can-ho-quan-go-vap-l5920-c32','ban-nha-can-ho-quan-phu-nhuan-l5921-c32','ban-nha-can-ho-quan-tan-binh-l5922-c32','ban-nha-can-ho-quan-tan-phu-l5923-c32','ban-nha-can-ho-quan-thu-duc-l5924-c32','ban-nha-can-ho-huyen-binh-chanh-l5901-c32','ban-nha-can-ho-huyen-can-gio-l5902-c32','ban-nha-can-ho-huyen-cu-chi-l5903-c32','ban-nha-can-ho-huyen-hoc-mon-l5904-c32','ban-nha-can-ho-huyen-nha-be-l5905-c32'];
    protected $time_start = 0;
    protected $time_end = 0;

    /**
     * @return mixed
     */
    public static function find()
    {
        return Yii::createObject(Muaban_net::className());
    }

    public function parse()
    {
        ob_start();
        $this->time_start = time();
//        $this->getPages();
        $this->getPageMuaBan();
        $this->time_end = time();
        print_r("\nTime: ");
        print_r($this->time_end - $this->time_start);
    }

    public function getPageMuaBan(){
        $log = $this->loadFileLog();
        $current_page = empty($log["current_page"]) ? 1 : ($log["current_page"] + 1);
        $page = SimpleHTMLDom::file_get_html($this->link_hcm."&cp=".$current_page);
        if(!empty($page)){
            $link_page1 = $page->find('.mbn-body .container .mbn-box-right .mbn-box-list li.mbn-title a');
            if(count($link_page1)){
                foreach ($link_page1 as $a) {
                    $href = $a->href;
                    if (!empty($href)) {
                        $product_id = $this->getId($href);
                        if(empty($product_id))
                            continue;
                        // check product exists in folder
                        $path = Yii::getAlias('@console') . "/data/muaban_html/files/";
                        if($this->checkProductExists($path, $product_id)){
                            var_dump($product_id);
                            continue;
                        } else {
                            $this->getFileDetail($path, $product_id, $href, $current_page);
                        }
                    }
                    sleep(1);
                    ob_flush();
                }
                $log["current_page"] = $current_page;
                $this->writeFileLog($log);
                print_r("\nPage {$current_page} done.\n");
            }
        }

        $total_record = 0; // lay tong so record
        $scriptTags = $page->find('script');
        if(!empty($scriptTags)) {
            foreach ($scriptTags as $script) {
                $text = $script->innertext;
                $start = strpos($text, "TotalResult");
                if ($start > 0) {
                    $str_total_record = substr($text, $start);
                    $str_total_record = substr($str_total_record, 0, strpos($str_total_record, "' || 0);"));
                    $str_total_record = str_replace("TotalResult('", "", trim($str_total_record));
                    $total_record = (int)$str_total_record;
                    break;
                }
            }
        }
        if($total_record > 0){
            $total_page = round($total_record/30);
            $current_page_add = $current_page + 4; // +4 => total page to run are 5.
            if($current_page_add > $total_page)
                $current_page_add = $total_page;

            if($current_page > $total_page)
                $current_page = 0;

            for($i=$current_page+1; $i<=$current_page_add; $i++){
                $link = "https://muaban.net/ban-nha-can-ho-ho-chi-minh-l59-c32?display=simple&cp={$i}";
                $page_ = SimpleHTMLDom::file_get_html($link);
                if(!empty($page_)){
                    $link_page = $page_->find('.mbn-body .container .mbn-box-right .mbn-box-list li.mbn-title a');
                    if(count($link_page)){
                        foreach ($link_page as $a) {
                            $href = $a->href;
                            if (!empty($href)) {
                                $product_id = $this->getId($href);
                                if(empty($product_id))
                                    continue;
                                // check product exists in folder
                                $path = Yii::getAlias('@console') . "/data/muaban_html/files/";
                                if($this->checkProductExists($path, $product_id)){
                                    var_dump($product_id);
                                    continue;
                                } else {
                                    $res = $this->getFileDetail($path, $product_id, $href, $i);
                                }
                            }
                            sleep(1);
                            ob_flush();
                        }
                        $log["current_page"] = $i;
                        $this->writeFileLog($log);
                        print_r("\nPage {$i} done.\n");
                    }
                }
            }
        }
    }

    public function getFileDetail($path, $product_id, $href, $current_page){
        $page = SimpleHTMLDom::file_get_html($href);
        if(!empty($page)){
            $bottom = $page->find('#dvmbnBottom', 0);
            if(!empty($bottom)){
                if(!is_dir($path)){
                    mkdir($path , 0777, true);
                    echo "\nDirectory {$path} was created";
                }
                $res = $this->writeFileJson($path.$product_id, $page);
                if($res){
                    $this->writeFileLogUrlSuccess("P{$current_page}: ".$href."\n");
                    return true;
                }
            }
        }
        return false;
    }

    public function getId($href){
        if (preg_match('/id(\d+)/',$href, $matches)) {
            if(!empty($matches[1])){
                return $product_id = $matches[1];
            }
        }
        return null;
    }

    public function checkProductExists($path, $product_id){
        if(!is_dir($path)){
            mkdir($path , 0777, true);
            echo "\nDirectory {$path} was created";
        }
        $filename = $path.$product_id;
        return file_exists($filename);
    }

    function getCityId($cityFile, $cityDB)
    {
        foreach ($cityDB as $obj) {
            $c = new Collator('vi_VN');
            $check = $c->compare(trim($cityFile), trim($obj->name));
            if ($check == 0) {
                return (int)$obj->id;
            }
        }
        return null;
    }

    function getDistrictId($districtFile, $districtDB, $city_id)
    {
        if(!empty($city_id)) {
            foreach ($districtDB as $obj) {
                $c = new Collator('vi_VN');
                $check = $c->compare(trim($districtFile), trim($obj->name));
                if ($check == 0 && $obj->city_id == $city_id) {
                    return (int)$obj->id;
                }
            }
        }
        return null;
    }

    function getWardId($_file, $_data, $_id)
    {
        if(!empty($_id)) {
            foreach ($_data as $obj) {
                preg_match('/'.trim($obj->name).'$/', trim($_file), $match);
                if (!empty($match[0]) && $obj->district_id == $_id) {
                    return (int)$obj->id;
                }
            }
        }
        return null;
    }

    function getStreetId($_file, $_data, $_id)
    {
        if(!empty($_id)) {
            foreach ($_data as $obj) {
                $a = preg_quote(trim($obj->name), '/'); //  / -> \/
                $b = preg_quote(trim($_file));
                preg_match('/'.$a.'$/', $b, $match);
                if (!empty($match[0]) && $obj->district_id == $_id) {
                    return (int)$obj->id;
                }

            }
        }
        return null;
    }


    function loadFileLog(){
        $path_folder = Yii::getAlias('@console') . "/data/muaban_html/";
        $path = $path_folder."muaban_log.json";
        if(!is_dir($path_folder)){
            mkdir($path_folder , 0777, true);
            echo "\nDirectory {$path_folder} was created";
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

    function writeFileLog($log){
        $file_name = Yii::getAlias('@console') . "/data/muaban_html/muaban_log.json";
        $log_data = json_encode($log);
        $this->writeFileJson($file_name, $log_data);
    }

    function writeFileLogFail($log){
        $file_name = Yii::getAlias('@console') . "/data/muaban_html/muaban_log_fail";
        if(!file_exists($file_name)){
            fopen($file_name, "w");
        }
        if( strpos(file_get_contents($file_name),$log) === false) {
            $this->writeToFile($file_name, $log, 'a');
        }
    }

    function writeFileLogUrlSuccess($log){
        $file_name = Yii::getAlias('@console') . "/data/muaban_html/muaban_log_urls";
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
        curl_setopt($ch, CURLOPT_REFERER, $this->link_hcm);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($httpcode >= 200 && $httpcode < 300) ? $data : null;
    }

    function loadImportLog($type){
        $path_folder = Yii::getAlias('@console') . "/data/muaban_html/import/";
        if(!is_dir($path_folder)){
            mkdir($path_folder , 0777, true);
            echo "\nDirectory {$path_folder} was created";
        }
        $data = null;
        $path = $path_folder."bds_import_{$type}.json";
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

    function writeImportLog($type, $log){
        $file_name = Yii::getAlias('@console') . "/data/muaban_html/import/bds_import_{$type}.json";
        $log_data = json_encode($log);
        $this->writeFileJson($file_name, $log_data);
    }

    function loadBdsImportLog($filename){
        $path_folder = Yii::getAlias('@console') . "/data/muaban_html/import/";
        if(!is_dir($path_folder)){
            mkdir($path_folder , 0777, true);
            echo "\nDirectory {$path_folder} was created";
        }
        $data = null;
        $path = $path_folder.$filename;
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

    function writeBdsImportLog($filename, $log){
        $file_name = Yii::getAlias('@console') . "/data/muaban_html/import/".$filename;
        $log_data = json_encode($log);
        $this->writeFileJson($file_name, $log_data);
    }

    public function getAddress($lat, $long){
        $api_key1 = 'AIzaSyCTwptkS584b_mcZWt0j_86ZFYLL0j-1Yw';
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$long}&key={$api_key1}";
        $address = array();
        $response = @file_get_contents($url);
        if(!empty($response)){
            $results = json_decode($response, true);
            if(!empty($results["results"])) {
                if (!empty($results["results"][0]["address_components"])) {
                    $detail = $results["results"][0]["address_components"];
                    if ($detail[count($detail) - 1]["short_name"] == "VN") {
                        foreach ($detail as $d) {
                            if ($d["types"][0] == "street_number") {
                                $address["home_no"] = $d["long_name"];
                            } elseif ($d["types"][0] == "route") {
                                $address["street"] = $d["long_name"];
                            } elseif ($d["types"][0] == "sublocality_level_1") {
                                $address["ward"] = $d["long_name"];
                            } elseif ($d["types"][0] == "administrative_area_level_2") {
                                $address["district"] = $d["long_name"];
                            } elseif ($d["types"][0] == "administrative_area_level_1") {
                                $address["city"] = $d["long_name"];
                            }
                        }
                    }
                }
            } else {
                print_r("\nGoogle Map API limits at lat: {$lat} , long: {$long}");
            }
        }
        return $address;
    }

    public function importData()
    {
        $start_time = time();
        $insertCount = 0;
        $count_file = 1;

        $bds_import_log = $this->loadBdsImportLog("bds_import_log.json");
        if(empty($bds_import_log["type"])){
            $bds_import_log["type"] = array();
        }

        $last_type_import = empty($bds_import_log["last_type_index"]) ? 0 : ($bds_import_log["last_type_index"] + 1);
        $file_imported = empty($bds_import_log["file_imported"]) ? false : $bds_import_log["file_imported"];

        if(!$file_imported) {
            $columnNameArray = ['category_id', 'user_id', 'home_no',
                'city_id', 'district_id', 'ward_id', 'street_id',
                'type', 'content', 'area', 'price', 'lat', 'lng',
                'start_date', 'end_date', 'verified', 'created_at', 'source'];
            $bulkInsertArray = array();
            $imageArray = array();
            $infoArray = array();
            $contactArray = array();

            $cityData = AdCity::find()->all();
            $districtData = AdDistrict::find()->all();
            $wardData = AdWard::find()->all();
            $streetData = AdStreet::find()->all();
            $tableName = AdProduct::tableName();
            $break_type = false; // detect next type if it is false
            foreach ($this->types as $key_type => $type) {
                if ($key_type >= $last_type_import && !$break_type) {

                    $path = Yii::getAlias('@console') . "/data/muaban_html/{$type}/files";
                    if (is_dir($path)) {
                        $log_import = $this->loadImportLog($type);
                        if (empty($log_import["files"])) $log_import["files"] = array();

                        $files = scandir($path, 1);
                        $counter = count($files) - 2;
                        $last_file_index = $counter - 1;
                        if ($counter > 0) {
                            for ($i = 0; $i <= $last_file_index; $i++) {
                                if ($count_file > 500) {
                                    $break_type = true;
                                    break;
                                }
                                $filename = $files[$i];
                                if (in_array($filename, $log_import["files"])) {
                                    continue;
                                } else {
                                    $filePath = $path . "/" . $filename;
                                    if (file_exists($filePath)) {
                                        print_r("\n".$count_file." - Prepare data {$type}: {$filename}");
                                        $value = $this->parseDetail($filePath);
                                        if(empty($value)){
                                            if (!in_array($filename, $log_import["files"]))
                                                array_push($log_import["files"], $filename);
                                            if (empty($log_import["NoContent"])) $log_import["NoContent"] = array();
                                            if(!in_array($filename, $log_import["NoContent"]))
                                                array_push($log_import["NoContent"], $filename);
                                            print_r(" Error: no content\n");
                                            continue;
                                        }

                                        if(count($value[$filename]["thumbs"]) <= 0){
                                            if (empty($log_import["NoImage"])) $log_import["NoImage"] = array();
                                            if(!in_array($filename, $log_import["NoImage"]))
                                                array_push($log_import["NoImage"], $filename);
//                                            print_r(" [No Image]");
                                        }
                                        $imageArray[$count_file] = $value[$filename]["thumbs"];
                                        $infoArray[$count_file] = $value[$filename]["info"];
                                        $contactArray[$count_file] = $value[$filename]["contact"];

                                        $city_id = $this->getCityId($value[$filename]["city"], $cityData);
                                        if (empty($city_id)) {
                                            if (!in_array($filename, $log_import["files"]))
                                                array_push($log_import["files"], $filename);
                                            if (empty($log_import["NoCity"])) $log_import["NoCity"] = array();
                                            if (!in_array($filename, $log_import["NoCity"]))
                                                array_push($log_import["NoCity"], $filename);
                                            print_r(" Error: no city\n");
                                            continue;
                                        }
                                        $district_id = $this->getDistrictId($value[$filename]["district"], $districtData, $city_id);
                                        if (empty($district_id)) {
                                            if (!in_array($filename, $log_import["files"]))
                                                array_push($log_import["files"], $filename);
                                            if (empty($log_import["NoDistrict"])) $log_import["NoDistrict"] = array();
                                            if (!in_array($filename, $log_import["NoDistrict"]))
                                                array_push($log_import["NoDistrict"], $filename);
                                            print_r(" Error: no district\n");
                                            continue;
                                        }

                                        $ward_id = $this->getWardId($value[$filename]["ward"], $wardData, $district_id);
                                        if (empty($ward_id)) {
                                            if (empty($log_import["NoWard"])) $log_import["NoWard"] = array();
                                            if (!in_array($filename, $log_import["NoWard"]))
                                                array_push($log_import["NoWard"], $filename);
//                                            print_r(" [No Ward]");
                                        }

                                        $street_id = $this->getStreetId($value[$filename]["street"], $streetData, $district_id);
                                        if (empty($street_id)) {
                                            if (empty($log_import["NoStreet"])) $log_import["NoStreet"] = array();
                                            if (!in_array($filename, $log_import["NoStreet"]))
                                                array_push($log_import["NoStreet"], $filename);
//                                            print_r(" [No Street]");
                                        }

                                        $area = $value[$filename]["dientich"];
                                        $price = $value[$filename]["price"];
                                        if ($price == 0) { // gia thoa thuan, gia trieu/m2 * 0 dien tich
                                            if ($area > 0) {
                                                if (empty($log_import["NoPrice"])) $log_import["NoPrice"] = array();
                                                if (!in_array($filename, $log_import["NoPrice"]))
                                                    array_push($log_import["NoPrice"], $filename);
                                                print_r(" Error: no price\n");
                                            } else {
                                                if (empty($log_import["NoArea"])) $log_import["NoArea"] = array();
                                                if (!in_array($filename, $log_import["NoArea"]))
                                                    array_push($log_import["NoArea"], $filename);
                                                print_r(" Error: no area\n");
                                            }
                                            if (!in_array($filename, $log_import["files"]))
                                                array_push($log_import["files"], $filename);
                                            continue;
                                        }

                                        $desc = $value[$filename]["description"];
                                        $content = null;
                                        if (!empty($desc)) {
                                            $content = strip_tags($desc, '<br>');
                                            $pos = strpos($content, 'Tìm kiếm theo từ khóa');
                                            if ($pos) {
                                                $content = substr($content, 0, $pos);
                                                $content = str_replace('Tìm kiếm theo từ khóa', '', $content);
                                            }
                                            $content = str_replace('<br/>', PHP_EOL, $content);
                                            $content = trim($content);
                                        }

                                        $record = [
                                            'category_id' => $value[$filename]["loai_tai_san"],
                                            'user_id' => null,
                                            'home_no' => $value[$filename]["home_no"],
                                            'city_id' => $city_id,
                                            'district_id' => $district_id,
                                            'ward_id' => $ward_id,
                                            'street_id' => $street_id,
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
                                        // source = 1 for Batdongsan.com.vn
                                        $bulkInsertArray[] = $record;

                                        print_r(" Added.\n");
                                        array_push($log_import["files"], $filename);
                                        $log_import["import_total"] = count($log_import["files"]);
                                        $log_import["import_time"] = date("d-m-Y H:i");
                                        $this->writeImportLog($type, $log_import);
                                        $count_file++;
                                    }
                                }
                            } // end file loop

                            if ($break_type == false && count($bulkInsertArray) > 0) {
                                if (!in_array($type, $bds_import_log["type"])) {
                                    array_push($bds_import_log["type"], $type);
                                }
                                $bds_import_log["last_type_index"] = $key_type;
                                $this->writeBdsImportLog("bds_import_log.json", $bds_import_log);
                                print_r("\nADD: {$type} DONE!\n");
                            }
                        }
                    }
                }
            } // end types
            if (count($bulkInsertArray) > 0) {
                print_r("\nInsert data...");
                // below line insert all your record and return number of rows inserted
                $insertCount = Yii::$app->db->createCommand()
                    ->batchInsert($tableName, $columnNameArray, $bulkInsertArray)->execute();
                print_r(" DONE!");

                if ($insertCount > 0) {
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
                        if (!empty($ad_product)) {
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
                } else {
                    print_r("\nCannot insert ad_product!!");
                }

                if (!$break_type) {
                    $bds_import_log["file_imported"] = true;
                    $this->writeBdsImportLog("bds_import_log.json",$bds_import_log);
                }
            }
        }

        print_r("\n\n------------------------------");
        print_r("\nFiles have been imported!\n");
        $end_time = time();
        print_r("\n"."Time: ");
        print_r($end_time-$start_time);
        print_r("s - Total Record: ". $insertCount);
    }

    public function parseDetail($filename)
    {
        $json = array();
        $page = file_get_contents($filename);
        if(empty($page))
            return null;
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
            }

            $left_detail = $detail->find('.pm-content-detail .left-detail', 0);
            $div_info = $left_detail->find('div div');
            $left = '';
            $city = null;
            $district = null;
            $ward = null;
            $street = null;
            $home_no = null;
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
                // set address with link emailregister
                $emailregister = trim($detail->find('#emailregister', 0)->href);
                if(!empty($emailregister)) {
                    $emailregister = substr($emailregister, strpos($emailregister, "cityCode="), strlen($emailregister) - 1);
//                    print_r($emailregister);
                    $address = explode("&amp;", $emailregister);
                    if (count($address) > 8) {
                        $divCityOptions = $detail->find('#divCityOptions ul li');
                        $divDistrictOptions = $detail->find('#divDistrictOptions ul li');
                        $divWardOptions = $detail->find('#divWardOptions ul li');
                        $divStreetOptions = $detail->find('#divStreetOptions ul li');

                        foreach ($address as $val) {
                            if ($this->beginWith($val, "cityCode=")) {
                                $cityCode = str_replace("cityCode=", "", $val);
                                if(!empty($cityCode)) {
                                    foreach ($divCityOptions as $cityValue) {
                                        if ($cityCode == $cityValue->vl) {
                                            $city = $cityValue->plaintext;
                                            break;
                                        }
                                    }
                                }
                            } elseif ($this->beginWith($val, "distId=")) {
                                $d_id = (string)str_replace("distId=", "", $val);
                                if($d_id != "0") {
                                    foreach ($divDistrictOptions as $districtValue) {
                                        if ($d_id == $districtValue->vl) {
                                            $district = $districtValue->plaintext;
                                            break;
                                        }
                                    }
                                }
                            } elseif ($this->beginWith($val, "wardId=")) {
                                $w_id = (string)str_replace("wardId=", "", $val);
                                if($w_id != "0") {
                                    foreach ($divWardOptions as $wardValue) {
                                        if ($w_id == $wardValue->vl) {
                                            $ward = $wardValue->plaintext;
                                            break;
                                        }
                                    }
                                }
                            } elseif ($this->beginWith($val, "streetId=")) {
                                $s_id = (string)str_replace("streetId=", "", $val);
                                if($s_id != "0") {
                                    foreach ($divStreetOptions as $streetValue) {
                                        if ($s_id === $streetValue->vl) {
                                            $street = $streetValue->plaintext;
                                            break;
                                        }
                                    }
                                }
                            }
                        } // end for address
                    }
                }
                // truong hop ko co city hoac district
                if(empty($city) || empty($district)){
                    if (!empty($arr_info["Địa chỉ"])) {
                        $address = mb_split(',', $arr_info["Địa chỉ"]);
                        $count_address = count($address);
                        if ($count_address >= 3) {
                            $city = !empty($address[$count_address - 1]) ? $address[$count_address - 1] : null;
                            $district = !empty($address[$count_address - 2]) ? $address[$count_address - 2] : null;
                        }
                    }
                }

                $startdate = empty($arr_info["Ngày đăng tin"]) ? time() : trim($arr_info["Ngày đăng tin"]);
                $startdate = strtotime($startdate);

                $endate = empty($arr_info["Ngày hết hạn"]) ? time() : trim($arr_info["Ngày hết hạn"]);
                $endate = strtotime($endate);

                $loai_tin = empty($arr_info["Loại tin rao"]) ? "Bán căn hộ chung cư" : trim($arr_info["Loại tin rao"]);

                if ($this->beginWith($loai_tin, "Bán căn hộ chung cư")) {
                    $loai_tai_san = 6;
                } else if ($this->beginWith($loai_tin, "Bán nhà")) {
                    $loai_tai_san = 7;
                } else if ($this->beginWith($loai_tin, "Bán đất")) {
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
            $json[$product_id] = [
                'lat' => trim($lat),
                'lng' => trim($long),
                'description' => trim($content),
                'thumbs' => $thumbs,
                'info' => $arr_info,
                'contact' => $arr_contact,
                'city' => $city,
                'district' => $district,
                'ward' => $ward,
                'street' => $street,
                'home_no' => $home_no,
                'loai_tai_san' => $loai_tai_san,
                'loai_giao_dich' => 1,
                'price' => $price,
                'dientich' => $dt,
                'start_date' => $startdate,
                'end_date' => $endate,
//                'link' => self::DOMAIN . $href
            ];
        }
        return $json;
    }

    function beginWith($haystack, $needle) {
        return substr($haystack, 0, strlen($needle)) === $needle;
    }

    public function updateData(){
        $products = AdProduct::find()->where(['ward_id' => null])->andWhere(['verified' => 1])->all();
        if(count($products) > 0){
//            $cityData = AdCity::find()->all();
//            $districtData = AdDistrict::find()->all();
            $wardData = AdWard::find()->all();
            $streetData = AdStreet::find()->all();
            $log_update = $this->loadBdsImportLog("bds_update_log.json");
            if(empty($log_update["pids"])) $log_update["pids"] = array();
            $i = 1;
            foreach($products as $product){
                if($i > 500)
                    break;
//                if(in_array($product->id, $log_update["pids"]))
//                    continue;
                $lat = $product->lat;
                $lng = $product->lng;
                $address = $this->getAddress($lat, $lng);
                if (count($address) > 0) {
                    if (empty($address["ward"])) {
                        $product->verified = 0;
                        if(empty($log_update["NoWard"])) $log_update["NoWard"] = array();
                        if(!in_array($product->id, $log_update["NoWard"]))
                            array_push($log_update["NoWard"], $product->id);
                        print_r("\nNot verified ward: {$product->id} and {$lat}, {$lng}");
                        continue;
                    }

                    if (!empty($address["street"])) {
                        $street_id = $this->getStreetId($address["street"], $streetData, $product->district_id);
                        $product->street_id = $street_id;
                    }

                    if(!empty($address["home_no"]))
                        $product->home_no = $address["home_no"];

                    $ward_id = $this->getWardId($address["ward"], $wardData, $product->district_id);
                    if(empty($ward_id)){
                        if(empty($log_update["NoWard"])) $log_update["NoWard"] = array();
                        if(!in_array($product->id, $log_update["NoWard"]))
                            array_push($log_update["NoWard"], $product->id);
                        print_r("\nNot found ward in DB: {$product->id} and {$lat}, {$lng}");
                        continue;
                    } else {
                        $product->ward_id = $ward_id;
                        if($product->update(false))
                            print_r("\n{$i} - updated id: {$product->id}");
                        else
                            print_r("\n{$i} - failed id: {$product->id}");
                    }
                }
                if(!in_array($product->id, $log_update["pids"]))
                    array_push($log_update["pids"], $product->id);
                $log_update["total-update"] = count($log_update["pids"]);
                $this->writeBdsImportLog("bds_update_log.json", $log_update);
                $i++;
                sleep(2);
            } // end for products loop
        }
        else {
            print_r("\nAll products have been updated");
        }
    }

}