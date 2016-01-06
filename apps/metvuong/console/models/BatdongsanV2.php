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
        $url = self::DOMAIN . '/nha-dat-ban';
        $page = $this->getUrlContent($url);
        if(!empty($page)) {
            $html = SimpleHTMLDom::str_get_html($page, true, true, DEFAULT_TARGET_CHARSET, false);
            $pagination = $html->find('.container-default .background-pager-right-controls a');
            $count_page = count($pagination);
            $last_page = (int)str_replace("/nha-dat-ban/p", "", $pagination[$count_page-1]->href);
//            $last_page = str_replace("/nha-dat-ban-phuong-tan-quy/p", "", $pagination[$count_page-1]->href);
            if($count_page > 0) {
//                $last_page = 3;
                $log = $this->loadFileLog();
                $current_page = empty($log["current_page"]) ? 1 : ($log["current_page"]+1);
                $current_page_add = $current_page + 4;
                if($current_page_add <= $last_page) {
                    for ($i = $current_page; $i <= $current_page_add; $i++) {
                        $log = $this->loadFileLog();
                        $sequence_id = empty($log["last_id"]) ? 0 : ($log["last_id"] + 1);
                        $list_return = $this->getListProject($i, $sequence_id, $log);
                        if (!empty($list_return["data"])) {
                            $list_return["data"]["current_page"] = $i;
                            $this->writeFileLog($list_return["data"]);
                            print_r("Page " . $i . " done!");
                            print_r("\n");
                        }
                        sleep(1);
                        ob_flush();
                    }
                }
            } else {
                echo "Cannot find listing. End page!".self::DOMAIN;
            }
        } else {
            echo "Cannot access in get pages of ".self::DOMAIN;
        }
    }

    public function getListProject($current_page, $sequence_id, $log)
    {
        $href = "/nha-dat-ban/p".$current_page;
//        $href = "/nha-dat-ban-phuong-tan-quy/p".$current_page;
        $page = $this->getUrlContent(self::DOMAIN . $href);
        if(!empty($page)) {
            $html = SimpleHTMLDom::str_get_html($page, true, true, DEFAULT_TARGET_CHARSET, false);
            $list = $html->find('div.p-title a');
            if (count($list) > 0) {
                // about 20 listing
                foreach ($list as $item) {
                    $startIndex = strripos($item->href, '-pr');
                    $productId = substr($item->href, $startIndex, strlen($item->href));
                    $productId = str_replace('-pr', '', $productId);
                    $checkExists = false;
                    if(!empty($log["files"])) {
                        $checkExists = in_array($productId, $log["files"]);
                    }

                    if ($checkExists == false) {
                        $res = $this->getProjectDetail($item->href);
                        if (!empty($res)) {
                            $log["files"][$sequence_id] = $res;
                            $log["last_id"] = $sequence_id;
                            $sequence_id = $sequence_id + 1;
                        }
                    } else {
                        var_dump($productId);
                    }
                }
                return ['data' => $log];
            } else {
                echo "Cannot find listing. End page!".self::DOMAIN;
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
        $matches = array();
        if (preg_match('/pr(\d+)/', self::DOMAIN . $href, $matches)) {
            if(!empty($matches[1])){
                $product_id = $matches[1];
            }
        }

        if(!empty($product_id)) {
                $path = Yii::getAlias('@console') . '/data/bds_html/files/';
                if(!is_dir($path)){
                    mkdir($path , 0777, true);
                    echo "Directory {$path} was created";
                }
                $res = $this->writeFileJson($path.$product_id, $page);
                if($res)
                    return $product_id;
                else
                    return null;

        }
        else {
            echo "Error go to detail at " .self::DOMAIN.$href;
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

    function loadFileLog(){
        $path_folder = Yii::getAlias('@console') . "/data/bds_html/";
        $path = $path_folder."bds_log.json";
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

    function writeFileLog($log){
        $file_name = Yii::getAlias('@console') . '/data/bds_html/bds_log.json';
        $log_data = json_encode($log);
        $this->writeFileJson($file_name, $log_data);
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
        curl_setopt($ch, CURLOPT_REFERER, self::DOMAIN . '/nha-dat-ban/');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($httpcode >= 200 && $httpcode < 300) ? $data : null;
    }

}