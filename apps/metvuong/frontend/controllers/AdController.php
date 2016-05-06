<?php

namespace frontend\controllers;
use frontend\components\Controller;
use frontend\models\Ad;
use frontend\models\Profile;
use frontend\models\ShareForm;
use frontend\models\Tracking;
use frontend\models\UserActivity;
use vsoft\ad\models\AdCategory;
use vsoft\ad\models\AdContactInfo;
use vsoft\ad\models\AdDistrict;
use vsoft\ad\models\AdImages;
use vsoft\ad\models\AdProduct;
use vsoft\ad\models\AdProductAdditionInfo;
use vsoft\ad\models\AdProductReport;
use vsoft\express\components\ImageHelper;
use vsoft\express\components\StringHelper;
use vsoft\news\models\CmsShow;
use vsoft\news\models\Status;
use vsoft\tracking\models\base\AdProductShare;
use Yii;
use yii\base\Exception;
use yii\data\Pagination;
use yii\db\IntegrityException;
use yii\helpers\Url;
use yii\web\Response;
use yii\widgets\LinkPager;
use frontend\models\ProfileForm;
use vsoft\ad\models\AdProductSaved;
use vsoft\ad\models\AdCity;
use vsoft\ad\models\AdWard;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use yii\web\Session;
use vsoft\express\components\AdImageHelper;
use vsoft\ad\models\AdStreet;
use vsoft\ad\models\AdBuildingProject;
use frontend\models\AdProductSearch;
use yii\db\ActiveRecord;
use frontend\models\Elastic;

class AdController extends Controller
{
    public $layout = '@app/views/layouts/layout';

	/**
	 * @return string
	 */
	public function beforeAction($action)
	{
		$this->view->params['noFooter'] = true;
		
		if(!Yii::$app->request->isAjax) {
			\lajax\translatemanager\helpers\Language::registerAssets();
		}
		
		return parent::beforeAction($action);
	}
    
