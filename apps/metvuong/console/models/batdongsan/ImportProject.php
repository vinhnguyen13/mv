<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 7/12/2016 10:51 AM
 */

namespace console\models\batdongsan;


use Collator;
use common\components\Slug;
use console\models\Helpers;
use keltstr\simplehtmldom\SimpleHTMLDom;
use vsoft\craw\models\AdBuildingProject;
use vsoft\craw\models\AdInvestor;
use vsoft\craw\models\AdInvestorBuildingProject;
use vsoft\craw\models\AdStreet;
use vsoft\craw\models\AdWard;
use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

class ImportProject extends Component
{
    public static function find()
    {
        return Yii::createObject(ImportProject::className());
    }

    /*
     *  De chay 1 du an can copy file du an do ra (vd file 979) thu muc files
     *  - Xoa import_project_log.json
     *  - Xoa 979 trong mang files cua khu-dan-cu.json
     *
     *  File in error folder while file none city and none district
     */

    public function importProjects($limit = 300){
        $start = time();
        $path = Yii::getAlias('@console') . "/data/bds_html/projects/";
        $file_log = "import_project_log.json";
        $project_log_import = Helpers::loadLog($path."import/", $file_log);
        if(!isset($project_log_import["type"]))
            $project_log_import["type"] = array();
        $types = Project::find()->projects;
        $count_type = count($types) - 1;
        $break_type = false; // detect next type if it is false
        $current_type = empty($project_log_import["current_type"]) ? 0 : $project_log_import["current_type"]+1;

        if($project_log_import["current_type"] == $count_type){
            $project_log_import["type"] = array();
            $project_log_import["current_type"] = null;
            Helpers::writeLog($project_log_import, $path."import/", $file_log);
            $current_type = 0;
        }

        $wards = AdWard::getDb()->cache(function (){
            return AdWard::getDb()->createCommand("SELECT id, name, district_id FROM `ad_ward`")->queryAll();
        });
        $streets = AdStreet::getDb()->cache(function () {
            return AdStreet::getDb()->createCommand("SELECT id, name, district_id FROM `ad_street`")->queryAll();
        });

        $count_file_insert = 0;
        $count_file_update = 0;
        $no = 1;

        for($t=$current_type; $t <= $count_type; $t++){
            $type = $types[$t];
            if($t >= $current_type && !$break_type){
                if (is_dir($path.$type)) {
                    $log_import = Helpers::loadLog($path."import/", $type.".json");
                    if (!isset($log_import["files"]))
                        $log_import["files"] = array();

                    $files = scandir($path.$type."/files", 1);
                    $counter = count($files) - 2;
                    $last_file_index = $counter - 1;
                    if ($counter > 0) {
                        $filename = null;
                        for ($i = 0; $i <= $last_file_index; $i++) {
                            if ($no > $limit) {
                                $break_type = true;
                                break;
                            }

                            $filename = $files[$i];
                            if (in_array($filename, $log_import["files"])) {
                                continue;
                            }
                            else {
                                $filePath = $path . $type. "/files/" . $filename;
                                if (file_exists($filePath)) {
                                    $value = $this->parseProjectDetail($path . $type. "/files/" , $filename);
                                    if (count($value) <= 0 || empty($value)) {
                                        print_r("\n Error: no content");
                                        continue;
                                    }

                                    $street_id = null;
                                    $ward_id = null;
                                    $home_no = null;
                                    $city = $value[$filename]["city"];
                                    $district = $value[$filename]["district"];
                                    if(empty($city) || empty($district)){
                                        $toFolder = $path."import/error/".$type."/";
                                        if(!is_dir($toFolder)){
                                            mkdir($toFolder, 0777, true);
                                            print_r("\nMake Project Error Folder");
                                        }
                                        $re = rename($filePath, $toFolder.$filename);
                                        if($re) continue;
                                    }

                                    $city_result = Helpers::getCityId($city);
                                    if($city_result == 500){
                                        $toFolder = $path."import/error/".$type."/";
                                        if(!is_dir($toFolder)){
                                            mkdir($toFolder, 0777, true);
                                            print_r("\nMake Error Folder City {$filename}");
                                        }
                                        rename($filePath, $toFolder.$filename);
                                    }
                                    $city_id = isset($city_result['id']) ? (int)$city_result['id'] : 0;
                                    $district_id = 0;
                                    if($city_id > 0){
                                        $district_result = Helpers::getDistrictId($district, $city_id);
                                        if($district_result == 500){
                                            $toFolder = $path."import/error/".$type."/";
                                            if(!is_dir($toFolder)){
                                                mkdir($toFolder, 0777, true);
                                                print_r("\nMake Error Folder District {$filename}");
                                            }
                                            rename($filePath, $toFolder.$filename);
                                        }
                                        $district_id = isset($district_result['id']) ? (int)$district_result['id'] : 0;
                                    }

                                    $location = $value[$filename]["location"];
                                    $project_name = !empty($value[$filename]["name"]) ? $value[$filename]["name"] : null;
                                    $slug = $value[$filename]["slug"];
                                    $lat = $value[$filename]["lat"];
                                    $lng = $value[$filename]["lng"];

                                    $project = $this->getProject($city_id, $district_id, $project_name, $lat, $lng);
                                    if (count($project) > 0) { // neu co ton tai trong db crawl
                                        if (!empty($project->district_id)) {
                                            $ws = $this->getWardAndStreet($location, $streets, $wards, $project->district_id);
                                            if (count($ws) > 0) {
                                                $project->street_id = (int)(isset($ws["street_id"]) && !empty($ws["street_id"])) ? $ws["street_id"] : null;
                                                $project->ward_id = (int)(isset($ws["ward_id"]) && !empty($ws["ward_id"])) ? $ws["ward_id"] : null;
                                                $project->home_no = (int)(isset($ws["home_no"]) && !empty($ws["home_no"])) ? $ws["home_no"] : null;
                                            }
                                        }
                                        $project->file_name = $type . "/" . $filename;
                                        $project->is_crawl = 1;
                                        $project->data_html = $value[$filename]["data_html"];

                                        $project->update(false);
                                        if (!in_array($filename, $log_import["files"])) {
                                            array_push($log_import["files"], $filename);
                                        }
                                        $log_import["import_total"] = count($log_import["files"]);
                                        $log_import["import_time"] = date("d-m-Y H:i");
                                        $count_file_update++;
                                        print_r("\n". $no . " {$type} - ".$project_name." - {$filename}: ". "updated");
                                    }
                                    // add new project
                                    else {
                                        $ws = $this->getWardAndStreet($location, $streets, $wards, $district_id);
                                        if (count($ws) > 0) {
                                            $street_id = (isset($ws["street_id"]) && !empty($ws["street_id"])) ? $ws["street_id"] : null;
                                            $ward_id = (isset($ws["ward_id"]) && !empty($ws["ward_id"])) ? (int)$ws["ward_id"] : null;
                                            $home_no = (isset($ws["home_no"]) && !empty($ws["home_no"])) ? (int)$ws["home_no"] : null;
                                        }

                                        $record = [
                                            'city_id' => $city_id,
                                            'district_id' => $district_id,
                                            'name' => $project_name,
                                            'logo' => $value[$filename]["logo"],
                                            'location' => $location,
                                            'description' => $value[$filename]["description"],
                                            'investment_type' => $value[$filename]["investment_type"],
                                            'hotline' => $value[$filename]["hotline"],
                                            'website' => $value[$filename]["website"],
                                            'lng' => $lng,
                                            'lat' => $lat,
                                            'slug' => $slug,
                                            'status' => $value[$filename]["status"],
                                            'created_at' => $value[$filename]["created_at"],
                                            'file_name' => $type . "/" . $filename,
                                            'is_crawl' => 1,
                                            'data_html' => $value[$filename]["data_html"],
                                            'home_no' => $home_no,
                                            'street_id' => (int)$street_id,
                                            'ward_id' => (int)$ward_id
                                        ];
                                        $crawl_project = new AdBuildingProject($record);
                                        $crawl_project->save(false);
                                        $project_id = $crawl_project->id;

                                        $investor = $value[$filename]["investor"];
                                        $investor_name = $investor["name"];
                                        $investor_id = 0;
                                        if(count($investor) > 0 && !empty($investor_name)) {
                                            // Check duplicate Investor :D
                                            $crawl_investor = AdInvestor::find()->where([
                                                'name' => $investor["name"],
                                                'address' => $investor["address"],
                                                'phone' => $investor["phone"],
                                                'fax' => $investor["fax"],
                                                'website' => $investor["website"],
                                                'email' => $investor["email"],
                                            ])->one();
                                            if(count($crawl_investor) > 0) {
                                                $investor_id = $crawl_investor->id;
                                            } else {
                                                $recordInvestor = [
                                                    'name' => $investor["name"],
                                                    'address' => $investor["address"],
                                                    'phone' => $investor["phone"],
                                                    'fax' => $investor["fax"],
                                                    'website' => $investor["website"],
                                                    'email' => $investor["email"],
                                                    'logo' => $investor["logo"],
                                                    'status' => 1,
                                                    'created_at' => time()
                                                ];
                                                $crawl_investor = new AdInvestor($recordInvestor);
                                                $crawl_investor->save(false);
                                                $investor_id = $crawl_investor->id;
                                            }
                                        }

                                        if($project_id > 0 && $investor_id > 0){
                                            $investorProject = new AdInvestorBuildingProject();
                                            $investorProject->investor_id = $investor_id;
                                            $investorProject->building_project_id = $project_id;
                                            $investorProject->save(false);
                                        }

                                        $count_file_insert++;
                                        print_r("\n" . $no . " {$type} - ".$project_name." - {$filename}");
                                    }

                                    if (!in_array($filename, $log_import["files"])) {
                                        array_push($log_import["files"], $filename);
                                    }
                                    $log_import["import_total"] = count($log_import["files"]);
                                    $log_import["import_time"] = date("d-m-Y H:i");
                                }
                            }

                            if($no >0 && ($no % 50) == 0) {
                                print_r(PHP_EOL);
                                print_r("Parse data {$no} records...");
                                print_r(PHP_EOL);
                            }
                            $no++;
                        } // end file loop

                        Helpers::writeLog($log_import, $path . "import/", $type . ".json");
                    }
                } else {
                    print_r("\nCannot find ".$path.$type."\n");
                }
            }

            if($break_type)
                break;

            if (!in_array($type, $project_log_import["type"])) {
                array_push($project_log_import["type"], $type);
                $project_log_import["current_type"] = $t;
                Helpers::writeLog($project_log_import, $path."import/", $file_log);
                print_r("\nADD TYPE: {$type} DONE!\n");
            }
        }

        $end_time = time();
        if($count_file_update > 0)
            print_r("\nUpdated project: {$count_file_update} ");
        if($count_file_insert > 0)
            print_r("\nNew project: {$count_file_insert}");
        print_r("\n"."Time: ");
        print_r($end_time-$start);
        print_r("s");
    }

