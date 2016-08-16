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
use yii\helpers\Url;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use vsoft\craw\models\AdProductSearch;
use yii\widgets\LinkPager;
use vsoft\craw\models\AdProductSearch2;
use vsoft\craw\models\AdCategory;
use frontend\models\Elastic;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\Border;
use Box\Spout\Writer\Style\BorderBuilder;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;

class ManagerController extends Controller {
	
	public function actionSearch() {
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$v = \Yii::$app->request->get('value');
		$type = \Yii::$app->request->get('type');
		
		$params = Elastic::buildParams($v);
		
		$params['query']['function_score']['functions'][] = [
			"field_value_factor" => [
				"field" => "total_sell",
				"modifier" => "log1p",
				"factor" => 0.1
			]
		];
		 
		$params['query']['function_score']['functions'][] = [
			"field_value_factor" => [
				"field" => "total_rent",
				"modifier" => "log1p",
				"factor" => 0.1
			]
		];
		
		$result = Elastic::requestResult($params, Elastic::elasticUrl('/' . $type));
		
		$response = [];
		
		foreach ($result['hits']['hits'] as $hit) {
			$response[$hit['_id']] = $hit['_source']['full_name'];
		}
		
		return $response;
	}
	
	public function actionExport() {
		include str_replace('controllers', 'components', dirname(__FILE__)) . '/Spout/Autoloader/autoload.php';
		
		ini_set('max_execution_time', 0);
		
		$fileName = "metvuong-craw.xlsx";
		Yii::$app->language = 'vi-VN';
		
		$writer = WriterFactory::create(Type::XLSX);
		$writer->openToBrowser($fileName);
		
		$searchModel = new AdProductSearch2();
		$provider = $searchModel->search(\Yii::$app->request->queryParams);
		$query = $provider->query;
		$query->orderBy($provider->sort->getAttributeOrders());
		$query->limit = 5000;
		
		foreach ($query->select as &$select) {
			if($select == 'ad_product.content') {
				$select = "IF(`ad_product`.`content` = '', NULL, '...') `ad_product.content`";
			} else if($select == 'ad_product_addition_info.interior') {
				$select = "IF(`ad_product_addition_info`.`interior` IS NULL, NULL, '...') `ad_product_addition_info.interior`";
			}
		}
		
		$columnName = AdProductSearch2::$_columnsName;
		$titles = array_map(function($item) use ($columnName) {
			return $columnName[$item];
		}, array_values($searchModel->columns));
		
		$style = (new StyleBuilder())->setFontSize(11)->build();
		$titleStyle = clone $style;
		$titleStyle->setFontColor(Color::BLUE)->setFontBold();
			
		$writer->addRowWithStyle($titles, $titleStyle);
		
		$type = AdProduct::getAdTypes();
		$categories = ArrayHelper::map(AdCategory::find()->asArray(true)->all(), 'id', function($item) {
			return Yii::t('ad', $item['name']);
		});
		$directions = AdProductAdditionInfo::directionList();
		
		for($i = 0; true; $i += $query->limit) {
				
			$query->offset = $i;
			$products = $query->all(\Yii::$app->dbCraw);
			
			foreach ($products as $product) {
				foreach ($product as $k => &$value) {
					if($value) {
						if($k == 'id') {
							$value = '' . $value;
						} else if($k == 'type') {
							$value = $type[$value];
						} else if($k == 'category_id') {
							$value = $categories[$value];
						} else if($k == 'home_direction') {
							$value = $directions[$value];
						} else if($k == 'facade_direction') {
							$value = $directions[$value];
						} else if($k == 'created_at') {
							$value = date("F d, Y", $value);
						} else if($k == 'id') {
							$value = intval($value);
						} else if($k == 'price' || $k == 'room_no' || $k == 'toilet_no' || $k == 'floor_no' || $k == 'land_width' || $k == 'facade_width') {
							$value = intval($value);
						} else if($k == 'area') {
							$value = floatval($value);
						}
					} else if($value === '0') {
						$value = null;
					}
				}
				$writer->addRowWithStyle(array_values($product), $style);
			}
			
			if(count($products) < $query->limit) {
				break;
			}
		}
		
		setcookie("downloadComplete", 1);
		
		$writer->close();
	}
	
	public function actionIndex() {
		return $this->redirect(Url::to(['/craw/manager/index2']));
		
        $import = Yii::$app->request->get("import");
		$adProduct = new AdProductSearch();
		$provider = $adProduct->search(\Yii::$app->request->queryParams);

		return $this->render('index', [
			'filterModel' => $adProduct,
			'dataProvider' => $provider,
            'import' => $import
		]);
	}
	
