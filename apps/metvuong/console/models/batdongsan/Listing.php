<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 7/11/2016 4:48 PM
 */

namespace console\models\batdongsan;

use console\models\Helpers;
use keltstr\simplehtmldom\SimpleHTMLDom;
use vsoft\craw\models\AdProduct;
use vsoft\craw\models\AdProductFile;
use Yii;
use yii\base\Component;
use yii\base\Exception;

class Listing extends Component
{
    const DOMAIN = 'batdongsan.com.vn';
//    const SG = 1;
//    const HN = 2;
//    const BD = 3;
//    const DDN = 4;
//    const HP = 5;
//    const LA = 6;
//    const VT = 7;
//    const CT = 18;

    public $sale_types =[
        'ho-chi-minh' => ['nha-dat-ban-quan-1','nha-dat-ban-quan-2','nha-dat-ban-quan-3','nha-dat-ban-quan-4','nha-dat-ban-quan-5','nha-dat-ban-quan-6',
            'nha-dat-ban-quan-7','nha-dat-ban-quan-8', 'nha-dat-ban-quan-9','nha-dat-ban-quan-10','nha-dat-ban-quan-11','nha-dat-ban-quan-12',
            'nha-dat-ban-binh-chanh','nha-dat-ban-binh-tan','nha-dat-ban-binh-thanh','nha-dat-ban-can-gio','nha-dat-ban-cu-chi','nha-dat-ban-go-vap',
            'nha-dat-ban-hoc-mon','nha-dat-ban-nha-be','nha-dat-ban-tan-binh','nha-dat-ban-tan-phu','nha-dat-ban-phu-nhuan','nha-dat-ban-thu-duc'],

        'ha-noi' => ['nha-dat-ban-ba-dinh', 'nha-dat-ban-ba-vi', 'nha-dat-ban-bac-tu-liem', 'nha-dat-ban-cau-giay', 'nha-dat-ban-chuong-my', 'nha-dat-ban-dan-phuong',
            'nha-dat-ban-dong-anh', 'nha-dat-ban-dong-da', 'nha-dat-ban-gia-lam', 'nha-dat-ban-ha-dong', 'nha-dat-ban-hai-ba-trung', 'nha-dat-ban-hoai-duc',
            'nha-dat-ban-hoan-kiem', 'nha-dat-ban-hoang-mai', 'nha-dat-ban-long-bien', 'nha-dat-ban-me-linh', 'nha-dat-ban-my-duc', 'nha-dat-ban-nam-tu-liem',
            'nha-dat-ban-phu-xuyen', 'nha-dat-ban-phuc-tho', 'nha-dat-ban-quoc-oai', 'nha-dat-ban-soc-son', 'nha-dat-ban-son-tay', 'nha-dat-ban-tay-ho',
            'nha-dat-ban-thach-that', 'nha-dat-ban-thanh-oai', 'nha-dat-ban-thanh-tri', 'nha-dat-ban-thanh-xuan', 'nha-dat-ban-thuong-tin', 'nha-dat-ban-ung-hoa'],

        'binh-duong' => ['nha-dat-ban-bau-bang-bd', 'nha-dat-ban-ben-cat-bd', 'nha-dat-ban-dau-tieng-bd', 'nha-dat-ban-di-an-bd',
            'nha-dat-ban-phu-giao-bd', 'nha-dat-ban-tan-uyen-bd', 'nha-dat-ban-thu-dau-mot-bd', 'nha-dat-ban-thuan-an-bd'],

        'da-nang' => ['nha-dat-ban-cam-le-ddn', 'nha-dat-ban-hai-chau-ddn', 'nha-dat-ban-hoa-vang-ddn',
            'nha-dat-ban-lien-chieu-ddn', 'nha-dat-ban-ngu-hanh-son-ddn', 'nha-dat-ban-son-tra-ddn', 'nha-dat-ban-thanh-khe-ddn'],

        'hai-phong' => ['nha-dat-ban-an-duong-hp', 'nha-dat-ban-bach-long-vi-hp', 'nha-dat-ban-an-lao-hp', 'nha-dat-ban-cat-hai-hp', 'nha-dat-ban-do-son-hp',
            'nha-dat-ban-duong-kinh-hp', 'nha-dat-ban-hai-an-hp', 'nha-dat-ban-hong-bang-hp', 'nha-dat-ban-kien-an-hp', 'nha-dat-ban-kien-thuy-hp',
            'nha-dat-ban-le-chan-hp', 'nha-dat-ban-ngo-quyen-hp', 'nha-dat-ban-thuy-nguyen-hp', 'nha-dat-ban-tien-lang-hp', 'nha-dat-ban-vinh-bao-hp'],

        'long-an' => ['nha-dat-ban-ben-luc-la', 'nha-dat-ban-can-duoc-la', 'nha-dat-ban-can-giuoc-la', 'nha-dat-ban-chau-thanh-la', 'nha-dat-ban-duc-hoa-la',
            'nha-dat-ban-duc-hue-la', 'nha-dat-ban-kien-tuong-la', 'nha-dat-ban-moc-hoa-la', 'nha-dat-ban-tan-an-la', 'nha-dat-ban-tan-hung-la',
            'nha-dat-ban-tan-thanh-la', 'nha-dat-ban-tan-tru-la', 'nha-dat-ban-thanh-hoa-la', 'nha-dat-ban-thu-thua-la', 'nha-dat-ban-vinh-hung-la'],

        'ba-ria-vung-tau' => ['nha-dat-ban-ba-ria-vt', 'nha-dat-ban-chau-duc-vt', 'nha-dat-ban-con-dao-vt', 'nha-dat-ban-dat-do-vt', 'nha-dat-ban-long-dien-vt',
            'nha-dat-ban-tan-thanh-vt', 'nha-dat-ban-vung-tau-vt', 'nha-dat-ban-xuyen-moc-vt'],

        'can-tho' => ['nha-dat-ban-binh-thuy-ct', 'nha-dat-ban-cai-rang-ct', 'nha-dat-ban-co-do-ct', 'nha-dat-ban-ninh-kieu-ct', 'nha-dat-ban-o-mon-ct',
            'nha-dat-ban-phong-dien-ct', 'nha-dat-ban-thoi-lai-ct', 'nha-dat-ban-thot-not-ct', 'nha-dat-ban-vinh-thanh-ct']

    ];