    /**
     * @param $path_folder
     * @param $filename
     * @return array|null
     */
    public function parseProjectDetail($path_folder, $filename, $page=null){
        $json = array();

        if(!empty($path_folder) && !empty($filename))
            $page = file_get_contents($path_folder . $filename);

        if(empty($page))
            return null;

        $detail = SimpleHTMLDom::str_get_html($page, true, true, DEFAULT_TARGET_CHARSET, false);
//        $detail = SimpleHTMLDom::file_get_html($path_folder.$filename);
        if (!empty($detail)) {
            $detail_innertext = $detail->innertext;

            $name = trim($detail->find('h1', 0)->innertext);
            $name = html_entity_decode(trim($name), ENT_HTML5, 'utf-8');
            if(empty($name)){
                $str_name = substr($detail_innertext, strpos($detail_innertext, '<h1>'));
                $str_name = trim(str_replace('<h1>', '', $str_name));
                $str_name = substr($str_name, 0, strpos($str_name, '</h1>'));
                $name = trim($str_name);
            }

            $city = $detail->find('#divCityOptions .current', 0)->innertext;
            $city = html_entity_decode(trim($city), ENT_HTML5, 'utf-8');
            $district = $detail->find('#divDistrictOptions .current', 0)->innertext;
            $district = html_entity_decode(trim($district), ENT_HTML5, 'utf-8');

            $lat = $detail->find('#hdLat', 0)->value;
            $long = $detail->find('#hdLong', 0)->value;
            $address = $detail->find('#hdAddress', 0)->value;
            $address = html_entity_decode(trim($address), ENT_HTML5, 'utf-8');
            $inv_type = $detail->find('#divCatagoryOptions .current', 0)->innertext;


            $investor_name = trim($detail->find('#enterpriseInfo h3', 0)->plaintext);
            $investor = array();
            if(!empty($investor_name)) {
                $investor["name"] = html_entity_decode(trim($investor_name), ENT_HTML5, 'utf-8');
                $investor_desc = $detail->find('.info .d11 img', 0)->src;
                $investor["logo"] = strpos($investor_desc, "no-photo") == true ? null : $investor_desc;
            }
            // parse investor vi batdongsan thay doi code moi
            $pre_fix_inv = substr($detail_innertext, 0, strpos($detail_innertext, 'id="enterpriseInfo"'));
            $str_inv_detail = str_replace($pre_fix_inv . 'id="enterpriseInfo"', '', $detail_innertext);
            $str_inv_detail = substr($str_inv_detail, 0, strpos($str_inv_detail, 'id="otherProject"'));
            if(!empty($str_inv_detail))
            {
                if(empty($investor["name"])){
                    $str_name = substr($str_inv_detail, strpos($str_inv_detail, '<span>'));
                    $str_name = str_replace('<span>', '', $str_name);
                    $inv_name = trim(substr($str_name, 0, strpos($str_name, '</span>')));
                    $investor["name"] = html_entity_decode(trim($inv_name), ENT_HTML5, 'utf-8');
                }
                if(empty($investor["logo"])){
                    $str_logo = substr($str_inv_detail, strpos($str_inv_detail, 'LeftMainContent__projectDetail_EnterpriseInfo1_imgLogo" title="'));
                    $str_logo = trim(str_replace('LeftMainContent__projectDetail_EnterpriseInfo1_imgLogo" title="', '', $str_logo));
                    $str_logo = substr($str_logo, strpos($str_logo, 'src="'));
                    $str_logo = trim(str_replace('src="', '', $str_logo));
                    $str_logo = substr($str_logo, 0, strpos($str_logo, '"'));
                    $investor['logo'] = trim($str_logo);
                }

                // investor address
                $str_inv_address = substr($str_inv_detail, strpos($str_inv_detail, 'LeftMainContent__projectDetail_EnterpriseInfo1_lblAddress">Địa chỉ</span>:'));
                $str_inv_address = trim(str_replace('LeftMainContent__projectDetail_EnterpriseInfo1_lblAddress">Địa chỉ</span>:', '', $str_inv_address));
                $inv_address = substr($str_inv_address, 0, strpos($str_inv_address, '</li>'));
                if(empty($inv_address))
                    $inv_address = substr($str_inv_address, 0, strpos($str_inv_address, '</div>'));
                $investor["address"] = html_entity_decode(trim($inv_address), ENT_HTML5, 'utf-8');

                // investor phone
                $str_inv_phone = substr($str_inv_detail, strpos($str_inv_detail, 'LeftMainContent__projectDetail_EnterpriseInfo1_lblPhone">Điện thoại</span>:'));
                $str_inv_phone = trim(str_replace('LeftMainContent__projectDetail_EnterpriseInfo1_lblPhone">Điện thoại</span>:', '', $str_inv_phone));
                $str_inv_phone_li = substr($str_inv_phone, 0, strpos($str_inv_phone, '</li>'));
                $inv_phone = substr($str_inv_phone_li, 0, strpos($str_inv_phone_li, '|'));
                if(empty($str_inv_phone_li)) {
                    $inv_phone = substr($str_inv_phone, 0, strpos($str_inv_phone, '|'));
                }
                $investor["phone"] = trim($inv_phone);

                // investor fax
                $str_inv_fax = substr($str_inv_phone, strpos($str_inv_phone, 'LeftMainContent__projectDetail_EnterpriseInfo1_lblFax">Fax</span>:'));
                $str_inv_fax = trim(str_replace('LeftMainContent__projectDetail_EnterpriseInfo1_lblFax">Fax</span>:', '', $str_inv_fax));
                $inv_fax = substr($str_inv_fax, 0, strpos($str_inv_fax, '</li>'));
                if(empty($inv_fax))
                    $inv_fax = substr($str_inv_fax, 0, strpos($str_inv_fax, '</div>'));
                $investor['fax'] = trim($inv_fax);

                // investor website
                $str_inv_web = substr($str_inv_detail, strpos($str_inv_detail, 'LeftMainContent__projectDetail_EnterpriseInfo1_hplWebpage" title="'));
                $str_inv_web = trim(str_replace('LeftMainContent__projectDetail_EnterpriseInfo1_hplWebpage" title="', '', $str_inv_web));
                //">
                $str_inv_web = substr($str_inv_web, strpos($str_inv_web, '">'));
                $str_inv_web = trim(str_replace('">', '', $str_inv_web));
                //</span>
                $inv_web = substr($str_inv_web, 0, strpos($str_inv_web, '</span>'));
                $investor['website'] = trim($inv_web);

                // investor email
                $email = substr($str_inv_detail, strpos($str_inv_detail, "var attr = '"));
                $email = str_replace("var attr = '", "", $email);
                $email = substr($email, 0, strpos($email, "var txt ="));
                $email = str_replace("';", "", $email);
                $email = trim(html_entity_decode($email));
                $investor["email"] = $email;

            }

            $pre_fix = substr($detail_innertext, 0, strpos($detail_innertext, 'hdLat'));
            $str_detail = str_replace($pre_fix . "hdLat", "", $detail_innertext);
            $str_detail = substr($str_detail, 0, strpos($str_detail, "RightMainContent__projectSearchbox_btnSearch"));

            // parse Lat
            if(empty($lat)) {
                $str_lat = substr($str_detail, strpos($str_detail, '" id="hdLat" value="'));
                $str_lat = str_replace($str_lat . '" id="hdLat" value="', '', $str_detail);
                $lat = substr($str_lat, 0, strpos($str_lat, '" />'));
                $lat = str_replace('" id="hdLat" value="', '', $lat);
            }

            // parse Lng
            if(empty($long)) {
                $str_lng = substr($str_detail, strpos($str_detail, '" id="hdLong" value="'));
                $str_lng = str_replace($str_lng . '" id="hdLong" value="', '', $str_lng);
                $long = substr($str_lng, 0, strpos($str_lng, '" />'));
                $long = str_replace('" id="hdLong" value="', '', $long);
            }

            // parse Address
            if(empty($address)) {
                $str_address = substr($str_detail, strpos($str_detail, '" id="hdAddress" value="'));
                $str_address = str_replace($str_address . '" id="hdAddress" value="', '', $str_address);
                $address = substr($str_address, 0, strpos($str_address, '" />'));
                $address = str_replace('" id="hdAddress" value="', '', $address);
            }

            // parse Investment Type
            if(empty($inv_type)) {
                $str_inv_type = substr($str_detail, strpos($str_detail, 'id="divCatagoryOptions"'));
                $str_inv_type = substr($str_inv_type, strpos($str_inv_type, 'advance-options current">'));
                $str_inv_type = substr($str_inv_type, 0, strpos($str_inv_type, '</li>'));
                $inv_type = str_replace('advance-options current">', '', $str_inv_type);
                $inv_type = html_entity_decode(trim($inv_type), ENT_HTML5, 'utf-8');
            }

            // parse City
            if(empty($city)) {
                $str_city = substr($str_detail, strpos($str_detail, 'id="divCityOptions"'));
                $str_city = substr($str_city, strpos($str_city, 'advance-options current">'));
                $str_city = substr($str_city, 0, strpos($str_city, '</li>'));
                $city = str_replace('advance-options current">', '', $str_city);
                $city = html_entity_decode(trim($city), ENT_HTML5, 'utf-8');
            }

            // parse District
            if(empty($district)) {
                $str_district = substr($str_detail, strpos($str_detail, 'id="divDistrictOptions"'));
                $str_district = substr($str_district, strpos($str_district, 'advance-options current">'));
                $str_district = substr($str_district, 0, strpos($str_district, '</li>'));
                $district = str_replace('advance-options current">', '', $str_district);
                $district = html_entity_decode(trim($district), ENT_HTML5, 'utf-8');
            }

            $hotline = $detail->find('.prjinfo li', 2)->innertext;
            if(!empty($hotline)) {
                $hotline = trim(strip_tags($hotline));
                $hotline = substr($hotline, strpos($hotline, "Số điện thoại: "), strpos($hotline, "|"));
                $hotline = trim(str_replace("Số điện thoại:", "", $hotline));
            } else {
                $hotline = $detail->find('.prjinfo div', 1)->innertext;
                $hotline = trim(strip_tags($hotline));
                $hotline = substr($hotline, strpos($hotline, "Số điện thoại: "), strpos($hotline, "|"));
                $hotline = trim(str_replace("Số điện thoại:", "", $hotline));
            }

            $website = $detail->find('.prjinfo li', 3)->innertext;
            if(!empty($website)) {
                $website = trim(strip_tags($website));
                $website = trim(str_replace("Website:", "", $website));
            } else {
                $website = $detail->find('.prjinfo div', 2)->innertext;
                $website = trim(strip_tags($website));
                $website = trim(str_replace("Website:", "", $website));
            }

            $slug = $detail->find('link[rel=canonical]', 0)->href;
            $slug = trim($slug);
            $slug = substr($slug, 1, strpos($slug, "-pj")-1);

            $logoTop = $detail->find('.prjava img', 0)->src;
            $general = $detail->find('#detail .a1', 0);
            $imgGeneral = $general->find('img', 0);
            if(empty($imgGeneral))
                $logo = $logoTop;
            else
                $logo = $imgGeneral->src;

            $description = $general->innertext;
            $description = trim(strip_tags($description));
            $description = trim(StringHelper::truncate($description, 1000));

            $data_html = array();
            $editors = $detail->find('#detail .editor');
            if(count($editors) > 0){
                foreach($editors as $editor){
                    $a1 = $editor->find('.a1', 0);
                    if(!empty($a1)) {
                        $tabId = (int)$editor->find('input', 0)->value;
                        $tabContent = trim($a1->innertext);
                        $tabContent = str_replace("<br />", PHP_EOL, $tabContent);
                        $tabKey = Project::find()->tabProject[$tabId];
                        $data_html[$tabKey] = empty($tabContent) ? $tabContent :trim($tabContent);
                    }
                }
            }

            $json[$filename] = [
                'city' => $city,
                'district' => $district,
                'name' => $name,
                'logo' => $logo,
                'location' => $address,
                'description' => $description,
                'investment_type' => $inv_type,
                'hotline' => $hotline == "Đang cập nhật" ? null : $hotline,
                'website' => $website == "Đang cập nhật" ? null : $website,
                'lng' => number_format(floatval($long), 6, '.', ''),
                'lat' => number_format(floatval($lat), 6, '.', ''),
                'slug' => $slug,
                'status' => 1,
                'created_at' => time(),
                'data_html' => json_encode($data_html),
                'investor' => $investor,
            ];

            return $json;
        }
        return null;
    }

