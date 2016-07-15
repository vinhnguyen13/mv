<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 7/11/2016 5:09 PM
 */

namespace console\models;


use Yii;

class Helpers
{
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

    public static function setZeroCurrentPage($types, $path_folder){
        foreach ($types as $key_type => $type) {
            $path_folder = $path_folder."/".$type."/";
            $filename = "bds_log_{$type}.json";
            $file = $path_folder.$filename;
            if(file_exists($file)){
                $file_log = Helpers::loadLog($file, $filename);
                if(!empty($file_log["current_page"])){
                    $file_log["current_page"] = 0;
                    Helpers::writeLog($file_log, $path_folder, $filename);
                }
            }
        }
    }

    public static function getUrlContent($url)
    {
        $agent = "Mozilla/5.0 (X11; U; Linux i686; en-US) " .
            "AppleWebKit/532.4 (KHTML, like Gecko) " .
            "Chrome/4.0.233.0 Safari/532.4";
        $referer = "http://www.google.com/";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($httpcode >= 200 && $httpcode < 300) ? $data : null;
    }

    public static function writeToFile($filePath, $data, $mode = 'a')
    {
        $handle = fopen($filePath, $mode) or die('Cannot open file:  ' . $filePath);
        $int = fwrite($handle, $data);
        fclose($handle);
        return $int;
    }

    public static function writeFileLogFail($type, $log){
        $file_name = Yii::getAlias('@console') . "/data/bds_html/{$type}/bds_log_fail";
        if(!file_exists($file_name)){
            fopen($file_name, "w");
        }
        if( strpos(file_get_contents($file_name),$log) === false) {
            Helpers::writeToFile($file_name, $log, 'a');
        }
    }

    public static function writeFileLogUrlSuccess($type, $log, $path_folder){
        $file_name = $path_folder."{$type}/bds_log_urls";
        if(!file_exists($file_name)){
            fopen($file_name, "w");
        }
        if( strpos(file_get_contents($file_name),$log) === false) {
            Helpers::writeToFile($file_name, $log, 'a');
        }
    }

    public static function beginWith($haystack, $needle) {
        return substr($haystack, 0, strlen($needle)) === $needle;
    }

    public static function str_replace_first($from, $to, $subject)
    {
        $from = '/'.preg_quote($from, '/').'/';

        return preg_replace($from, $to, $subject, 1);
    }

}