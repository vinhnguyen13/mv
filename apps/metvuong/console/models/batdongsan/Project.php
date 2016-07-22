<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 7/12/2016 10:38 AM
 */

namespace console\models\batdongsan;


use console\models\Helpers;
use keltstr\simplehtmldom\SimpleHTMLDom;
use Yii;
use yii\base\Component;

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
}