    public function getWardAndStreet($location, $streets, $wards, $district_id){
        $parseLocation = $this->parseLocation($location);

        if(isset($parseLocation['street']) && !isset($parseLocation['ward']) && $parseLocation['remainSplit']) {
            $parseLocation['ward'] = $parseLocation['remainSplit'][0];
        }

        if(isset($parseLocation['homeNo']) && !isset($parseLocation['street']) && $parseLocation['remainSplit']) {
            $parseLocation['street'] = $parseLocation['remainSplit'][0];

            if(!isset($parseLocation['ward']) && count($parseLocation['remainSplit']) > 1) {
                $parseLocation['ward'] = $parseLocation['remainSplit'][1];
            }
        }

        if(!isset($parseLocation['street']) && !isset($parseLocation['ward']) && $parseLocation['remainSplit']) {
            if(count($parseLocation['remainSplit']) >= 2) {
                $parseLocation['street'] = $parseLocation['remainSplit'][0];
                $parseLocation['ward'] = $parseLocation['remainSplit'][1];
            } else {
                $parseLocation['ward'] = $parseLocation['remainSplit'][0];
            }
        }

        $update = [];

        if(isset($parseLocation['homeNo'])) {
            $update['home_no'] = $parseLocation['homeNo'];
        }

        if(isset($parseLocation['street'])) {
            if(stripos($parseLocation['street'], ' và ')) {
                $parseLocation['street'] = explode(' và ', $parseLocation['street']);
                $parseLocation['street'] = $parseLocation['street'][0];
            }
            if(stripos($parseLocation['street'], ' – ')) {
                $parseLocation['street'] = explode(' – ', $parseLocation['street']);
                $parseLocation['street'] = $parseLocation['street'][0];
            }
            if(stripos($parseLocation['street'], ' - ')) {
                $parseLocation['street'] = explode(' - ', $parseLocation['street']);
                $parseLocation['street'] = $parseLocation['street'][0];
            }

            $parseLocation['street'] = str_ireplace([' nối dài', ' kéo dài'], '', $parseLocation['street']);

            foreach ($streets as $street) {
                if(strcasecmp($parseLocation['street'], $street['name']) === 0 && $street['district_id'] == $district_id) {
                    $update['street_id'] = $street['id'];
                    break;
                } else if($this->slug($parseLocation['street']) == $this->slug($street['name']) && $street['district_id'] == $district_id) {
                    $update['street_id'] = $street['id'];
                    break;
                }
            }
        }

        if(isset($parseLocation['ward'])) {
            foreach ($wards as $ward) {
                if(strcasecmp($parseLocation['ward'], $ward['name']) === 0 && $ward['district_id'] == $district_id) {
                    $update['ward_id'] = $ward['id'];
                    break;
                } else if($this->slug($parseLocation['ward']) == $this->slug($ward['name']) && $ward['district_id'] == $district_id) {
                    $update['ward_id'] = $ward['id'];
                    break;
                }
            }
        }
        return $update;
    }