    public $rent_types = [
        'ho-chi-minh' => ['nha-dat-cho-thue-quan-1','nha-dat-cho-thue-quan-2','nha-dat-cho-thue-quan-3','nha-dat-cho-thue-quan-4','nha-dat-cho-thue-quan-5','nha-dat-cho-thue-quan-6',
            'nha-dat-cho-thue-quan-7','nha-dat-cho-thue-quan-8', 'nha-dat-cho-thue-quan-9','nha-dat-cho-thue-quan-10','nha-dat-cho-thue-quan-11','nha-dat-cho-thue-quan-12',
            'nha-dat-cho-thue-binh-chanh','nha-dat-cho-thue-binh-tan','nha-dat-cho-thue-binh-thanh','nha-dat-cho-thue-can-gio','nha-dat-cho-thue-cu-chi','nha-dat-cho-thue-go-vap',
            'nha-dat-cho-thue-hoc-mon','nha-dat-cho-thue-nha-be','nha-dat-cho-thue-tan-binh','nha-dat-cho-thue-tan-phu','nha-dat-cho-thue-phu-nhuan','nha-dat-cho-thue-thu-duc'],

        'ha-noi' => ['nha-dat-cho-thue-ba-dinh', 'nha-dat-cho-thue-ba-vi', 'nha-dat-cho-thue-bac-tu-liem', 'nha-dat-cho-thue-cau-giay', 'nha-dat-cho-thue-chuong-my',
            'nha-dat-cho-thue-dan-phuong', 'nha-dat-cho-thue-dong-anh', 'nha-dat-cho-thue-dong-da', 'nha-dat-cho-thue-gia-lam', 'nha-dat-cho-thue-ha-dong',
            'nha-dat-cho-thue-hai-ba-trung', 'nha-dat-cho-thue-hoai-duc', 'nha-dat-cho-thue-hoan-kiem', 'nha-dat-cho-thue-hoang-mai', 'nha-dat-cho-thue-long-bien',
            'nha-dat-cho-thue-me-linh', 'nha-dat-cho-thue-my-duc', 'nha-dat-cho-thue-nam-tu-liem', 'nha-dat-ban-phu-xuyen', 'nha-dat-cho-thue-phuc-tho',
            'nha-dat-cho-thue-quoc-oai', 'nha-dat-cho-thue-soc-son', 'nha-dat-cho-thue-son-tay', 'nha-dat-cho-thue-tay-ho', 'nha-dat-cho-thue-thach-that',
            'nha-dat-cho-thue-thanh-oai', 'nha-dat-cho-thue-thanh-tri', 'nha-dat-cho-thue-thanh-xuan', 'nha-dat-cho-thue-thuong-tin', 'nha-dat-cho-thue-ung-hoa'],

        'binh-duong' => ['nha-dat-cho-thue-bau-bang-bd', 'nha-dat-cho-thue-ben-cat-bd', 'nha-dat-cho-thue-dau-tieng-bd', 'nha-dat-cho-thue-di-an-bd',
            'nha-dat-cho-thue-phu-giao-bd', 'nha-dat-cho-thue-tan-uyen-bd', 'nha-dat-cho-thue-thu-dau-mot-bd', 'nha-dat-cho-thue-thuan-an-bd'],

        'da-nang' => ['nha-dat-cho-thue-cam-le-ddn', 'nha-dat-cho-thue-hai-chau-ddn', 'nha-dat-cho-thue-hoa-vang-ddn',
            'nha-dat-cho-thue-lien-chieu-ddn', 'nha-dat-cho-thue-ngu-hanh-son-ddn', 'nha-dat-cho-thue-son-tra-ddn', 'nha-dat-cho-thue-thanh-khe-ddn'],

        'hai-phong' => ['nha-dat-cho-thue-an-duong-hp', 'nha-dat-cho-thue-an-lao-hp', 'nha-dat-cho-thue-bach-long-vi-hp', 'nha-dat-cho-thue-cat-hai-hp', 'nha-dat-cho-thue-do-son-hp',
            'nha-dat-cho-thue-duong-kinh-hp', 'nha-dat-cho-thue-hai-an-hp', 'nha-dat-cho-thue-hong-bang-hp', 'nha-dat-cho-thue-kien-an-hp', 'nha-dat-cho-thue-kien-thuy-hp',
            'nha-dat-cho-thue-le-chan-hp', 'nha-dat-cho-thue-ngo-quyen-hp', 'nha-dat-cho-thue-thuy-nguyen-hp', 'nha-dat-cho-thue-tien-lang-hp', 'nha-dat-cho-thue-vinh-bao-hp'],

        'long-an' => ['nha-dat-cho-thue-ben-luc-la', 'nha-dat-cho-thue-can-duoc-la', 'nha-dat-cho-thue-can-giuoc-la', 'nha-dat-cho-thue-chau-thanh-la', 'nha-dat-cho-thue-duc-hoa-la',
            'nha-dat-cho-thue-duc-hue-la', 'nha-dat-cho-thue-kien-tuong-la', 'nha-dat-cho-thue-moc-hoa-la', 'nha-dat-cho-thue-tan-an-la', 'nha-dat-cho-thue-tan-hung-la',
            'nha-dat-cho-thue-tan-thanh-la', 'nha-dat-cho-thue-tan-tru-la', 'nha-dat-cho-thue-thanh-hoa-la', 'nha-dat-cho-thue-thu-thua-la', 'nha-dat-cho-thue-vinh-hung-la'],

        'ba-ria-vung-tau' => ['nha-dat-cho-thue-ba-ria-vt', 'nha-dat-cho-thue-chau-duc-vt', 'nha-dat-cho-thue-con-dao-vt', 'nha-dat-cho-thue-dat-do-vt', 'nha-dat-cho-thue-long-dien-vt',
            'nha-dat-cho-thue-tan-thanh-vt', 'nha-dat-cho-thue-vung-tau-vt', 'nha-dat-cho-thue-xuyen-moc-vt'],

        'can-tho' => ['nha-dat-cho-thue-binh-thuy-ct', 'nha-dat-cho-thue-cai-rang-ct', 'nha-dat-cho-thue-co-do-ct', 'nha-dat-cho-thue-ninh-kieu-ct', 'nha-dat-cho-thue-o-mon-ct',
            'nha-dat-cho-thue-phong-dien-ct', 'nha-dat-cho-thue-thoi-lai-ct', 'nha-dat-cho-thue-thot-not-ct', 'nha-dat-cho-thue-vinh-thanh-ct']
    ];

