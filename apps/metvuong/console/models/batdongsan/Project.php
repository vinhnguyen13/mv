<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 7/12/2016 10:38 AM
 */

namespace console\models\batdongsan;


use common\components\Slug;
use console\models\Helpers;
use keltstr\simplehtmldom\SimpleHTMLDom;
use vsoft\ad\models\AdInvestor;
use vsoft\ad\models\AdInvestorBuildingProject;
use vsoft\ad\models\AdStreet;
use vsoft\ad\models\AdWard;
use vsoft\express\components\AdImageHelper;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\image\drivers\Image;
use yii\image\drivers\Image_Imagick;

class Project extends Component
{
    const DOMAIN = 'batdongsan.com.vn';
    public $time_start = 0;
    public $time_end = 0;

    public $projects=['khu-can-ho','cao-oc-van-phong','khu-do-thi-moi','khu-dan-cu','khu-thuong-mai-dich-vu',
        'khu-cong-nghiep','khu-du-lich-nghi-duong','khu-phuc-hop','du-an-khac'];

    public $tabProject = [
        1 => 'tong-quan',
        4 => 'vi-tri',
        2 => 'ha-tang',
        3 => 'thiet-ke',
        8 => 'tien-do',
        5 => 'ban-hang',
        9 => 'ho-tro',
    ];

    public static function find()
    {
        return Yii::createObject(Project::className());
    }

    public function getProjects(){
        ob_start();
        $this->time_start = time();
        $types = $this->projects;
        $path_folder = Yii::getAlias('@console') . "/data/bds_html/projects/";
        $project_log = Helpers::loadLog($path_folder, "project.json");
        if(empty($project_log["type"])){
            $project_log["type"] = array();
        }

        $write_log = false;
        $current_type = empty($project_log["current_type"]) ? 0 : ($project_log["current_type"] + 1);
        $count_type = count($types) - 1;

        if($project_log["current_type"] == $count_type){
            $project_log["type"] = array();
            $project_log["current_type"] = null;
            Helpers::writeLog($project_log, $path_folder, "project.json");
            $current_type = 0;
        }

        for($i=$current_type; $i <= $count_type; $i++){
            $type = $types[$i];
            if ($i >= $current_type) {
                $url = self::DOMAIN . "/" . $type;
                $page = Helpers::getUrlContent($url);
                if (!empty($page)) {
                    $html = SimpleHTMLDom::str_get_html($page, true, true, DEFAULT_TARGET_CHARSET, false);
                    $pagination = $html->find('.ks-pagination-links a');
                    $count_page = count($pagination);
                    $last_page = (int)str_replace("/".$type . "/p", "", $pagination[$count_page-1]->href);

                    if ($last_page > 0) {
                        $log = Helpers::loadLog($path_folder . $type . "/", "{$type}.json");
                        if(empty($log["files"])){
                            $log["files"] = array();
                        }

                        if($log["current_page"] == $last_page) {
                            $log['current_page'] = 0;
                            Helpers::writeLog($log, $path_folder.$type."/", "{$type}.json");
                            print_r("\nLast file of {$type} finish.");
                        } else {
                            $current_page = empty($log["current_page"]) ? 1 : ($log["current_page"] + 1);
                            $current_page_add = $current_page + 4; // +4 is total pages to run that are 5.
                            if ($current_page_add > $last_page) {
                                $current_page_add = $last_page; // comment to run to last page.
                            }

                            if ($current_page <= $current_page_add) {
                                for ($i = $current_page; $i <= $current_page_add; $i++) {
                                    $list_return = $this->projectList($type, $i, $log, $path_folder);
                                    if (!empty($list_return["data"])) {
                                        $log = $list_return["data"];
                                        $log["current_page"] = $i;
                                        Helpers::writeLog($log, $path_folder . $type . "/", "{$type}.json");
                                        print_r("\n{$type}-page " . $i . " done!\n");
                                    }
                                    sleep(3);
                                    ob_flush();
                                }
                            }
                            // stop after crawl 5 pages
                            if($last_page == $current_page_add)
                                $write_log = true;
                            else
                                break;
                        }
                    }
                }

                if($write_log) {
                    if (!in_array($type, $project_log["type"])) {
                        array_push($project_log["type"], $type);
                    }
                    $project_log["current_type"] = $i;
                    Helpers::writeLog($project_log, $path_folder, "project.json");
                }
            }
        }
        $this->time_end = time();
        print_r("\nTime: ");
        print_r($this->time_end - $this->time_start);
    }