	public function actionIndex2() {
		if(!isset($_GET['main_id'])) {
			unset(AdProductSearch2::$_columns['appmi']);
		}
		
		$searchModel = new AdProductSearch2();
		$dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
		
		return $this->render('index2', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider
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

    public function actionImport($totalCount, $page, $filter){
        $params = json_decode($filter, true);
        if(isset($params["page"]))
            $params["page"] = $page;

        $adProduct = new AdProductSearch();
        $provider = $adProduct->search($params);
        $provider->pagination->pageSize = 1000;
//        $provider->setPagination(false); // out of memory error if don't pagination
        $models = $provider->getModels();
        $totalPage = (int)floor($totalCount/1000)+1;
        if(count($models) > 0 && $totalPage >= $page){
            $adProductToolMapIDs = ArrayHelper::index(AdProductToolMap::find()->all(), 'product_tool_id');
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

            $no = 0;
            $count_price = 0;
            foreach ($models as $model) {
                if(empty($model->price) || $model->price < 0){
                    $count_price++;
                    continue;
                }
                if(array_key_exists($model->id, $adProductToolMapIDs)) {
                    $no++;
                    continue;
                }
                else {
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
                }
            }

            print_r("<br>".$no." products exists.");
            print_r("<br>".$count_price." products no price.");
            $countBulkProduct = count($bulkInsertArray);
//            $mod = $countBulkProduct % 1000;
            if ($countBulkProduct > 0) {
                $insertCount = AdProduct::getDb()->createCommand()->batchInsert(AdProduct::tableName(), $columnNameArray, $bulkInsertArray)->execute();
                if ($insertCount > 0) {
                    print_r("<br>Inserted {$insertCount} products done in page {$page}.");
                    $fromProductId = (int)\vsoft\ad\models\AdProduct::getDb()->getLastInsertID();
                    $toProductId = $fromProductId + ($insertCount - 1);

                    $index = 0; // dung de lay nhieu image cho 1 product hoat dong khi da insert vao db
                    for ($i = $fromProductId; $i <= $toProductId; $i++) {
                        if (count($imageArray) > 0 && isset($imageArray[$index])) {
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

                        if (count($infoArray) > 0 && isset($infoArray[$index])) {
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

                        if (count($contactArray) > 0 && isset($contactArray[$index])) {
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

                        if (count($productToolMaps) > 0 && isset($productToolMaps[$index])) {
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
//                        if ($imageCount > 0) {
//                            print_r("<br>Insert image done");
//                        }
                    }
                    if (count($bulkInfo) > 0) {
                        $infoCount = AdProductAdditionInfo::getDb()->createCommand()
                            ->batchInsert(AdProductAdditionInfo::tableName(), $ad_info_columns, $bulkInfo)
                            ->execute();
//                        if ($infoCount > 0) {
//                            print_r("<br>Insert addition info done");
//                        }
                    }
                    if (count($bulkContact) > 0) {
                        $contactCount = AdContactInfo::getDb()->createCommand()
                            ->batchInsert(AdContactInfo::tableName(), $ad_contact_columns, $bulkContact)
                            ->execute();
//                        if ($contactCount > 0) {
//                            print_r("<br>Insert contact info done");
//                        }
                    }

                    // update product tool map
                    if (count($bulkProductToolMap) > 0) {
                        $ptmCount = AdProductToolMap::getDb()->createCommand()
                            ->batchInsert(AdProductToolMap::tableName(), $ad_product_tool_map_columns, $bulkProductToolMap)
                            ->execute();
//                        if ($ptmCount > 0) {
//                            print_r("<br>Insert Product Tool Map done");
//                        }
                    }
                }
            }
            $page = $page+1;
            if($page > $totalPage ){
                print_r("<br><br><span style=\"font-size: 14pt; color: green;\">Import Done.</span>");
            }
            else {
                print_r("<br><br><a href=\"".Url::to(['/craw/manager/import', 'totalCount' => $totalCount, 'page' => $page, 'filter' => json_encode($params)], true)."\" style=\"font-size: 14pt; text-tran; color: blue;\">Continue import page {$page} in {$totalPage} pages.</a>");
            }
        }

        print_r("<br><br><a href=\"".Url::to(['/craw/manager'], true)."\" style=\"font-size: 14pt; color: black;\">Return Index</a>");
    }
}