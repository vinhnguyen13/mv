<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 7/11/2016 5:09 PM
 */

namespace console\models;

use common\components\Slug;
use vsoft\craw\models\AdCity;
use vsoft\craw\models\AdDistrict;
use Yii;

class Helpers
{
    public static function getCityId($city_name)
    {
        if(!empty($city_name)) {
            $city_slug = Slug::me()->slugify($city_name);
            $city = AdCity::getDb()->cache(function () use ($city_slug) {
                return AdCity::find()->select(['id', 'name', 'slug'])->where("slug like '%{$city_slug}'")->asArray()->all();
            });
            $count = count($city);
            if($count == 1)
                return $city[0];
            else if($count > 1)
                return 500;
            else {
                $sql = "SELECT `id`, `name`, slug FROM ad_city WHERE `name` LIKE '" . $city_name . "' LIMIT 1";
                $city = AdCity::getDb()->createCommand($sql)->queryAll();
                if($city) {
                    return $city[0];
                } else {
                    return null;
                }
            }
        }
        return null;
    }

    public static function getDistrictId($district_name, $city_id)
    {
        if(!empty($city_id) && !empty($district_name)) {
            $district_slug = Slug::me()->slugify($district_name);
            $district = AdDistrict::getDb()->cache(function() use($district_slug, $city_id){
                return AdDistrict::find()->select(['id', 'name', 'slug', 'pre'])->where("slug like '%{$district_slug}' AND city_id = {$city_id}")->asArray()->all();
            });
            $count = count($district);
            if($count == 1)
                return $district[0];
            else if($count > 1)
                return 500;
            else {
                $sql = "SELECT `id`, `name`, slug, pre FROM ad_district WHERE (`name` LIKE '".$district_name."' OR '".$district_name."' LIKE CONCAT(pre, ' ', name)) AND `city_id` = ".$city_id. " LIMIT 1";
                $district = AdDistrict::getDb()->createCommand($sql)->queryAll();
                if($district) {
                    return $district[0];
                } else {
                    return null;
                }
            }
        }
        return null;
    }

    public static function writeFileJson($filePath, $data)
    {
        $handle = fopen($filePath, 'w') or die('Cannot open file:  ' . $filePath);
        $int = fwrite($handle, $data);
        fclose($handle);
        return $int;
    }

    public static function loadLog($path, $filename){
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
            Helpers::writeFileJson($filename, null);
            $data = file_get_contents($filename);
        }

        if(!empty($data)){
            $data = json_decode($data, true);
            return $data;
        }
        else
            return null;
    }

    public static function writeLog($log, $path, $filename){
        $file_name = $path.$filename;
        $log_data = json_encode($log);
        Helpers::writeFileJson($file_name, $log_data);
    }

    public static function moveFile($fromFilePath, $toFilePath, $folder)
    {
        if(!is_dir($folder)){
            mkdir($folder, 0777);
        }
        return rename($fromFilePath, $toFilePath);
    }

    public static function getUrlContent($url, $post_string = null)
    {
        $agent = "Mozilla/5.0 (X11; U; Linux i686; en-US) " .
            "AppleWebKit/532.4 (KHTML, like Gecko) " .
            "Chrome/4.0.233.0 Safari/532.4";
        $referer = "https://www.google.com.vn/";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if($post_string){
//            $post_items = [];
//            foreach ( $postData as $key => $value) {
//                $post_items[] = $key . '=' . $value;
//            }
//            $post_string = implode ('&', $post_items);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($httpcode >= 200 && $httpcode < 300) ? $data : null;
    }

    public static function beginWith($haystack, $needle) {
        return substr($haystack, 0, strlen($needle)) === $needle;
    }

    public static function str_replace_first($from, $to, $subject)
    {
        $from = '/'.preg_quote($from, '/').'/';

        return preg_replace($from, $to, $subject, 1);
    }

    public static function getDbTool()
    {
        $db_tool = 'db_mv_tool';
        $dbCraw = \Yii::$app->dbCraw;
        if($dbCraw) {
            $dsn = explode(";", $dbCraw->dsn);
            $db_tool = str_replace("dbname=",'', $dsn[1]);
        }
        return $db_tool;
    }

}