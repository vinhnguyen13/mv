<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 11/27/2015 11:12 AM
 */


$from = time();
include_once('simple_html_dom.php');
// url get from config
$url = 'http://batdongsan.com.vn/nha-dat-ban';
$host = parse_url($url, PHP_URL_HOST);
$homepage = getUrlContent($url);
$html = str_get_html($homepage, true, true, DEFAULT_TARGET_CHARSET, false);
$list = $html->find('div.p-title a');
$json = [];
$c = 1;
foreach ($list as $a) {
    $link = $a->href . '';
    if (strpos($link, $host) === false)
        $link = $host . $a->href;
    $detail = getUrlContent($link);
    $detail = str_get_html($detail, true, true, DEFAULT_TARGET_CHARSET, false);
    if (!empty($detail)) {
        $title = $detail->find('h1', 0)->innertext;
//        empty($title) == false ? $json["nha-dat-ban"][$c]["title"] = trim($title) : $json["nha-dat-ban"][$c]["title"] = '';

        $lat = $detail->find('#hdLat', 0)->value;
//        empty($lat) == false ? $json["nha-dat-ban"][$c]["lat"] = $lat : $json["nha-dat-ban"][$c]["lat"] = '';

        $long = $detail->find('#hdLong', 0)->value;
//        empty($long) == false ? $json["nha-dat-ban"][$c]["long"] = $long : $json["nha-dat-ban"][$c]["long"] = '';

        $content = $detail->find('.pm-content', 0)->innertext;
//        empty($content) == false ? $json["nha-dat-ban"][$c]["content"] = trim($content) : $json["nha-dat-ban"][$c]["content"] = '';

        $imgs = $detail->find('#thumbs li img');
        $thumbs = array();
        if(count($imgs) > 0) {
            foreach ($imgs as $img) {
                array_push($thumbs, $img->src);
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
//                    if($right == 'Email'){
//                        $value = substr(0, 200);
//                    }
                    $arr_contact[$right] = trim($value);
                }
            }
        }
        $json[$c] = [
            'title' => $title,
            'lat' => $lat,
            'long' => $long,
            'thumbs' => $thumbs,
            'info' => $arr_info,
            'contact' => $arr_contact
        ];
        $c++;
    }
}
$json = json_encode($json);

$file_name = 'files/news_data_'.time().'.json';
$num = writeFileJson($file_name, $json);
//print_r(json_decode($json));
//print_r($json);

echo $num > 0 ? 'Da ghi file ' : 'Loi ghi file ';
$to = time();
echo $to - $from;
echo 's';


function getUrlContent($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 100);
    $data = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ($httpcode >= 200 && $httpcode < 300) ? $data : false;
}

function writeFileJson($filePath, $data){
    $handle = fopen($filePath, 'w') or die('Cannot open file:  '.$filePath);
    $int = fwrite($handle, $data);
    fclose($handle);
    return $int;
}

function readFileJson($filePath){
    $handle = fopen($filePath, 'r') or die('Cannot open file:  '.$filePath);
    if(filesize($filePath) > 0) {
        $data = fread($handle, filesize($filePath));
        return $data;
    }
    else return null;

}


