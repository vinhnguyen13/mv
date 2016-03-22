<?php
namespace vsoft\craw\controllers;

use common\components\Util;
use common\models\AdBuildingProject;
use common\models\AdInvestor;
use vsoft\ad\models\AdContactInfo;
use vsoft\ad\models\AdImages;
use vsoft\ad\models\AdInvestorBuildingProject;
use vsoft\ad\models\AdProduct;
use vsoft\ad\models\AdProductAdditionInfo;
use vsoft\craw\models\AdProductToolMap;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use vsoft\craw\models\AdProductSearch;

class ManagerController extends Controller {
	public function actionIndex() {
		$adProduct = new AdProductSearch();
		$provider = $adProduct->search(\Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'filterModel' => $adProduct,
			'dataProvider' => $provider,
		]);
	}

    public function actionCopyProject(){
        $countProjectPri = AdBuildingProject::find()->count('id');
        if($countProjectPri < 15){
            $dbNameCraw = Util::me()->getDsnAttribute('dbname', Yii::$app->get('dbCraw')->dsn);
            $dbNamePri = Util::me()->getDsnAttribute('dbname', Yii::$app->get('db')->dsn);

            $sql= "INSERT `".$dbNamePri."`.`".AdBuildingProject::tableName()."` SELECT * FROM `".$dbNameCraw."`.`".AdBuildingProject::tableName()."`;";
            $return = Yii::$app->get('db')->createCommand($sql)->execute();
            if(!empty($return)){
                return "Copied data to Building Project";
            } else {
                return "Error: in copying...";
            }
        } else {
            return "Data exists so don't copy Building Project";
        }
    }

    public function actionCopyInvestor(){
        $countProjectPri = AdInvestor::find()->count('id');
        if($countProjectPri < 15){
            $dbNameCraw = Util::me()->getDsnAttribute('dbname', Yii::$app->get('dbCraw')->dsn);
            $dbNamePri = Util::me()->getDsnAttribute('dbname', Yii::$app->get('db')->dsn);

            $sql= "INSERT `".$dbNamePri."`.`".AdInvestor::tableName()."` SELECT * FROM `".$dbNameCraw."`.`".AdInvestor::tableName()."`;";
            $return = Yii::$app->get('db')->createCommand($sql)->execute();
            if(!empty($return)){
                return "Copied data to AdInvestor Project";
            } else {
                return "Error: in copying...";
            }
        } else {
            return "Data exists so don't copy AdInvestor Project";
        }
    }

    public function actionCopyInvestorProject(){
        $countProjectPri = AdInvestorBuildingProject::find()->count('id');
        if($countProjectPri < 15){
            $dbNameCraw = Util::me()->getDsnAttribute('dbname', Yii::$app->get('dbCraw')->dsn);
            $dbNamePri = Util::me()->getDsnAttribute('dbname', Yii::$app->get('db')->dsn);

            $sql= "INSERT `".$dbNamePri."`.`".AdInvestorBuildingProject::tableName()."` SELECT * FROM `".$dbNameCraw."`.`".AdInvestorBuildingProject::tableName()."`;";
            $return = Yii::$app->get('db')->createCommand($sql)->execute();
            if(!empty($return)){
                return "Copied data to AdInvestor Project";
            } else {
                return "Error: in copying...";
            }
        } else {
            return "Data exists so don't copy AdInvestor Project";
        }
    }

    public function actionImport($filter){
        $params = json_decode($filter, true);
        $adProduct = new AdProductSearch();
        $provider = $adProduct->search($params);
        $provider->setPagination(false);

        if($provider->getTotalCount() > 0){
            $adProductToolMapIDs = ArrayHelper::index(AdProductToolMap::find()->all(), 'product_tool_id');
            $models = $provider->getModels();
            $columnNameArray = ['category_id', 'project_building_id', 'user_id', 'home_no',
                'city_id', 'district_id', 'ward_id', 'street_id',
                'type', 'content', 'area', 'price', 'price_type', 'lat', 'lng',
                'start_date', 'end_date', 'verified', 'created_at', 'source'];
            $ad_image_columns = ['user_id', 'product_id', 'file_name', 'uploaded_at'];
            $ad_info_columns = ['product_id', 'facade_width', 'land_width', 'home_direction', 'facade_direction', 'floor_no', 'room_no', 'toilet_no', 'interior'];
            $ad_contact_columns = ['product_id', 'name', 'phone', 'mobile', 'address', 'email'];
            $ad_product_tool_map_columns = ['product_main_id', 'product_tool_id'];

            $imageArray = array();
            $infoArray = array();
            $contactArray = array();
            $productToolMaps = array();

            $bulkInsertArray = array();
            $bulkImage = array();
            $bulkInfo = array();
            $bulkContact = array();
            $bulkProductToolMap = array();

            foreach($models as $key => $model){
                if(!array_key_exists($model->id, $adProductToolMapIDs)) {

                    array_push($imageArray, $model->adImages);

                    if (count($model->adProductAdditionInfo) > 0) {
                        array_push($infoArray, $model->adProductAdditionInfo);
                    }
                    if (count($model->adContactInfo) > 0) {
                        array_push($contactArray, $model->adContactInfo);
                    }

                    array_push($productToolMaps, $model->id);

                    $record = [
                        'category_id' => $model->category_id,
                        'project_building_id' => $model->project_building_id,
                        'user_id' => $model->user_id,
                        'home_no' => $model->home_no,
                        'city_id' => $model->city_id,
                        'district_id' => $model->district_id,
                        'ward_id' => $model->ward_id,
                        'street_id' => $model->street_id,
                        'type' => $model->type,
                        'content' => $model->content,
                        'area' => $model->area,
                        'price' => $model->price,
                        'price_type' => $model->price_type,
                        'lat' => $model->lat,
                        'lng' => $model->lng,
                        'start_date' => $model->start_date,
                        'end_date' => $model->end_date,
                        'verified' => $model->verified,
                        'created_at' => $model->created_at,
                        'source' => $model->source
                    ];
                    $bulkInsertArray[] = $record;
                } else {
                    $no = $key+1;
                    print_r("<br>{$no}. Exists: ".mb_substr($model->content, 0, 100, 'UTF-8'). "...");
                }
            }

            $insertCount = 0;
            if(count($bulkInsertArray) > 0) {
                $insertCount = AdProduct::getDb()->createCommand()->batchInsert(AdProduct::tableName(), $columnNameArray, $bulkInsertArray)->execute();
            }

            if($insertCount > 0){
                print_r("\nInsert products done");
                $fromProductId = (int)\vsoft\ad\models\AdProduct::getDb()->getLastInsertID();
                $toProductId = $fromProductId + ($insertCount - 1);

                $index = 0; // dung de lays nhieu image cho 1 product
                for ($i = $fromProductId; $i <= $toProductId; $i++) {
                    if (count($imageArray) > 0) {
                        foreach ($imageArray[$index] as $image) {
                            if (count($image) > 0 && !empty($image)) {
                                $imageRecord = [
                                    'user_id' => $image->user_id,
                                    'product_id' => $i,
                                    'file_name' => $image->file_name,
                                    'uploaded_at' => $image->uploaded_at
                                ];
                                $bulkImage[] = $imageRecord;
                            }
                        }
                    }

                    if (count($infoArray) > 0) {
                        $infoRecord = [
                            'product_id' => $i,
                            'facade_width' => $infoArray[$index]->facade_width,
                            'land_width' => $infoArray[$index]->land_width,
                            'home_direction' => $infoArray[$index]->home_direction,
                            'facade_direction' => $infoArray[$index]->facade_direction,
                            'floor_no' => $infoArray[$index]->floor_no,
                            'room_no' => $infoArray[$index]->room_no,
                            'toilet_no' => $infoArray[$index]->toilet_no,
                            'interior' => $infoArray[$index]->interior
                        ];
                        $bulkInfo[] = $infoRecord;
                    }

                    if (count($contactArray) > 0) {
                        $contactRecord = [
                            'product_id' => $i,
                            'name' => $contactArray[$index]->name,
                            'phone' => $contactArray[$index]->phone,
                            'mobile' => $contactArray[$index]->mobile == null ? $contactArray[$index]->phone : $contactArray[$index]->mobile,
                            'address' => $contactArray[$index]->address,
                            'email' => $contactArray[$index]->email
                        ];
                        $bulkContact[] = $contactRecord;
                    }

                    if(count($productToolMaps) > 0 ){
                        $ptmRecord = [
                            'product_main_id' => $i,
                            'product_tool_id' => $productToolMaps[$index]
                        ];
                        $bulkProductToolMap[] = $ptmRecord;
                    }

                    $index = $index + 1;
                }

                // execute image, info, contact
                if (count($bulkImage) > 0) {
                    $imageCount = AdImages::getDb()->createCommand()
                        ->batchInsert(AdImages::tableName(), $ad_image_columns, $bulkImage)
                        ->execute();
                    if ($imageCount > 0)
                        print_r("<br>Insert image done");
                }
                if (count($bulkInfo) > 0) {
                    $infoCount = AdProductAdditionInfo::getDb()->createCommand()
                        ->batchInsert(AdProductAdditionInfo::tableName(), $ad_info_columns, $bulkInfo)
                        ->execute();
                    if ($infoCount > 0)
                        print_r("<br>Insert addition info done");
                }
                if (count($bulkContact) > 0) {
                    $contactCount = AdContactInfo::getDb()->createCommand()
                        ->batchInsert(AdContactInfo::tableName(), $ad_contact_columns, $bulkContact)
                        ->execute();
                    if ($contactCount > 0)
                        print_r("<br>Insert contact info done");
                }

                // update product maps in DbMain and DbCraw
                $ptmCount = AdProductToolMap::getDb()->createCommand()
                    ->batchInsert(AdProductToolMap::tableName(), $ad_product_tool_map_columns, $bulkProductToolMap)
                    ->execute();
                if ($ptmCount > 0)
                    print_r("<br>Insert Product Tool Map done");
            }
        }
        print_r("<br><a href=\"javascript:history.back();\" style=\"font-size: 16pt;\">Back</a>");
    }
}