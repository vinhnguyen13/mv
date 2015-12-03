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
//echo count($list) .'<br>';
//$json = [
//    'nha-dat-ban' => []
//];
$c=1;
foreach($list as $a){
    $link = $a->href.'';
    if(strpos($link, $host) === false)
        $link = $host.$a->href;
    $detail = getUrlContent($link);
    $detail = str_get_html($detail, true, true, DEFAULT_TARGET_CHARSET, false);

    $title = $detail->find('h1', 0)->innertext;
//    $lat = $detail->find('#hdLat', 0)->value;
//    $long = $detail->find('#hdLong', 0)->value;
//    $content = $detail->find('.pm-content', 0)->innertext;
//    if($c == 12){
//        echo $title.'<br> -'.$lat.' -'.$long.'<br>';
//    }

//    $imgs = $detail->find('#thumbs li img');
//    $thumbs = array();
//    if(count($imgs) > 0) {
//        foreach ($imgs as $img) {
////            echo $img->src.'<br>';
//            array_push($thumbs, $img->src);
//        }
//    }

    echo '<b>'.$c.'</b><br>';
    echo $title.'<br>';

//    $left_detail = $detail->find('.pm-content-detail .left-detail');

    $contact = $detail->find('.pm-content-detail #divCustomerInfo', 0);
    echo $contact->innertext;


    echo '<br>';

    $c++;

}
$to = time();
echo $to - $from;

function getUrlContent($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 400);
    $data = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ($httpcode>=200 && $httpcode<300) ? $data : false;
}


