<?php

namespace frontend\controllers;
use frontend\components\Controller;
use frontend\models\Ad;
use frontend\models\ShareForm;
use frontend\models\Tracking;
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

class AdController extends Controller
{
    public $layout = '@app/views/layouts/layout';

    /**
     * @return string
     */
    public function actionIndex() {
    	if(Yii::$app->mobileDetect->isMobile()) {
    		return $this->listingMobile();
    	} else {
    		return $this->listing();
    	}
    }
    
    public function listingMobile() {
    	$this->layout = '@app/views/layouts/search';
    	
    	$query = AdProduct::find();
    	
    	$where = ['ad_product.status' => 1];
    	
    	// Other where
    	
    	$query->where($where);
    	
    	$countQuery = clone $query;
    	$pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 24]);
    	
    	$query->with('adProductAdditionInfo');
    	$products = $query->offset($pages->offset)->limit($pages->limit)->orderBy('created_at DESC')->all();
    	
    	return $this->render('index', ['products' => $products, 'pages' => $pages]);
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
		try{
			Tracking::find()->productVisitor(Yii::$app->user->id, $id, time());
		} catch(Exception $ex){

		}
    	$product = AdProduct::findOne($id);
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
    	return $this->render('post');
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

    public function actionUpload() {
    	$folder = 'ad';
    	$response = Yii::$app->runAction('express/upload/image', ['folder' => $folder, 'resizeForAds' => true]);
    	
    	if($response) {
    		$path = \Yii::getAlias('@store') . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $response['files'][0]['name'];
    		$imageHelper = new ImageHelper($path);
    		$imageHelper->makeMedium();
    		$imageHelper->makeLarge(true);
    		
    		$image = new AdImages();
    		$image->file_name = $response['files'][0]['name'];
    		$image->uploaded_at = time();
    		$image->user_id = Yii::$app->user->id;
    		$image->save(false);
    		
    		$response['files'][0]['deleteUrl'] = Url::to(['delete-image', 'id' => $image->id]);
    		$response['files'][0]['name'] = $image->id;
    		
    		return $response;
    	}
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
            $model = Yii::createObject([
                'class'    => ShareForm::className(),
                'scenario' => 'share',
            ]);
            $model->load($post);
            $model->validate();
            if (!$model->hasErrors()) {
                // send to
                Yii::$app->mailer->compose(['html' => '../mail/notifyReceivedEmail-html',], ['contact' => $model])
                    ->setFrom(Yii::$app->params['adminEmail'])
                    ->setTo([$model->recipient_email])
                    ->setSubject($model->your_email ." shared a listing on Metvuong.com to you")
                    ->send();

                // send from
                Yii::$app->mailer->compose(['html' => '../mail/notifyReceivedEmail-html',], ['contact' => $model])
                    ->setFrom(Yii::$app->params['adminEmail'])
                    ->setTo([$model->your_email])
                    ->setSubject("Thanks for sharing a listing with ".$model->your_email)
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
