<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 7/12/2016 10:51 AM
 */

namespace console\models\batdongsan;


use Collator;
use console\models\Helpers;
use keltstr\simplehtmldom\SimpleHTMLDom;
use vsoft\craw\models\AdBuildingProject;
use vsoft\craw\models\AdInvestor;
use vsoft\craw\models\AdInvestorBuildingProject;
use vsoft\craw\models\AdStreet;
use vsoft\craw\models\AdWard;
use Yii;
use yii\base\Component;
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

    public function importProjects(){
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

        $bulkInsertInvestor = array();
        $bulkInsertArray = array();
        $listInvestorProject = [];

        $wards = AdWard::getDb()->cache(function (){
            return AdWard::getDb()->createCommand("SELECT id, name, district_id FROM `ad_ward`")->queryAll();
        });
        $streets = AdStreet::getDb()->cache(function () {
            return AdStreet::getDb()->createCommand("SELECT id, name, district_id FROM `ad_street`")->queryAll();
        });

        $investorData = AdInvestor::find()->asArray()->all();

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
                            if ($no > 300) {
                                $break_type = true;
                                break;
                            }

                            $filename = $files[$i];
                            $filePath = $path . $type. "/files/" . $filename;

                            if (in_array($filename, $log_import["files"])) {
                                continue;
                            }
                            else {
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
                                    $project_name = !empty($value[$filename]["name"]) ? $value[$filename]["name"] : null;
                                    $slug = $value[$filename]["slug"];
                                    $project = $this->getProjectBySlug($slug);

                                    $location = $value[$filename]["location"];
                                    // neu co ton tai trong db crawl
                                    if (count($project) > 0) {
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
//                                        print_r("\n". $no . " {$type} - ".$project_name." - {$filename}: ". "updated");
                                    }
                                    // add new project
                                    else {
                                        if(empty($city) && empty($district)){
                                            $toFolder = $path."import/error/".$type."/";
                                            if(!is_dir($toFolder)){
                                                mkdir($toFolder, 0777, true);
                                                print_r("\nMake Error Folder");
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

                                        if($city_id <= 0 || $district_id <= 0)
                                            continue;

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
                                            'lng' => $value[$filename]["lng"],
                                            'lat' => $value[$filename]["lat"],
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
                                        $bulkInsertArray[] = $record;

                                        // Check duplicate Investor :D
                                        $investor = $value[$filename]["investor"];
                                        $investor_name = $investor["name"];
                                        $investor_id = null;
                                        if (!empty($investor_name)) {
                                            $checkInvestorExists = count($listInvestorProject) > 0 ? $this->investorExists($investor_name, $listInvestorProject) : false;
                                            $investor_id = count($investorData) > 0 ? $this->getIdExists($investor_name, $investorData) : null;
                                            if ($checkInvestorExists == false && empty($investor_id)) {
                                                $recordInvestor = [
                                                    'name' => $investor_name,
                                                    'address' => $investor["address"],
                                                    'phone' => $investor["phone"],
                                                    'fax' => $investor["fax"],
                                                    'website' => $investor["website"],
                                                    'email' => $investor["email"],
                                                    'logo' => $investor["logo"],
                                                    'status' => 1,
                                                    'created_at' => time()
                                                ];
                                                $bulkInsertInvestor[] = $recordInvestor;
                                            }
                                        }

                                        $listInvestorProject[$slug] = [
                                            'investor_id' => $investor_id,
                                            'investor_name' => $investor_name
                                        ];

                                        $count_file_insert++;
//                                        print_r("\n" . $no . " {$type} - ".$project_name." - {$filename}");
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

                        $res_insert = $this->insert($bulkInsertArray, $bulkInsertInvestor, $listInvestorProject);
                        if($res_insert > 0) {
                            $bulkInsertArray = array();
                            $bulkInsertInvestor = array();
                            $listInvestorProject = array();
                            Helpers::writeLog($log_import, $path . "import/", $type . ".json");
                        }
                        if($count_file_update > 0)
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

    public function insert($bulkInsertArray, $bulkInsertInvestor, $listInvestorProject){
        $columnInvestor = ['name', 'address', 'phone', 'fax', 'website', 'email', 'logo', 'status', 'created_at'];
        $columnNameArray = ['city_id', 'district_id', 'name', 'logo',
            'location', 'description', 'investment_type', 'hotline', 'website',
            'lng', 'lat', 'slug', 'status', 'created_at', 'file_name', 'is_crawl', 'data_html',
            'home_no', 'street_id', 'ward_id'];
        $insertCountInvestor = 0;
        if (count($bulkInsertInvestor) > 0) {
            // below line insert all your record and return number of rows inserted
            $insertCountInvestor = AdInvestor::getDb()->createCommand()
                ->batchInsert(AdInvestor::tableName(), $columnInvestor, $bulkInsertInvestor)->execute();
//            print_r("\n\nInsert {$insertCountInvestor} INVESTOR data ... DONE!");
        }

        $insertCount = 0;
        if (count($bulkInsertArray) > 0) {
            // below line insert all your record and return number of rows inserted
            $insertCount = AdBuildingProject::getDb()->createCommand()
                ->batchInsert(AdBuildingProject::tableName(), $columnNameArray, $bulkInsertArray)->execute();
//            print_r("\nInsert {$insertCount} BUILDING PROJECT data ... DONE");
        }

        if(count($listInvestorProject) > 0 && $insertCount > 0 && $insertCountInvestor > 0){
            $bulkInsertInvestorProject = array();
            foreach($listInvestorProject as $k => $v){
                $buildingProject = $this->getProjectBySlug($k);
                $count_new_project = count($buildingProject);
                if($count_new_project <= 0)
                    continue;

                $new_investor_id = 0;
                if (isset($listInvestorProject[$k]['investor_id']) && $listInvestorProject[$k]['investor_id'] > 0)
                    $new_investor_id = (int)$listInvestorProject[$k]['investor_id'];
                else {
                    if (isset($listInvestorProject[$k]['investor_name'])) {
                        $investor_name = $listInvestorProject[$k]['investor_name'];
                        $new_ad_investor = AdInvestor::find()->where('name = :n', [':n' => $investor_name])->asArray()->one();
                        $new_investor_id = (int)$new_ad_investor['id'];
                    }
                }

                if($new_investor_id <= 0)
                    continue;

                $recordInvestorProject = [
                    'building_project_id' => $buildingProject->id,
                    'investor_id' => $new_investor_id
                ];
                $bulkInsertInvestorProject[] = $recordInvestorProject;
            }

            if(count($bulkInsertInvestorProject) >0 ) {
                $columnInvestorProject = ['building_project_id', 'investor_id'];
                AdInvestorBuildingProject::getDb()->createCommand()->batchInsert(AdInvestorBuildingProject::tableName(), $columnInvestorProject, $bulkInsertInvestorProject)->execute();
//                if ($insertCountInvestorProject > 0)
//                    print_r("\nMaps {$insertCountInvestorProject} INVESTOR with BUILDING PROJECT data DONE!");
            }
        }
        return $insertCount;
    }

    public function parseProjectDetail($path_folder, $filename){
        $json = array();
        $page = file_get_contents($path_folder . $filename);
        if(empty($page))
            return null;
        $detail = SimpleHTMLDom::str_get_html($page, true, true, DEFAULT_TARGET_CHARSET, false);
//        $detail = SimpleHTMLDom::file_get_html($path_folder.$filename);
        if (!empty($detail)) {
            $name = trim($detail->find('h1', 0)->innertext);
            $name = html_entity_decode(trim($name), ENT_HTML5, 'utf-8');

            $lat = $detail->find('#hdLat', 0)->value;
            $long = $detail->find('#hdLong', 0)->value;
            $address = $detail->find('#hdAddress', 0)->value;
            $address = html_entity_decode(trim($address), ENT_HTML5, 'utf-8');

            $city = $detail->find('#divCityOptions .current', 0, true)->innertext;
            $city = html_entity_decode(trim($city), ENT_HTML5, 'utf-8');

            $district = $detail->find('#divDistrictOptions .current', 0)->innertext;
            $district = html_entity_decode(trim($district), ENT_HTML5, 'utf-8');

            $logoTop = $detail->find('.prjava img', 0)->src;
            $inv_type = $detail->find('#divCatagoryOptions .current', 0)->innertext;

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

            $general = $detail->find('#detail .a1', 0);
            $imgGeneral = $general->find('img', 0);
            if(empty($imgGeneral))
                $logo = $logoTop;
            else
                $logo = $imgGeneral->src;

            $description = $general->innertext;
            $description = trim(strip_tags($description));
            $description = trim(StringHelper::truncate($description, 1000));

            $investor_name = trim($detail->find('#enterpriseInfo h3', 0)->plaintext);
            $investor = array();
            if($investor_name){
                $investor["name"] = $investor_name;
                $investor_desc = $detail->find('.info .d11 img', 0)->src;
                $investor["logo"] = strpos($investor_desc, "no-photo") == true ? null : $investor_desc;
                $investor_info = $detail->find('.info .d12 ul li');
                if(count($investor_info) > 0){
                    if(!empty($investor_info[0])) {
                        $investor_address = trim($investor_info[0]->plaintext);
                        $investor_address = trim(str_replace("Địa chỉ :", "", $investor_address));
                        $investor_address = $investor_address == "Đang cập nhật" ? null : $investor_address;
                        $investor["address"] = $investor_address;
                    }

                    if(!empty($investor_info[1])) {
                        $investor_phone_fax = trim($investor_info[1]->plaintext);
                        $investor_phone_fax = trim(str_replace("Điện thoại :", "", $investor_phone_fax));

                        $investor_phone = trim(substr($investor_phone_fax, 0, strpos($investor_phone_fax, "|")));
                        $investor_phone = $investor_phone == "Đang cập nhật" ? null : $investor_phone;
                        $investor["phone"] = $investor_phone;

                        $investor_fax = trim(substr($investor_phone_fax, strpos($investor_phone_fax, "Fax"), strlen($investor_phone_fax)-1));
                        $investor_fax = trim(str_replace("Fax :", "", $investor_fax));
                        $investor_fax = $investor_fax == "Đang cập nhật" ? null : $investor_fax;
                        $investor["fax"] = $investor_fax;
                    }

                    if(!empty($investor_info[2])) {
                        $investor_web = trim($investor_info[2]->plaintext);
                        $investor_web = trim(str_replace("Website :", "", $investor_web));
                        $investor_web = $investor_web == "Đang cập nhật" ? null : $investor_web;
                        $investor["website"] = $investor_web;
                    }

                    if(!empty($investor_info[3])) {
                        $str_email = trim($investor_info[3]->innertext);
                        $email = substr($str_email, strpos($str_email, "var attr = '"));
                        $email = str_replace("var attr = '", "", $email);
                        $email = substr($email, 0, strpos($email, "var txt ="));
                        $email = str_replace("';", "", $email);
                        $email = trim(html_entity_decode($email));
                        $investor["email"] = $email;
                    }
                }
            }

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
                'lng' => $long,
                'lat' => $lat,
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

    public function getProjectBySlug($slug){
        $project = AdBuildingProject::find()->select(['id', 'name', 'slug', 'district_id'])->where('slug = :s', [':s' => $slug])->one();
        if(count($project) > 0){
            return $project;
        }
        return null;
    }

}