    public function actionUpload() {
        //sleep(500);
        if($_FILES) {
    		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    		
    		$image = UploadedFile::getInstanceByName('upload');
    		
    		$response = [
				'thumbnailUrl' => 'http://support.xbox.com/Content/Images/ECLT_Exclamation_40x40.png',
				'name' => $image->name
    		];
    		
    		if($image->extension != "jpg" && $image->extension != "png" && $image->extension != "jpeg") {
    			$response['error'] = \Yii::t('ad', 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.');
    		} else if($image->size > 4194304) {
    			$response['error'] = \Yii::t('ad', 'File too large. File must be less than 4 megabytes.');
    		} else {
    			$helper = new AdImageHelper();
    			
    			$sessionFolder = Yii::createObject(Session::className())->getId();
    			$tempFolder = $helper->getTempFolderPath($sessionFolder);
    			
    			if(!file_exists($tempFolder)) {
    				mkdir($tempFolder, 0777);
    				$helper->makeFolderSizes($tempFolder);
    			}
    			
    			$fileName = uniqid() . '.' . $image->extension;
    			$filePath = $tempFolder . DIRECTORY_SEPARATOR . $fileName;
    			
    			$image->saveAs($filePath);
    			$helper->resize($filePath);
    			
    			$tempUrl = "/store/{$helper->tempFolderName}/{$helper->adFolderName}/$sessionFolder/";
    			
    			$response['name'] = $fileName;
    			$response['url'] = Url::to($tempUrl . $fileName);
    			$response['thumbnailUrl'] = Url::to($tempUrl . $helper->makeFolderName(AdImageHelper::$sizes['thumb']) . '/' . $fileName);
    			$response['deleteUrl'] = Url::to(['delete-temp-file', 'file' => $fileName]);
    			$response['deleteType'] = 'DELETE';
    		}
    		
    		return ['files' => [$response]];
    	}
    }
    
    public function actionDeleteTempFile($file) {
    	Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	
    	$helper = new AdImageHelper();
    	
    	$sessionFolder = Yii::createObject(Session::className())->getId();
    	 
    	$tempFolder = $helper->getTempFolderPath($sessionFolder);
    	
    	$helper->removeTempFile($tempFolder, $file);
    	
    	return ['files' => []];
    }

    public function actionDeleteFile($file) {
    	Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	
    	return ['files' => []];
    }

    public function actionGetMarkers() {
    	Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	
    	$model = new AdProductSearch();
    	$query = $model->search(Yii::$app->request->get());

    	$query->addSelect('ad_product.created_at, ad_product.category_id, ad_product.type, ad_images.file_name, ad_images.folder');
    	$query->leftJoin('ad_images', 'ad_images.order = 0 AND ad_images.product_id = ad_product.id');
    	
    	return $query->indexBy('id')->asArray(true)->all();
    }

    function actionGetProject($id) {
    	$model = AdBuildingProject::find()->where('`id` = :id', [':id' => $id])->one();
    
    	return $this->renderPartial('_partials/projectInfo', ['project' => $model]);
    }
    
    function actionGetGeometry($cityId) {
    	Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	
    	$seconds = 24 * 60 * 60 * 30;
    	
    	header("Cache-Control: public, max-age=$seconds");
    	header("Expires: " . gmdate('r', time() + $seconds));
    	header("Pragma: cache");
    	
    	$response = [];
    	
		$response['city'] = AdCity::find()->indexBy('id')->select('center, geometry, name, id')->where(['id' => $cityId])->all();
		$response['district'] = AdDistrict::find()->indexBy('id')->select('center, geometry, name, id, pre, city_id')->where(['city_id' => $cityId])->all();
		$response['ward'] = AdWard::find()->indexBy('id')->select('center, geometry, name, id, pre, district_id')->where(['district_id' => ArrayHelper::getColumn($response['district'], 'id')])->all();
		$response['street'] = AdStreet::find()->indexBy('id')->select('name, id, pre, geometry, center')->where(['district_id' => ArrayHelper::getColumn($response['district'], 'id')])->all();
					
		return $response;
    }
    
    public function actionIndex() {
		$this->view->params['body'] = [
			'class' => 'ad-listing'
		];
		if($type = Yii::$app->request->get('type')) {
			$this->view->params['menuBuy'] = (!empty($type) && $type==1) ? true : false;
			$this->view->params['menuRent'] = (!empty($type) && $type==2) ? true : false;
		}
		
		$model = new AdProductSearch();
		$query = $model->search(Yii::$app->request->get());
		$query->addSelect('ad_product.created_at, ad_product.category_id, ad_product.type, ad_images.file_name, ad_images.folder');
		$query->leftJoin('ad_images', 'ad_images.order = 0 AND ad_images.product_id = ad_product.id');
		$query->groupBy('ad_product.id');
		
		$countQuery = clone $query;
		$pages = new Pagination(['totalCount' => $countQuery->count()]);
		$pages->setPageSize(Yii::$app->params['listingLimit']);
			
		$products = $query->with(['city', 'district', 'ward', 'street'])->offset($pages->offset)->limit($pages->limit)->all();
		
		if(Yii::$app->request->isAjax) {
			Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			
			$categories = AdCategory::find ()->indexBy ( 'id' )->asArray ( true )->all ();
			
			return ['items' => $this->renderPartial('_partials/list', ['products' => $products, 'categories' => $categories]), 'total' => intval($pages->totalCount)];
		} else {
			$model->fetchValues();
			
			return $this->render('index', ['searchModel' => $model, 'pages' => $pages, 'products' => $products]);
		}
    }
    
    public function actionSavedListing() {
    	if(!Yii::$app->user->isGuest) {
    		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    		
    		$query = AdProductSaved::find();
    		$query->select('ad_product.id, ad_product.city_id, ad_product.district_id, ad_product.ward_id, ad_product.street_id, ad_product.lat, ad_product.lng,
        					ad_product.home_no, ad_product.category_id, ad_product.price, ad_product.area, ad_product.created_at, ad_product.type,
        					ad_product_addition_info.floor_no, ad_product_addition_info.room_no, ad_product_addition_info.toilet_no, ad_images.file_name');
    		$query->innerJoin('ad_product', 'ad_product.id = ad_product_saved.product_id');
    		$query->innerJoin('ad_product_addition_info', 'ad_product_addition_info.product_id = ad_product.id');
    		$query->leftJoin('ad_images', 'ad_images.order = 0 AND ad_images.product_id = ad_product.id');
    		$query->where(['ad_product_saved.user_id' => Yii::$app->user->id]);
    		
    		$rawProducts = $query->andWhere(['!=', 'saved_at', 0])->asArray(true)->groupBy('ad_product.id')->all();
    		
    		$products = [];
    		
    		foreach ($rawProducts as $k => $product) {
    			$products[$k] = $product;
    			$products[$k]['previous_time'] = StringHelper::previousTime($product['created_at']);
    		}
    		
    		return $products;
    	}
    }
    
    public function actionDetail($id) {

    	$product = AdProduct::findOne($id);
        try{
			if($type = $product->type) {
				$this->view->params['menuBuy'] = (!empty($type) && $type==1) ? true : false;
				$this->view->params['menuRent'] = (!empty($type) && $type==2) ? true : false;
			}
            if(Yii::$app->user->id != $product->user_id) {
//                if(isset(Yii::$app->params['tracking']['all']) && Yii::$app->params['tracking']['all'] == true) {
//                    Tracking::find()->productVisitor(Yii::$app->user->id, $id, time());
//                }
				UserActivity::me()->saveActivity(UserActivity::ACTION_AD_CLICK, [
					'owner' => Yii::$app->user->id,
					'product' => $product->id,
					'buddy' => $product->user_id
				], $product->id);
            }
        } catch(Exception $ex){

        }
        
        return $this->render('detail', ['product' => $product]);
        
// 		if(Yii::$app->request->isAjax){
// 			return $this->renderAjax('_partials/detail', ['product' => $product]);
// 		}else{
// 			return $this->render('detail', ['product' => $product]);
// 		}

    }

    /**
     * @return \yii\web\Response
     */
    public function actionRedirect()
    {
		$url = Ad::find()->redirect();
        $this->redirect($url);
    }

    
    public function actionPost() {
		$this->view->params['menuSell'] = true;
//     	if(Yii::$app->mobileDetect->isMobile()) {
//     		return $this->postMobile();
//     	} else {
//     		return $this->post();
//     	}
    	return $this->postMobile();
    }
    
    public function actionUpdate($id) {
    	if(Yii::$app->user->isGuest) {
    		return $this->render('/_systems/require_login');
    	}
    	
    	$product = AdProduct::find()->where('id = :id AND user_id = :uid', [':id' => $id, ':uid' => Yii::$app->user->identity->id])->one();
    	
    	if($product) {
    		$additionInfo = $product->adProductAdditionInfo;
    		$contactInfo = $product->adContactInfo;
    		
    		if(Yii::$app->request->isPost) {
    			Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    			$post = Yii::$app->request->post();
    			
    			//$post['AdProductAdditionInfo']['addition_fields'] = json_encode($post['AdProductAdditionInfo']['addition_fields']);

    			$product->load($post);
    			$additionInfo->load($post);
    			$contactInfo->load($post);
    			
    			$result = ['success' => true];
    			
    			if($product->validate() && $additionInfo->validate() && $contactInfo->validate()) {
    				$product->save(false);
    				$additionInfo->save(false);
    				$contactInfo->save(false);
    				
    				$oldImages = ArrayHelper::map($product->adImages, 'file_name', 'id');
    				
    				if(isset($post['images'])) {
    					$addImages = [];
    					
    					foreach ($post['images'] as $order => $name) {
    						if(isset($oldImages[$name])) {
    							$image = $product->adImages[$oldImages[$name]];
    							if($image->order != $order) {
    								$image->order = $order;
    								$image->save(false);
    							}
    							unset($oldImages[$name]);
    						} else {
    							$addImages[$order] = $name;
    						}
    					}
    					
    					if($addImages) {
    						$helper = new AdImageHelper();
    						$tempFolder = $helper->getTempFolderPath(Yii::createObject(Session::className())->getId());
    						
    						$now = time();
    						
    						$newFolderAbsolute = $helper->getAbsoluteUploadFolderPath($now);
    						$newFolder = $helper->getUploadFolderPath($newFolderAbsolute);
    						
    						$newFolderAbsoluteUrl = str_replace(DIRECTORY_SEPARATOR, '/', $newFolderAbsolute);
    						
    						if(!file_exists($newFolder)) {
    							mkdir($newFolder, 0777, true);
    							$helper->makeFolderSizes($newFolder);
    						}
    						
    						foreach($addImages as $k => $image) {
    							$helper->moveTempFile($tempFolder, $newFolder, $image);
    							 
    							$adImage = new AdImages();
    							$adImage->user_id = Yii::$app->user->id;
    							$adImage->product_id = $product->id;
    							$adImage->file_name = $image;
    							$adImage->uploaded_at = $now;
    							$adImage->order = $k;
    							$adImage->folder = $newFolderAbsoluteUrl;
    							$adImage->save(false);
    						}
    					}
    				}

    				foreach ($oldImages as $id) {
    					$product->adImages[$id]->delete();
    				}
    				
    				$result['url'] = $product->urlDetail();
    			} else {
    				$result['success'] = false;
    				$result['errors'] = [
	    				'product' => $product->getErrors(),
	    				'additionInfo' => $additionInfo->getErrors(),
	    				'contactInfo' => $contactInfo->getErrors()
    				];
    			}
    			
    			return $result;
    		}
    		 
    		return $this->render('form', ['product' => $product, 'additionInfo' => $additionInfo, 'contactInfo' => $contactInfo]);
    	}
    }
    
    public function postMobile() {
    	if(Yii::$app->user->isGuest) {
    		return $this->render('/_systems/require_login');
    	}
    	
    	$product = new AdProduct();
    	$additionInfo = new AdProductAdditionInfo();
    	$contactInfo = new AdContactInfo();
    	
    	if(Yii::$app->request->isPost) {
    		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    		
    		$post = Yii::$app->request->post();

    		//$post['AdProductAdditionInfo']['addition_fields'] = json_encode($post['AdProductAdditionInfo']['addition_fields']);
    		
    		$product->load($post);
    		$additionInfo->load($post);
    		$contactInfo->load($post);
    		
    		$result = ['success' => true];
    		
    		if($product->validate() && $additionInfo->validate() && $contactInfo->validate()) {
    			$product->user_id = Yii::$app->user->id;
    			$product->save(false);
    			
    			$additionInfo->product_id = $product->id;
    			$additionInfo->save(false);
    				
    			$contactInfo->product_id = $product->id;
    			$contactInfo->save(false);
    			
    			$profileForm = new ProfileForm();
    			$profileForm->compareToUpdate($post['AdContactInfo']);
    			
    			if(!empty($post['images'])) {
    				$helper = new AdImageHelper();
    				$tempFolder = $helper->getTempFolderPath(Yii::createObject(Session::className())->getId());
    				
    				$now = time();
    				
    				$newFolderAbsolute = $helper->getAbsoluteUploadFolderPath($now);
    				$newFolder = $helper->getUploadFolderPath($newFolderAbsolute);
    				
    				$newFolderAbsoluteUrl = str_replace(DIRECTORY_SEPARATOR, '/', $newFolderAbsolute);
    				
    				if(!file_exists($newFolder)) {
    					mkdir($newFolder, 0777, true);
    					$helper->makeFolderSizes($newFolder);
    				}
    				
    				foreach($post['images'] as $k => $image) {
    					$helper->moveTempFile($tempFolder, $newFolder, $image);
    					
    					$adImage = new AdImages();
    					$adImage->user_id = Yii::$app->user->id;
    					$adImage->product_id = $product->id;
    					$adImage->file_name = $image;
    					$adImage->uploaded_at = $now;
    					$adImage->order = $k;
    					$adImage->folder = $newFolderAbsoluteUrl;
    					$adImage->save(false);
    				}
    			}
    			
    			$result['url'] = $product->urlDetail();
    		} else {
    			$result['success'] = false;
    			$result['errors'] = [
					'product' => $product->getErrors(),
					'additionInfo' => $additionInfo->getErrors(),
					'contactInfo' => $contactInfo->getErrors()
				];
    		}
    		
    		return $result;
    	} else {
    		$product->loadDefaultValues();
    		
    		$contactInfo->name = Yii::$app->user->identity->profile->name;
    		$contactInfo->mobile = Yii::$app->user->identity->profile->mobile;
    		$contactInfo->email = Yii::$app->user->identity->profile->public_email;
    		$contactInfo->address = Yii::$app->user->identity->profile->address;
    	}
    	
    	return $this->render('form', ['product' => $product, 'additionInfo' => $additionInfo, 'contactInfo' => $contactInfo]);
    }
    
    /**
     * @return string
     */
    public function post()
    {
    	if(Yii::$app->user->isGuest) {
    		return $this->render('/_systems/require_login');
    	}
    	
		$type = Yii::$app->request->get('type');
    	$cityId = Yii::$app->request->get('city');
    	$districtId = Yii::$app->request->get('district');
    	$categoryId = Yii::$app->request->get('category');
	    		
		$district = AdDistrict::findOne(['`id`' => $districtId, '`city_id`' => $cityId, '`status`' => 1]);
	    		 
	    if($district) {
	    	$category = AdCategory::findOne(['`id`' => $categoryId, '`status`' => 1]);
	    		
	    	if($category && ($category->apply_to_type == AdCategory::APPLY_TO_TYPE_BOTH || $category->apply_to_type == $type)) {
				$model = new AdProduct();
				$model->loadDefaultValues();
    				$model->city_id = $cityId;
    				$model->district_id = $districtId;
    				$model->category_id = $categoryId;
    				$model->type = $type;
    		
    				$adProductAdditionInfo = $model->adProductAdditionInfo ? $model->adProductAdditionInfo : (new AdProductAdditionInfo())->loadDefaultValues();
    				$adContactInfo = $model->adContactInfo ? $model->adContactInfo : (new AdContactInfo())->loadDefaultValues();
    				 
    				if(Yii::$app->request->isPost) {
    					Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    		
    					$post = Yii::$app->request->post();
    		
    					$model->load($post);
    					$model->start_date = time();
    					$model->end_date = $model->start_date + (24 * 60 * 60);
    					$model->created_at = $model->start_date;
    		
    					$adProductAdditionInfo->load($post);
    					$adContactInfo->load($post);
    		
    					if($model->validate() && $adProductAdditionInfo->validate() && $adContactInfo->validate()) {
    						$model->user_id = Yii::$app->user->id;
    						$model->save(false);
    				   
    						$adProductAdditionInfo->product_id = $model->id;
    						$adProductAdditionInfo->save(false);
    				   
    						$adContactInfo->product_id = $model->id;
    						$adContactInfo->save();
    						
    						$profileForm = new ProfileForm();
    						$profileForm->compareToUpdate($post['AdContactInfo']);
    				   
    						if(isset($post['images']) && $post['images']) {
    							$images = explode(',', $post['images']);
    							foreach($images as $k => $image) {
    								if(ctype_digit($image)) {
    									Yii::$app->db->createCommand()->update('ad_images', ["product_id" => $model->id, "order" => $k], "`id` = $image AND user_id = " . Yii::$app->user->id)->execute();
    								}
    							}
    						}
    				   
    						$result = ['success' => true];
    					} else {
    						$result = ['success' => false, 'errors' => ['adproduct' => $model->getErrors(), 'adproductadditioninfo' => $adProductAdditionInfo->getErrors(), 'adcontactinfo' => $adContactInfo->getErrors()]];
    					}
					return $result;
				}
				return $this->render('post', ['model' => $model, 'adProductAdditionInfo' => $adProductAdditionInfo, 'adContactInfo' => $adContactInfo, 'category' => $category]);
			}
	    }
	    		
		return $this->render('requireParam', [
			'type' => $type,
			'cityId' => $cityId,
			'districtId' => $districtId,
			'categoryId' => $categoryId
		]);
    }

	public function actionPostListing()
	{
		return $this->render('post/index');
	}
    
    public function actionDeleteImage($id) {
    	$image = AdImages::findOne($id);
    	if($image) {
    		$pathInfo = pathinfo($image->file_name);
    		$thumb = $pathInfo['filename'] .  '.thumb.' . $pathInfo['extension'];
    		
    		$response = Yii::$app->runAction('express/upload/delete-image', ['orginal' => $image->file_name, 'thumbnail' => $thumb, 'folder' => 'ad', 'resizeForAds' => true]);
    		
    		$image->delete();
    		
    		return $response;
    	}
    }

	public function actionFavorite() {
		return Ad::find()->favorite();
	}

	public function actionReport() {
		return Ad::find()->report();
	}

	public function actionRating() {
		return Ad::find()->rating();
	}

    public function actionSendreport(){
        if(Yii::$app->request->isPost && Yii::$app->request->isAjax) {
//            Yii::$app->response->format = Response::FORMAT_JSON;
            $post = Yii::$app->request->post();
            if (!empty($post["uid"]) && !empty($post["pid"]) && !empty($post["optionsRadios"])) {
                try {
                    $product_report = AdProductReport::find()->where(['user_id' => (int)$post["uid"]])->andWhere(['product_id' => (int)$post["pid"]])
                                    ->orderBy(['report_at' => SORT_DESC])->one();
                    if(!$product_report)
                        $product_report = new AdProductReport();

                    $product_report->user_id = $post["uid"];
                    $product_report->product_id = $post["pid"];
                    $type = (int)$post["optionsRadios"];
                    $product_report->type = $type;
                    if($type == -1 && !empty($post["description"])){
                        $product_report->description = $post["description"];
                    }
                    $product_report->ip = Yii::$app->getRequest()->getUserIP();;
                    $product_report->status = Status::STATUS_ACTIVE;
                    $product_report->report_at = time();
                    if ($product_report->save())
                        return 200;
                } catch(IntegrityException $ie){
                    if($ie->getMessage())
                        return 13 . " - " . $ie->getMessage();
                }
            }
        }
        return 404;
    }

	public function actionSendmail(){
		if(Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
			$post = Yii::$app->request->post();
            if(count($post) <= 0)
                return ['status' => 400, 'parameters' => 'Post variable not found'];

            $model = Yii::createObject([
                'class'    => ShareForm::className(),
                'scenario' => 'share',
            ]);
            $model->load($post);
            $model->validate();

            if (!$model->hasErrors()) {

                $from_name = !empty($model->from_name) ? $model->from_name : $model->your_email;
                $type_email = $model->type == "share" ? "chia sẻ" : "liên hệ với bạn qua";

                // send to
                $subjectEmail = "Metvuong.com - {$from_name} {$type_email} tin {$model->pid}";
                $result = Yii::$app->mailer->compose(['html' => '../mail/notifyReceivedEmail-html',], ['contact' => $model])
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setTo([trim($model->recipient_email)])
                ->setSubject($subjectEmail)
                ->send();
                if($result){
                    $send_from = isset($post["send_from"]) ? $post["send_from"] : null;
                    $from_email = trim($model->your_email);
                    $to_email = trim($model->recipient_email);
                    $subject = trim($model->subject);
                    $content = trim($model->content);
                    Tracking::find()->saveEmailLog($from_name, $from_email, $to_email, $subject, $content, $send_from);
                }
                return ['status' => 200, 'result' => $result];
            } else {
                return ['status' => 404, 'parameters' => $model->errors];
            }
		}
        return ['status' => 400, 'parameters' => 'Send mail error. Please, try again later'];
	}
	
	public function actionGeocode() {
		$token = \Yii::$app->request->post('token') ? \Yii::$app->request->post('token') : \Yii::$app->request->get('token');
		
		if($token && $token == '2UWa0KRnCQAON9Js6kae') {
			if(Yii::$app->request->isPost) {
				Yii::$app->response->format = Response::FORMAT_JSON;
				
				$data = \Yii::$app->request->post('data');
				
				if(isset($data[0])) {
					$city = $data[0][0];
					\Yii::$app->db->createCommand()->update('ad_city', ['center' => $city['latLng']], 'id = :id', [':id' => $city['id']])->execute();
				}
				
				if(isset($data[1])) {
					$districts = $data[1];
					foreach ($districts as $district) {
						\Yii::$app->db->createCommand()->update('ad_district', ['center' => $district['latLng']], 'id = :id', [':id' => $district['id']])->execute();
					}
				}
				
				if(isset($data[1])) {
					$wards = $data[2];
					foreach ($wards as $ward) {
						\Yii::$app->db->createCommand()->update('ad_ward', ['center' => $ward['latLng']], 'id = :id', [':id' => $ward['id']])->execute();
					}
				}
				
				return [];
			}
			
			return $this->render('geocode');
		}
	}
	
	public function actionListDistrict($cityId) {
		Yii::$app->response->format = Response::FORMAT_JSON;
		
		return AdDistrict::getListByCity($cityId);
	}
	
	public function actionListSwp($districtId) {
		Yii::$app->response->format = Response::FORMAT_JSON;
		
		$response = [];
		
		$response['wards'] = AdWard::getListByDistrict($districtId);
		$response['streets'] = AdStreet::getListByDistrict($districtId);
		$response['projects'] = AdBuildingProject::getListByDistrict($districtId);
		
		return $response;
	}

	public function actionListSw($districtId) {
		Yii::$app->response->format = Response::FORMAT_JSON;
	
		$response = [];
	
		$response['wards'] = AdWard::getListByDistrict($districtId);
		$response['streets'] = AdStreet::getListByDistrict($districtId);
	
		return $response;
	}

    public function actionTrackingShare(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $uid = Yii::$app->user->isGuest ? null : Yii::$app->user->id;
        $pid = (int)Yii::$app->request->get('product_id');
        $type = (int)Yii::$app->request->get('type');
        if($pid > 0) {
            return Tracking::find()->productShare($uid, $pid, time(), $type);
        }
        return false;
    }

    public function actionListProject() {
    	$v = \Yii::$app->request->get('v');
    	$v = Elastic::transform($v);
    	$response = [];
    	
    	Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	
    	$params = [
			'query' => [
				'match_phrase_prefix' => [
					'search_field' => [
						'query' => $v,
						'max_expansions' => 100
					]
				],
			],
			'_source' => ['full_name']
    	];
    	
    	$ch = curl_init(Yii::$app->params['elastic']['config']['hosts'][0] . '/term/project_building/_search');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    		 
		$result = json_decode(curl_exec($ch), true);
		
		foreach ($result['hits']['hits'] as $k => $hit) {
			$response[$k]['full_name'] = $hit['_source']['full_name'];
			$response[$k]['id'] = $hit['_id'];
		}
		
		return $response;
    }
}
