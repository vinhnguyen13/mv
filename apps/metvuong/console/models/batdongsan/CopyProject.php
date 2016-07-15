<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 7/12/2016 1:50 PM
 */

namespace console\models\batdongsan;

use vsoft\craw\models\AdBuildingProject;
use vsoft\craw\models\AdInvestor;
use Yii;
use yii\base\Component;
use yii\base\Exception;

class CopyProject extends Component
{
    public static function find()
    {
        return Yii::createObject(CopyProject::className());
    }

    public function copyProjects(){
        $start = time();
        $query = AdBuildingProject::find()->where('project_main_id = :pid', [':pid' => 0]);
        $count = (int)$query->count('id');
        if($count > 0) {
            $bulkInsertInvestor = array();
            $listInvestorProject = array();
            $no = 0;
            $count_update = 0;
            $ad_investor_id = 0;
            $investor_name = null;
            $ad_project_id = 0;
            $insertCount = $insertInvestorProject = 0;
            // limit 3
            $models = $query->limit(300)->all();
            foreach ($models as $model) {
                $slug = $model->slug;
                $ad_project = \vsoft\ad\models\AdBuildingProject::find()->select(['id', 'name', 'slug'])->where('slug = :n', [':n' => $slug])->asArray()->one();
                $count_ad_project = count($ad_project);
                if ($count_ad_project > 0) {
                    $model->project_main_id = (int)$ad_project['id'];
                    $model->update(false);
                    $ad_project_id = (int)$ad_project['id'];
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
                            $bulkInsertInvestor[] = $recordInvestor;
                        } else {
                            $ad_investor_id = $ad_investor['id'];
                        }
                    }

                    $adBuildingProject = new \vsoft\ad\models\AdBuildingProject();
                    $adBuildingProject->city_id = $model->city_id;
                    $adBuildingProject->district_id = $model->district_id;
                    $adBuildingProject->name = $model->name;
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
                        print_r("\nCopied {$adBuildingProject->file_name}");
                    }
                    $insertCount++;
                }

                $new_inv_project = \vsoft\ad\models\AdInvestorBuildingProject::find()->where(['building_project_id' => $ad_project_id])->count();
                if($new_inv_project <= 0) {
                    $listInvestorProject[$slug] = [
                        'investor_id' => $ad_investor_id,
                        'investor_name' => $investor_name,
                        'project_id' => $ad_project_id
                    ];
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

            if (count($bulkInsertInvestor) > 0) {
                $columnInvestor = ['name', 'address', 'phone', 'fax', 'website', 'email', 'logo', 'status', 'created_at'];
                $insertCountInvestor = \vsoft\ad\models\AdInvestor::getDb()->createCommand()
                    ->batchInsert(\vsoft\ad\models\AdInvestor::tableName(), $columnInvestor, $bulkInsertInvestor)->execute();
                print_r("\nCopy {$insertCountInvestor} Investor data ... DONE");
            } else {
                print_r("\nDon't have new Investor to copy");
            }

            if(count($listInvestorProject) > 0) {
                $bulkInsertInvestorProject = array();
                foreach ($listInvestorProject as $inv_project) {
                    if (count($inv_project) > 0) {
                        $new_investor_id = 0;
                        if (isset($inv_project['investor_id']) && $inv_project['investor_id'] > 0)
                            $new_investor_id = $inv_project['investor_id'];
                        else {
                            if (isset($inv_project['investor_name']) && !empty($inv_project['investor_name'])) {
                                $investor_name = $inv_project['investor_name'];
                                $new_ad_investor = \vsoft\ad\models\AdInvestor::find()->where('name = :n', [':n' => $investor_name])->asArray()->one();
                                $new_investor_id = $new_ad_investor['id'];
                            }
                        }

                        if ($new_investor_id <= 0 || $inv_project['project_id'] <= 0)
                            continue;

                        $recordInvestorProject = [
                            'building_project_id' => $inv_project['project_id'],
                            'investor_id' => $new_investor_id
                        ];
                        $bulkInsertInvestorProject[] = $recordInvestorProject;
                    }
                }

                if (count($bulkInsertInvestorProject) > 0) {
                    $columnInvestorProject = ['building_project_id', 'investor_id'];
                    $insertCountInvestorProject = \vsoft\ad\models\AdInvestorBuildingProject::getDb()->createCommand()
                        ->batchInsert(\vsoft\ad\models\AdInvestorBuildingProject::tableName(), $columnInvestorProject, $bulkInsertInvestorProject)->execute();
                    if ($insertCountInvestorProject > 0)
                        print_r("\nMap Investor and Building Project {$insertCountInvestorProject}");
                }
            }
        }

        $stop = time();
        $time = $stop - $start;
        print_r("\nTime: {$time}s");
    }
}