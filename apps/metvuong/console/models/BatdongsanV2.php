<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/8/2015
 * Time: 2:19 PM
 */
namespace console\models;

use Collator;
use DOMDocument;
use DOMXPath;
use keltstr\simplehtmldom\SimpleHTMLDom;
use vsoft\ad\models\AdCity;
use vsoft\ad\models\AdContactInfo;
use vsoft\ad\models\AdDistrict;
use vsoft\ad\models\AdImages;
use vsoft\ad\models\AdInvestorBuildingProject;
use vsoft\ad\models\AdProduct;
use vsoft\ad\models\AdProductAdditionInfo;
use vsoft\ad\models\AdStreet;
use vsoft\ad\models\AdWard;
use vsoft\craw\models\AdAgent;
use vsoft\craw\models\AdBuildingProject;
use vsoft\craw\models\AdInvestor;
use vsoft\craw\models\AdProductToolMap;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

class BatdongsanV2 extends Component
{
    const DOMAIN = 'http://batdongsan.com.vn';
    protected $domain = 'http://batdongsan.com.vn';
    protected $types = ['nha-dat-ban-quan-1','nha-dat-ban-quan-2','nha-dat-ban-quan-3','nha-dat-ban-quan-4','nha-dat-ban-quan-5','nha-dat-ban-quan-6',
        'nha-dat-ban-quan-7','nha-dat-ban-quan-8', 'nha-dat-ban-quan-9','nha-dat-ban-quan-10','nha-dat-ban-quan-11','nha-dat-ban-quan-12',
        'nha-dat-ban-binh-chanh','nha-dat-ban-binh-tan','nha-dat-ban-binh-thanh','nha-dat-ban-can-gio','nha-dat-ban-cu-chi','nha-dat-ban-go-vap',
        'nha-dat-ban-hoc-mon','nha-dat-ban-nha-be','nha-dat-ban-tan-binh','nha-dat-ban-tan-phu','nha-dat-ban-phu-nhuan','nha-dat-ban-thu-duc'];

    protected $rent_types = ['nha-dat-cho-thue-quan-1','nha-dat-cho-thue-quan-2','nha-dat-cho-thue-quan-3','nha-dat-cho-thue-quan-4','nha-dat-cho-thue-quan-5','nha-dat-cho-thue-quan-6',
        'nha-dat-cho-thue-quan-7','nha-dat-cho-thue-quan-8', 'nha-dat-cho-thue-quan-9','nha-dat-cho-thue-quan-10','nha-dat-cho-thue-quan-11','nha-dat-cho-thue-quan-12',
        'nha-dat-cho-thue-binh-chanh','nha-dat-cho-thue-binh-tan','nha-dat-cho-thue-binh-thanh','nha-dat-cho-thue-can-gio','nha-dat-cho-thue-cu-chi','nha-dat-cho-thue-go-vap',
        'nha-dat-cho-thue-hoc-mon','nha-dat-cho-thue-nha-be','nha-dat-cho-thue-tan-binh','nha-dat-cho-thue-tan-phu','nha-dat-cho-thue-phu-nhuan','nha-dat-cho-thue-thu-duc'];
    protected $time_start = 0;
    protected $time_end = 0;

    protected $projects=['khu-can-ho','cao-oc-van-phong','khu-do-thi-moi','khu-thuong-mai-dich-vu','khu-phuc-hop','khu-dan-cu','khu-du-lich-nghi-duong','khu-cong-nghiep','du-an-khac'];
//    protected $projects=['khu-do-thi-moi'];

    public $tabProject = [
        1 => 'tong-quan',
        4 => 'vi-tri',
        2 => 'ha-tang',
        3 => 'thiet-ke',
        8 => 'tien-do',
        5 => 'ban-hang',
        9 => 'ho-tro',
    ];

    /**
     * @return mixed
     */
    public static function find()
    {
        return Yii::createObject(BatdongsanV2::className());
    }

    public function parse()
    {
        try {
//            ob_start();
            $this->time_start = time();
            $this->getPages(1);
            $this->time_end = time();
            print_r("\nTime: ");
            print_r($this->time_end - $this->time_start);
        } catch(Exception $e) {
            $currentBuffers = ob_get_clean();
            ob_end_clean(); // Let's end and clear ob...
            echo "<br />Some error occured: " . $e->getMessage();
        }
    }

    public function parseRent()
    {
        ob_start();
        $this->time_start = time();
        $this->getPages(2);
        $this->time_end = time();
        print_r("\nTime: ");
        print_r($this->time_end - $this->time_start);
    }

    public function setZeroCurrentPage($types, $path_folder){
        foreach ($types as $key_type => $type) {
            $path_folder = $path_folder."/".$type."/";
            $filename = "bds_log_{$type}.json";
            $file = $path_folder.$filename;
            if(file_exists($file)){
                $file_log = $this->loadFileLog($type, $path_folder, $filename);
                if(!empty($file_log["current_page"])){
                    $file_log["current_page"] = 0;
                    $this->writeFileLog($file_log, $path_folder, $filename);
                }
            }
        }
    }

    public function getPages($product_type)
    {
        $types = $this->types;
        $bds_log = $this->loadBdsLog();
        $path_folder = Yii::getAlias('@console') . "/data/bds_html/";
        if($product_type == 2){
            $types = $this->rent_types;
            $path_folder = Yii::getAlias('@console') . "/data/bds_html/rents/";
            $bds_log = $this->loadLog($path_folder, "bds_rent_log.json");
        }
        if(empty($bds_log["type"])){
            $bds_log["type"] = array();
        }
        $last_type = empty($bds_log["last_type_index"]) ? 0 : ($bds_log["last_type_index"] + 1);
        $count_type = count($types);

        if($last_type >= $count_type) {
            $bds_log["type"] = array();
            unset($bds_log["last_type_index"]);
            $last_type = 0;
            if($product_type == 1)
                $this->writeBdsLog($bds_log);
            else
                $this->writeLog($bds_log, $path_folder, "bds_rent_log.json");
            $this->setZeroCurrentPage($types, $path_folder);
        }

        foreach ($types as $key_type => $type) {
            if ($key_type >= $last_type) {
                $url = self::DOMAIN . '/' . $type;
                $page = $this->getUrlContent($url);
                if (!empty($page)) {
                    $html = SimpleHTMLDom::str_get_html($page, true, true, DEFAULT_TARGET_CHARSET, false);
                    $pagination = $html->find('.container-default .background-pager-right-controls a');
                    $count_page = count($pagination);
                    $last_page = (int)str_replace("/" . $type . "/p", "", $pagination[$count_page - 1]->href);
                    if ($count_page > 0) {
                        $log = $this->loadFileLog($type, $path_folder."/".$type."/", "bds_log_{$type}.json");
                        $current_page = empty($log["current_page"]) ? 1 : ($log["current_page"] + 1);
                        $current_page_add = $current_page + 4; // +4 => total pages to run that are 5.
                        if($current_page_add > $last_page)
                            $current_page_add = $last_page;

                        if ($current_page <= $last_page) {
                            for ($i = $current_page; $i <= $current_page_add; $i++) {
                                $log = $this->loadFileLog($type, $path_folder."/".$type."/", "bds_log_{$type}.json");
                                $sequence_id = empty($log["last_id"]) ? 0 : ($log["last_id"] + 1);
                                $list_return = $this->getListProject($type, $i, $sequence_id, $log, $product_type, $path_folder);
                                if (!empty($list_return["data"])) {
                                    $list_return["data"]["current_page"] = $i;
                                    $this->writeFileLog($list_return["data"], $path_folder."/".$type."/", "bds_log_{$type}.json");
                                    print_r("\n\n{$type}: Page " . $i . " done!\n");
                                }
                                sleep(1);
                                ob_flush();
                            }

                            if($current_page != $current_page_add){
                                break;
                            }

                        } else {
                            $log['current_page'] = 0;
                            $this->writeFileLog($log, $path_folder."/".$type."/", "bds_log_{$type}.json");
                            print_r("\nLast file of {$type} done.");
                        }
                    } else {
                        echo "\nCannot find listing. End page!" . self::DOMAIN;
                    }
                } else {
                    echo "\nCannot access in get pages of " . self::DOMAIN;
                }

                if(!in_array($type, $bds_log["type"])) {
                    array_push($bds_log["type"], $type);
                }
                $bds_log["last_type_index"] = $key_type;
                $this->writeLog($bds_log, $path_folder, "bds_rent_log.json");
                print_r("\nTYPE: {$type} DONE!\n");
            }
        }
    }

    public function getListProject($type, $current_page, $sequence_id, $log, $product_type, $path_folder)
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

