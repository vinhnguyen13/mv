<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 7/12/2016 9:22 AM
 */

namespace console\models\batdongsan;


use console\models\Metvuong;
use frontend\models\Elastic;
use vsoft\ad\models\AdContactInfo;
use vsoft\ad\models\AdImages;
use vsoft\ad\models\AdProduct;
use vsoft\ad\models\AdProductAdditionInfo;
use vsoft\ad\models\AdStreet;
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
    public function copyToMainDB($validate=0, $limit=300){
        $begin = time();
        $sql = "file_name is not null and product_main_id = 0 ";

        if($validate == 1) {
            $sql = $sql ." and price > 0 and area > 0 and city_id > 0 and district_id > 0 and ward_id > 0 and street_id > 0 and (is_expired is null or is_expired = 0)";
        }

        $models = $models = \vsoft\craw\models\AdProduct::find()
            ->where($sql)->limit($limit)->orderBy(['id' => SORT_ASC])->all();

        if(count($models) > 0){
            $no = 0;
            $arrElastic = [];
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
                $lat = empty($model->lat) ? null : $model->lat;
                $lng = empty($model->lng) ? null : $model->lng;

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
                    $lat = empty($project->lat) ? (empty($model->lat) ? null : $model->lat) : $project->lat;
                    $lng = empty($project->lng) ? (empty($model->lng) ? null : $model->lng) : $project->lng;
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

                $end_date = $model->start_date + 30 * 86400;
//                if($end_date < time()) {
//                    $model->is_expired = 1;
//                    $model->save(false);
//                    print_r(" is expired");
//                    continue;
//                }

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
                    'lat' => $lat,
                    'lng' => $lng,
                    'start_date' => $model->start_date,
                    'end_date' => $end_date,
                    'verified' => 1,
                    'created_at' => $model->created_at,
                    'updated_at' => $model->created_at,
                    'source' => $model->source,
                    'status' => 1,
                    'is_expired' => 0
                ];

                $product = new AdProduct($record);
                $record['score'] = AdProduct::calcScore($product, $adProductAdditionInfo, $adContactInfo, count($productImages));
                $connection = AdProduct::getDb();
                try {
                    $connection->createCommand()
                        ->insert('ad_product', $record)
                        ->execute();

                    $last_product_id = (int)$connection->getLastInsertID();
                    if ($last_product_id > 0) {
                        $model->product_main_id = $last_product_id;
                        $model->update(false);

                        // product additional info
                        $infoRecord = null;
                        if (isset($adProductAdditionInfo) && count($adProductAdditionInfo) > 0) {
                            $infoRecord = [
                                'product_id' => $last_product_id,
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
                                'product_id' => $last_product_id,
                                'name' => $adContactInfo->name,
                                'phone' => $adContactInfo->phone,
                                'mobile' => $adContactInfo->mobile == null ? $adContactInfo->phone : $adContactInfo->mobile,
                                'address' => $adContactInfo->address,
                                'email' => $adContactInfo->email
                            ];
                            $adContact = new AdContactInfo($contactRecord);
                            $adContact->save(false);
                        }

                        // product image
                        $first_image_path = '';
                        if (isset($productImages) && count($productImages) > 0) {
                            foreach ($productImages as $key_image => $image) {
                                if (count($image) > 0 && !empty($image)) {
                                    $result = Metvuong::DownloadImage($image->file_name, $image->uploaded_at);
                                    $imageRecord = [
                                        'user_id' => $image->user_id,
                                        'product_id' => $last_product_id,
                                        'file_name' => $result[0],
                                        'uploaded_at' => $image->uploaded_at,
                                        'folder' => $result[1]
                                    ];
                                    if ($key_image == 0) {
                                        $first_image_path = "/store/{$result[1]}/240x180/{$result[0]}";
                                    }
                                    $adImage = new AdImages($imageRecord);
                                    $adImage->save(false);
                                }
                            }
                        }

                        $project_id = empty($project_id) ? 0 : $project_id;
                        $address = '';
                        $street_id = empty($street_id) ? 0 : $street_id;
                        $street = AdStreet::find()->where(['id' => $street_id])->asArray()->one();
                        if (count($street) > 0) {
                            $address = $street['pre'] . " " . $street['name'];
                            if (!empty($home_no)) {
                                $address = $home_no . " " . $address;
                            }
                        }
                        $arrElastic[] = [
                            "id" => $last_product_id,
                            "category_id" => $record['category_id'],
                            "project_building_id" => $project_id,
                            "project_building" => $project_id > 0 ? $project->name : "",
                            "user_id" => 0,
                            "city_id" => empty($city_id) ? 0 : $city_id,
                            "district_id" => empty($district_id) ? 0 : $district_id,
                            "ward_id" => empty($ward_id) ? 0 : $ward_id,
                            "street_id" => $street_id,
                            "address" => $address,
                            "type" => $record['type'],
                            "area" => $record['area'],
                            "price" => $record['price'],
                            "location" => [
                                "lat" => empty($lat) ? 0 : $lat,
                                "lon" => empty($lng) ? 0 : $lng,
                            ],
                            "score" => empty($score) ? 0 : $score,
                            "start_date" => $record['price'],
                            "boost_time" => 0,
                            "boost_start_time" => 0,
                            "boost_sort" => 0,
                            "facade_width" => isset($adProductAdditionInfo) &&  empty($adProductAdditionInfo->facade_width) ? 0 : $adProductAdditionInfo->facade_width,
                            "land_width" => isset($adProductAdditionInfo) &&  empty($adProductAdditionInfo->land_width) ? 0 : $adProductAdditionInfo->land_width,
                            "home_direction" => isset($adProductAdditionInfo) &&  empty($adProductAdditionInfo->home_direction) ? 0 : $adProductAdditionInfo->home_direction,
                            "facade_direction" => isset($adProductAdditionInfo) &&  empty($adProductAdditionInfo->facade_direction) ? 0 : $adProductAdditionInfo->facade_direction,
                            "floor_no" => isset($adProductAdditionInfo) &&  empty($adProductAdditionInfo->floor_no) ? 0 : $adProductAdditionInfo->floor_no,
                            "room_no" => isset($adProductAdditionInfo) &&  empty($adProductAdditionInfo->room_no) ? 0 : $adProductAdditionInfo->room_no,
                            "toilet_no" => isset($adProductAdditionInfo) &&  empty($adProductAdditionInfo->toilet_no) ? 0 : $adProductAdditionInfo->toilet_no,
                            "img" => $first_image_path
                        ];

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

            /**
             * Ham lưu elastic by Lệnh
             */
            Elastic::insertProducts(\Yii::$app->params['indexName']['product'], Elastic::$productEsType, $arrElastic);

        } else {
            print_r("\nNot found new product. Please, try again!");
        }
        $end = time();
        $time = $end - $begin;
        print_r("\nTime: {$time}s");
    }

}