    public function parseLocation($location){
        preg_match('/^(((ngõ )|(số )|(((lô )|(Lô đất ))[a-z]*)|[a-z])?[0-9]\S*( (–|-) [a-z]?[0-9]\S*)?( ngõ [a-z]*[0-9]\S*)?)/i', $location, $matches);

        $return = [];

        if($matches) {
            $return['homeNo'] = preg_replace('/số /i', '', rtrim($matches[0], ','));
            $remain = trim(str_replace($matches[0], '', $location));
        } else {
            $remain = $location;
        }

        $remainSplit = array_map('trim', explode(',', $remain));
        $streetKey = $this->streetDetect($remainSplit);

        if($streetKey !== null) {
            $return['street'] = trim(preg_replace('/^((đường)|(Đường)|(phố))\s?/i', '', $remainSplit[$streetKey]));
            $remainSplit = array_slice($remainSplit, $streetKey + 1);
        }

        $wardKey = $this->wardDetect($remainSplit);

        if($wardKey !== null) {
            $return['ward'] = trim(preg_replace('/^((phường)|(thị trấn)|(xã)|(x\.)|(p\.))\s?/i', '', $remainSplit[$wardKey]));

            if($streetKey === null && $wardKey > 0) {
                $return['street'] = $remainSplit[$wardKey - 1];
            }
        }

        $return['remainSplit'] = $remainSplit;

        return $return;
    }

