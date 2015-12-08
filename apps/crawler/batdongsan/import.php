<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 12/7/2015 10:24 AM
 */
//namespace common\vendor\vsoft\ad\models;
//
//$fileAddressPath = realpath(__DIR__ . '/../../..') . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . "data.js";
//$handle = fopen($fileAddressPath, 'r') or die('Cannot open file:  ' . $fileAddressPath);
//if (filesize($fileAddressPath) > 0) {
//    $address = fread($handle, filesize($fileAddressPath));
//    $address = str_replace('var dataCities = ', '', $address);
//    $address = str_replace(';', '', $address);
//    $address = json_decode($address, true);
//    if (is_array($address)) {
//        import($address);
//    }
//}
import('');
function import($address)
{
    $dir = dirname(__FILE__) . "\\files" . DIRECTORY_SEPARATOR;
    $lastMod = 0;
    $lastModFile = '';
    foreach (scandir($dir) as $entry) {
        if (is_file($dir . $entry) && filectime($dir . $entry) > $lastMod) {
            $lastMod = filectime($dir . $entry);
            $lastModFile = $entry;
        }
    }
    $filePath = $dir . '\\' . $lastModFile;//'files/news_data_1449224604.json';
    $handle = fopen($filePath, 'r') or die('Cannot open file:  ' . $filePath);
    if (filesize($filePath) > 0) {
        $data = fread($handle, filesize($filePath));
        $data = json_decode($data, true);
        print_r($data);
//        foreach ($data as $key => $value) {
//            $addr = $value["info"]["Địa chỉ"];
//            $arr_data = explode(",", $addr);
//            $cityId = null;
//            $districtId = null;
//            $wardId = null;
//            $streetId = null;
//            $projectId = null;
//
//            $city_pos = count($arr_data) - 1;
//
//            $cityName = trim($arr_data[$city_pos]);
//            $districtName = trim($arr_data[$city_pos-1]);
//            $wardName = trim($arr_data[$city_pos-2]);
//            $streetName = trim($arr_data[$city_pos-3]);
//            $projectName = trim($arr_data[$city_pos-4]);
//
//            foreach ($address as $c => $city) {
//                if ($cityName == $city['name']) {
//                    $cityId = $c;
//                    break;
//                }
//            }
//
//            if(!empty($cityId)){
//                foreach ($address[$cityId]['districts'] as $d => $district) {
//                    if ($districtName == $district['name']) {
//                        $districtId = $d;
//                        break;
//                    }
//                }
//            }
//
//            if(!empty($districtId)){
//                foreach($address[$cityId][$districtId]['wards'] as $w => $ward){
//                    if($wardName == $ward['name']){
//                        $wardId = $w;
//                        break;
//                    }
//                }
//                foreach($address[$cityId][$districtId]['streets'] as $s => $street){
//                    if($streetName == $street['name']){
//                        $streetId = $s;
//                        break;
//                    }
//                }
//                foreach($address[$cityId][$districtId]['projects'] as $p => $project){
//                    if($projectName == $project['name']){
//                        $projectId = $p;
//                        break;
//                    }
//                }
//            }
//        }

    }
}