    public static function find()
    {
        return Yii::createObject(Listing::className());
    }

    public function parse($city=null)
    {
        if($city) {
            try {
                $time_start = time();
                $this->getPages(1, $city);
                $time_end = time();
                print_r("\nTime: ");
                print_r($time_end - $time_start);

            } catch (Exception $e) {
                $currentBuffers = ob_get_clean();
                ob_end_clean(); // Let's end and clear ob...
                echo "<br />Some error occured: " . $e->getMessage();
            }
        }
    }

    public function parseRent($city=null)
    {
        if($city) {
            try {
                $time_start = time();
                $this->getPages(2, $city);
                $time_end = time();
                print_r("\nTime: ");
                print_r($time_end - $time_start);

            } catch (Exception $e) {
                $currentBuffers = ob_get_clean();
                ob_end_clean(); // Let's end and clear ob...
                echo "<br />Some error occured: " . $e->getMessage();
            }
        }
    }

    // $product_type == 1 nha-dat-ban; $product_type == 2 nha-dat-cho-thue
    public function getPages($product_type, $city=null)
    {
        $types = null;
        $path_folder = null;
        $count_type = 0;
        $bds_log_name = "bds_log.json";
        if($product_type == 1) {
            $types = Listing::find()->sale_types[$city];
            $count_type = count($types);
            if($count_type <= 0) {
                print_r("{$city} no value in sale_types");
                return;
            }
            $path_folder = Yii::getAlias('@console') . "/data/bds_html/{$city}/sales/";
            $bds_log = Helpers::loadLog($path_folder, $bds_log_name);
        }

        if ($product_type == 2) {
            $types = Listing::find()->rent_types[$city];
            $count_type = count($types);
            if($count_type <= 0) {
                print_r("{$city} no value in rent_types");
                return;
            }
            $bds_log_name = "bds_rent_log.json";
            $path_folder = Yii::getAlias('@console') . "/data/bds_html/{$city}/rents/";
            $bds_log = Helpers::loadLog($path_folder, $bds_log_name);
        }

        $write_log = false;
        $last_type = empty($bds_log["last_type_index"]) ? 0 : ($bds_log["last_type_index"] + 1);
        if($last_type >= ($count_type-1)) {
            unset($bds_log["last_type_index"]);
            $last_type = 0;
            Helpers::writeLog($bds_log, $path_folder, $bds_log_name);
        } // last change

        for($t=$last_type; $t < $count_type; $t++) {
            $type = $types[$t];
            $url = self::DOMAIN . '/' . $type;
            $page = Helpers::getUrlContent($url);
            if (!empty($page)) {
                $html = SimpleHTMLDom::str_get_html($page, true, true, DEFAULT_TARGET_CHARSET, false);
                $pagination = $html->find('.container-default .background-pager-right-controls a');
                $count_page = count($pagination);
                $last_page = (int)str_replace("/" . $type . "/p", "", $pagination[$count_page - 1]->href);
                if ($count_page > 0) {
                    $log = Helpers::loadLog($path_folder . $type . "/", "bds_log_{$type}.json");
                    $current_page = empty($log["current_page"]) ? 1 : ($log["current_page"] + 1);
                    $current_page_add = $current_page + 4; // +4 => total pages to run that are 5.
                    if ($current_page_add > $last_page)
                        $current_page_add = $last_page;

                    if ($current_page <= $last_page) {
                        for ($i = $current_page; $i <= $current_page_add; $i++) {
                            $this->getListProject($type, $i, $product_type, $path_folder, $city);
                            $log["current_page"] = $i;
                            Helpers::writeLog($log, $path_folder . $type . "/", "bds_log_{$type}.json");
                            print_r("\n\n{$type}: Page " . $i . " done!\n");
                        }

                        if($last_page == $current_page_add) {
                            $write_log = true;
                            $log["current_page"] = 0;
                            Helpers::writeLog($log, $path_folder . $type . "/", "bds_log_{$type}.json");
                        }
                        else
                            break;

                    } else {
                        $write_log = true;
                        $log['current_page'] = 0;
                        Helpers::writeLog($log, $path_folder . $type . "/", "bds_log_{$type}.json");
                        print_r("\nLast file of {$type} finish.");
                    }
                } else {
                    echo "\nCannot find listing. End page!" . Listing::DOMAIN . "/" . $type;
                }
            } else {
                echo "\nCannot access in get pages of " . Listing::DOMAIN . "/" . $type;
            }

            if($write_log) {
                $bds_log["last_type_index"] = $t;
                Helpers::writeLog($bds_log, $path_folder, $bds_log_name);
                print_r("\nTYPE: {$type} DONE!\n");
            }
        }  // end foreach types
    }

