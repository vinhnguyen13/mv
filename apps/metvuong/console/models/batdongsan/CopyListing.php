<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 7/12/2016 9:22 AM
 */

namespace console\models\batdongsan;


use console\models\Metvuong;
use vsoft\ad\models\AdContactInfo;
use vsoft\ad\models\AdImages;
use vsoft\ad\models\AdProduct;
use vsoft\ad\models\AdProductAdditionInfo;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class CopyListing extends Component
{
    public static function find()
    {
        return Yii::createObject(CopyListing::className());
    }

    /* End date = Start date Db Crawl + 30 days */
    public function copyToMainDB($validate=0){
        $begin = time();
        $models = \vsoft\craw\models\AdProduct::find()
            ->where(['not', ['file_name' => null]])
            ->andWhere(['product_main_id' => 0]);

        if($validate == 1) {
            $models = $models->andWhere('price > :p',[':p' => 0])
                ->andWhere('area > :a',[':a' => 0])
                ->andWhere('city_id > :c',[':c' => 0])
                ->andWhere('district_id > :d',[':d' => 0])
                ->andWhere('ward_id > :w',[':w' => 0])
                ->andWhere('street_id > :s',[':s' => 0]);
        }
        $models = $models->limit(300)->orderBy(['id' => SORT_ASC])->all();
        if(count($models) > 0){
            $no = 0;
            foreach ($models as $key=>$model) {
                print_r("\nCopy: {$model->file_name}");
                $productImages = $model->adImages;
                $adProductAdditionInfo = $model->adProductAdditionInfo;
                $adContactInfo = $model->adContactInfo;

                $city_id = empty($model->city_id) ? null : $model->city_id;
                $district_id = empty($model->district_id) ? null : $model->district_id;
                $ward_id = empty($model->ward_id) ? null : $model->ward_id;
                $street_id = empty($model->street_id) ? null : $model->street_id;
                $home_no = empty($model->home_no) ? null : $model->home_no;

                $crawl_project = $model->project;
                $project_name = $crawl_project->name;
                $project = \vsoft\ad\models\AdBuildingProject::find()->where('slug = :s', [':s' => $crawl_project->slug])->orWhere('name = :n', [':n' => $project_name])->one();

                if (count($project) > 0) { // lay address theo address project
                    $project_id = $project->id;
                    $city_id = empty($project->city_id) ? (empty($model->city_id) ? null : $model->city_id) : $project->city_id;
                    $district_id = empty($project->district_id) ? (empty($model->district_id) ? null : $model->district_id) : $project->district_id;
                    $ward_id = empty($project->ward_id) ? (empty($model->ward_id) ? null : $model->ward_id) : $project->ward_id;
                    $street_id = empty($project->street_id) ? (empty($model->street_id) ? null : $model->street_id) : $project->street_id;
                    $home_no = empty($project->home_no) ? (empty($model->home_no) ? null : $model->home_no) : $project->home_no;
                }

                if($city_id <= 0 || $district_id <= 0 || $ward_id <= 0 || $street_id <= 0) {
                    continue;
                }

//                $is_expired = $model->end_date > time() ? 0 : 1;
//                $is_expired = 0;
                $content = $model->content;
                if(empty($content)){
                    $content = $model->address;
                }

                $end_date = strtotime('+30 days', $model->start_date);

                $record = [
                    'category_id' => $model->category_id,
                    'project_building_id' => empty($project_id) ? null : $project->id,
                    'user_id' => empty($model->user_id) ? null : $model->user_id,
                    'home_no' => $home_no,
                    'city_id' => $city_id,
                    'district_id' => $district_id,
                    'ward_id' => $ward_id,
                    'street_id' => $street_id,
                    'type' => $model->type,
                    'content' => $content,
                    'area' => $model->area,
                    'price' => $model->price,
                    'price_type' => $model->price_type,
                    'lat' => $model->lat,
                    'lng' => $model->lng,
                    'start_date' => $model->start_date,
                    'end_date' => $end_date,
                    'verified' => 1,
                    'created_at' => $model->created_at,
                    'updated_at' => $model->created_at,
                    'source' => $model->source,
                    'status' => 1,
                    'is_expired' => 0
                ];
                try {
                    $product = new AdProduct($record);
                    $product->score = AdProduct::calcScore($product, $adProductAdditionInfo, $adContactInfo, count($productImages));
                    if ($product->save(false)) {
                        $model->product_main_id = $product->id;
                        $model->update(false);

                        // product image
                        if (isset($productImages) && count($productImages) > 0) {
                            foreach ($productImages as $image) {
                                if (count($image) > 0 && !empty($image)) {
                                    $result = Metvuong::DownloadImage($image->file_name, $image->uploaded_at);
                                    $imageRecord = [
                                        'user_id' => $image->user_id,
                                        'product_id' => $product->id,
                                        'file_name' => $result[0],
                                        'uploaded_at' => $image->uploaded_at,
                                        'folder' => $result[1]
                                    ];
                                    $adImage = new AdImages($imageRecord);
                                    $adImage->save(false);
                                }
                            }
                        }

                        // product additional info
                        $infoRecord = null;
                        if (isset($adProductAdditionInfo) && count($adProductAdditionInfo) > 0) {
                            $infoRecord = [
                                'product_id' => $product->id,
                                'facade_width' => $adProductAdditionInfo->facade_width,
                                'land_width' => $adProductAdditionInfo->land_width,
                                'home_direction' => $adProductAdditionInfo->home_direction,
                                'facade_direction' => $adProductAdditionInfo->facade_direction,
                                'floor_no' => $adProductAdditionInfo->floor_no,
                                'room_no' => $adProductAdditionInfo->room_no,
                                'toilet_no' => $adProductAdditionInfo->toilet_no,
                                'interior' => $adProductAdditionInfo->interior
                            ];
                            $adInfo = new AdProductAdditionInfo($infoRecord);
                            $adInfo->save(false);
                        }

                        // contact info
                        $contactRecord = null;
                        if (isset($adContactInfo) && count($adContactInfo) > 0) {
                            $contactRecord = [
                                'product_id' => $product->id,
                                'name' => $adContactInfo->name,
                                'phone' => $adContactInfo->phone,
                                'mobile' => $adContactInfo->mobile == null ? $adContactInfo->phone : $adContactInfo->mobile,
                                'address' => $adContactInfo->address,
                                'email' => $adContactInfo->email
                            ];
                            $adContact = new AdContactInfo($contactRecord);
                            $adContact->save(false);
                        }


                        if ($no > 0 && $no % 50 == 0) {
                            print_r(PHP_EOL);
                            print_r("\n Copied {$no} records...");
                            print_r(PHP_EOL);
                        }
                        $no++;
                    }
                } catch (Exception $e) {
                    throw $e;
                }
            } // end foreach models

//                // execute image, info, contact
//                $imageCount = $infoCount = $contactCount = 0;
//                if (count($bulkImage) > 0) {
//                    $ad_image_columns = ['user_id', 'product_id', 'file_name', 'uploaded_at', 'folder'];
//                    $imageCount = AdImages::getDb()->createCommand()
//                        ->batchInsert(AdImages::tableName(), $ad_image_columns, $bulkImage)
//                        ->execute();
//                }
//                if (count($bulkInfo) > 0) {
//                    $ad_info_columns = ['product_id', 'facade_width', 'land_width', 'home_direction', 'facade_direction', 'floor_no', 'room_no', 'toilet_no', 'interior'];
//                    $infoCount = AdProductAdditionInfo::getDb()->createCommand()
//                        ->batchInsert(AdProductAdditionInfo::tableName(), $ad_info_columns, $bulkInfo)
//                        ->execute();
//                }
//                if (count($bulkContact) > 0) {
//                    $ad_contact_columns = ['product_id', 'name', 'phone', 'mobile', 'address', 'email'];
//                    $contactCount = AdContactInfo::getDb()->createCommand()
//                        ->batchInsert(AdContactInfo::tableName(), $ad_contact_columns, $bulkContact)
//                        ->execute();
//                }
//
//                if ($imageCount > 0 && $infoCount > 0 && $contactCount > 0) {
//                    print_r("\nCopied {$no} records to main database success.\n");
//                }

        } else {
            print_r("\nNot found new product. Please, try again!");
        }
        $end = time();
        $time = $end - $begin;
        print_r("\nTime: {$time}s");
    }

}