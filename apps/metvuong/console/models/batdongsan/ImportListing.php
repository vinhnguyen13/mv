<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 7/12/2016 8:52 AM
 */

namespace console\models\batdongsan;

use common\components\Slug;
use console\models\BatdongsanV2;
use console\models\Helpers;
use DOMDocument;
use DOMXPath;
use keltstr\simplehtmldom\SimpleHTMLDom;
use vsoft\ad\models\AdCity;
use vsoft\ad\models\AdDistrict;
use vsoft\ad\models\AdStreet;
use vsoft\ad\models\AdWard;
use vsoft\craw\models\AdBuildingProject;
use vsoft\craw\models\AdContactInfo;
use vsoft\craw\models\AdImages;
use vsoft\craw\models\AdProduct;
use vsoft\craw\models\AdProductAdditionInfo;
use vsoft\craw\models\AdProductFile;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

class ImportListing extends Component
{
    public static function find()
    {
        return Yii::createObject(ImportListing::className());
    }

    public function getWardId2($wardName, $district_id)
    {
        if (!empty($district_id) && !empty($wardName)) {
            $sql = "SELECT `id` FROM ad_ward WHERE (`name` LIKE '" . $wardName . "' OR '" . $wardName . "' LIKE CONCAT(pre, ' ', name)) AND `district_id` = " . $district_id . " LIMIT 1";
            $ward = AdWard::getDb()->createCommand($sql)->queryScalar();
            if ($ward) {
                return $ward;
            }
        }
        return null;
    }

    public function getStreetId2($streetName, $district_id)
    {
        if (!empty($district_id) && !empty($streetName)) {
            $sql = "SELECT `id` FROM ad_street WHERE (`name` LIKE '" . $streetName . "' OR '" . $streetName . "' LIKE CONCAT(pre, ' ', name)) AND `district_id` = " . $district_id . " LIMIT 1";
            $street = AdStreet::getDb()->createCommand($sql)->queryScalar();
            if ($street) {
                return $street;
            }
        }
        return null;
    }