    public function getListProject($type, $current_page, $product_type, $path_folder, $city)
    {
        $href = "/".$type."/p".$current_page;
        $page = Helpers::getUrlContent(self::DOMAIN . $href);
        if(!empty($page)) {
            $html = SimpleHTMLDom::str_get_html($page, true, true, DEFAULT_TARGET_CHARSET, false);
            $list = $html->find('div.p-title a');
            if (count($list) > 0) {
                $item_no = 0;
                // about 20 listing
                foreach ($list as $item) {
                    $productId = '';
                    if (preg_match('/pr(\d+)/', self::DOMAIN . $item->href, $matches)) {
                        if(!empty($matches[1])){
                            $productId = $matches[1];
                        }
                    }
                    $checkExists = false;
                    if(!empty($productId)) {
                        $checkExists = AdProductFile::checkFileExists($productId); // in_array($productId, $log["files"]);
                    }
                    if ($checkExists == false) {
                        $return_detail = $this->getProductDetail($type, $item->href, $product_type, $path_folder, $productId, $city);
                        if(!empty($return_detail))
                            $item_no++;
                    } else {
                        print_r(PHP_EOL.$productId . " exists");
                    }

                } // end foreach list item
                return $item_no;
            } else {
                echo "\nCannot find listing. End page! ".Listing::DOMAIN;
            }

        } else {
            echo "\nCannot access in get List Project of ".Listing::DOMAIN;
        }
        return null;
    }