    public function projectList($type, $current_page, $log, $path_folder){
        $href = "/".$type."/p".$current_page;
        $page = Helpers::getUrlContent(self::DOMAIN . $href);
        if(!empty($page)) {
            $html = SimpleHTMLDom::str_get_html($page, true, true, DEFAULT_TARGET_CHARSET, false);
            $list = $html->find('.list2item2 .tc-img a');
            if (count($list) > 0) {
                // about 20 listing
                $id = null;
                foreach ($list as $item) {
                    if (preg_match('/pj(\d+)/', self::DOMAIN . $item->href, $matches)) {
                        if(!empty($matches[1])){
                            $id = $matches[1];
                        }
                    }
                    // Check exists
                    $checkExists = false;
                    if(!empty($id) && !empty($log["files"])) {
                        $checkExists = in_array($id, $log["files"]);
                    }

                    if ($checkExists == false) {
                        $res = $this->projectDetail($type, $id, $item->href, $path_folder);
                        if (!empty($res)) {
                            array_push($log["files"], $id);
                            print_r("\nCrawled {$id}");
                        }
                    }
                }
                return ['data' => $log];
            } else {
                echo "\nCannot find listing: {$href}\n";
            }

        } else {
            echo "\nCannot access: {$href}\n";
        }
        return null;
    }

