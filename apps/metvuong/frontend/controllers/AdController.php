<?php

namespace frontend\controllers;
use frontend\components\Controller;
use frontend\models\Ad;
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

class AdController extends Controller
{
    public $layout = '@app/views/layouts/layout';
    
    public function actionUpload() {
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
    
    /**
     * @return string
     */
    public function beforeAction($action)
    {
    	$this->view->params['noFooter'] = true;
    	return parent::beforeAction($action);
    }
    
    public function actionIndex() {
    	if(Yii::$app->mobileDetect->isMobile()) {
    		return $this->listingMobile();
    	} else {
    		return $this->listing();
    	}
    }
    
    public function listingMobile() {
    	$query = AdProduct::find();
    	
    	$where = ['ad_product.status' => 1];
    	
    	$cityId = ($cityId = Yii::$app->request->get('city')) ? $cityId : 1;
    	$districtId = ($districtId = Yii::$app->request->get('district')) ? $districtId : 10;
    	
    	if($districtId) {
    		$where['ad_product.district_id'] = $districtId;
    	} else {
    		$where['ad_product.city_id'] = $cityId;
    	}
    	 
    	if($categoryId = Yii::$app->request->get('category')) {
    		$where['ad_product.category_id'] = intval($categoryId);
    	}
    	
    	$query->where($where);
    	
    	if($priceMin = Yii::$app->request->get('costMin')) {
    		$query->andWhere(['>=', 'ad_product.price', $priceMin]);
    	}
    	 
    	if($priceMax = Yii::$app->request->get('costMax')) {
    		$query->andWhere(['<=', 'ad_product.price', $priceMax]);
    	}
    	
    	if($dtMin = Yii::$app->request->get('areaMin')) {
    		$query->andWhere(['>=', 'ad_product.area', $dtMin]);
    	}
    	
    	if($dtMax = Yii::$app->request->get('areaMax')) {
    		$query->andWhere(['<=', 'ad_product.area', $dtMax]);
    	}
    	
    	if($roomNo = Yii::$app->request->get('roomNo')) {
    		$query->andWhere(['>=', 'ad_product_addition_info.room_no', $roomNo]);
    	}
    	
    	if($toiletNo = Yii::$app->request->get('toiletNo')) {
    		$query->andWhere(['>=', 'ad_product_addition_info.toilet_no', $toiletNo]);
    	}
    	
    	$query->leftJoin('ad_product_addition_info', '`ad_product_addition_info`.`product_id` = `ad_product`.`id`');
    	
    	$countQuery = clone $query;
    	$pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 24]);
    	
    	$query->with('adProductAdditionInfo');
    	
    	$sort = Yii::$app->request->get('sort', 'created_at');
    	$doa = 'DESC';
    	if(StringHelper::startsWith($sort, '-')) {
    		$sort = str_replace('-', '', $sort);
    		$doa = 'ASC';
    	}
    	
    	$products = $query->offset($pages->offset)->limit($pages->limit)->orderBy("$sort $doa")->all();
        if(count($products) > 0) {
            foreach ($products as $product) {
                try {
                    Tracking::find()->productFinder(Yii::$app->user->id, (int)$product->id, time());
                } catch (Exception $ex) {

                }
            }
        }
    	