    public function getProductDetail($type, $href, $product_type, $path_folder, $productId, $city)
    {
        $url = self::DOMAIN.$href;
        $folder = $product_type == 1 ? "files" : "rent_files";
        $sales_rents = $product_type == 1 ? "sales" : "rents";
        $path = $path_folder . $type. "/" . $folder . "/";
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
            echo "\nDirectory {$path} was created";
        }

        $ad_product_file_path = $city . "/" . $sales_rents . "/" . $type . "/" . $folder;
        $ad_crawl_product = AdProduct::find()->where(['file_name' => $productId])->one();
        if(count($ad_crawl_product) > 0){
            $ad_product_file = new AdProductFile();
            $ad_product_file->file = $ad_crawl_product->file_name;
            $ad_product_file->path = $ad_product_file_path;
            $ad_product_file->created_at = time();
            $ad_product_file->is_import = 1;
            $ad_product_file->imported_at = $ad_crawl_product->created_at;
            $ad_product_file->product_tool_id = $ad_crawl_product->id;
            if($ad_crawl_product->product_main_id > 0) {
                $ad_product_file->is_copy = 1;
                $ad_product_file->copied_at = $ad_crawl_product->updated_at;
                $ad_product_file->product_main_id = $ad_crawl_product->product_main_id;
            }
            $ad_product_file->vendor_link = $url;
            $ad_product_file->save(false);
            return null;
        } else {
            $page = Helpers::getUrlContent($url);
            sleep(3);
            ob_flush();
            if (!empty($page) && !empty($productId)) {
                $res = Helpers::writeFileJson($path . $productId, $page);
                if ($res) {
                    // log product file
                    $ad_product_file = new AdProductFile();
                    $ad_product_file->file = $productId;
                    $ad_product_file->path = $ad_product_file_path;
                    $ad_product_file->created_at = time();
                    $ad_product_file->vendor_link = $url;
                    $ad_product_file->save(false);
                    return $productId;
                } else {
                    return null;
                }
            } else {
                $this->writeFileLogFail("\nFailed at ".Listing::DOMAIN.$href."\n", $path_folder . $type. "/" );
                echo "\nError go to detail at " . Listing::DOMAIN . $href;
                return null;
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

    public function writeFileLogFail($log, $path_folder){
        $file_name = $path_folder."bds_log_fail";
        if(!file_exists($file_name)){
            fopen($file_name, "w");
        }
        if( strpos(file_get_contents($file_name),$log) === false) {
            $this->writeToFile($file_name, $log, 'a');
        }
    }

    public function writeFileLogUrlSuccess($log, $path_folder){
        $file_name = $path_folder."/bds_log_urls";
        if(!file_exists($file_name)){
            fopen($file_name, "w");
        }
        if( strpos(file_get_contents($file_name),$log) === false) {
            $this->writeToFile($file_name, $log, 'a');
        }
    }
}