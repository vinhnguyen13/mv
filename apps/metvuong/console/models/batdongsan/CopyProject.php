<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 7/12/2016 1:50 PM
 */

namespace console\models\batdongsan;

use vsoft\ad\models\AdInvestorBuildingProject;
use vsoft\craw\models\AdBuildingProject;
use vsoft\craw\models\AdInvestor;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

class CopyProject extends Component
{
    public static function find()
    {
        return Yii::createObject(CopyProject::className());
    }

    public function copyProjects($limit=300){
        $start = time();
        $query = AdBuildingProject::find()->where('project_main_id = :pid', [':pid' => 0])
            ->andWhere("city_id is not null and district_id is not null");
        $count = (int)$query->count('id');
        if($count > 0) {
//            $bulkInsertInvestor = array();
//            $listInvestorProject = array();
            $no = 0;
            $count_update = 0;
            $investor_name = null;
//            $ad_project_id = 0;
            $insertCount = $insertInvestorProject = 0;
            $models = $query->limit($limit)->orderBy(['id' => SORT_ASC])->all();
            foreach ($models as $model) {
                $ad_investor_id = 0;
//                $ad_project = \vsoft\ad\models\AdBuildingProject::find()->select(['id', 'name', 'slug'])->where('slug = :n', [':n' => $slug])->asArray()->one();
                $sql_where = "CAST(lat AS decimal) = CAST({$model->lat} AS decimal) and CAST(lng AS decimal) = CAST({$model->lng}  AS decimal) ";
                $ad_project = \vsoft\ad\models\AdBuildingProject::find()->select(['id', 'name', 'slug'])
                    ->where([
                        'city_id' => $model->city_id,
                        'district_id' => $model->district_id,
                        'name' => $model->name
                    ])
                    ->andWhere($sql_where)
                    ->asArray()->one();

                $count_ad_project = count($ad_project);
                if ($count_ad_project > 0) {
                    $model->project_main_id = (int)$ad_project['id'];
                    $model->update(false);
                    $count_update++;
                } else {
                    if (empty($model->city_id) && empty($model->district_id))
                        continue;

                    $investorBuildingProject = $model->adInvestorBuildingProjects[0];
                    $inv_id = $investorBuildingProject->investor_id;
                    $investor = AdInvestor::find()->where('id = :invid', [':invid' => $inv_id])->asArray()->one();
                    $investor_name = $investor["name"];

                    $ad_investor = null;
                    if (!empty($investor_name)) {
                        $ad_investor = \vsoft\ad\models\AdInvestor::find()->where('name = :n', [':n' => $investor_name])->asArray()->one();
                        $count_ad_investor = count($ad_investor);
                        if ($count_ad_investor <= 0) {
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
                            $new_ad_investor = new \vsoft\ad\models\AdInvestor($recordInvestor);
                            $new_ad_investor->save(false);
                            $ad_investor_id = $new_ad_investor->id;
//                            $bulkInsertInvestor[] = $recordInvestor;
                        } else {
                            $ad_investor_id = (int)$ad_investor['id'];
                        }
                    }

                    $adBuildingProject = new \vsoft\ad\models\AdBuildingProject();
                    $adBuildingProject->city_id = $model->city_id;
                    $adBuildingProject->district_id = $model->district_id;
                    $adBuildingProject->name = $model->name;
                    $adBuildingProject->bds_name = $model->name;
                    $adBuildingProject->logo = $model->logo;
                    $adBuildingProject->location = $model->location;
                    $adBuildingProject->description = $model->description;
                    $adBuildingProject->investment_type = $model->investment_type;
                    $adBuildingProject->hotline = $model->hotline;
                    $adBuildingProject->website = $model->website;
                    $adBuildingProject->facilities = $model->facilities;
                    $adBuildingProject->lng = $model->lng;
                    $adBuildingProject->lat = $model->lat;
                    $adBuildingProject->slug = $model->slug;
                    $adBuildingProject->status = $model->status;
                    $adBuildingProject->created_at = $model->created_at;
                    $adBuildingProject->file_name = $model->file_name;
                    $adBuildingProject->is_crawl = 1;
                    $adBuildingProject->data_html = $model->data_html;
                    $adBuildingProject->home_no = $model->home_no;
                    $adBuildingProject->street_id = $model->street_id;
                    $adBuildingProject->ward_id = $model->ward_id;
                    $int = $adBuildingProject->save(false);

                    // update product_main_id from DB Main Product ID
                    if ($int > 0) {
                        $model->project_main_id = $ad_project_id = $adBuildingProject->id;
                        $model->update(false);

                        // Map du an moi va chu dau tu DB Main
                        if($ad_investor_id > 0 && $ad_project_id > 0) {
                            $investorProject = new AdInvestorBuildingProject();
                            $investorProject->investor_id = $ad_investor_id;
                            $investorProject->building_project_id = $ad_project_id;
                            $investorProject->save(false);
                        }

                        print_r("\nCopied Tool ID {$model->id}: {$model->file_name}");
                    }
                    $insertCount++;
                }

                if ($no > 0 && $no % 50 == 0) {
                    print_r(PHP_EOL);
                    print_r("Copy {$no} records...");
                    print_r(PHP_EOL);
                }
                $no++;
            }

            if($count_update > 0)
                print_r("\nUpdate {$count_update} Project");
            if($insertCount > 0)
                print_r("\nCopy {$insertCount} Project data ... DONE");
            else
                print_r("\nDon't have new Project to copy");
        }

        $stop = time();
        $time = $stop - $start;
        print_r("\nTime: {$time}s");
    }
}