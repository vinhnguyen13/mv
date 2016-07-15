<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 7/11/2016 4:48 PM
 */

namespace console\models\batdongsan;


use console\models\BatdongsanV2;
use console\models\Helpers;
use keltstr\simplehtmldom\SimpleHTMLDom;
use Yii;
use yii\base\Component;
use yii\base\Exception;

class Listing extends Component
{

    public static function find()
    {
        return Yii::createObject(Listing::className());
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
        try {
//        ob_start();
            $this->time_start = time();
            $this->getPages(2);
            $this->time_end = time();
            print_r("\nTime: ");
            print_r($this->time_end - $this->time_start);
        } catch(Exception $e){
            $currentBuffers = ob_get_clean();
            ob_end_clean(); // Let's end and clear ob...
            echo "<br />Some error occured: " . $e->getMessage();
        }
    }

    public function getPages($product_type)
    {
        $types = BatdongsanV2::find()->types;
        $path_folder = Yii::getAlias('@console') . "/data/bds_html/";
        $bds_log = Helpers::loadLog($path_folder, "bds_log.json");
        if($product_type == 2){
            $types = BatdongsanV2::find()->rent_types;
            $path_folder = Yii::getAlias('@console') . "/data/bds_html/rents/";
            $bds_log = Helpers::loadLog($path_folder, "bds_rent_log.json");
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
                Helpers::writeLog($bds_log, $path_folder, "bds_log.json");
            else
                Helpers::writeLog($bds_log, $path_folder, "bds_rent_log.json");

            $this->setZeroCurrentPage($types, $path_folder);
        }

        foreach ($types as $key_type => $type) {
            if ($key_type >= $last_type) {
                $url = BatdongsanV2::DOMAIN . '/' . $type;
                $page = Helpers::getUrlContent($url);
                if (!empty($page)) {
                    $html = SimpleHTMLDom::str_get_html($page, true, true, DEFAULT_TARGET_CHARSET, false);
                    $pagination = $html->find('.container-default .background-pager-right-controls a');
                    $count_page = count($pagination);
                    $last_page = (int)str_replace("/" . $type . "/p", "", $pagination[$count_page - 1]->href);
                    if ($count_page > 0) {
                        $log = Helpers::loadLog($path_folder."/".$type."/", "bds_log_{$type}.json");
                        $current_page = empty($log["current_page"]) ? 1 : ($log["current_page"] + 1);
                        $current_page_add = $current_page + 4; // +4 => total pages to run that are 5.
                        if($current_page_add > $last_page)
                            $current_page_add = $last_page;

                        if ($current_page <= $last_page) {
                            for ($i = $current_page; $i <= $current_page_add; $i++) {
                                $log = Helpers::loadLog($path_folder."/".$type."/", "bds_log_{$type}.json");
                                $sequence_id = empty($log["last_id"]) ? 0 : ($log["last_id"] + 1);
                                $list_return = $this->getListProject($type, $i, $sequence_id, $log, $product_type, $path_folder);
                                if (!empty($list_return["data"])) {
                                    $list_return["data"]["current_page"] = $i;
                                    Helpers::writeLog($list_return["data"], $path_folder."/".$type."/", "bds_log_{$type}.json");
                                    print_r("\n\n{$type}: Page " . $i . " done!\n");
                                }
                                sleep(3);
                                ob_flush();
                            }

                            if($current_page != $current_page_add){
                                break;
                            }

                        } else {
                            $log['current_page'] = 0;
                            Helpers::writeLog($log, $path_folder."/".$type."/", "bds_log_{$type}.json");
                            print_r("\nLast file of {$type} done.");
                        }
                    } else {
                        echo "\nCannot find listing. End page!" . BatdongsanV2::DOMAIN."/".$type;
                    }
                } else {
                    echo "\nCannot access in get pages of " . BatdongsanV2::DOMAIN."/".$type;
                }

                if(!in_array($type, $bds_log["type"])) {
                    array_push($bds_log["type"], $type);
                }
                $bds_log["last_type_index"] = $key_type;
                Helpers::writeLog($bds_log, $path_folder, "bds_rent_log.json");
                print_r("\nTYPE: {$type} DONE!\n");
            }
        }
    }

