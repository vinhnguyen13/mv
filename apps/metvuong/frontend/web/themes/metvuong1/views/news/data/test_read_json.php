<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 12/4/2015 5:02 PM
 */
$dir = dirname(__FILE__)."\\files".DIRECTORY_SEPARATOR;
$lastMod = 0;
$lastModFile = '';
foreach (scandir($dir) as $entry) {
    if (is_file($dir.$entry) && filectime($dir.$entry) > $lastMod) {
        $lastMod = filectime($dir.$entry);
        $lastModFile = $entry;
    }
}

//print_r($lastModFile);
$filePath = $dir.'\\'.$lastModFile;//'files/news_data_1449224604.json';
$handle = fopen($filePath, 'r') or die('Cannot open file:  '.$filePath);
if(filesize($filePath) > 0) {
    $data = fread($handle, filesize($filePath));
    $data = json_decode($data, true);
    print_r($data);
}