    public function projectDetail($type, $id, $href, $path_folder){
        $page = Helpers::getUrlContent(self::DOMAIN . $href);
        if(!empty($page)) {
            $path = $path_folder."{$type}/files/";
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                echo "\nDirectory {$path} was created";
            }
            $res = Helpers::writeFileJson($path . $id, $page);
            if ($res) {
//                Listing::find()->writeFileLogUrlSuccess($type, self::DOMAIN . $href . "\n", $path_folder);
                return $id;
            } else {
//                Listing::find()->writeFileLogFail($type, self::DOMAIN . $href . "\n", $path_folder);
                return null;
            }
        }
        else {
            echo "\nError go to detail at " .self::DOMAIN.$href;
            return null;
        }
    }

    public function updateProject($limit=300, $updateImage=false, $updateInvestor=false){
        $start = time();
        $path = Yii::getAlias('@console'). "/data/bds_html/projects/update/";
        if(!is_dir($path))
        {
            mkdir($path, 0777, true);
        }
        $file_last_id_name = "update_project_last_id.json";
        $log = Helpers::loadLog($path, $file_last_id_name);
        $last_id = 0;
        if(!empty($log)){
            $last_id = (int)$log['last_id'];
        }
        $query = \vsoft\ad\models\AdBuildingProject::find()->where("id > {$last_id}");
        $count = (int)$query->count('id');
        if($count > 0) {
            $projects = $query->orderBy(['id' => SORT_ASC])->limit($limit)->all();
            if(count($projects) > 0){
                $folder = Yii::getAlias('@store'). "/building-project-images";
                foreach ($projects as $k => $project) {
                    $value = null;
                    $page = null;
                    if(empty($project->file_name)) {
                        $log['last_id'] = $project->id;
                        $log['last_time'] = date('d M Y H:i', time());
                        Helpers::writeLog($log, $path, $file_last_id_name);
                        continue;
                    }

                    $search_url = Listing::DOMAIN . "/phan-muc-cac-du-an-bds?k=" . rawurlencode($project->name);
                    $arr_file_name = explode("/", $project->file_name);
                    $file_name = $arr_file_name[1];
                    print_r("\n" . ($k + 1) . " " . $file_name);
                    $page = $this->searchProject($search_url, $file_name);

                    if(!empty($page)){
                        $value = ImportProject::find()->parseProjectDetail(null, $file_name, $page);
                        if (empty($value)) {
                            print_r(" - Error: no content.");
                            $log['last_id'] = $project->id;
                            $log['last_time'] = date('d M Y H:i', time());
                            Helpers::writeLog($log, $path, $file_last_id_name);
                            continue;
                        }

                        if($updateImage) {
                            $project_logo = $value[$file_name]['logo'];
                            if (Helpers::beginWith($project_logo, 'http')) {
                                if(strpos($project_logo, " ")) {
                                    $arrProjectUrl = explode("/", $project_logo);
                                    $arrEnd = end($arrProjectUrl);
                                    $encode = rawurlencode($arrEnd);
                                    if(strpos($project_logo, $arrEnd)){
                                        $project_logo = str_replace($arrEnd, $encode, $project_logo);
                                    }
                                }

                                $file_download = $this->downloadImage($project_logo, $folder);
                                if($file_download == -1){
                                    print_r(" - cannot download project image");
                                }
                                if ($file_download && $file_download != -1) {
                                    $project->logo = $file_download;
                                    if($project->save(false))
                                        print_r(" - update project image");
                                }
                            }
                        }

                        if($updateInvestor) {
                            $investor = $value[$file_name]["investor"];
                            if (count($investor) > 0 && isset($investor['name']) && !empty($investor['name'])) {
                                $investor_logo = $investor["logo"];
                                $investor_folder = Yii::getAlias('@store'). "/investor";
                                if (Helpers::beginWith($investor_logo, 'http')) {
                                    if(strpos($investor_logo, " ")) {
                                        $arrUrl = explode("/", $investor_logo);
                                        $end = end($arrUrl);
                                        $inv_encode = rawurlencode($end);
                                        if(strpos($investor_logo, $end)){
                                            $investor_logo = str_replace($end, $inv_encode, $investor_logo);
                                        }
                                    }

                                    $inv_logo_file_download = $this->downloadImage($investor_logo, $investor_folder);
                                    if ($inv_logo_file_download && $inv_logo_file_download != -1) {
                                        $investor_logo = $inv_logo_file_download;
                                    }
                                }

                                $address = isset($investor["address"]) && !empty($investor["address"]) ? $investor["address"] : null;
                                $phone = isset($investor["phone"]) && !empty($investor["phone"]) ? $investor["phone"] : null;
                                $fax = isset($investor["fax"]) && !empty($investor["fax"]) ? $investor["fax"] : null;
                                $website = isset($investor["website"]) && !empty($investor["website"]) ? $investor["website"] : null;
                                $email = isset($investor["email"]) && !empty($investor["email"]) ? $investor["email"] : null;

                                $buildingInvestor = AdInvestorBuildingProject::find()->where(['building_project_id' => $project->id])->one();
                                if (count($buildingInvestor) > 0 && $buildingInvestor->investor_id > 0) {
                                    $recordInvUpdate = [
                                        'logo' => $investor_logo,
                                        'name' => $investor["name"],
                                        'address' => $address,
                                        'phone' => $phone,
                                        'fax' => $fax,
                                        'website' => $website,
                                        'email' => $email,
                                        'updated_at' => time()
                                    ];
                                    $resUpdate = AdInvestor::getDb()->createCommand()
                                        ->update(AdInvestor::tableName(), $recordInvUpdate, 'id=:id', [':id' => $buildingInvestor->investor_id])
                                        ->execute();
                                    if ($resUpdate > 0)
                                        print_r(" - update investor success.");
                                }
                                else {
                                    $old_investor = AdInvestor::find()->where([
                                        'name' => $investor["name"]
                                    ])->one();
                                    if (count($old_investor) > 0) {
                                        $old_investor->logo = $investor_logo;
                                        $old_investor->updated_at = time();
                                        if ($old_investor->save(false)) {
                                            $new_buildingInvestor = new AdInvestorBuildingProject();
                                            $new_buildingInvestor->investor_id = $old_investor->id;
                                            $new_buildingInvestor->building_project_id = $project->id;
                                            $new_buildingInvestor->save(false);
                                            print_r(" - update project & investor success");
                                        }
                                    } else {
                                        $recordInv = [
                                            'name' => $investor["name"],
                                            'address' => $address,
                                            'phone' => $phone,
                                            'fax' => $fax,
                                            'website' => $website,
                                            'email' => $email,
                                            'logo' => $investor_logo,
                                            'status' => 1,
                                            'created_at' => time()
                                        ];
                                        $new_investor = new AdInvestor($recordInv);
                                        if ($new_investor->save(false)) {
                                            $new_buildingInvestor = new AdInvestorBuildingProject();
                                            $new_buildingInvestor->investor_id = $new_investor->id;
                                            $new_buildingInvestor->building_project_id = $project->id;
                                            $new_buildingInvestor->save(false);
                                            print_r(" - update investor success");
                                        }
                                    }

                                }
                            }
                        }
                    } else {
                        print_r(' - Cannot crawl project');
                    }

                    $log['last_id'] = $project->id;
                    $log['last_time'] = date('d M Y H:i', time());
                    Helpers::writeLog($log, $path, $file_last_id_name);
                    usleep(50000);
                } // end foreach
            } else {
                print_r("\nProject not found");
            }

        } else {
            print_r("\nCannot find project");
        }
        $stop = time();
        $time = $stop - $start;
        print_r("\nTime: {$time}s");
    }

    public function downloadImage($link, $folder)
    {
        $width = 498;
        $height = 300;
        $supported_image = array('gif','jpg','jpeg','png');
        try {
            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
            }

            $checkImage = getimagesize($link);
            if(!isset($checkImage['mime'])){
                $link = 'https://metvuong.com/themes/metvuong2/resources/images/default-ads.jpg';
                $checkImage = getimagesize($link);
            }

            if(isset($checkImage['mime']) && Helpers::beginWith($checkImage['mime'], 'image/')) {
                $arrLink = explode('.', $link);
                $length = count($arrLink) - 1;
                $fileName = uniqid("img_") . '.' . $arrLink[$length];
                $filePath = $folder . "/" . $fileName;
                $content = file_get_contents($link);
                if ($content) {
                    $result = file_put_contents($filePath, $content);
                    if (!strpos($folder, 'investor')) {
                        $pathInfo = pathinfo($filePath);
                        if (isset($pathInfo['filename']) && isset($pathInfo['extension'])) {
                            $ext = $pathInfo['extension'];
                            $thumbFile = "thumb_" . $fileName;
                            $thumbPath = $folder . "/" . $thumbFile;

                            if (in_array($ext, $supported_image) && !strpos($checkImage['mime'], 'bmp') && $checkImage[0] > $width) {
                                $resource = \Yii::$app->image->load($filePath);
                                $resource->resize($width, $height, Image::WIDTH)->save($thumbPath);
                            } else {
                                file_put_contents($thumbPath, $content);
                            }
                        }
                    }
                    return $result > 0 ? $fileName : null;
                }
            } else {
                return -1;
            }
        } catch(Exception $e){
            print_r("\n \t".$e->getMessage());
        }
        return null;
    }

    public function searchProject($search_url, $file_name){
        $search_page = Helpers::getUrlContent($search_url);
        $page = null;
        if(!empty($search_page)){
            $search_detail = SimpleHTMLDom::str_get_html($search_page, true, true, DEFAULT_TARGET_CHARSET, false);
            if (!empty($search_detail)) {
                $list_result_project = $search_detail->find('.project-by-cate-list .list2item2');
                if(count($list_result_project) > 0) {
                    foreach ($list_result_project as $result_project) {
                        $link = $result_project->find('a', 0)->href;
                        if (strpos($link, "pj" . $file_name)) {
                            $url = Listing::DOMAIN . "/" . $link;
                            $page = Helpers::getUrlContent($url);
                            break;
                        }
                    }
                }
            }

            if(empty($page) && !empty($file_name)) {
                $search_url = Listing::DOMAIN . "/view-pj" . $file_name;
                $page = Helpers::getUrlContent($search_url);
            }

        }
        return $page;
    }
}