    public function getListProject($type, $current_page, $sequence_id, $log, $product_type, $path_folder)
    {
        $href = "/".$type."/p".$current_page;

        $page = Helpers::getUrlContent(BatdongsanV2::DOMAIN . $href);
        if(!empty($page)) {
            $html = SimpleHTMLDom::str_get_html($page, true, true, DEFAULT_TARGET_CHARSET, false);
            $list = $html->find('div.p-title a');
            if (count($list) > 0) {
                // about 20 listing
                foreach ($list as $item) {
                    $productId = 0;
                    if (preg_match('/pr(\d+)/', BatdongsanV2::DOMAIN . $item->href, $matches)) {
                        if(!empty($matches[1])){
                            $productId = $matches[1];
                        }
                    }
                    // Check exists do chan IP
//                    if(!empty($productId)) {
//                        $res = $this->getProjectDetail($type, $item->href, $product_type, $path_folder);
//                        if (!empty($res)) {
//                            $log["files"][$sequence_id] = $res;
//                            $log["last_id"] = $sequence_id;
//                            $sequence_id = $sequence_id + 1;
//                            $checkExists = in_array($productId, $log["files"]);
//                            if($checkExists)
//                                print_r("\n{$type}: ".$productId." override");
//                            else
//                                array_push($log["files"], $productId);
//                        }
//                    }
                    // check exists Ko ghi de len file lay ve
                    $checkExists = false;
                    if(!empty($productId) && !empty($log["files"])) {
                        $checkExists = in_array($productId, $log["files"]);
                    }

                    if ($checkExists == false) {
                        $res = $this->getProjectDetail($type, $item->href, $product_type, $path_folder);
                        if (!empty($res)) {
                            $log["files"][$sequence_id] = $res;
                            $log["last_id"] = $sequence_id;
                            $sequence_id = $sequence_id + 1;
                        }
                    } else {
                        print_r($productId . " exists\n");
                    }
                }
                return ['data' => $log];
            } else {
                echo "\nCannot find listing. End page!".BatdongsanV2::DOMAIN;
                $this->writeFileLogFail($type, "\nCannot find listing: $href"."\n");
            }

        } else {
            echo "\nCannot access in get List Project of ".BatdongsanV2::DOMAIN;
            $this->writeFileLogFail($type, "\nCannot access: $href"."\n");
        }
        return null;
    }

    public function getProjectDetail($type, $href, $product_type, $path_folder)
    {
        $folder = $product_type == 1 ? "files" : "rent_files";
        $page = Helpers::getUrlContent(BatdongsanV2::DOMAIN . $href);
        $matches = array();
        if (preg_match('/pr(\d+)/', BatdongsanV2::DOMAIN . $href, $matches)) {
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
            $res = Helpers::writeFileJson($path . $product_id, $page);
            if ($res) {
                $this->writeFileLogUrlSuccess($type, BatdongsanV2::DOMAIN . $href . "\n", $path_folder);
                return $product_id;
            } else {
                return null;
            }
        }
        else {
            echo "\nError go to detail at " .BatdongsanV2::DOMAIN.$href;
            $this->writeFileLogFail($type, "\nCannot find detail: ".BatdongsanV2::DOMAIN.$href."\n");
            return null;
        }
    }

    public function setZeroCurrentPage($types, $path_folder){
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

    public function writeToFile($filePath, $data, $mode = 'a')
    {
        $handle = fopen($filePath, $mode) or die('Cannot open file:  ' . $filePath);
        $int = fwrite($handle, $data);
        fclose($handle);
        return $int;
    }

    public function writeFileLogFail($type, $log){
        $file_name = Yii::getAlias('@console') . "/data/bds_html/{$type}/bds_log_fail";
        if(!file_exists($file_name)){
            fopen($file_name, "w");
        }
        if( strpos(file_get_contents($file_name),$log) === false) {
            $this->writeToFile($file_name, $log, 'a');
        }
    }

    public function writeFileLogUrlSuccess($type, $log, $path_folder){
        $file_name = $path_folder."{$type}/bds_log_urls";
        if(!file_exists($file_name)){
            fopen($file_name, "w");
        }
        if( strpos(file_get_contents($file_name),$log) === false) {
            $this->writeToFile($file_name, $log, 'a');
        }
    }

}