    public function streetDetect($remainSplit) {
        foreach ($remainSplit as $k => $rs) {
            if(preg_match('/^((đ|Đường)|(phố))/i', $rs)) {
                return $k;
            }
        }
        return null;
    }

    public function wardDetect($remainSplit) {
        foreach ($remainSplit as $k => $rs) {
            if(preg_match('/^((phường)|(thị trấn)|(xã)|(x\.)|(p\.))/i', $rs)) {
                return $k;
            }
        }
        return null;
    }

    public function slug($str) {
        $str = trim(mb_strtolower($str, 'UTF-8'));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        return $str;
    }

    public function investorExists($name, $list)
    {
        foreach ($list as $obj) {
            $c = new Collator('vi_VN');
            $check = $c->compare(trim($name), trim($obj));
            if ($check == 0) {
                return true;
            }
        }
        return false;
    }
    public function getIdExists($name, $data)
    {
        foreach ($data as $obj) {
            $c = new Collator('vi_VN');
            $check = $c->compare(trim($name), trim($obj['name']));
            if ($check == 0) {
                return (int)$obj['id'];
            }
        }
        return null;
    }

    public function getProject($city_id, $district_id, $name, $lat, $lng){
        $sql_where = "CAST(lat AS decimal) = CAST({$lat} AS decimal) and CAST(lng AS decimal) = CAST({$lng}  AS decimal) ";
        $project = AdBuildingProject::find()->select(['id', 'name', 'slug'])
            ->where([
                'city_id' => $city_id,
                'district_id' => $district_id,
                'name' => $name
            ])
            ->andWhere($sql_where)
            ->one();
        if(count($project) > 0){
            return $project;
        }
        return null;
    }