    	return $this->render('index', ['products' => $products, 'pages' => $pages, 'districtId' => $districtId, 'cityId' => $cityId]);
    }
    
    public function listing()
    {
        $this->layout = '@app/views/layouts/search';
        
        if(Yii::$app->request->isAjax) {
        	Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        	
        	$query = AdProduct::find();
        	$query->select('ad_product.id, ad_product.city_id, ad_product.district_id, ad_product.ward_id, ad_product.street_id, ad_product.lat, ad_product.lng,
        					ad_product.home_no, ad_product.category_id, ad_product.price, ad_product.area, ad_product.created_at, ad_product.type,
        					ad_product_addition_info.floor_no, ad_product_addition_info.room_no, ad_product_addition_info.toilet_no, ad_images.file_name');
        	$query->from('ad_product');
        	$query->innerJoin('ad_product_addition_info', 'ad_product_addition_info.product_id = ad_product.id');
        	$query->leftJoin('ad_images', 'ad_images.order = 0 AND ad_images.product_id = ad_product.id');
        	$where = ['ad_product.status' => 1];
        	
        	if($type = Yii::$app->request->post('type')) {
        		$where['ad_product.type'] = intval($type);
        	}
        	
        	if($districtId = Yii::$app->request->post('districtId')) {
        		$where['ad_product.district_id'] = $districtId;
        	} else {
        		$cityId = Yii::$app->request->post('cityId') ? Yii::$app->request->post('cityId') : 1;
        		$where['ad_product.city_id'] = $cityId;
        	}
        	
        	if($categoryId = Yii::$app->request->post('categoryId')) {
        		$where['ad_product.category_id'] = intval($categoryId);
        	}
        	
        	$query->where($where);
        	
        	if($costMin = Yii::$app->request->post('costMin')) {
        		$query->andWhere(['>=', 'ad_product.price', $costMin]);
        	}
        	
        	if($costMax = Yii::$app->request->post('costMax')) {
        		$query->andWhere(['<=', 'ad_product.price', $costMax]);
        	}
        	
        	if($areaMin = Yii::$app->request->post('areaMin')) {
        		$query->andWhere(['>=', 'ad_product.area', $areaMin]);
        	}
        	
        	if($areaMax = Yii::$app->request->post('areaMax')) {
        		$query->andWhere(['<=', 'ad_product.area', $areaMax]);
        	}
        	
        	if($roomNo = Yii::$app->request->post('roomNo')) {
        		$query->andWhere(['>=', 'ad_product_addition_info.room_no', $roomNo]);
        	}
        	
			if($toiletNo = Yii::$app->request->post('toiletNo')) {
        		$query->andWhere(['>=', 'ad_product_addition_info.toilet_no', $toiletNo]);
        	}
        	
        	if($time = Yii::$app->request->post('time')) {
        		$query->andWhere(['>=', 'ad_product.created_at', strtotime($time)]);
        	}
        	
        	$order = Yii::$app->request->post('orderBy', 'created_at');
        	if($order == 'created_at') {
        		$query->orderBy("created_at DESC");
        	} else {
        		$query->orderBy("price ASC");
        	}
        	
        	$rawProducts = $query->asArray(true)->groupBy('ad_product.id')->all();
        	
        	$products = [];
        	
        	foreach ($rawProducts as $k => $product) {
        		$products[$k] = $product;
        		$products[$k]['previous_time'] = StringHelper::previousTime($product['created_at']);
                try {
                    Tracking::find()->productFinder(Yii::$app->user->id, $product['id'], time());
                } catch (Exception $ex) {

                }
        	}
        	return $products;
        } else {
        	$cityId = Yii::$app->request->get('city', 1);
        	$city = AdCity::find()->select('id, name, geometry, center, color')->asArray(true)->where(['id' => $cityId])->one();
        	
        	if(!$city) {
        		throw new NotFoundHttpException('The requested page does not exist.');
        	}
        	
        	$districts = AdDistrict::find()->select('id, name, pre, center, color')->asArray(true)->indexBy('id')->where(['city_id' => $cityId, 'status' => 1])->all();
        	$productSaved = ArrayHelper::getColumn(AdProductSaved::find()->where(['user_id' => Yii::$app->user->id])->andWhere(['!=', 'saved_at', 0])->all(), 'product_id');
        	$districtId = Yii::$app->request->get('district', 0);
        	
        	if($districtId) {
        		$initialZoom = 'listing.WARD_ZOOM_LEVEL';
        		$whereDistrictId = $districtId;
        	} else {
        		$initialZoom = 'listing.DISTRICT_ZOOM_LEVEL';
        		$whereDistrictId = ArrayHelper::getColumn($districts, 'id');
        	}
        	
        	// $wards = AdWard::find()->select('id, name, pre, geometry, center, color, district_id')->asArray(true)->indexBy('id')->where(['district_id' => $whereDistrictId, 'status' => 1])->all();
        	$wards = [];
        	
        	return $this->render('index', ['city' => $city, 'districts' => $districts, 'productSaved' => $productSaved, 'initialZoom' => $initialZoom, 'districtId' => $districtId, 'wards' => $wards]);
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
            if(Yii::$app->user->id != $product->user_id) {
                Tracking::find()->productVisitor(Yii::$app->user->id, $id, time());
				UserActivity::me()->saveActivity(UserActivity::ACTION_AD_CLICK, [
					'owner' => Yii::$app->user->id,
					'product' => $product->id,
					'buddy' => $product->user_id
				], $product->id);
            }
        } catch(Exception $ex){

        }
		if(Yii::$app->request->isAjax){
			return $this->renderAjax('_partials/detail', ['product' => $product]);
		}else{
			$this->layout = '@app/views/layouts/layoutFull';
			return $this->render('detail', ['product' => $product]);
		}

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
    	if(Yii::$app->mobileDetect->isMobile()) {
    		return $this->postMobile();
    	} else {
    		return $this->post();
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
		$this->layout = '@app/views/layouts/layoutFull';
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
            $user_ip = Yii::$app->getRequest()->getUserIP();
            if (!empty($post["uid"]) && !empty($post["pid"]) && !empty($post["optionsRadios"])) {
                try {
                    $product_report = new AdProductReport();
                    $product_report->user_id = $post["uid"];
                    $product_report->product_id = $post["pid"];
                    $product_report->type = $post["optionsRadios"];
                    $product_report->ip = $user_ip;
                    $product_report->status = Status::STATUS_ACTIVE;
                    $product_report->report_at = time();
                    if ($product_report->save())
                        return 200;
                } catch(IntegrityException $ie){
                    if($ie->getMessage())
                        return 13;
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
                return ['status' => 404, 'parameters' => 'Post variable not found'];

            $model = Yii::createObject([
                'class'    => ShareForm::className(),
                'scenario' => 'share',
            ]);
            $model->load($post);
            $model->validate();
            if (!$model->hasErrors()) {
//                // send from
//                Yii::$app->mailer->compose(['html' => '../mail/notifyReceivedEmail-html',], ['contact' => $model])
//                    ->setFrom(Yii::$app->params['adminEmail'])
//                    ->setTo([$model->your_email])
//                    ->setSubject($model->subject." - Thanks for sharing a listing with ".$model->your_email)
//                    ->send();

                // send to
                Yii::$app->mailer->compose(['html' => '../mail/notifyReceivedEmail-html',], ['contact' => $model])
                    ->setFrom(Yii::$app->params['adminEmail'])
                    ->setTo([$model->recipient_email])
                    ->setSubject($model->your_email ." shared a listing on Metvuong.com to you - ".$model->subject)
                    ->send();
                return ['status' => 200];
            } else {
                return ['status' => 404, 'parameters' => $model->errors];
            }
		}
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
}