                    if(!empty($productId)) {
                        $res = $this->getProjectDetail($type, $item->href, $product_type, $path_folder);
                        if (!empty($res)) {
                            $log["files"][$sequence_id] = $res;
                            $log["last_id"] = $sequence_id;
                            $sequence_id = $sequence_id + 1;
                            $checkExists = in_array($productId, $log["files"]);
                            if($checkExists)
                                print_r("\n{$type}: ".$productId." override");
                            else
                                array_push($log["files"], $productId);
                        }
                    }
                    // Ko check exists de ghi de len file moi lay ve
//                    $checkExists = false;
//                    if ($checkExists == false) {
//                        $res = $this->getProjectDetail($type, $item->href, $product_type, $path_folder);
//                        if (!empty($res)) {
//                            $log["files"][$sequence_id] = $res;
//                            $log["last_id"] = $sequence_id;
//                            $sequence_id = $sequence_id + 1;
//                        }
//                    } else {
//                        print_r($productId . " exists\n");
//                    }
                }
                return ['data' => $log];
            } else {
                echo "\nCannot find listing. End page!".self::DOMAIN;
                $this->writeFileLogFail($type, "\nCannot find listing: $href"."\n");
            }

        } else {
            echo "\nCannot access in get List Project of ".self::DOMAIN;
            $this->writeFileLogFail($type, "\nCannot access: $href"."\n");
        }
        return null;
    }

    public function getProjectDetail($type, $href, $product_type, $path_folder)
    {
        $folder = $product_type == 1 ? "files" : "rent_files";
        $page = $this->getUrlContent(self::DOMAIN . $href);
        $matches = array();
        if (preg_match('/pr(\d+)/', self::DOMAIN . $href, $matches)) {
            if(!empty($matches[1])){
                $product_id = $matches[1];
            }
        }

        if(!empty($product_id)) {

            $path = $path_folder."{$type}/{$folder}/";
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                echo "\nDirectory {$path} was created";
            }
            $res = $this->writeFileJson($path . $product_id, $page);
            if ($res) {
                $this->writeFileLogUrlSuccess($type, self::DOMAIN . $href . "\n", $path_folder);
                return $product_id;
            } else {
                return null;
            }
        }
        else {
            echo "\nError go to detail at " .self::DOMAIN.$href;
            $this->writeFileLogFail($type, "\nCannot find detail: ".self::DOMAIN.$href."\n");
            return null;
        }
    }

    function getCityId($cityFile, $cityDB)
    {
        $c = new Collator('vi_VN');
        foreach ($cityDB as $obj) {
            $check = $c->compare(mb_strtolower($cityFile), mb_strtolower($obj->name));
            if ($check == 0) {
                return (int)$obj->id;
            }
        }
        return null;
    }
    function getCityId2($cityName)
    {
        if(!empty($cityName)) {
//            $city = AdCity::find()->select(['id'])->where('name = :name',[':name' => $cityName])->one();
            $sql = "SELECT `id` FROM ad_city WHERE `name` LIKE '" . $cityName . "' LIMIT 1";
            $city = AdCity::getDb()->createCommand($sql)->queryScalar();
            if ($city) {
                return $city;
            }
        }
        return null;
    }

    function getDistrictId($districtFile, $districtDB, $city_id)
    {
        if(!empty($city_id)) {
            $c = new Collator('vi_VN');
            foreach ($districtDB as $obj) {
                $check = $c->compare(mb_strtolower($districtFile), mb_strtolower($obj->name));
                if ($check == 0 && $obj->city_id == $city_id) {
                    return (int)$obj->id;
                }
            }
        }
        return null;
    }
    function getDistrictId2($districtName, $city_id)
    {
        if(!empty($city_id) && !empty($districtName)) {
            $sql = "SELECT `id` FROM ad_district WHERE (`name` LIKE '".$districtName."' OR '".$districtName."' LIKE CONCAT(pre, ' ', name)) AND `city_id` = ".$city_id. " LIMIT 1";
            $district_id = AdDistrict::getDb()->createCommand($sql)->queryScalar();
            if($district_id) {
                return $district_id;
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
    function getWardId2($wardName, $district_id)
    {
        if(!empty($district_id) && !empty($wardName)) {
            $sql = "SELECT `id` FROM ad_ward WHERE (`name` LIKE '".$wardName."' OR '".$wardName."' LIKE CONCAT(pre, ' ', name)) AND `district_id` = ".$district_id. " LIMIT 1";
            $ward = AdWard::getDb()->createCommand($sql)->queryScalar();
            if($ward) {
                return $ward;
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
    function getStreetId2($streetName, $district_id)
    {
        if(!empty($district_id) && !empty($streetName)) {
//            $street = AdStreet::find()->select(['id'])->where(['like', 'name', '%'.$streetName, false])
//                ->andWhere('district_id = :d',[':d' => $district_id])->one();
            $sql = "SELECT `id` FROM ad_street WHERE (`name` LIKE '".$streetName."' OR '".$streetName."' LIKE CONCAT(pre, ' ', name)) AND `district_id` = ".$district_id. " LIMIT 1";
            $street = AdStreet::getDb()->createCommand($sql)->queryScalar();
            if($street) {
                return $street;
            }
        }
        return null;
    }

    function getProjectId($projectName)
    {
//        $project = \vsoft\ad\models\AdBuildingProject::find()->select(['id','name'])->where(['like', 'name', '%'.$projectName.'%', false])->one(); // Sai dự án Cao Ốc BMC
        $sql = "SELECT * FROM ad_building_project WHERE `name` LIKE '" . $projectName . "' LIMIT 1";
        $project = (int)\vsoft\ad\models\AdBuildingProject::getDb()->createCommand($sql)->queryScalar();
        if($project) {
            return $project;
        }
        return null;
    }

    function loadBdsLog(){
        $path_folder = Yii::getAlias('@console') . "/data/bds_html/";
        $path = $path_folder."bds_log.json";
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

    function writeBdsLog($log){
        $file_name = Yii::getAlias('@console') . "/data/bds_html/bds_log.json";
        $log_data = json_encode($log);
        $this->writeFileJson($file_name, $log_data);
    }

    function loadFileLog($type, $path_folder, $filename){
//        $path_folder = Yii::getAlias('@console') . "/data/bds_html/{$type}/";
        $path = $path_folder.$filename;
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

    function writeFileLog($log, $path_folder, $filename){
        $file_name = $path_folder.$filename;
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

    function writeFileLogUrlSuccess($type, $log, $path_folder){
        $file_name = $path_folder."{$type}/bds_log_urls";
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
        curl_setopt($ch, CURLOPT_REFERER, self::DOMAIN);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($httpcode >= 200 && $httpcode < 300) ? $data : null;
    }

    function loadImportLog($type, $path_folder){
        $path_folder = $path_folder."import/";
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

    function writeImportLog($type, $log, $path_folder){
        $file_name = $path_folder."import/bds_import_{$type}.json";
        $log_data = json_encode($log);
        $this->writeFileJson($file_name, $log_data);
    }

    function loadBdsImportLog($filename){
        $path_folder = Yii::getAlias('@console') . "/data/bds_html/import/";
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

    function writeBdsImportLog($path_folder, $filename, $log){
        $file_name = $path_folder."import/".$filename;
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

    public function importDataForTool($product_type)
    {
        $types = $this->types;
        $folder = "files";
        $path_folder = Yii::getAlias('@console') . "/data/bds_html/";
        $bds_import_filename = "bds_import_log.json";

        if ($product_type == 2) {
            $types = $this->rent_types;
            $folder = "rent_files";
            $path_folder = $path_folder . "rents/";
            $bds_import_filename = "bds_import_rent_log.json";
        }

        $start_time = time();
        $insertCount = 0;
        $count_file = 1;

        $bds_import_log = $this->loadLog($path_folder."import/", $bds_import_filename);
        if (!isset($bds_import_log["type"]) == false) {
            $bds_import_log["type"] = array();
        }

        $last_type_import = isset($bds_import_log["last_type_index"]) ? ($bds_import_log["last_type_index"] + 1) : 0;
        $count_types = count($types)-1;
        if($last_type_import > $count_types) {
            $last_type_import = 0;
        }

        $columnNameArray = ['category_id', 'project_building_id', 'user_id', 'home_no',
            'city_id', 'district_id', 'ward_id', 'street_id',
            'type', 'content', 'area', 'price', 'price_type', 'lat', 'lng',
            'start_date', 'end_date', 'verified', 'created_at', 'source', 'file_name'];
        $bulkInsertArray = array();
        $imageArray = array();
        $infoArray = array();
        $contactArray = array();

        $tableName = \vsoft\craw\models\AdProduct::tableName();
        $break_type = false; // detect next type if it is false

        $old_log = null; // used when insert error
        $last_type = null;
        $count_project = 0;
        foreach ($types as $key_type => $type) {
            $last_type = $type;
            if ($key_type >= $last_type_import && !$break_type) {
                $path = $path_folder."{$type}/{$folder}";
                if (is_dir($path)) {
                    $old_log = $log_import = $this->loadImportLog($type, $path_folder);
                    $this->writeLog($old_log, $path_folder."import/", "last_import_" .$type);

                    if (empty($log_import["files"]))
                        $log_import["files"] = array();

                    $files = scandir($path, 1);
                    $counter = count($files) - 2;
                    $last_file_index = $counter - 1;

                    if ($counter > 0) {
                        $filename = null;
                        for ($i = 0; $i <= $last_file_index; $i++) {
                            if ($count_file > 100) {
                                $break_type = true;
                                break;
                            }
                            $filename = $files[$i];
                            $filePath = $path . "/" . $filename;
                            print_r("\n" . $count_file . " {$type}: {$filename}");
                            if (in_array($filename, $log_import["files"])) {
//                                continue;
                                $value = $this->parseDetail($filePath);
                                $project_name = !empty($value[$filename]["project"]) ? $value[$filename]["project"] : null;
                                if(!empty($project_name)) {
                                    $project = AdBuildingProject::find()->where('name = :n', [':n' => $project_name])->one();
                                    if(count($project) > 0){
                                        $project_id = $project->id;
                                        $city_id = $project->city_id;
                                        $district_id = $project->district_id;
                                        $ward_id = $project->ward_id;
                                        $street_id = $project->street_id;
                                        $home_no = $project->home_no;
                                        $listing = \vsoft\craw\models\AdProduct::find()->where('file_name = :f', [':f' => $filename])->one();
                                        if(count($listing) > 0){
                                            if($listing->project_building_id == $project_id && $listing->city_id == $city_id && $listing->district_id == $district_id && $listing->street_id == $street_id){
                                                print_r(" - {$project_name} true.\n");
                                                continue;
                                            }

                                            $listing->project_building_id = $project_id;
                                            $listing->city_id = $city_id;
                                            $listing->district_id = $district_id;
                                            $listing->ward_id = $ward_id;
                                            $listing->street_id = $street_id;
                                            $listing->home_no = $home_no;
                                            $listing->update(false);
                                            print_r(" - {$project_name} updated.\n");
                                            continue;
                                        }
                                    }
                                } else {
                                    continue;
                                }
                            } else {
                                if (file_exists($filePath)) {
                                    $value = $this->parseDetail($filePath);
                                    if (empty($value)) {
                                        print_r(" Error: no content\n");
                                        continue;
                                    }

                                    $imageArray[$count_file] = $value[$filename]["thumbs"];
                                    $infoArray[$count_file] = $value[$filename]["info"];
                                    $contactArray[$count_file] = $value[$filename]["contact"];

                                    $project_id = null;
                                    $home_no = null;
                                    $city_id = null;
                                    $district_id = null;
                                    $ward_id = null;
                                    $street_id = null;

                                    $project_name = !empty($value[$filename]["project"]) ? $value[$filename]["project"] : null;
                                    if(!empty($project_name)) {
//                                        $project_id = $this->getProjectId($project_name);
                                        $project = AdBuildingProject::find()->where('name = :n', [':n' => $project_name])->one();
                                        if (count($project) > 0) {
                                            $project_id = $project->id;
                                            $city_id = $project->city_id;
                                            $district_id = $project->district_id;
                                            $ward_id = $project->ward_id;
                                            $street_id = $project->street_id;
                                            $home_no = $project->home_no;
                                            $count_project++;
                                            print_r(" - " . $project_name);
                                        }
                                    } else {
                                        $city_id = $this->getCityId2($value[$filename]["city"]);
                                        $district_id = $this->getDistrictId2($value[$filename]["district"], $city_id);
                                        $ward_id = $this->getWardId2($value[$filename]["ward"], $district_id);
                                        $street_id = $this->getStreetId2($value[$filename]["street"], $district_id);
                                        $home_no = $value[$filename]["home_no"];
                                    }

                                    $area = $value[$filename]["dientich"];
                                    $price = $value[$filename]["price"];

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
                                        'project_building_id' => $project_id,
                                        'user_id' => null,
                                        'home_no' => $home_no,
                                        'city_id' => $city_id,
                                        'district_id' => $district_id,
                                        'ward_id' => $ward_id,
                                        'street_id' => $street_id,
                                        'type' => $product_type,
                                        'content' => $content,
                                        'area' => $area,
                                        'price' => $price,
                                        'price_type' => empty($price) ? 0 : 1,
                                        'lat' => $value[$filename]["lat"],
                                        'lng' => $value[$filename]["lng"],
                                        'start_date' => $value[$filename]["start_date"],
                                        'end_date' => $value[$filename]["end_date"],
                                        'verified' => 1,
                                        'created_at' => $value[$filename]["start_date"],
                                        'source' => 1, 'file_name' => $filename
                                    ];
                                    // source = 1 for Batdongsan.com.vn
                                    $bulkInsertArray[] = $record;

//                                    print_r(" Added.\n");
                                    array_push($log_import["files"], $filename);
                                    $log_import["import_total"] = count($log_import["files"]);
                                    $log_import["import_time"] = date("d-m-Y H:i");
                                    $count_file++;
                                }
                            }
                        } // end file loop
                        $this->writeImportLog($type, $log_import, $path_folder);
                        if ($break_type == false && count($bulkInsertArray) > 0) {
                            if (!in_array($type, $bds_import_log["type"])) {
                                array_push($bds_import_log["type"], $last_type);
                            }
                            $bds_import_log["last_type_index"] = $key_type;
                            $this->writeLog($bds_import_log, $path_folder."import/", $bds_import_filename);
                            print_r("\nADD TYPE: {$type} DONE!\n");
                        }
                    }
                }
            }
        } // end types
        if (count($bulkInsertArray) > 0) {
            print_r("\nInsert data...");
            // below line insert all your record and return number of rows inserted
            $insertCount = \vsoft\craw\models\AdProduct::getDb()->createCommand()
                ->batchInsert($tableName, $columnNameArray, $bulkInsertArray)->execute();
            print_r(" DONE!");

            if ($insertCount > 0) {
                $ad_image_columns = ['user_id', 'product_id', 'file_name', 'uploaded_at'];
                $ad_info_columns = ['product_id', 'facade_width', 'land_width', 'home_direction', 'facade_direction', 'floor_no', 'room_no', 'toilet_no', 'interior'];
                $ad_contact_columns = ['product_id', 'name', 'phone', 'mobile', 'address', 'email'];

                $bulkImage = array();
                $bulkInfo = array();
                $bulkContact = array();

                $fromProductId = \vsoft\craw\models\AdProduct::getDb()->getLastInsertID();
                $toProductId = $fromProductId + $insertCount - 1;

                $index = 1;
                for ($i = $fromProductId; $i <= $toProductId; $i++) {
                    if (count($imageArray) > 0) {
                        foreach ($imageArray[$index] as $imageValue) {
                            if (!empty($imageValue)) {
                                $imageRecord = [
                                    'user_id' => null,
                                    'product_id' => $i,
                                    'file_name' => $imageValue,
                                    'uploaded_at' => time()
                                ];
                                $bulkImage[] = $imageRecord;
                            }
                        }
                    }

                    if (count($infoArray) > 0) {
                        $facade_width = empty($infoArray[$index]["Mặt tiền"]) == false ? trim($infoArray[$index]["Mặt tiền"]) : null;
                        $land_width = empty($infoArray[$index]["Đường vào"]) == false ? trim($infoArray[$index]["Đường vào"]) : null;
                        $home_direction = empty($infoArray[$index]["direction"]) == false ? trim($infoArray[$index]["direction"]) : null;
                        $facade_direction = null;
                        $floor_no = empty($infoArray[$index]["Số tầng"]) == false ? trim(str_replace('(tầng)', '', $infoArray[$index]["Số tầng"])) : 0;
                        $room_no = empty($infoArray[$index]["Số phòng ngủ"]) == false ? trim(str_replace('(phòng)', '', $infoArray[$index]["Số phòng ngủ"])) : 0;
                        $toilet_no = empty($infoArray[$index]["Số toilet"]) == false ? trim($infoArray[$index]["Số toilet"]) : 0;
                        $interior = empty($infoArray[$index]["Nội thất"]) == false ? trim($infoArray[$index]["Nội thất"]) : null;
                        $infoRecord = [
                            'product_id' => $i,
                            'facade_width' => $facade_width,
                            'land_width' => $land_width,
                            'home_direction' => $home_direction,
                            'facade_direction' => $facade_direction,
                            'floor_no' => $floor_no,
                            'room_no' => $room_no,
                            'toilet_no' => $toilet_no,
                            'interior' => $interior
                        ];
                        $bulkInfo[] = $infoRecord;
                    }
                    if (count($contactArray) > 0) {
                        $name = empty($contactArray[$index]["Tên liên lạc"]) == false ? trim($contactArray[$index]["Tên liên lạc"]) : null;
                        $phone = empty($contactArray[$index]["Điện thoại"]) == false ? trim($contactArray[$index]["Điện thoại"]) : null;
                        $mobile = empty($contactArray[$index]["Mobile"]) == false ? trim($contactArray[$index]["Mobile"]) : null;
                        $address = empty($contactArray[$index]["Địa chỉ"]) == false ? trim($contactArray[$index]["Địa chỉ"]) : null;
                        $email = empty($contactArray[$index]["Email"]) == false ? trim($contactArray[$index]["Email"]) : null;
                        $contactRecord = [
                            'product_id' => $i,
                            'name' => $name,
                            'phone' => $phone,
                            'mobile' => $mobile == null ? $phone : $mobile,
                            'address' => $address,
                            'email' => $email
                        ];
                        $bulkContact[] = $contactRecord;
                    }
                    $index = $index + 1;
                }

                // execute image, info, contact
                if (count($bulkImage) > 0) {
                    $imageCount = \vsoft\craw\models\AdImages::getDb()->createCommand()
                        ->batchInsert(\vsoft\craw\models\AdImages::tableName(), $ad_image_columns, $bulkImage)
                        ->execute();
                    if ($imageCount > 0)
                        print_r("\nInsert image done");
                }
                if (count($bulkInfo) > 0) {
                    $infoCount = \vsoft\craw\models\AdProductAdditionInfo::getDb()->createCommand()
                        ->batchInsert(\vsoft\craw\models\AdProductAdditionInfo::tableName(), $ad_info_columns, $bulkInfo)
                        ->execute();
                    if ($infoCount > 0)
                        print_r("\nInsert addition info done");
                }
                if (count($bulkContact) > 0) {
                    $contactCount = \vsoft\craw\models\AdContactInfo::getDb()->createCommand()
                        ->batchInsert(\vsoft\craw\models\AdContactInfo::tableName(), $ad_contact_columns, $bulkContact)
                        ->execute();
                    if ($contactCount > 0)
                        print_r("\nInsert contact info done");
                }
            } else {
                print_r("\nCannot insert ad_product!!");

            }

            if (!$break_type) {
                $bds_import_log = array();
                $this->writeLog($bds_import_log, $path_folder."import/", $bds_import_filename);
            }
        }

        print_r("\n\n------------------------------");
        print_r("\nFiles have been imported!\n");
        $end_time = time();
        print_r("\n" . "Time: ");
        print_r($end_time - $start_time);
        print_r("s - Total Record: " . $insertCount);
        if($count_project > 0)
            print_r(" - Total Project Listing: " . $count_project);
    }

    public function parseDetail($filename, $product_type=null)
    {
        $json = array();
        $page = file_get_contents($filename);
        if(empty($page))
            return null;
        $detail = SimpleHTMLDom::str_get_html($page, true, true, DEFAULT_TARGET_CHARSET, false);
        if (!empty($detail)) {
//            $title = $detail->find('h1', 0)->innertext;
//            $href = $detail->find('#form1', 0)->action;
            $project = $detail->find('#divProjectOptions .current', 0);
            $project = empty($project) ? null : trim($project->innertext);

            $lat = $detail->find('#hdLat', 0)->value;
            $long = $detail->find('#hdLong', 0)->value;

            $product_id = $detail->find('.pm-content', 0)->cid;
            $content = $detail->find('.pm-content', 0)->innertext;

            $dientich = trim($detail->find('.gia-title', 1)->plaintext);
            $dt = null;
            if (strpos($dientich, 'm²')) {
                $dientich = str_replace('m²', '', $dientich);
                $dientich = str_replace('Diện tích:', '', $dientich);
                $dientich = trim($dientich);
                $dt = (float)$dientich;
            }

            $gia = trim($detail->find('.gia-title', 0)->plaintext);
            $price = null;
            if (strpos($gia, ' triệu')) {
                $gia = str_replace('Giá:', '', $gia);
                if (strpos($gia, ' triệu/m²')) {
                    $gia = str_replace(' triệu/m²&nbsp;', '', $gia);
                    $dt_temp = empty($dt) ? 0 : $dt;
                    $gia = $gia * $dt_temp;
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

            $arr_contact = array();
            $contact = $detail->find('.pm-content-detail #divCustomerInfo', 0);
            if(!empty($contact)) {
                $div_contact = $contact->find('div.right-content div');
                $right = '';
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
                    if(!empty($arr_contact["Email"])) {
                        $str_email = $arr_contact["Email"];
                        $email = substr($str_email, strpos($str_email, "mailto:"));
                        $email = str_replace("mailto:", "", $email);
                        $email = substr($email, 0, strpos($email, "'>"));
                        $email = str_replace("';", "", $email);
                        $email = html_entity_decode($email);
                        $arr_contact["Email"] = $email;
                    }
                }
            }

            $city = null;
            $district = null;
            $ward = null;
            $street = null;
            $home_no = null;
            $startdate = time();
            $endate = time();
            $loai_tai_san = null;
            $arr_info = [];
            $left_detail = $detail->find('.pm-content-detail .left-detail', 0);
            if(empty($left_detail)){
                $dom = new DOMDocument();
                @$dom->loadHTMLFile($filename);
                if($dom->hasChildNodes()) {
                    $dom->preserveWhiteSpace = false; // discard white space
                    $xpath = new DOMXPath($dom);
                    // thumbs
                    foreach ($xpath->query('.//ul[@id="thumbs"]/li') as $li) {
                        $img_link = $xpath->query('.//img', $li)->item(0)->attributes[3]->value;
                        array_push($thumbs, $img_link);
                    }

                    // arr_info
                    foreach ($xpath->query('.//div[@class="left-detail"]/div') as $add) {
                        $div_left = trim($xpath->query('.//div[@class="left"]', $add)->item(0)->nodeValue);
                        $div_right = trim($xpath->query('.//div[@class="right"]', $add)->item(0)->nodeValue);
                        $arr_info[$div_left] = $div_right;
                    }

                    // arr_contact
                    foreach ($xpath->query('.//div[@id="divCustomerInfo"]/div[@class="right-content"]') as $ad) {
                        $div_left = trim($xpath->query('.//div[@class="normalblue left"]', $ad)->item(0)->nodeValue);
                        $div_right = trim($xpath->query('.//div[@class="right"]', $ad)->item(0)->nodeValue);
                        $arr_contact[$div_left] = $div_right;
                    }
                    if(!empty($arr_contact["Email"])) {
                        $str_email = $arr_contact["Email"];
                        $email = substr($str_email, strpos($str_email, "mailto:"));
                        $email = str_replace("mailto:", "", $email);
                        $email = substr($email, 0, strpos($email, "'>"));
                        $email = str_replace("';", "", $email);
                        $email = html_entity_decode($email);
                        $arr_contact["Email"] = $email;
                    }

                } else {
                    return null;
                }
            }
            else {
                $div_info = $left_detail->find('div div');
                $left = '';
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
            }

            if (count($arr_info) > 0) {
                $city = $detail->find('#divCityOptions .current', 0);
                $city = empty($city) ? null : trim($city->innertext);

                $district = $detail->find('#divDistrictOptions .current', 0);
                $district = empty($district) ? null : trim($district->innertext);

                $ward = $detail->find('#divWardOptions .current', 0);
                $ward = empty($ward) ? null : trim($ward->innertext);

                $street = $detail->find('#divStreetOptions .current', 0);
                $street = empty($street) ? null : trim($street->innertext);

                $direction = $detail->find('#divHomeDirectionOptions .current', 0);
                $arr_info["direction"] = empty($direction) ? null : trim($direction->vl);

//                // set address with link emailregister
//                $emailregister = trim($detail->find('#emailregister', 0)->href);
//                if(!empty($emailregister)) {
//                    $emailregister = substr($emailregister, strpos($emailregister, "cityCode="), strlen($emailregister) - 1);
////                    print_r($emailregister);
//                    $address = explode("&amp;", $emailregister);
//                    if (count($address) > 8) {
//                        foreach ($address as $val) {
//                            if ($this->beginWith($val, "direction=")) {
//                                $direction_id = (int)str_replace("direction=", "", $val);
//                                $arr_info["direction"] = $direction_id;
//                            }
//                        } // end for address
//                    }
//                }
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

                if ($this->beginWith($loai_tin, "Bán căn hộ chung cư") || strpos($loai_tin, "căn hộ chung cư")) {
                    $loai_tai_san = 6;
                } else if ($this->beginWith($loai_tin, "Bán nhà riêng") || strpos($loai_tin, "nhà riêng")) {
                    $loai_tai_san = 7;
                } else if ($this->beginWith($loai_tin, "Bán nhà biệt thự, liền kề")) {
                    $loai_tai_san = 8;
                } else if ($this->beginWith($loai_tin, "Bán nhà mặt phố") || strpos($loai_tin, "nhà mặt phố")) {
                    $loai_tai_san = 9;
                } else if ($this->beginWith($loai_tin, "Bán đất nền dự án")) {
                    $loai_tai_san = 10;
                } else if ($this->beginWith($loai_tin, "Bán đất")) {
                    $loai_tai_san = 11;
                } else if ($this->beginWith($loai_tin, "Bán trang trại, khu nghỉ dưỡng")) {
                    $loai_tai_san = 12;
                } else if ($this->beginWith($loai_tin, "Bán kho, nhà xưởng")) {
                    $loai_tai_san = 13;
                } else if (strpos($loai_tin, "nhà trọ, phòng trọ")) {
                    $loai_tai_san = 15;
                } else if (strpos($loai_tin, "văn phòng")) {
                    $loai_tai_san = 16;
                } else if (strpos($loai_tin, "cửa hàng, ki ốt")) {
                    $loai_tai_san = 17;
                } else if (strpos($loai_tin, "kho, nhà xưởng, đất")) {
                    $loai_tai_san = 18;
                } else {
                    $loai_tai_san = 14;
                }
            } else {
                return null;
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
//                'loai_giao_dich' => empty($product_type) ? 1 : $product_type,
                'price' => $price,
                'dientich' => $dt,
                'start_date' => $startdate,
                'end_date' => $endate,
                'project' => $project
//                'link' => self::DOMAIN . $href
            ];
        }
        return $json;
    }

    public function copyToMainDB($price, $build){
        $begin = time();
        $models = \vsoft\craw\models\AdProduct::find()->where(['not', ['file_name' => null]]);

        $product_tool_ids = AdProductToolMap::find()->select(['product_tool_id'])->all();
        $product_tool_ids = ArrayHelper::getColumn($product_tool_ids, 'product_tool_id');
        if(count($product_tool_ids) > 0)
            $models = $models->where(['not in', 'id', $product_tool_ids]);

        if($price == "price=1")
            $models = $models->andWhere(['price_type' => 1]);

        $models = $models->limit(1)->all();

        $insertCount = 0;
        if(count($models) > 0){
            $columnNameArray = ['category_id', 'project_building_id', 'user_id', 'home_no',
                'city_id', 'district_id', 'ward_id', 'street_id',
                'type', 'content', 'area', 'price', 'price_type', 'lat', 'lng',
                'start_date', 'end_date', 'verified', 'created_at', 'source', 'status'];
            $ad_image_columns = ['user_id', 'product_id', 'file_name', 'uploaded_at'];
            $ad_info_columns = ['product_id', 'facade_width', 'land_width', 'home_direction', 'facade_direction', 'floor_no', 'room_no', 'toilet_no', 'interior'];
            $ad_contact_columns = ['product_id', 'name', 'phone', 'mobile', 'address', 'email'];
            $ad_product_tool_map_columns = ['product_main_id', 'product_tool_id'];

            $imageArray = array();
            $infoArray = array();
            $contactArray = array();
            $productToolMaps = array();

            $bulkInsertArray = array();
            $bulkImage = array();
            $bulkInfo = array();
            $bulkContact = array();
            $bulkProductToolMap = array();

            foreach ($models as $model) {
//                if($price == 1){
//                    if(empty($model->price) || $model->price < 0){
//                        continue;
//                    }
//                }

                array_push($imageArray, $model->adImages);
                if (count($model->adProductAdditionInfo) > 0) {
                    array_push($infoArray, $model->adProductAdditionInfo);
                }
                if (count($model->adContactInfo) > 0) {
                    array_push($contactArray, $model->adContactInfo);
                }
                array_push($productToolMaps, $model->id);
                $record = [
                    'category_id' => $model->category_id,
                    'project_building_id' => empty($model->project_building_id) ? null : $model->project_building_id,
                    'user_id' => empty($model->user_id) ? null : $model->user_id,
                    'home_no' => empty($model->home_no) ? null : $model->home_no,
                    'city_id' => empty($model->city_id) ? null : $model->city_id,
                    'district_id' => empty($model->district_id) ? null : $model->district_id,
                    'ward_id' => empty($model->ward_id) ? null : $model->ward_id,
                    'street_id' => empty($model->street_id) ? null : $model->street_id,
                    'type' => $model->type,
                    'content' => $model->content,
                    'area' => $model->area,
                    'price' => $model->price,
                    'price_type' => $model->price_type,
                    'lat' => $model->lat,
                    'lng' => $model->lng,
                    'start_date' => $model->start_date,
                    'end_date' => $model->end_date,
                    'verified' => $model->verified,
                    'created_at' => $model->created_at,
                    'source' => $model->source,
                    'status' => 1
                ];
                $bulkInsertArray[] = $record;
            }

            $countBulkProduct = count($bulkInsertArray);
            if ($countBulkProduct > 0) {
                $insertCount = AdProduct::getDb()->createCommand()->batchInsert(AdProduct::tableName(), $columnNameArray, $bulkInsertArray)->execute();
                if ($insertCount > 0) {
                    $fromProductId = (int)\vsoft\ad\models\AdProduct::getDb()->getLastInsertID();
                    $toProductId = $fromProductId + ($insertCount - 1);

                    $index = 0; // dung de lay nhieu image cho 1 product hoat dong khi da insert vao db
                    for ($i = $fromProductId; $i <= $toProductId; $i++) {
                        if (count($imageArray) > 0 && isset($imageArray[$index])) {
                            foreach ($imageArray[$index] as $image) {
                                if (count($image) > 0 && !empty($image)) {
                                    $imageRecord = [
                                        'user_id' => $image->user_id,
                                        'product_id' => $i,
                                        'file_name' => $image->file_name,
                                        'uploaded_at' => $image->uploaded_at
                                    ];
                                    $bulkImage[] = $imageRecord;
                                }
                            }
                        }

                        if (count($infoArray) > 0 && isset($infoArray[$index])) {
                            $infoRecord = [
                                'product_id' => $i,
                                'facade_width' => $infoArray[$index]->facade_width,
                                'land_width' => $infoArray[$index]->land_width,
                                'home_direction' => $infoArray[$index]->home_direction,
                                'facade_direction' => $infoArray[$index]->facade_direction,
                                'floor_no' => $infoArray[$index]->floor_no,
                                'room_no' => $infoArray[$index]->room_no,
                                'toilet_no' => $infoArray[$index]->toilet_no,
                                'interior' => $infoArray[$index]->interior
                            ];
                            $bulkInfo[] = $infoRecord;
                        }

                        if (count($contactArray) > 0 && isset($contactArray[$index])) {
                            $contactRecord = [
                                'product_id' => $i,
                                'name' => $contactArray[$index]->name,
                                'phone' => $contactArray[$index]->phone,
                                'mobile' => $contactArray[$index]->mobile == null ? $contactArray[$index]->phone : $contactArray[$index]->mobile,
                                'address' => $contactArray[$index]->address,
                                'email' => $contactArray[$index]->email
                            ];
                            $bulkContact[] = $contactRecord;
                        }

                        if (count($productToolMaps) > 0 && isset($productToolMaps[$index])) {
                            $ptmRecord = [
                                'product_main_id' => $i,
                                'product_tool_id' => $productToolMaps[$index]
                            ];
                            $bulkProductToolMap[] = $ptmRecord;
                        }

                        $index = $index + 1;
                    }

                    // execute image, info, contact
                    if (count($bulkImage) > 0) {
                        $imageCount = AdImages::getDb()->createCommand()
                            ->batchInsert(AdImages::tableName(), $ad_image_columns, $bulkImage)
                            ->execute();
                    }
                    if (count($bulkInfo) > 0) {
                        $infoCount = AdProductAdditionInfo::getDb()->createCommand()
                            ->batchInsert(AdProductAdditionInfo::tableName(), $ad_info_columns, $bulkInfo)
                            ->execute();
                    }
                    if (count($bulkContact) > 0) {
                        $contactCount = AdContactInfo::getDb()->createCommand()
                            ->batchInsert(AdContactInfo::tableName(), $ad_contact_columns, $bulkContact)
                            ->execute();
                    }

                    // update product tool map
                    if (count($bulkProductToolMap) > 0) {
                        $ptmCount = AdProductToolMap::getDb()->createCommand()
                            ->batchInsert(AdProductToolMap::tableName(), $ad_product_tool_map_columns, $bulkProductToolMap)
                            ->execute();
                    }

                    if($imageCount > 0 && $infoCount > 0 && $contactCount > 0 && $ptmCount > 0) {
                        print_r("\nCopied {$insertCount} records to main database\n");
                    }
                }
            }
        } else {
            print_r("\nNot found new product. Please, try again!");
        }
        $end = time();
        $time = $end - $begin;
        print_r("Time: {$time}s");

        if($build == "build=true" && $insertCount > 0){
            $startElastic = time();
            print_r("\nBuild elastic ...");
            Yii::$app->runAction('elastic/build-index');
            $stopElastic = time();
            $timeElastic = $stopElastic - $startElastic;
            print_r("\nBuild finish. ".$timeElastic."s");
        }
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

    function loadLog($path, $filename){
        $filename = $path.$filename;
        if(!is_dir($path)){
            mkdir($path , 0777, true);
            echo "\nDirectory {$path} was created";
        }
        $data = null;
        if(file_exists($filename))
            $data = file_get_contents($filename);
        else
        {
            $this->writeFileJson($filename, null);
            $data = file_get_contents($filename);
        }

        if(!empty($data)){
            $data = json_decode($data, true);
            return $data;
        }
        else
            return null;
    }

    function writeLog($log, $path, $filename){
        $file_name = $path.$filename;
        $log_data = json_encode($log);
        $this->writeFileJson($file_name, $log_data);
    }

    public function getAgentId($href){
        $lastIndex = strripos($href, '-')+1;
        $result = substr($href, $lastIndex);
        return $result;
    }

    public function checkProductExists($path, $product_id, $mobile, $current_page){
        if(!is_dir($path)){
            mkdir($path , 0777, true);
            echo "\nDirectory {$path} was created";
        }
        $filename = $path.$current_page."-".$product_id."-".$mobile;;
        return file_exists($filename);
    }

    /***
     * CRAWLER AGENTs
     */
    public function getAgents(){
        $path = Yii::getAlias('@console') . "/data/bds_html/agents/";
        $file_log = "agent_log.json";
        $log = $this->loadLog($path, $file_log);
        $current_page = empty($log["current_page"]) ? 1 : ($log["current_page"] + 1);
        $url = "http://batdongsan.com.vn/nha-moi-gioi/p{$current_page}";
        $page = $this->getUrlContent($url);
        if(!empty($page)){
            print_r("\nGoto: {$url}\n");
            $html = SimpleHTMLDom::str_get_html($page, true, true, DEFAULT_TARGET_CHARSET, false);
            $list_page = $html->find('.ttmgl');
            if(count($list_page)){
                foreach ($list_page as $p) {
                    $a = $p->find('.tenmg a', 0);
                    $href = $a->href;
                    if (!empty($href)) {
                        $product_id = $this->getAgentId($href);
                        if(empty($product_id))
                            continue;
                        $_info = $p->find('.ttmg div');
                        $broker_info = array();
                        $left = '';
                        foreach($_info as $div){
                            $class = trim($div->class);
                            if (!(empty($class))) {
                                if ($class == 'left')
                                    $left = trim($div->innertext);
                                else if ($class == 'right') {
                                    $broker_info[$left] = trim($div->plaintext);
                                }
                            }
                        }
                        $mobile = empty($broker_info["Di động:"]) ? null : ($broker_info["Di động:"] == "Đang cập nhật" ? null : $broker_info["Di động:"]);
                        // check agent exists in folder
                        if($this->checkProductExists($path."files/", $product_id, $mobile, $current_page)){
                            print_r("\nduplicate: ".$current_page."-".$product_id."-".$mobile);
                            continue;
                        } else {
                            $this->getAgentDetail($path."files/", $product_id, $href, $broker_info, $current_page);

                        }
                    }
                }
                $log["current_page"] = $current_page;
                $this->writeLog($log, $path, $file_log);
                print_r("\nPage {$current_page} done.\n");
            }

            $pagination = $html->find('.container-default .pager-block a');
            $total_page = (int)str_replace("/nha-moi-gioi/p", "", $pagination[count($pagination) - 1]->href);
            if($total_page > 0){
                $current_page_add = $current_page + 9; // +4 => total page to run are 10.
                if($current_page_add > $total_page)
                    $current_page_add = $total_page;

                if($current_page > $total_page)
                    $current_page = 0;

                for($i=$current_page+1; $i<=$current_page_add; $i++){
                    $url = "http://batdongsan.com.vn/nha-moi-gioi/p{$i}";
                    $page = $this->getUrlContent($url);
                    if(!empty($page)){
                        print_r("\nGoto: {$url}\n");
                        $html_page = SimpleHTMLDom::str_get_html($page, true, true, DEFAULT_TARGET_CHARSET, false);
                        $list_page = $html_page->find('.ttmgl');
                        if(count($list_page)){
                            foreach ($list_page as $p) {
                                $a = $p->find('.tenmg a', 0);
                                $href = $a->href;
                                if (!empty($href)) {
                                    $product_id = $this->getAgentId($href);
                                    if(empty($product_id))
                                        continue;

                                    $_info = $p->find('.ttmg div');
                                    $broker_info = array();
                                    $left = '';
                                    foreach($_info as $div){
                                        $class = trim($div->class);
                                        if (!(empty($class))) {
                                            if ($class == 'left')
                                                $left = trim($div->innertext);
                                            else if ($class == 'right') {
                                                $broker_info[$left] = trim($div->plaintext);
                                            }
                                        }
                                    }
                                    $mobile = empty($broker_info["Di động:"]) ? null : ($broker_info["Di động:"] == "Đang cập nhật" ? null : $broker_info["Di động:"]);
                                    // check agent exists in folder
                                    if($this->checkProductExists($path."files/", $product_id, $mobile, $i)){
                                        print_r("\n".$i."-".$product_id."-".$mobile." duplicated.");
                                        continue;
                                    } else {

                                        $this->getAgentDetail($path."files/", $product_id, $href, $broker_info, $i);
                                    }
                                }
                            }
                            $log["current_page"] = $i;
                            $this->writeLog($log, $path, $file_log);
                            print_r("\nPage {$i} done.\n");
                            sleep(1);
                        }
                    } else {
                        print_r("\nCannot access {$url}");
                    }
                }
            } else {
                print_r("Why not get total page?");
            }
        }
    }

    public function getAgentDetail($path, $product_id, $href, $broker_info, $current_page){
        $page = $this->getUrlContent($this->domain.$href);
        if(!empty($page)){
            if(!is_dir($path)){
                mkdir($path , 0777, true);
                echo "\nDirectory {$path} was created";
            }
            $mobile = empty($broker_info["Di động:"]) ? null : ($broker_info["Di động:"] == "Đang cập nhật" ? null : $broker_info["Di động:"]);
            $filename = $current_page."-".$product_id."-".$mobile;
            if($this->writeFileJson($path.$filename, $page) > 0){
                return true;
            }
        } else {
            print_r("\nCannot access {$href}");
        }
        return false;
    }

    public function importAgent(){
        $path = Yii::getAlias('@console') . "/data/bds_html/agents/";
        $file_log = "import_agent_log.json";
        $log_import = $this->loadLog($path, $file_log);
        if (empty($log_import["files"]))
            $log_import["files"] = array();
        $agent_imported = empty($log_import["agent_imported"]) ? false : $log_import["agent_imported"];
        $break_type = false;
        if(!$agent_imported) {
            $columnNameArray = ['name', 'address', 'mobile', 'phone', 'fax', 'email', 'website', 'rating', 'working_area', 'source', 'type', 'tax_code', 'updated_at'];
            $bulkInsertArray = array();
            $files = scandir($path."/files", 1);
            $counter = count($files) - 2;
            $last_file_index = $counter - 1;
            if ($counter > 0) {
                $filename = null;
                $count_file = 1;
                for ($i = 0; $i <= $last_file_index; $i++) {
                    if ($count_file > 500) {
                        $break_type = true;
                        break;
                    }
                    $filename = $files[$i];
                    if (in_array($filename, $log_import["files"])) {
                        continue;
                    } else {
                        $filePath = $path ."files/". $filename;
                        if (file_exists($filePath)) {
                            print_r("\n" . $count_file . " - {$filename}");
                            $value = $this->parseAgentDetail($path, $filename);
                            if (empty($value)) {
                                print_r(" Error: no content\n");
                                continue;
                            }

                            $record = [
                                'name' => $value["name"],
                                'address' => $value["address"],
                                'mobile' => $value["mobile"],
                                'phone' => $value["phone"],
                                'fax' => $value["fax"],
                                'email' => $value["email"],
                                'website' => $value["website"],
                                'rating' => $value["rating"],
                                'working_area' => $value["working_area"],
                                'source' => $value["source"],
                                'type' => $value["type"],
                                'tax_code' => $value["tax_code"],
                                'updated_at' => $value["updated_at"]
                            ];
                            // source = 1 for Batdongsan.com.vn
                            $bulkInsertArray[] = $record;
                            array_push($log_import["files"], $filename);
                            $log_import["import_total"] = count($log_import["files"]);
                            $log_import["import_time"] = date("d-m-Y H:i");
                            $count_file++;
                        }
                    }
                } // end file loop
                $this->writeLog($log_import, $path, $file_log);
                if (count($bulkInsertArray) > 0) {
                    print_r("\nInsert data...");
                    // below line insert all your record and return number of rows inserted
                    $insertCount = AdAgent::getDb()->createCommand()
                        ->batchInsert("ad_agent", $columnNameArray, $bulkInsertArray)->execute();
                    if($insertCount > 0) {
                        print_r(" DONE!");
                    }
                }
            }

//            if(!$break_type){
//                $log_import["agent_imported"] = true;
//                $this->writeLog($log_import, $path, "import_agent_log.json");
//            }

        } else {
            print_r("Agents imported !!");
        }
    }

    public function parseAgentDetail($path, $filename){
        $json = array();
        $filePath = $path."files/".$filename;
        $page = file_get_contents($filePath);
        if(empty($page))
            return null;
        $detail = SimpleHTMLDom::str_get_html($page, true, true, DEFAULT_TARGET_CHARSET, false);
        if (!empty($detail)) {
            $name_obj = $detail->find('.broker-detail h1', 0);
            $name = null;
            if(!empty($name_obj)){
                $name = $name_obj->plaintext;
            } else {
                return null;
            }
            $rating = 0;

            $broker_info = array();
            $left = '';
            $broker_detail = $detail->find('.broker-detail .ttmg div');
            if(empty($broker_detail)) return null;
            foreach ($broker_detail as $div) {
                $class = $div->class;
                if (!(empty($class))) {
                    if ($class == 'left')
                        $left = trim($div->innertext);
                    else if ($class == 'right') {
                        $broker_info[$left] = trim(str_replace(":","",$div->innertext));
                    }
                }
            }

            $address = empty($broker_info["Địa chỉ"]) ? null : strip_tags($broker_info["Địa chỉ"]);
            $filename_array = explode("-",$filename);
            $mobile = null;
            if(count($filename_array) > 0)
                $mobile = $filename_array[count($filename_array)-1];

            $dt = empty($broker_info["ĐT"]) ? null : ($broker_info["ĐT"] == "Đang cập nhật" ? null : trim($broker_info["ĐT"]));
            $dienthoai = empty($broker_info["Điện thoại"]) ? null : ($broker_info["Điện thoại"] == "Đang cập nhật" ? null : trim($broker_info["Điện thoại"]));
            $fax = empty($broker_info["Fax"]) ? null : ($broker_info["Fax"] == "Đang cập nhật" ? null : $broker_info["Fax"]);
            $str_email = empty($broker_info["Email"]) ? null : ($broker_info["Email"] == "Đang cập nhật" ? null : $broker_info["Email"]);
            $email = null;
            if(!empty($str_email)) {
                $email = substr($str_email, strpos($str_email, "var attr = '"));
                $email = str_replace("var attr = '", "", $email);
                $email = substr($email, 0, strpos($email, "var txt ="));
                $email = str_replace("';", "", $email);
                $email = html_entity_decode($email);
            }
            $web = empty($broker_info["Website"]) ? null : ($broker_info["Website"] == "Đang cập nhật" ? null : strip_tags($broker_info["Website"]));
            $type_ = trim($detail->find('.introtitle', 0)->plaintext);
            $type = null;
            if(!empty($type_)) {
                if ($type_ == "Khu vực công ty môi giới")
                    $type = 1;
                else
                    $type = 2;
            }
            $working_list = $detail->find('.ltrAreaIntro ul li');
            $working = null;
            if(!empty($working_list)){
                foreach($working_list as $place){
                    $working .= " ".trim($place->plaintext).";";
                }
                $working = rtrim($working, ";");
            }

            $tax = empty($broker_info["Mã số thuế"]) ? null : ($broker_info["Mã số thuế"] == "Đang cập nhật" ? null : $broker_info["Mã số thuế"]);

            $json = [
                'name' => trim($name),
                'address' => trim($address),
                'mobile' => trim($mobile),
                'phone' => empty($dt) ? trim($dienthoai) : trim($dt),
                'fax' => trim($fax),
                'email' => trim($email),
                'website' => trim($web),
                'rating' => trim($rating),
                'working_area' => trim($working),
                'source' => 1,
                'type' => $type,
                'tax_code' => empty($tax) ? null : trim($tax),
                'updated_at' => time()
            ];
            return $json;
        }
    }

    /***
     * CRAWLER PROJECTs
     */
    public function getProjects(){
        ob_start();
        $this->time_start = time();
        $types = $this->projects;
        $path_folder = Yii::getAlias('@console') . "/data/bds_html/projects/";
        $project_log = $this->loadLog($path_folder, "project.json");
        if(empty($project_log["type"])){
            $project_log["type"] = array();
        }
        $current_type = empty($project_log["current_type"]) ? 0 : $project_log["current_type"]+1;
        $count_type = count($types) - 1;
        if($current_type >= $count_type - 1){
            $project_log["type"] = array();
            $project_log["current_type"] = 0;
            $current_type = 0;
            $this->writeLog($project_log, $path_folder, "project.json");
        }

        foreach($types as $key_type => $type){
            if ($key_type >= $current_type) {
                $url = self::DOMAIN . "/" . $type;
                $page = $this->getUrlContent($url);
                if (!empty($page)) {
                    $html = SimpleHTMLDom::str_get_html($page, true, true, DEFAULT_TARGET_CHARSET, false);
                    $pagination = $html->find('.ks-pagination-links a');
                    $count_page = count($pagination);
                    $last_page = (int)str_replace("/".$type . "/p", "", $pagination[$count_page-1]->href);

                    if ($last_page > 0) {
                        $log = $this->loadLog($path_folder . $type . "/", "{$type}.json");
                        if(empty($log["files"])){
                            $log["files"] = array();
                        }
                        $sequence_id = empty($log["files_last_id"]) ? 0 : ($log["files_last_id"] + 1);
                        $current_page = empty($log["current_page"]) ? 1 : ($log["current_page"] + 1);
//                        $current_page_add = $current_page + 4; // +4 => total pages to run that are 5.
//                        if ($current_page_add > $last_page)
//                            $current_page_add = $last_page; // comment to run to last page.

                        if ($current_page <= $last_page) {
                            for ($i = $current_page; $i <= $last_page; $i++) {
                                $list_return = $this->projectList($type, $i, $sequence_id, $log, $path_folder);
                                if (!empty($list_return["data"])) {
                                    $log = $list_return["data"];
                                    $log["current_page"] = $i;
                                    $this->writeLog($log, $path_folder.$type."/", "{$type}.json");
                                    print_r("\n{$type}-page " . $i . " done!\n");
                                }
                                sleep(1);
                                ob_flush();
                            }

//                            if($current_page != $current_page_add){
//                                break;
//                            }

                        } else {
                            $log['current_page'] = 0;
                            $this->writeLog($log, $path_folder.$type."/", "{$type}.json");
                            print_r("\nLast file of {$type} done.");
                        }
                    }
                }

                if(!in_array($type, $project_log["type"])) {
                    array_push($project_log["type"], $type);
                }
                $project_log["current_type"] = $key_type;
                $this->writeLog($project_log, $path_folder, "project.json");

            }
        }
        $this->time_end = time();
        print_r("\nTime: ");
        print_r($this->time_end - $this->time_start);
    }

    public function projectList($type, $current_page, $sequence_id, $log, $path_folder){
        $href = "/".$type."/p".$current_page;
        $page = $this->getUrlContent(self::DOMAIN . $href);
        if(!empty($page)) {
            $html = SimpleHTMLDom::str_get_html($page, true, true, DEFAULT_TARGET_CHARSET, false);
            $list = $html->find('.list2item2 .tc-img a');
            if (count($list) > 0) {
                // about 20 listing
                $id = null;
                foreach ($list as $item) {
                    if (preg_match('/pj(\d+)/', self::DOMAIN . $item->href, $matches)) {
                        if(!empty($matches[1])){
                            $id = $matches[1];
                        }
                    }
//                    $checkExists = false;
                    if(!empty($id)) {
                        $res = $this->projectDetail($type, $id, $item->href, $path_folder);
                        if (!empty($res)) {
                            $checkExists = in_array($id, $log["files"]);
                            if($checkExists)
                                print_r("\n".$id." override");
                            else
                                array_push($log["files"], $id);
                        }
                    }
//
//                    if ($checkExists == false) {
//                        $res = $this->projectDetail($type, $id, $item->href, $path_folder);
//                        if (!empty($res)) {
//                            array_push($log["files"], $id);
//                        }
//                    } else {
//                        var_dump($id);
//                    }
                }
                return ['data' => $log];
            } else {
                echo "\nCannot find listing. End page!".self::DOMAIN;
                $this->writeFileLogFail($type, "\nCannot find listing: $href"."\n");
            }

        } else {
            echo "\nCannot access in get List Project of ".self::DOMAIN;
            $this->writeFileLogFail($type, "\nCannot access: $href"."\n");
        }
        return null;
    }

    public function projectDetail($type, $id, $href, $path_folder){
        $page = $this->getUrlContent(self::DOMAIN . $href);
        if(!empty($page)) {
            $path = $path_folder."{$type}/files/";
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                echo "\nDirectory {$path} was created";
            }
            $res = $this->writeFileJson($path . $id, $page);
            if ($res) {
                $this->writeFileLogUrlSuccess($type, self::DOMAIN . $href . "\n", $path_folder);
                return $id;
            } else {
                return null;
            }
        }
        else {
            echo "\nError go to detail at " .self::DOMAIN.$href;
            return null;
        }
    }

    public function importProjects(){
        $start = time();
        $path = Yii::getAlias('@console') . "/data/bds_html/projects/";
        $file_log = "import_project_log.json";
        $project_log_import = $this->loadLog($path."import/", $file_log);
        if(!isset($project_log_import["type"]))
            $project_log_import["type"] = array();
        $types = $this->projects;
        $break_type = false; // detect next type if it is false
        $current_type = empty($project_log_import["current_type"]) ? 0 : $project_log_import["current_type"]+1;
//        $count_types = count($types)-1;
//        if($current_type > $count_types)
//            $current_type = 0;
        $count_file = 1;

        $columnContractor = ['name', 'address', 'phone', 'fax', 'website', 'email', 'logo', 'status', 'created_at'];
        $bulkInsertContractor = array();

        $columnNameArray = ['city_id', 'district_id', 'name', 'logo',
            'location', 'description', 'investment_type', 'hotline', 'website',
            'lng', 'lat', 'slug', 'status', 'created_at', 'file_name', 'is_crawl', 'data_html',
            'home_no', 'street_id', 'ward_id'];
        $bulkInsertArray = array();

        $listContractorProject = [];

        $cityData = \vsoft\ad\models\AdCity::find()->select(['id','name'])->all();
        $wards = \Yii::$app->db->createCommand("SELECT id, name, district_id FROM `ad_ward`")->queryAll();
        $streets = \Yii::$app->db->createCommand("SELECT id, name, district_id FROM `ad_street`")->queryAll();
        $contractorData = \vsoft\craw\models\AdInvestor::find()->all();

        foreach($types as $key_type => $type){
            if($key_type >= $current_type && !$break_type){
                if (is_dir($path.$type)) {
                    $log_import = $this->loadLog($path."import/", $type.".json");
                    if (empty($log_import["files"]))
                        $log_import["files"] = array();

                    $files = scandir($path.$type."/files", 1);
                    $counter = count($files) - 2;
                    $last_file_index = $counter - 1;
                    if ($counter > 0) {
                        $filename = null;
                        for ($i = 0; $i <= $last_file_index; $i++) {
                            if ($count_file > 300) {
                                $break_type = true;
                                break;
                            }
                            $filename = $files[$i];

                            if (in_array($filename, $log_import["files"])) {
//                                continue;
                                // neu co ton tai trong db và ten file trong log roi thi cap nhat lai
                                $value = $this->parseProjectDetail($path . $type. "/files/" , $filename);
                                $project = null;
                                $project_name = !empty($value[$filename]["name"]) ? $value[$filename]["name"] : null;
                                if(!empty($project_name))
                                    $project = AdBuildingProject::find()->where('name = :n', [':n' => $project_name])->one();
                                if (count($project) > 0) {
                                    // comment empty to update all project
                                    if(!empty($project->data_html) && (!empty($project->street_id) || !empty($project->ward_id))) {
                                        print_r("\n{$type}: {$filename} continue");
                                        continue;
                                    }

                                    $city_id = !empty($project->city_id) ? $project->city_id : $this->getCityId($value[$filename]["city"], $cityData);
                                    if(empty($city_id)) {
                                        $city_id = !empty($project->city_id) ? $project->city_id : $this->getCityId2($value[$filename]["city"]);
                                    }

                                    $district_id = !empty($project->district_id) ? $project->district_id : $this->getDistrictId2($value[$filename]["district"], $city_id);
                                    if(empty($district_id)) {
                                        $districtData = \vsoft\ad\models\AdDistrict::find()->select(['id','city_id','name'])->where(['city_id' => $city_id])->all();
                                        $district_id = !empty($project->district_id) ? $project->district_id : $this->getDistrictId($value[$filename]["district"], $districtData, $city_id);
                                    }

                                    $project->city_id = $city_id;
                                    $project->district_id = $district_id;
                                    $location = $value[$filename]["location"];
                                    $ws = $this->getWardAndStreet($location, $streets, $wards, $district_id);
                                    if (count($ws) > 0) {
                                        $project->street_id = (isset($ws["street_id"]) && !empty($ws["street_id"])) ? $ws["street_id"] : null;
                                        $project->ward_id = (isset($ws["ward_id"]) && !empty($ws["ward_id"])) ? $ws["ward_id"] : null;
                                        $project->home_no = (isset($ws["home_no"]) && !empty($ws["home_no"])) ? $ws["home_no"] : null;
                                    }

                                    $project->file_name = $type . "/" . $filename;
                                    $project->is_crawl = 1;
                                    $project->data_html = $value[$filename]["data_html"];
                                    $project->update(false);
                                    print_r("\n{$type}: {$filename} - ".$project_name." updated - {$project->city_id} - {$project->district_id}\n");
                                }
                            }
                            else {
                                $filePath = $path . $type. "/files/" . $filename;
                                if (file_exists($filePath)) {
                                    $street_id = null;
                                    $ward_id = null;
                                    $home_no = null;

                                    $value = $this->parseProjectDetail($path . $type. "/files/" , $filename);
                                    if (empty($value)) {
                                        print_r(" Error: no content\n");
                                        continue;
                                    }

                                    $project = null;
                                    $project_name = !empty($value[$filename]["name"]) ? $value[$filename]["name"] : null;
                                    if(!empty($project_name))
                                        $project = AdBuildingProject::find()->where('name = :n', [':n' => $project_name])->one();
                                    // neu co ton tai trong db va ko co ten file trong log thi cap nhat lai
                                    if (count($project) > 0) {
//                                        $city_id = !empty($project->city_id) ? $project->city_id : $this->getCityId2($this->slug($value[$filename]["city"]));
                                        $city_id = !empty($project->city_id) ? $project->city_id : $this->getCityId($value[$filename]["city"], $cityData);
                                        if(empty($city_id))
                                            $city_id = !empty($project->city_id) ? $project->city_id : $this->getCityId2($value[$filename]["city"]);

                                        $district_id = !empty($project->district_id) ? $project->district_id : $this->getDistrictId2($value[$filename]["district"], $city_id);
                                        if(empty($district_id)) {
                                            $districtData = \vsoft\ad\models\AdDistrict::find()->select(['id','city_id','name'])->where(['city_id' => $city_id])->all();
                                            $district_id = !empty($project->district_id) ? $project->district_id : $this->getDistrictId($value[$filename]["district"], $districtData, $city_id);
                                        }

                                        $project->city_id = $city_id;
                                        $project->district_id = $district_id;
                                        $location = $value[$filename]["location"];
                                        $ws = $this->getWardAndStreet($location, $streets, $wards, $district_id);
                                        if (count($ws) > 0) {
                                            $project->street_id = (isset($ws["street_id"]) && !empty($ws["street_id"])) ? $ws["street_id"] : null;
                                            $project->ward_id = (isset($ws["ward_id"]) && !empty($ws["ward_id"])) ? $ws["ward_id"] : null;
                                            $project->home_no = (isset($ws["home_no"]) && !empty($ws["home_no"])) ? $ws["home_no"] : null;
                                        }

                                        $project->file_name = $type . "/" . $filename;
                                        $project->is_crawl = 1;
                                        $project->data_html = $value[$filename]["data_html"];
                                        $project->update(false);
                                        print_r("{$type}: {$filename} - ". $project_name." - ". "updated\n");

                                    } else
                                    {
                                        // add new project
                                        $city_id = $this->getCityId($value[$filename]["city"], $cityData);
                                        if(empty($city_id))
                                            $city_id = !empty($project->city_id) ? $project->city_id : $this->getCityId2($value[$filename]["city"]);

                                        $district_id = !empty($project->district_id) ? $project->district_id : $this->getDistrictId2($value[$filename]["district"], $city_id);
                                        if(empty($district_id)) {
                                            $districtData = \vsoft\ad\models\AdDistrict::find()->select(['id','city_id','name'])->where(['city_id' => $city_id])->all();
                                            $district_id = !empty($project->district_id) ? $project->district_id : $this->getDistrictId($value[$filename]["district"], $districtData, $city_id);
                                        }
                                        $location = $value[$filename]["location"];
                                        $ws = $this->getWardAndStreet($location, $streets, $wards, $district_id);
                                        if (count($ws) > 0) {
                                            $street_id = (isset($ws["street_id"]) && !empty($ws["street_id"])) ? $ws["street_id"] : null;
                                            $ward_id = (isset($ws["ward_id"]) && !empty($ws["ward_id"])) ? $ws["ward_id"] : null;
                                            $home_no = (isset($ws["home_no"]) && !empty($ws["home_no"])) ? $ws["home_no"] : null;
                                        }

                                        $record = [
                                            'city_id' => $city_id,
                                            'district_id' => $district_id,
                                            'name' => $value[$filename]["name"],
                                            'logo' => $value[$filename]["logo"],
                                            'location' => $location,
                                            'description' => $value[$filename]["description"],
                                            'investment_type' => $value[$filename]["investment_type"],
                                            'hotline' => $value[$filename]["hotline"],
                                            'website' => $value[$filename]["website"],
                                            'lng' => $value[$filename]["lng"],
                                            'lat' => $value[$filename]["lat"],
                                            'slug' => $value[$filename]["slug"],
                                            'status' => $value[$filename]["status"],
                                            'created_at' => $value[$filename]["created_at"],
                                            'file_name' => $type . "/" . $filename,
                                            'is_crawl' => 1,
                                            'data_html' => $value[$filename]["data_html"],
                                            'home_no' => $home_no,
                                            'street_id' => $street_id,
                                            'ward_id' => $ward_id
                                        ];
                                        $bulkInsertArray[] = $record;

                                        $contractor = $value[$filename]["contractor"];
                                        // Check duplicate contractor :D
                                        if (count($contractor) > 0) {
                                            $checkContractorExists = $this->contractorExists($contractor["name"], $listContractorProject);
                                            $contractor_old_id = $this->getIdExists($contractor["name"], $contractorData);
                                            if ($checkContractorExists == false && $contractor_old_id == null) {
                                                $recordContractor = [
                                                    'name' => $contractor["name"],
                                                    'address' => $contractor["address"],
                                                    'phone' => $contractor["phone"],
                                                    'fax' => $contractor["fax"],
                                                    'website' => $contractor["website"],
                                                    'email' => $contractor["email"],
                                                    'logo' => $contractor["logo"],
                                                    'status' => 1,
                                                    'created_at' => time()
                                                ];
                                                $bulkInsertContractor[] = $recordContractor;
                                            }
                                            $listContractorProject[$value[$filename]["name"]] = $contractor["name"];
                                        }
                                        print_r("\n" . $count_file . " {$type}: {$filename}");
                                        $count_file++;
                                    }
                                    if (!in_array($filename, $log_import["files"])) {
                                        array_push($log_import["files"], $filename);
                                    }
                                    $log_import["import_total"] = count($log_import["files"]);
                                    $log_import["import_time"] = date("d-m-Y H:i");

                                }
                            }
                        } // end file loop

                        $this->writeLog($log_import, $path."import/", $type.".json");
                        if ($break_type == false && count($bulkInsertArray) > 0) {
                            if (!in_array($type, $project_log_import["type"])) {
                                array_push($project_log_import["type"], $type);
                            }
                            $project_log_import["current_type"] = $key_type;
                            $this->writeLog($project_log_import, $path."import/", $file_log);
                            print_r("\nADD TYPE: {$type} DONE!\n");
                        }
                    }
                } else {
                    print_r("\nCannot find ".$path.$type."\n");
                }
            }
        }

        $insertCountContractor = 0;
        if (count($bulkInsertContractor) > 0) {

            // below line insert all your record and return number of rows inserted
            $insertCountContractor = \vsoft\craw\models\AdInvestor::getDb()->createCommand()
                ->batchInsert(\vsoft\craw\models\AdInvestor::tableName(), $columnContractor, $bulkInsertContractor)->execute();
            print_r("\nInsert {$insertCountContractor} INVESTOR data ... DONE!");
        }

        $insertCount = 0;
        if (count($bulkInsertArray) > 0) {
            // below line insert all your record and return number of rows inserted
            $insertCount = \vsoft\craw\models\AdBuildingProject::getDb()->createCommand()
                ->batchInsert(\vsoft\craw\models\AdBuildingProject::tableName(), $columnNameArray, $bulkInsertArray)->execute();
            print_r("\nInsert {$insertCount} BUILDING PROJECT data ... DONE");
        }

        if(count($listContractorProject) > 0 && $insertCount > 0 && $insertCountContractor > 0){
            $columnContractorProject = ['building_project_id', 'investor_id'];
            $bulkInsertContractorProject = array();
            $buildingProjectData = \vsoft\craw\models\AdBuildingProject::find()->all();
            $investorDataNew = AdInvestor::find()->all();
            foreach($listContractorProject as $k => $v){
                $buildingProject_id = $this->getIdExists($k, $buildingProjectData);
                $investor_id = $this->getIdExists($v, $investorDataNew);
                $recordContractorProject = [
                    'building_project_id' => $buildingProject_id,
                    'investor_id' => $investor_id
                ];
                $bulkInsertContractorProject[] = $recordContractorProject;
            }

            print_r("\nMaps INVESTOR with BUILDING PROJECT data...");
            $insertCountContractorProject = \vsoft\craw\models\AdInvestorBuildingProject::getDb()->createCommand()
                ->batchInsert(\vsoft\craw\models\AdInvestorBuildingProject::tableName(), $columnContractorProject, $bulkInsertContractorProject)->execute();
            if($insertCountContractorProject > 0)
                print_r(" DONE!");
        }

        $end_time = time();
        print_r("\n"."Time: ");
        print_r($end_time-$start);
        print_r("s");

    }

    public function copyProjects(){
        $tool_projects = AdBuildingProject::find()->all();
        foreach($tool_projects as $tool_project){

        }
    }

    public function updateProjects(){
        $start = time();
        $path = Yii::getAlias('@console') . "/data/bds_html/projects/";
        $file_log = "update_project_log.json";
        $project_log_import = $this->loadLog($path."update/", $file_log);
        if(!isset($project_log_import["type"]))
            $project_log_import["type"] = array();
        $types = $this->projects;
        $break_type = false; // detect next type if it is false
        $current_type = empty($project_log_import["current_type"]) ? 0 : $project_log_import["current_type"]+1;
//        $count_types = count($types)-1;
//        if($current_type > $count_types)
//            $current_type = 0;
        $count_file = 1;

//        $cityData = \vsoft\craw\models\AdCity::find()->all();
//        $districtData = \vsoft\craw\models\AdDistrict::find()->all();
//        $contractorData = \vsoft\craw\models\AdInvestor::find()->all();

        foreach($types as $key_type => $type){
            if($key_type >= $current_type && !$break_type){
                if (is_dir($path.$type)) {
                    $log_import = $this->loadLog($path."update/", $type.".json");
                    if (empty($log_import["files"]))
                        $log_import["files"] = array();

                    $files = scandir($path.$type."/files", 1);
                    $counter = count($files) - 2;
                    $last_file_index = $counter - 1;
                    if ($counter > 0) {
                        $filename = null;
                        $from = isset($log_import["update_total"]) ? $log_import["update_total"] : 0;
                        for ($i = $from; $i <= $last_file_index; $i++) {
//                            if ($count_file > 1000) {
//                                $break_type = true;
//                                break;
//                            }
                            $filename = $files[$i];
                            $filePath = $path . $type. "/files/" . $filename;
                            if (file_exists($filePath)) {
                                $value = $this->parseProjectDetail($path . $type . "/files/", $filename);
                                if (empty($value)) {
                                    print_r(" Error: no content\n");
                                    continue;
                                }

                                $recordUpdate = [
                                    'logo' => $value[$filename]["logo"],
                                    'file_name' => $type . "/" . $filename,
                                    'is_crawl' => 1,
                                    'data_html' => $value[$filename]["data_html"],
                                ];
                                $project = \vsoft\ad\models\AdBuildingProject::getProjectBySlug($value[$filename]["slug"]);
                                if(!empty($project)) {
                                    $buildingInvestor = AdInvestorBuildingProject::findOne(['building_project_id' => $project->id]);
                                    $investor = $value[$filename]["contractor"];
                                    if(count($investor) > 0){
                                        $recordInvUpdate = [
                                            'logo' => $investor["logo"]
                                        ];
                                        \vsoft\ad\models\AdInvestor::getDb()->createCommand()
                                            ->update(\vsoft\ad\models\AdInvestor::tableName(), $recordInvUpdate, 'id=:id', [':id' => $buildingInvestor->investor_id])
                                            ->execute();
                                    }

                                    \vsoft\ad\models\AdBuildingProject::getDb()->createCommand()
                                        ->update(\vsoft\ad\models\AdBuildingProject::tableName(), $recordUpdate, 'id=:id', [':id' => $project->id])
                                        ->execute();

                                    print_r("\n" . $count_file . " {$type}: {$filename} updated.");
                                    array_push($log_import["files"], $filename);
                                    $log_import["update_total"] = count($log_import["files"]);
                                    $log_import["update_time"] = date("d-m-Y H:i");
                                    $this->writeLog($log_import, $path."update/", $type.".json");
                                }
                            }
                            $count_file++;
                        } // end file loop

                        if ($break_type == false) {
                            if (!in_array($type, $project_log_import["type"])) {
                                array_push($project_log_import["type"], $type);
                            }
                            $project_log_import["current_type"] = $key_type;
                            $this->writeLog($project_log_import, $path."update/", $file_log);
                            print_r("\nADD TYPE: {$type} DONE!\n");
                        }
                    }
                } else {
                    print_r("\nCannot find ".$path.$type."\n");
                }
            }
        }

        $end_time = time();
        print_r("\n"."Time: ");
        print_r($end_time-$start);
        print_r("s");

    }

    public function parseLocation($location){
        preg_match('/^(((ngõ )|(số )|(((lô )|(Lô đất ))[a-z]*)|[a-z])?[0-9]\S*( (–|-) [a-z]?[0-9]\S*)?( ngõ [a-z]*[0-9]\S*)?)/i', $location, $matches);

        $return = [];

        if($matches) {
            $return['homeNo'] = preg_replace('/số /i', '', rtrim($matches[0], ','));
            $remain = trim(str_replace($matches[0], '', $location));
        } else {
            $remain = $location;
        }

        $remainSplit = array_map('trim', explode(',', $remain));
        $streetKey = $this->streetDetect($remainSplit);

        if($streetKey !== null) {
            $return['street'] = trim(preg_replace('/^((đường)|(Đường)|(phố))\s?/i', '', $remainSplit[$streetKey]));
            $remainSplit = array_slice($remainSplit, $streetKey + 1);
        }

        $wardKey = $this->wardDetect($remainSplit);

        if($wardKey !== null) {
            $return['ward'] = trim(preg_replace('/^((phường)|(thị trấn)|(xã)|(x\.)|(p\.))\s?/i', '', $remainSplit[$wardKey]));

            if($streetKey === null && $wardKey > 0) {
                $return['street'] = $remainSplit[$wardKey - 1];
            }
        }

        $return['remainSplit'] = $remainSplit;

        return $return;
    }

    public function streetDetect($remainSplit) {
        foreach ($remainSplit as $k => $rs) {
            if(preg_match('/^((đ|Đường)|(phố))/i', $rs)) {
                return $k;
            }
        }
        return null;
    }

    public function wardDetect($remainSplit) {
        foreach ($remainSplit as $k => $rs) {
            if(preg_match('/^((phường)|(thị trấn)|(xã)|(x\.)|(p\.))/i', $rs)) {
                return $k;
            }
        }
        return null;
    }

    function slug($str) {
        $str = trim(mb_strtolower($str, 'UTF-8'));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        return $str;
    }

    public function getWardAndStreet($location, $streets, $wards, $district_id){
        $parseLocation = $this->parseLocation($location);

        if(isset($parseLocation['street']) && !isset($parseLocation['ward']) && $parseLocation['remainSplit']) {
            $parseLocation['ward'] = $parseLocation['remainSplit'][0];
        }

        if(isset($parseLocation['homeNo']) && !isset($parseLocation['street']) && $parseLocation['remainSplit']) {
            $parseLocation['street'] = $parseLocation['remainSplit'][0];

            if(!isset($parseLocation['ward']) && count($parseLocation['remainSplit']) > 1) {
                $parseLocation['ward'] = $parseLocation['remainSplit'][1];
            }
        }

        if(!isset($parseLocation['street']) && !isset($parseLocation['ward']) && $parseLocation['remainSplit']) {
            if(count($parseLocation['remainSplit']) >= 2) {
                $parseLocation['street'] = $parseLocation['remainSplit'][0];
                $parseLocation['ward'] = $parseLocation['remainSplit'][1];
            } else {
                $parseLocation['ward'] = $parseLocation['remainSplit'][0];
            }
        }

        $update = [];

        if(isset($parseLocation['homeNo'])) {
            $update['home_no'] = $parseLocation['homeNo'];
        }

        if(isset($parseLocation['street'])) {
            if(stripos($parseLocation['street'], ' và ')) {
                $parseLocation['street'] = explode(' và ', $parseLocation['street']);
                $parseLocation['street'] = $parseLocation['street'][0];
            }
            if(stripos($parseLocation['street'], ' – ')) {
                $parseLocation['street'] = explode(' – ', $parseLocation['street']);
                $parseLocation['street'] = $parseLocation['street'][0];
            }
            if(stripos($parseLocation['street'], ' - ')) {
                $parseLocation['street'] = explode(' - ', $parseLocation['street']);
                $parseLocation['street'] = $parseLocation['street'][0];
            }

            $parseLocation['street'] = str_ireplace([' nối dài', ' kéo dài'], '', $parseLocation['street']);

            foreach ($streets as $street) {
                if(strcasecmp($parseLocation['street'], $street['name']) === 0 && $street['district_id'] == $district_id) {
                    $update['street_id'] = $street['id'];
                    break;
                } else if($this->slug($parseLocation['street']) == $this->slug($street['name']) && $street['district_id'] == $district_id) {
                    $update['street_id'] = $street['id'];
                    break;
                }
            }
        }

        if(isset($parseLocation['ward'])) {
            foreach ($wards as $ward) {
                if(strcasecmp($parseLocation['ward'], $ward['name']) === 0 && $ward['district_id'] == $district_id) {
                    $update['ward_id'] = $ward['id'];
                    break;
                } else if($this->slug($parseLocation['ward']) == $this->slug($ward['name']) && $ward['district_id'] == $district_id) {
                    $update['ward_id'] = $ward['id'];
                    break;
                }
            }
        }
        return $update;
    }

    function getIdExists($name, $data)
    {
        foreach ($data as $obj) {
            $c = new Collator('vi_VN');
            $check = $c->compare(trim($name), trim($obj->name));
            if ($check == 0) {
                return (int)$obj->id;
            }
        }
        return null;
    }

    function contractorExists($name, $list)
    {
        foreach ($list as $obj) {
            $c = new Collator('vi_VN');
            $check = $c->compare(trim($name), trim($obj));
            if ($check == 0) {
                return true;
            }
        }
        return false;
    }


    public function parseProjectDetail($path_folder, $filename){
        $json = array();
        $page = file_get_contents($path_folder . $filename);
        if(empty($page))
            return null;
        $detail = SimpleHTMLDom::str_get_html($page, true, true, DEFAULT_TARGET_CHARSET, false);
        if (!empty($detail)) {
            $name = trim($detail->find('h1', 0)->innertext);
            $lat = $detail->find('#hdLat', 0)->value;
            $long = $detail->find('#hdLong', 0)->value;
            $address = $detail->find('#hdAddress', 0)->value;
            $city = $detail->find('#divCityOptions .current', 0, true)->innertext;
            $district = $detail->find('#divDistrictOptions .current', 0)->innertext;
            $logoTop = $detail->find('.prjava img', 0)->src;

            $inv_type = $detail->find('#divCatagoryOptions .current', 0)->innertext;

            $hotline = $detail->find('.prjinfo li', 2)->innertext;
            $hotline = trim(strip_tags($hotline));
            $hotline = substr($hotline, strpos($hotline, "Số điện thoại: "), strpos($hotline, "|"));
            $hotline = trim(str_replace("Số điện thoại:", "", $hotline));

            $website = $detail->find('.prjinfo li', 3)->innertext;
            $website = trim(strip_tags($website));
            $website = trim(str_replace("Website:", "", $website));

            $slug = $detail->find('link[rel=canonical]', 0)->href;
            $slug = substr($slug, 1, strpos($slug, "-pj")-1);

            $general = $detail->find('#detail .a1', 0);
            $imgGeneral = $general->find('img', 0);
            if(empty($imgGeneral))
                $logo = $logoTop;
            else
                $logo = $imgGeneral->src;

            $description = $general->innertext;
            $description = trim(strip_tags($description,'<br>'));
            $description = str_replace('<br />', PHP_EOL, $description);
            $description = trim($description);

            $contractor_name = trim($detail->find('#enterpriseInfo h3', 0)->plaintext);
            $contractor = array();
            if($contractor_name){
                $contractor["name"] = $contractor_name;
                $contractor_desc = $detail->find('.info .d11 img', 0)->src;
                $contractor["logo"] = strpos($contractor_desc, "no-photo") == true ? null : $contractor_desc;
                $contractor_info = $detail->find('.info .d12 ul li');
                if(count($contractor_info) > 0){
                    if(!empty($contractor_info[0])) {
                        $contractor_address = trim($contractor_info[0]->plaintext);
                        $contractor_address = trim(str_replace("Địa chỉ :", "", $contractor_address));
                        $contractor_address = $contractor_address == "Đang cập nhật" ? null : $contractor_address;
                        $contractor["address"] = $contractor_address;
                    }

                    if(!empty($contractor_info[1])) {
                        $contractor_phone_fax = trim($contractor_info[1]->plaintext);
                        $contractor_phone_fax = trim(str_replace("Điện thoại :", "", $contractor_phone_fax));

                        $contractor_phone = trim(substr($contractor_phone_fax, 0, strpos($contractor_phone_fax, "|")));
                        $contractor_phone = $contractor_phone == "Đang cập nhật" ? null : $contractor_phone;
                        $contractor["phone"] = $contractor_phone;

                        $contractor_fax = trim(substr($contractor_phone_fax, strpos($contractor_phone_fax, "Fax"), strlen($contractor_phone_fax)-1));
                        $contractor_fax = trim(str_replace("Fax :", "", $contractor_fax));
                        $contractor_fax = $contractor_fax == "Đang cập nhật" ? null : $contractor_fax;
                        $contractor["fax"] = $contractor_fax;
                    }

                    if(!empty($contractor_info[2])) {
                        $contractor_web = trim($contractor_info[2]->plaintext);
                        $contractor_web = trim(str_replace("Website :", "", $contractor_web));
                        $contractor_web = $contractor_web == "Đang cập nhật" ? null : $contractor_web;
                        $contractor["website"] = $contractor_web;
                    }

                    if(!empty($contractor_info[3])) {
                        $str_email = trim($contractor_info[3]->innertext);
                        $email = substr($str_email, strpos($str_email, "var attr = '"));
                        $email = str_replace("var attr = '", "", $email);
                        $email = substr($email, 0, strpos($email, "var txt ="));
                        $email = str_replace("';", "", $email);
                        $email = trim(html_entity_decode($email));
                        $contractor["email"] = $email;
                    }
                }
            }

            $data_html = array();
            $editors = $detail->find('#detail .editor');
            if(count($editors) > 0){
                foreach($editors as $editor){
                    if(!empty($editor->find('.a1', 0))) {
                        $tabId = (int)$editor->find('input', 0)->value;
                        $tabContent = trim($editor->find('.a1', 0)->innertext);
                        $tabKey = $this->tabProject[$tabId];
                        $data_html[$tabKey] = $tabContent;
                    }
                }
            }

            $json[$filename] = [
                'city' => trim($city),
                'district' => trim($district),
                'name' => trim($name),
                'logo' => $logo,
                'location' => trim($address),
                'description' => $description,
                'investment_type' => $inv_type,
                'hotline' => $hotline == "Đang cập nhật" ? null : $hotline,
                'website' => $website == "Đang cập nhật" ? null : $website,
                'lng' => $long,
                'lat' => $lat,
                'slug' => $slug,
                'status' => 1,
                'created_at' => time(),
                'data_html' => json_encode($data_html),
                'contractor' => $contractor
            ];
            return $json;
        }
        return null;
    }

    function beginWith($haystack, $needle) {
        return substr($haystack, 0, strlen($needle)) === $needle;
    }

    function str_replace_first($from, $to, $subject)
    {
        $from = '/'.preg_quote($from, '/').'/';

        return preg_replace($from, $to, $subject, 1);
    }

}