    public function updateProjectTool($limit=300){
        $query = AdBuildingProject::find()->where('project_main_id = :pid', [':pid' => 0])
            ->andWhere("city_id is null or district_id is null");
        $count = (int)$query->count('id');
        if($count > 0) {
            $path = Yii::getAlias('@console') . "/data/bds_html/projects/";
            $models = $query->limit($limit)->orderBy(['id' => SORT_ASC])->all();
            $wards = AdWard::getDb()->cache(function (){
                return AdWard::getDb()->createCommand("SELECT id, name, district_id FROM `ad_ward`")->queryAll();
            });
            $streets = AdStreet::getDb()->cache(function () {
                return AdStreet::getDb()->createCommand("SELECT id, name, district_id FROM `ad_street`")->queryAll();
            });
            foreach($models as $model){
                $arrFile = explode("/", $model->file_name);
                $type = $arrFile[0];
                $filename = $arrFile[1];
                $filePath = $path. $type. "/files/". $filename;
                if(file_exists($filePath)){
                    $value = $this->parseProjectDetail($path. $type.  "/files/", $filename);
                    $city = $value[$filename]["city"];
                    $district = $value[$filename]["district"];

                    if(empty($city) || empty($district)){
                        $toFolder = $path."import/error/".$type."/";
                        if(!is_dir($toFolder)){
                            mkdir($toFolder, 0777, true);
                            print_r("\nMake Project Error Folder");
                        }
                        $re = copy($filePath, $toFolder.$filename);
                        if($re) {
                            print_r("\n{$model->file_name} cannot find city or district");
                            continue;
                        }
                    }

                    $city_result = Helpers::getCityId($city);
                    if($city_result == 500){
                        $toFolder = $path."import/error/".$type."/";
                        if(!is_dir($toFolder)){
                            mkdir($toFolder, 0777, true);
                            print_r("\nMake Error Folder City {$filename}");
                        }
                        copy($filePath, $toFolder.$filename);
                    }
                    $city_id = isset($city_result['id']) ? (int)$city_result['id'] : 0;
                    $district_id = 0;
                    if($city_id > 0){
                        $model->city_id = $city_id;
                        $district_result = Helpers::getDistrictId($district, $city_id);
                        if($district_result == 500){
                            $toFolder = $path."import/error/".$type."/";
                            if(!is_dir($toFolder)){
                                mkdir($toFolder, 0777, true);
                                print_r("\nMake Error Folder District {$filename}");
                            }
                            copy($filePath, $toFolder.$filename);
                        }
                        $district_id = isset($district_result['id']) ? (int)$district_result['id'] : 0;
                    }

                    if($city_id == 0 || $district_id == 0){
                        continue;
                    }
                    $model->district_id = $district_id;

                    if(!empty($value[$filename]['investment_type']))
                        $model->investment_type = $value[$filename]['investment_type'];

                    if(!empty($value[$filename]['lat']))
                        $model->lat = $value[$filename]['lat'];

                    if(!empty($value[$filename]['lng']))
                        $model->lng = $value[$filename]['lng'];

                    $location = $value[$filename]["location"];
                    if(!empty($location))
                        $model->location = $location;

                    $ws = $this->getWardAndStreet($location, $streets, $wards, $district_id);
                    if (count($ws) > 0) {
                        $model->street_id = (int)(isset($ws["street_id"]) && !empty($ws["street_id"])) ? $ws["street_id"] : null;
                        if(empty($model->street_id) && $model->name == "Cape Pearl" && $city_id == 1 && $district_id == 3)
                            $model->street_id = 587; // Thanh Đa

                        $model->ward_id = (int)(isset($ws["ward_id"]) && !empty($ws["ward_id"])) ? $ws["ward_id"] : null;
                        $model->home_no = (int)(isset($ws["home_no"]) && !empty($ws["home_no"])) ? $ws["home_no"] : null;
                    }

                    if($model->save(false)){
                        print_r("\nUpdated Tool ID: {$model->id} - {$model->file_name}");
                    }
                }
            }
        }
    }

}