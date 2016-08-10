<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 7/12/2016 9:22 AM
 */

namespace console\models\batdongsan;


use console\models\Metvuong;
use frontend\models\Elastic;
use frontend\models\User;
use vsoft\ad\models\AdContactInfo;
use vsoft\ad\models\AdImages;
use vsoft\ad\models\AdProduct;
use vsoft\ad\models\AdProductAdditionInfo;
use vsoft\craw\models\AdProductFile;
use vsoft\express\components\AdImageHelper;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

class CopyListing extends Component
{
    public $copyListingFolder = "copylisting";
    public static function find()
    {
        return Yii::createObject(CopyListing::className());
    }

    /* End date = Start date Db Crawl + AdProduct::Expired days */
    public function copyToMainDB($validate=0, $limit=300, $check_expired=0){
        $begin = time();
        $sql = "file_name is not null and product_main_id = 0 ";

        if($validate == 1) {
            $sql = $sql . " and lat > 0 and lat is not null and lng > 0 and lng is not null ";
            $sql = $sql . " and price > 0 and area > 0 and city_id > 0 and district_id > 0 and ward_id > 0 and street_id > 0 and (is_expired is null or is_expired = 0)";
        }

        if($check_expired == 1)
            $sql = $sql . " and (start_date + ".AdProduct::EXPIRED.") >= unix_timestamp() ";

        $models = \vsoft\craw\models\AdProduct::find()
            ->where($sql)->limit($limit)->orderBy(['id' => SORT_DESC])->all();

        if(count($models) > 0) {
            $helper = new AdImageHelper();
                foreach ($models as $key => $model) {
                    $connection = AdProduct::getDb();
                    $transaction = $connection->beginTransaction();
                    try {
                        print_r("\n\n". ($key+1). " - Copy: {$model->file_name}");
                        $productImages = $model->adImages;
                        $adProductAdditionInfo = $model->adProductAdditionInfo;
                        if (count($adProductAdditionInfo) <= 0) {
                            continue;
                        }
                        $adContactInfo = $model->adContactInfo;
                        if (count($adContactInfo) <= 0) {
                            continue;
                        }

                        $city_id = empty($model->city_id) ? null : $model->city_id;
                        $district_id = empty($model->district_id) ? null : $model->district_id;
                        $ward_id = empty($model->ward_id) ? null : $model->ward_id;
                        $street_id = empty($model->street_id) ? null : $model->street_id;
                        $home_no = empty($model->home_no) ? null : trim($model->home_no);
                        $lat = empty($model->lat) ? null : $model->lat;
                        $lng = empty($model->lng) ? null : $model->lng;

                        $crawl_project = $model->project;
                        $project_name = $crawl_project->name;

                        $project = \vsoft\ad\models\AdBuildingProject::find()->where([
                            'city_id' => $city_id,
                            'district_id' => $district_id,
                            'name' => $project_name
                        ])->one();

                        if (count($project) > 0) { // lay address theo address project
                            $project_id = $project->id;
                            $city_id = empty($project->city_id) ? (empty($model->city_id) ? null : $model->city_id) : $project->city_id;
                            $district_id = empty($project->district_id) ? (empty($model->district_id) ? null : $model->district_id) : $project->district_id;
                            $ward_id = empty($project->ward_id) ? (empty($model->ward_id) ? null : $model->ward_id) : $project->ward_id;
                            $street_id = empty($project->street_id) ? (empty($model->street_id) ? null : $model->street_id) : $project->street_id;
                            $home_no = empty($project->home_no) ? (empty($model->home_no) ? null : $model->home_no) : trim($project->home_no);
                            $lat = empty($project->lat) ? (empty($model->lat) ? null : $model->lat) : $project->lat;
                            $lng = empty($project->lng) ? (empty($model->lng) ? null : $model->lng) : $project->lng;
                            print_r(" - {$project_name} ");
                        }

                        if ($city_id <= 0 || $district_id <= 0 || $ward_id <= 0 || $street_id <= 0) {
                            continue;
                        }

                        $content = $model->content;

                        $is_expired = 0;
                        $end_date = $model->start_date + AdProduct::EXPIRED;
                        if ($end_date < time()) {
                            $is_expired = 1;
                            print_r(" - expired");
                        }

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
                            'is_expired' => $is_expired
                        ];

                        // download images truoc khi tao product
                        $arrImage = [];
                        if (isset($productImages) && count($productImages) > 0 && $is_expired == 0) {
                            $sessionName = uniqid();
                            $tempFolder = Yii::getAlias('@store') . "/" . $helper->tempFolderName . "/{$this->copyListingFolder}/" . $sessionName . "/";
                            if (!is_dir($tempFolder)) {
                                mkdir($tempFolder, 0777, true);
                            }
                            foreach ($productImages as $key_image => $image) {
                                if (count($image) > 0 && !empty($image)) {
                                    $result = Metvuong::DownloadImage($image->file_name, $image->uploaded_at, $tempFolder);
                                    $arrImage[$key_image] = $result;
                                    usleep(50000);
                                }
                            }
                        }
                        $total_images = count($arrImage);
                        print_r(" - Total images: {$total_images}");

                        $product = new AdProduct($record);
                        $score = AdProduct::calcScore($product, $adProductAdditionInfo, $adContactInfo, count($productImages));
                        $product->score = $score;

                        $email = $adContactInfo->email;
                        if(!empty($email)) {
                            $user = Yii::$app->getDb()->cache(function() use($email){
                                return User::find()->where(['email' => $email])->one();
                            });
                            if (!empty($user->id)) {
                                $product->user_id = $user->id;
                                print_r(" - User: ".$user->id);
                            }
                        }

                        if ($product->save(false)) {
                            $last_product_id = $product->id;
                            $model->product_main_id = $last_product_id;
                            $model->update(false);
                            print_r(" - Product: {$last_product_id}");
                            print_r(" - City: {$product->city_id}");
                            // update ad_product_file
                            $product_file = AdProductFile::find()->where(['file' => $model->file_name])->one();
                            if ($product_file) {
                                $product_file->is_copy = 1;
                                $product_file->copied_at = time();
                                $product_file->product_main_id = $last_product_id;
                                $product_file->updated_at = time();
                                $product_file->save(false);
                            } else {
                                print_r("\nCannot copy because file_name: {$model->file_name} not exists AdProductFile");
                                continue;
                            }

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
                                if($adInfo->save(false)){
                                    print_r(" - Addition");
                                }
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
                                if($adContact->save(false)){
                                    print_r(" - Contact");
                                }
                            }

                            // product image
                            $list_image_id = null;
                            if (isset($productImages) && count($productImages) > 0 && $is_expired == 0) {
                                foreach ($productImages as $key_image => $image) {
                                    if (count($image) > 0 && !empty($image) && count($arrImage) > 0) {
                                        $resultImage = $arrImage[$key_image];
                                        $file_image = isset($resultImage[0]) && !empty($resultImage[0]) ? $resultImage[0] : null;
                                        $folderColumn = isset($resultImage[1]) && !empty($resultImage[1]) ? $resultImage[1] : null;
                                        if (empty($file_image) && empty($folderColumn))
                                            continue;

                                        $imageRecord = [
                                            'user_id' => $image->user_id,
                                            'product_id' => $last_product_id,
                                            'file_name' => !empty($file_image) ? $file_image : $image->file_name,
                                            'uploaded_at' => $image->uploaded_at,
                                            'folder' => $folderColumn
                                        ];
                                        $adImage = new AdImages($imageRecord);
                                        if ($adImage->save(false)) {
                                            $oldFolder = isset($resultImage[2]) && !empty($resultImage[2]) ? $resultImage[2] : null;
                                            $newFolder = Yii::getAlias('@store') . "/" . $folderColumn;
                                            if (!is_dir($newFolder)) {
                                                mkdir($newFolder, 0777, true);
                                            }
                                            $helper->makeFolderSizes($newFolder);
                                            $helper->moveTempFile($oldFolder, $newFolder, $file_image);
                                            $image_id = $key_image == 0 ? $adImage->id : ", ". $adImage->id;
                                            $list_image_id = $list_image_id. $image_id;
                                        }
                                    }
                                }
                            }
                            if(!empty($list_image_id)){
                                print_r(" - Images: {$list_image_id}");
                            }
                            if ($is_expired == 0) {
                                $product->insertEs(); // insert elastic
                            }
                        }
                        echo "<pre>";
                        print_r($key);
                        echo "</pre>";
                        exit;
                        $transaction->commit();
                    } catch (Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    } // end try-catch block
                } // end foreach models


            $copylisting_folder = Yii::getAlias('@store') . "/" . $helper->tempFolderName . "/{$this->copyListingFolder}";
            if (is_dir($copylisting_folder)) {
                $this->removeDirectory($copylisting_folder);
            }

        } else {
            print_r("\nNot found new product. Please, try again!");
        }
        $end = time();
        $time = $end - $begin;
        print_r("\nTime: {$time}s");
    }

    public function removeDirectory($path) {
        $files = glob($path . '/*');
        foreach ($files as $file) {
            is_dir($file) ? $this->removeDirectory($file) : unlink($file);
        }
        rmdir($path);
        return;
    }

}