    public function parseDetail($filename, $page = null)
    {
        $json = array();
        if(!empty($filename))
            $page = file_get_contents($filename);

        if (empty($page))
            return null;

        $detail = SimpleHTMLDom::str_get_html($page, true, true, DEFAULT_TARGET_CHARSET, false);
        if (!empty($detail)) {
            $project = $detail->find('#divProjectOptions .current', 0);
            $project = empty($project) ? null : trim($project->innertext);

            $type = $detail->find('#divCatagoryOptions .current', 0)->plaintext;
            if(!empty($type)) {
                $type = Slug::me()->slugify($type);
            }

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
                } else
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
            if (!empty($contact)) {
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
                    if (!empty($arr_contact["Email"])) {
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
            if (empty($left_detail)) {
                $dom = new DOMDocument();
                @$dom->loadHTMLFile($filename);
                if ($dom->hasChildNodes()) {
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
                    if (!empty($arr_contact["Email"])) {
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
            } else {
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
                                if (!empty($left))
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

                // truong hop ko co city hoac district

                if (empty($city) || empty($district)) {
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

                if (Helpers::beginWith($loai_tin, "Bán căn hộ chung cư") || strpos($loai_tin, "căn hộ chung cư")) {
                    $loai_tai_san = 6;
                } else if (Helpers::beginWith($loai_tin, "Bán nhà riêng") || strpos($loai_tin, "nhà riêng")) {
                    $loai_tai_san = 7;
                } else if (Helpers::beginWith($loai_tin, "Bán nhà biệt thự, liền kề")) {
                    $loai_tai_san = 8;
                } else if (Helpers::beginWith($loai_tin, "Bán nhà mặt phố") || strpos($loai_tin, "nhà mặt phố")) {
                    $loai_tai_san = 9;
                } else if (Helpers::beginWith($loai_tin, "Bán đất nền dự án")) {
                    $loai_tai_san = 10;
                } else if (Helpers::beginWith($loai_tin, "Bán đất")) {
                    $loai_tai_san = 11;
                } else if (Helpers::beginWith($loai_tin, "Bán trang trại, khu nghỉ dưỡng")) {
                    $loai_tai_san = 12;
                } else if (Helpers::beginWith($loai_tin, "Bán kho, nhà xưởng")) {
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
                'project' => $project,
                'type' => $type
            ];
        }
        return $json;
    }

    public function importDataForTool($limit = 300)
    {
        $start_time = time();
        $insertCount = 0;
        $count_project = 0;
//        $path_folder = Yii::getAlias('@console') . "/data/bds_html/";
        $ad_image_columns = ['user_id', 'product_id', 'file_name', 'uploaded_at'];
        $product_files = AdProductFile::find()->where(['is_import' => 0])->orderBy(['created_at' => SORT_DESC])->limit($limit)->all();
        if (count($product_files) > 0) {
            foreach ($product_files as $key_file => $product_file) {
                $bulkImage = array();
                $filename = $product_file->file;
                print_r("\n" . ($key_file + 1) . " {$filename} ");
                $page = Helpers::getUrlContent($product_file->vendor_link);
                if (empty($page)) {
                    $product_file->is_import = -1;
                    $product_file->save(false);
                    print_r(" - Cannot crawl page");
                    continue;
                } else {
                    $connection = AdProduct::getDb();
                    $transaction = $connection->beginTransaction();
                    try {
                        $value = $this->parseDetail(null, $page);
                        if (empty($value)) {
                            print_r(" Error: no content\n");
                            continue;
                        }

                        $product_type = strpos($product_file->path, 'nha-dat-ban') ? 1 : 2;
                        $project_id = null;
                        $city_id = null;
                        $district_id = null;

                        $ad_city = Helpers::getCityId($value[$filename]["city"]);
                        if (count($ad_city) > 0) {
                            $city_id = (int)$ad_city['id'];
                            $district = Helpers::getDistrictId($value[$filename]["district"], $city_id);
                            if (count($district) > 0) {
                                $district_id = (int)$district['id'];
                            }
                        }

                        $ward_id = $this->getWardId2($value[$filename]["ward"], $district_id);
                        $street_id = $this->getStreetId2($value[$filename]["street"], $district_id);
                        $home_no = $value[$filename]["home_no"];

                        $lat = $value[$filename]["lat"];
                        $lng = $value[$filename]["lng"];

                        $project_name = !empty($value[$filename]["project"]) ? $value[$filename]["project"] : null;
                        // neu co du an thi lay dia chi cua du an gan cho tin dang
                        if (!empty($project_name)) {
                            $project = AdBuildingProject::find()->where('name = :n', [':n' => $project_name])->andWhere(['city_id' => $city_id, 'district_id' => $district_id,])->one();
                            if (count($project) > 0) {
                                $project_id = $project->id;
                                $city_id = $project->city_id;
                                $district_id = $project->district_id;
                                $ward_id = $project->ward_id;
                                $street_id = $project->street_id;
                                $home_no = $project->home_no;
                                $lat = $project->lat;
                                $lng = $project->lng;
                                $count_project++;
                                print_r(" - " . $project_name);
                            }
                        }

                        $area = $value[$filename]["dientich"];
                        $price = $value[$filename]["price"];
                        $content = null;
                        $desc = $value[$filename]["description"];
                        if (!empty($desc)) {
                            $content = strip_tags($desc, '<br>');
                            $pos = strpos($content, 'Tìm kiếm theo từ khóa');
                            if ($pos) {
                                $content = substr($content, 0, $pos);
                                $content = str_replace('Tìm kiếm theo từ khóa', '', $content);
                            }
                            $content = str_replace('<br/>', PHP_EOL, $content);
                            $content = trim($content);
                        } else {
                            $content = 'Tin đang cập nhật.';
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
                            'lat' => $lat,
                            'lng' => $lng,
                            'start_date' => $value[$filename]["start_date"],
                            'end_date' => $value[$filename]["end_date"],
                            'verified' => 1,
                            'created_at' => $value[$filename]["start_date"],
                            'updated_at' => $value[$filename]["start_date"],
                            'source' => 1,
                            'file_name' => $filename
                        ];

                        $crawl_product = new AdProduct($record);
                        if ($crawl_product->save(false)) {
                            $product_id = $crawl_product->id;

                            $imageArray = $value[$filename]["thumbs"];
                            if (count($imageArray) > 0) {
                                foreach ($imageArray as $imageValue) {
                                    if (!empty($imageValue)) {
                                        $imageRecord = [
                                            'user_id' => null,
                                            'product_id' => $product_id,
                                            'file_name' => $imageValue,
                                            'uploaded_at' => time()
                                        ];
                                        $bulkImage[] = $imageRecord;
                                    }
                                }
                                $totalImage = count($bulkImage);
                                if ($totalImage > 0) {
                                    $resImg = AdImages::getDb()->createCommand()
                                        ->batchInsert(AdImages::tableName(), $ad_image_columns, $bulkImage)
                                        ->execute();
                                    if($resImg > 0)
                                        print_r(" - Total Image: {$totalImage}");
                                }
                            }

                            $infoArray = $value[$filename]["info"];
                            if (count($infoArray) > 0) {
                                $facade_width = empty($infoArray["Mặt tiền"]) == false ? trim($infoArray["Mặt tiền"]) : null;
                                $land_width = empty($infoArray["Đường vào"]) == false ? trim($infoArray["Đường vào"]) : null;
                                $home_direction = empty($infoArray["direction"]) == false ? trim($infoArray["direction"]) : null;
                                $facade_direction = null;
                                $floor_no = empty($infoArray["Số tầng"]) == false ? trim(str_replace('(tầng)', '', $infoArray["Số tầng"])) : 0;
                                $room_no = empty($infoArray["Số phòng ngủ"]) == false ? trim(str_replace('(phòng)', '', $infoArray["Số phòng ngủ"])) : 0;
                                $toilet_no = empty($infoArray["Số toilet"]) == false ? trim($infoArray["Số toilet"]) : 0;
                                $interior = empty($infoArray["Nội thất"]) == false ? trim($infoArray["Nội thất"]) : null;
                                $infoRecord = [
                                    'product_id' => $product_id,
                                    'facade_width' => $facade_width,
                                    'land_width' => $land_width,
                                    'home_direction' => $home_direction,
                                    'facade_direction' => $facade_direction,
                                    'floor_no' => $floor_no,
                                    'room_no' => $room_no,
                                    'toilet_no' => $toilet_no,
                                    'interior' => $interior
                                ];
                                $adInfo = new AdProductAdditionInfo($infoRecord);
                                if($adInfo->save(false)){
                                    print_r(" - Addition");
                                }
                            }

                            $contactArray = $value[$filename]["contact"];
                            if (count($contactArray) > 0) {
                                $name = isset($contactArray["Tên liên lạc"]) && !empty($contactArray["Tên liên lạc"]) ? trim($contactArray["Tên liên lạc"]) : null;
                                $phone = isset($contactArray["Điện thoại"]) && !empty($contactArray["Điện thoại"]) ? trim($contactArray["Điện thoại"]) : null;
                                $mobile = isset($contactArray["Mobile"]) && !empty($contactArray["Mobile"]) ? trim($contactArray["Mobile"]) : null;
                                $address = isset($contactArray["Địa chỉ"]) && !empty($contactArray["Địa chỉ"]) ? trim($contactArray["Địa chỉ"]) : null;
                                $email = isset($contactArray["Email"]) && !empty($contactArray["Email"]) ? trim($contactArray["Email"]) : null;
                                $contactRecord = [
                                    'product_id' => $product_id,
                                    'name' => $name,
                                    'phone' => $phone,
                                    'mobile' => $mobile == null ? $phone : $mobile,
                                    'address' => $address,
                                    'email' => $email
                                ];
                                $adContact = new AdContactInfo($contactRecord);
                                if($adContact->save(false)){
                                    print_r(" - Contact");
                                }
                            }
                        }

                        $transaction->commit();
                        $product_file->is_import = 1;
                        $product_file->imported_at = time();
                        $product_file->product_tool_id = $product_id;
                        $product_file->save(false);
                        $insertCount++;

                    }
                    catch (Exception $e1) {
                        $transaction->rollBack();
                        $product_file->is_import = -1;
                        $product_file->save(false);
                        print_r("\n\t" . $e1->getMessage() . PHP_EOL);
                    }

                } // else import product

            } // end for loop product file

            print_r("\n\n------------------------------");
            print_r("\nFiles have been imported!\n");
            $end_time = time();
            print_r("\n" . "Time: ");
            print_r($end_time - $start_time);
            print_r("s - Total Record: " . $insertCount);
            if ($count_project > 0)
                print_r(" - Total Project Listing: " . $count_project);
        }

    }

    // update lai product tool ko co city, district
    public function updateProductTool($limit=1000)
    {
        $query = AdProduct::find()->where("city_id is null and district_id is null and source > 0");
        $count_product = (int)$query->count('id');
        if($count_product > 0)
        {
            $products = $query->orderBy(['file_name' => SORT_ASC])->limit($limit)->all();
            $path_folder = Yii::getAlias('@console') . "/data/bds_html/";
            $no = 1;
            foreach($products as $product) {
                $filename = $product->file_name;
                print_r("\n {$filename}");
                $product_file = AdProductFile::find()->where(['file' => $filename])->one();
                if (count($product_file) > 0) {
                    $filepath = $path_folder . $product_file->path . "/" . $filename;
                    if (file_exists($filepath)) {
                        $value = $this->parseDetail($filepath);
                        if (empty($value)) {
                            print_r(" - Error: no content.");
                            continue;
                        }
                        $product_type = strpos($product_file->path, 'nha-dat-ban') ? 1 : 2;
                        $res = $this->updateProduct($value[$filename], $product, $product_type);
                        if($res) {
                            $no++;
                            print_r(" - updated.");
                        } else{
                            print_r(" - error.");
                        }
                    } else {
                        print_r(" - not file exists");
                        $page = Helpers::getUrlContent($product_file->vendor_link);
                        if(empty($page)){
                            $product->source = -1; // batdongsan.com.vn link sai
                            $product->save(false);
                            print_r(" - Cannot crawl page");
                            continue;
                        }
                        $value = $this->parseDetail(null, $page);
                        if (empty($value)) {
                            print_r(" - Error: no content.");
                            continue;
                        }
                        $filepath = $path_folder. $product_file->path. "/";
                        if (!is_dir($filepath)) {
                            mkdir($filepath, 0777, true);
                            echo "\nDirectory {$filepath} was created";
                        }
                        Helpers::writeFileJson($filepath. $filename, $page);

                        $product_type = strpos($product_file->path, 'nha-dat-ban') ? 1 : 2;
                        $res = $this->updateProduct($value[$filename], $product, $product_type);
                        if($res) {
                            $no++;
                            print_r(" - updated.");
                        } else{
                            print_r(" - error.");
                        }
                    }
                }
                else {
                    print_r(" - new file");
                    $url = Listing::DOMAIN."/update-product-tool-pr".$filename;
                    $page = Helpers::getUrlContent($url);
                    if(empty($page)){
                        $product->source = -1; // batdongsan.com.vn link sai
                        $product->save(false);
                        print_r(" - Cannot crawl page");
                        continue;
                    }
                    $value = $this->parseDetail(null, $page);
                    if (empty($value)) {
                        print_r(" - Error: no content.");
                        continue;
                    }
                    $city = $value[$filename]['city'];
                    $district = $value[$filename]['district'];
                    if(empty($city) || empty($district)) {
                        print_r(" - no city or no district.");
                        continue;
                    }

                    $type = $value[$filename]['type'];
                    if(empty($type))
                    {
                        print_r(" - no type.");
                        continue;
                    }
                    if($type == 'nha-dat-ban') {
                        $product_type = 1;
                        $sales_rents = 'sales';
                        $folder = 'files';
                    } else {
                        $product_type = 2;
                        $sales_rents = 'rents';
                        $folder = 'rent_files';
                    }

                    $city_slug = Slug::me()->slugify($city);
                    $district_slug = Slug::me()->slugify($district);
                    $type_slug = $type. "-". $district_slug;
                    $ad_product_file_path = $city_slug . "/" . $sales_rents . "/" . $type_slug . "/" . $folder;

                    // Crawl new file
                    $filepath = $path_folder. $ad_product_file_path. "/";
                    if (!is_dir($filepath)) {
                        mkdir($filepath, 0777, true);
                        echo "\nDirectory {$filepath} was created";
                    }
                    Helpers::writeFileJson($filepath. $filename, $page);

                    $ad_product_file = new AdProductFile();
                    $ad_product_file->file = $filename;
                    $ad_product_file->path = $ad_product_file_path;
                    $ad_product_file->created_at = time();
                    $ad_product_file->vendor_link = $url;
                    $ad_product_file->is_import = 1;
                    $ad_product_file->imported_at = $product->created_at;
                    $ad_product_file->product_tool_id = $product->id;
                    $ad_product_file->save(false);

                    $res2 = $this->updateProduct($value[$filename], $product, $product_type);
                    if($res2) {
                        $no++;
                        print_r(" - updated.");
                    } else{
                        print_r(" - error updated.");
                    }
//                    sleep(3);
                }

            } // end product loop
        } else {
            print_r("Not found product");
        }
    }

    public function updateProduct($value, $product, $product_type, $updateProduct=true, $updateInfo=false, $updateContact=false, $updateImage=false)
    {
        $res = null;
        if($updateProduct) {
            $project_id = null;
            $city_id = null;
            $district_id = null;

            $ad_city = Helpers::getCityId($value["city"]);
            if (count($ad_city) > 0) {
                $city_id = (int)$ad_city['id'];
                $district = Helpers::getDistrictId($value["district"], $city_id);
                if (count($district) > 0) {
                    $district_id = (int)$district['id'];
                } else {
                    return false;
                }

            } else {
                return false;
            }

            $ward_id = $this->getWardId2($value["ward"], $district_id);
            $street_id = $this->getStreetId2($value["street"], $district_id);
            $home_no = $value["home_no"];

            $lat = $value["lat"];
            $lng = $value["lng"];

            $project_name = !empty($value["project"]) ? $value["project"] : null;
            // neu co du an thi lay dia chi cua du an gan cho tin dang
            if (!empty($project_name)) {
                $project = AdBuildingProject::find()->where('name = :n', [':n' => $project_name])->one();
                if (count($project) > 0) {
                    $project_id = $project->id;
                    $city_id = $project->city_id;
                    $district_id = $project->district_id;
                    $ward_id = $project->ward_id;
                    $street_id = $project->street_id;
                    $home_no = $project->home_no;
                    $lat = $project->lat;
                    $lng = $project->lng;
                    print_r(" - " . $project_name);
                }
            }

            $area = $value["dientich"];
            $price = $value["price"];
            $content = null;
            $desc = $value["description"];
            if (!empty($desc)) {
                $content = strip_tags($desc, '<br>');
                $pos = strpos($content, 'Tìm kiếm theo từ khóa');
                if ($pos) {
                    $content = substr($content, 0, $pos);
                    $content = str_replace('Tìm kiếm theo từ khóa', '', $content);
                }
                $content = str_replace('<br/>', PHP_EOL, $content);
                $content = trim($content);
            } else {
                $content = 'Tin đang cập nhật';
            }

            if (empty($product->city_id))
                $product->city_id = $city_id;

            if (empty($product->district_id))
                $product->district_id = $district_id;

            if (empty($product->ward_id))
                $product->ward_id = $ward_id;

            if (empty($product->street_id))
                $product->street_id = $street_id;

            $product->project_building_id = $project_id;
            $product->type = $product_type;
            $product->home_no = $home_no;
            $product->lat = $lat;
            $product->lng = $lng;
            $product->product_main_id = 0;
            $product->area = $area;
            $product->price = $price;
            $product->content = $content;
            $resProduct = $product->save(false);
            $res = $resProduct > 0 ? $res. " - update product success" : false;
        }

        if($updateContact){
            $contactArray = $value['contact'];
            $name = isset($contactArray["Tên liên lạc"]) && !empty($contactArray["Tên liên lạc"]) ? trim($contactArray["Tên liên lạc"]) : null;
            $phone = isset($contactArray["Điện thoại"]) && !empty($contactArray["Điện thoại"]) ? trim($contactArray["Điện thoại"]) : null;
            $mobile = isset($contactArray["Mobile"]) && !empty($contactArray["Mobile"]) ? trim($contactArray["Mobile"]) : null;
            $address = isset($contactArray["Địa chỉ"]) && !empty($contactArray["Địa chỉ"]) ? trim($contactArray["Địa chỉ"]) : null;
            $email = isset($contactArray["Email"]) && !empty($contactArray["Email"]) ? trim($contactArray["Email"]) : null;

            $contactInfo = AdContactInfo::find()->where(['product_id' => $product->id])->one();
            if(count($contactInfo) > 0) {
                $contactInfo->product_id = $product->id;
                $contactInfo->name = $name;
                $contactInfo->phone = $phone;
                $contactInfo->mobile = $mobile == null ? $phone : $mobile;
                $contactInfo->address = $address;
                $contactInfo->email = $email;
                $resContact = $contactInfo->save(false);
                $res = $resContact > 0 ? $res. " - contact success" : false;
            } else {
                $contactRecord = [
                    'product_id' => $product->id,
                    'name' => $name,
                    'phone' => $phone,
                    'mobile' => $mobile == null ? $phone : $mobile,
                    'address' => $address,
                    'email' => $email
                ];
                $contactInfo = new AdContactInfo($contactRecord);
                $resContact = $contactInfo->save(false);
                $res = $resContact > 0 ? $res. " - new contact success" : false;
            }
        }

        if($updateImage){
            $adImageCount = AdImages::find()->where(['product_id' => $product->id])->count();
            if(count($adImageCount) <= 0) {
                $imageArray = $value["thumbs"];
                if (count($imageArray) > 0) {
                    $bulkImage = array();
                    foreach ($imageArray as $imageValue) {
                        if (!empty($imageValue)) {
                            $imageRecord = [
                                'user_id' => null,
                                'product_id' => $product->id,
                                'file_name' => $imageValue,
                                'uploaded_at' => time()
                            ];
                            $bulkImage[] = $imageRecord;
                        }

                    }
                    if (count($bulkImage) > 0) {
                        $ad_image_columns = ['user_id', 'product_id', 'file_name', 'uploaded_at'];
                        $resImage = AdImages::getDb()->createCommand()
                            ->batchInsert(\vsoft\craw\models\AdImages::tableName(), $ad_image_columns, $bulkImage)
                            ->execute();
                        $res = $resImage > 0 ? $res . " - images success" : false;
                    }
                } else {
                    $res = $res . " - no image";
                }
            }
        }

        if($updateInfo){
            $infoArray = $value["info"];
            $facade_width = empty($infoArray["Mặt tiền"]) == false ? trim($infoArray["Mặt tiền"]) : null;
            $land_width = empty($infoArray["Đường vào"]) == false ? trim($infoArray["Đường vào"]) : null;
            $home_direction = empty($infoArray["direction"]) == false ? trim($infoArray["direction"]) : null;
            $facade_direction = null;
            $floor_no = empty($infoArray["Số tầng"]) == false ? trim(str_replace('(tầng)', '', $infoArray["Số tầng"])) : 0;
            $room_no = empty($infoArray["Số phòng ngủ"]) == false ? trim(str_replace('(phòng)', '', $infoArray["Số phòng ngủ"])) : 0;
            $toilet_no = empty($infoArray["Số toilet"]) == false ? trim($infoArray["Số toilet"]) : 0;
            $interior = empty($infoArray["Nội thất"]) == false ? trim($infoArray["Nội thất"]) : null;

            $additionalInfo = AdProductAdditionInfo::find()->where(['product_id' => $product->id])->one();
            if(count($additionalInfo) > 0){
                $additionalInfo->product_id = $product->id;
                $additionalInfo->facade_width = $facade_width;
                $additionalInfo->land_width = $land_width;
                $additionalInfo->home_direction = $home_direction;
                $additionalInfo->facade_direction = $facade_direction;
                $additionalInfo->floor_no = $floor_no;
                $additionalInfo->room_no = $room_no;
                $additionalInfo->toilet_no = $toilet_no;
                $additionalInfo->interior = $interior;
                $resInfo = $additionalInfo->save(false);
                $res = $resInfo > 0 ? $res. " - info success" : false;
            } else {
                $infoRecord = [
                    'product_id' => $product->id,
                    'facade_width' => $facade_width,
                    'land_width' => $land_width,
                    'home_direction' => $home_direction,
                    'facade_direction' => $facade_direction,
                    'floor_no' => $floor_no,
                    'room_no' => $room_no,
                    'toilet_no' => $toilet_no,
                    'interior' => $interior
                ];
                $additionalInfo = new AdProductAdditionInfo($infoRecord);
                $resInfo = $additionalInfo->save(false);
                $res = $resInfo > 0 ? $res. " - new info success" : false;
            }
        }

        return $res;
    }

    // update product tool that no addition_info and contact_info
    public function updateProductInfo($limit=1000)
    {
        $db_tool_schema = Helpers::getDbTool();
        $path = Yii::getAlias('@console'). "/data/bds_html/map_product/";
        if(!is_dir($path))
        {
            mkdir($path, 0777, true);
        }
        $file_last_id_name = "update_product_info_last_id.json";
        $log = Helpers::loadLog($path, $file_last_id_name);
        $last_id = 0;
        if(!empty($log)){
            $last_id = (int)$log['last_id'];
        }
        $sql = "(id not in (select product_id from {$db_tool_schema}.ad_product_addition_info) or id not in (select product_id from {$db_tool_schema}.ad_contact_info))";
        if($last_id > 0){
            $sql = $sql. " and id < {$last_id}";
        }

        $query = AdProduct::find()->where($sql);
        $count_product = (int)$query->count('id');
        if($count_product > 0) {
            $products = $query->orderBy(['id' => SORT_DESC])->limit($limit)->all();
            $path_folder = Yii::getAlias('@console') . "/data/bds_html/";

            foreach ($products as $key_product => $product) {
                $filename = $product->file_name;
                $no = $key_product + 1;
                print_r("\n{$no} - {$product->id}");
                $product_file = AdProductFile::find()->where(['file' => $filename])->one();
                if (count($product_file) > 0) {
                    $filepath = $path_folder . $product_file->path . "/" . $filename;
                    if (file_exists($filepath)) {
                        $value = $this->parseDetail($filepath);
                        if (empty($value)) {
                            print_r(" - Error: no content.");
                            continue;
                        }

                        $product_type = strpos($product_file->path, 'nha-dat-ban') ? 1 : 2;
                        $res = $this->updateProduct($value[$filename], $product, $product_type, false, true, true, true);
                        if($res) {
                            print_r("{$res}.");
                        } else{
                            print_r(" - error.");
                        }
                    }
                    else {
                        print_r(" - not file exists");
                        $page = Helpers::getUrlContent($product_file->vendor_link);
                        if(empty($page)){
                            $product->source = -1; // batdongsan.com.vn link sai
                            $product->save(false);
                            print_r(" - Cannot crawl page");
                            continue;
                        }
                        $value = $this->parseDetail(null, $page);
                        if (empty($value)) {
//                            $product->delete();
//                            $product_file->delete();
                            print_r(" - Error: no content.");
                            continue;
                        }

                        $filepath = $path_folder. $product_file->path. "/";
                        if (!is_dir($filepath)) {
                            mkdir($filepath, 0777, true);
                            echo "\nDirectory {$filepath} was created";
                        }

                        // Helpers::writeFileJson($filepath. $filename, $page);

                        $product_type = strpos($product_file->path, 'nha-dat-ban') ? 1 : 2;
                        $res = $this->updateProduct($value[$filename], $product, $product_type, false, true, true, true);
                        if($res) {
                            print_r("{$res}");
                        } else{
                            print_r(" - error.");
                        }

                    }
                }
                else {
                    print_r(" - new file");
                    $url = Listing::DOMAIN."/update-product-tool-no-info-pr".$filename;
                    $page = Helpers::getUrlContent($url);
                    if(empty($page)){
                        $product->source = -1; // batdongsan.com.vn link sai
                        $product->save(false);
                        print_r(" - Cannot crawl page");
                        continue;
                    }
                    $value = $this->parseDetail(null, $page);
                    if (empty($value)) {
                        print_r(" - Error: no content.");
                        continue;
                    }
                    $city = $value[$filename]['city'];
                    $district = $value[$filename]['district'];
                    if(empty($city) || empty($district)) {
                        print_r(" - no city or no district.");
                        continue;
                    }

                    $type = $value[$filename]['type'];
                    if(empty($type))
                    {
                        print_r(" - no type.");
                        continue;
                    }
                    if($type == 'nha-dat-ban') {
                        $product_type = 1;
                        $sales_rents = 'sales';
                        $folder = 'files';
                    } else {
                        $product_type = 2;
                        $sales_rents = 'rents';
                        $folder = 'rent_files';
                    }

                    $city_slug = Slug::me()->slugify($city);
                    $district_slug = Slug::me()->slugify($district);
                    $type_slug = $type. "-". $district_slug;
                    $ad_product_file_path = $city_slug . "/" . $sales_rents . "/" . $type_slug . "/" . $folder;

                    // Crawl new file
                    $filepath = $path_folder. $ad_product_file_path. "/";
                    if (!is_dir($filepath)) {
                        mkdir($filepath, 0777, true);
                        echo "\nDirectory {$filepath} was created";
                    }
                    Helpers::writeFileJson($filepath. $filename, $page);

                    $ad_product_file = new AdProductFile();
                    $ad_product_file->file = $filename;
                    $ad_product_file->path = $ad_product_file_path;
                    $ad_product_file->created_at = time();
                    $ad_product_file->vendor_link = $url;
                    $ad_product_file->is_import = 1;
                    $ad_product_file->imported_at = $product->created_at;
                    $ad_product_file->product_tool_id = $product->id;
                    $ad_product_file->save(false);

                    $res2 = $this->updateProduct($value[$filename], $product, $product_type, false, true, true, true);
                    if($res2) {
                        print_r("{$res2}");
                    } else{
                        print_r(" - error updated.");
                    }
                }

                if($no >= count($products) || $no % 100 == 0){
                    $log['last_id'] = $product->id;
                    $log['last_time'] = date('d M Y H:i', time());
                    Helpers::writeLog($log, $path, $file_last_id_name);
                }

            } // end foreach
        }
    }

}