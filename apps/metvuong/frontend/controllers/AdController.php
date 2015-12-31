<?php

namespace frontend\controllers;
use frontend\components\Controller;
use frontend\models\Ad;
use Yii;
use yii\helpers\Url;
use vsoft\news\models\CmsShow;
use vsoft\ad\models\AdBuildingProject;
use vsoft\ad\models\AdProduct;
use vsoft\ad\models\AdImages;
use vsoft\ad\models\AdProductAdditionInfo;
use vsoft\ad\models\AdContactInfo;
use yii\web\Cookie;
use vsoft\express\components\ImageHelper;
use vsoft\express\components\StringHelper;
use vsoft\ad\models\base\ActiveRecord;
use yii\data\Pagination;
use yii\widgets\LinkPager;
use vsoft\ad\models\AdCity;
use yii\helpers\ArrayHelper;
use vsoft\ad\models\AdDistrict;
use vsoft\ad\models\AdCategory;

class AdController extends Controller
{
    public $layout = '@app/views/layouts/layout';

    /**
     * @return string
     */
    public function actionIndex($result = false)
    {
        $this->layout = '@app/views/layouts/search';
        
        if($result) {
        	Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        	
        	$query = (new \yii\db\Query())->select('ad_product.*, ad_images.file_name, ad_product_addition_info.*')
        				->from('ad_product')
						->innerJoin('ad_product_addition_info', 'ad_product.id = ad_product_addition_info.product_id')
        				->leftJoin('ad_images', 'ad_product.id = ad_images.product_id')
        				->groupBy('ad_product.id')
        				->andWhere('`status` = ' . AdProduct::STATUS_ACTIVE);
        	
        	$conditionMap = [
				['type', '=', 'type'],
				['city_id', '=', 'cityId'],
				['district_id', '=', 'districtId'],
				['category_id', '=', 'categoryId'],
				['price', '>=', 'costMin'],
				['price', '<=', 'costMax'],
				['area', '>=', 'areaMin'],
				['area', '<=', 'areaMax'],
				['room_no', '>=', 'roomNo'],
				['toilet_no', '>=', 'toiletNo'],
				['type', '=', 'type'],
				['type', '=', 'type'],
        	];
        	
        	foreach ($conditionMap as $cond) {
        		$param = Yii::$app->request->get($cond[2]);
        		
        		if($param) {
        			$query->andWhere("`{$cond[0]}` {$cond[1]} :{$cond[2]}", [":{$cond[2]}" => $param]);
        		}
        	}
        	
        	if($time = Yii::$app->request->get('time')) {
        		$query->andWhere('created_at >= :created_at', [':created_at' => strtotime($time)]);
        	}
        	
        	if(($order = Yii::$app->request->get('orderBy')) && $order != 'created_at') {
        		$query->orderBy("`$order` ASC");
        	} else {
        		$query->orderBy("`created_at` DESC");
        	}
        	
        	$pages = new Pagination(['totalCount' => $query->count()]);
        	$pages->pageSize = isset(Yii::$app->params['listingLimit']) ? Yii::$app->params['listingLimit'] : 20;
        	
        	$products = $query->limit($pages->limit)->offset($pages->offset)->all();
        	
        	$productResponse = [];
        	
        	foreach ($products as $k => $product) {
        		$productResponse[$k] = $product;
        		$productResponse[$k]['previous_time'] = StringHelper::previousTime($product['created_at']);
        		$productResponse[$k]['price'] = StringHelper::formatCurrency($product['price']);
        		$productResponse[$k]['area'] = StringHelper::formatCurrency($product['area']);
        		
        		if($product['file_name']) {
        			if(StringHelper::startsWith($product['file_name'], 'http')) {
        				$productResponse[$k]['image_url'] = str_replace('/745x510/', '/350x280/', $product['file_name']);
        			} else {
        				$productResponse[$k]['image_url'] = AdImages::getImageUrl($product['file_name']);
        			}
        		} else {
        			$productResponse[$k]['image_url'] = Yii::$app->view->theme->baseUrl . '/resources/images/default-ads.jpg';;
        		}
        	}
        	
        	return ['productResponse' => $productResponse, 'pages' => LinkPager::widget(['pagination' => $pages,]), 'total' => $pages->totalCount];
        }
        
        return $this->render('index');
    }
    
    public function actionSavedListing() {
    	if(!Yii::$app->user->isGuest) {
    		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    		 
    		$query = (new \yii\db\Query())->from('ad_product_saved')->where('ad_product_saved.user_id = ' . Yii::$app->user->id)
    					->andWhere('saved_at != 0')
    					->groupBy('ad_product.id')
    					->select('ad_product.*, ad_images.file_name, ad_product_addition_info.*')
    					->leftJoin('ad_product', 'ad_product.id = ad_product_saved.product_id')
    					->leftJoin('ad_product_addition_info', 'ad_product.id = ad_product_addition_info.product_id')
        				->leftJoin('ad_images', 'ad_product.id = ad_images.product_id');
    		
    		$pages = new Pagination(['totalCount' => $query->count()]);
    		$pages->pageSize = isset(Yii::$app->params['listingLimit']) ? Yii::$app->params['listingLimit'] : 20;
    		 
    		$products = $query->limit($pages->limit)->offset($pages->offset)->all();
    		 
    		$productResponse = [];
    		 
    		foreach ($products as $k => $product) {
    			$productResponse[$k] = $product;
    			$productResponse[$k]['previous_time'] = StringHelper::previousTime($product['created_at']);
    			$productResponse[$k]['price'] = StringHelper::formatCurrency($product['price']);
    			$productResponse[$k]['area'] = StringHelper::formatCurrency($product['area']);
    		
    			if($product['file_name']) {
    				if(StringHelper::startsWith($product['file_name'], 'http')) {
    					$productResponse[$k]['image_url'] = $product['file_name'];
    				} else {
    					$productResponse[$k]['image_url'] = AdImages::getImageUrl($product['file_name']);
    				}
    			} else {
    				$productResponse[$k]['image_url'] = Yii::$app->view->theme->baseUrl . '/resources/images/default-ads.jpg';;
    			}
    		}
    		
    		return ['productResponse' => $productResponse, 'pages' => LinkPager::widget(['pagination' => $pages,]), 'total' => $pages->totalCount];
    	}
    }
    
    public function actionDetail($id, $isCraw) {
    	$product = AdProduct::findOne($id);
    	return $this->renderPartial('detail', ['product' => $product]); 
    }

    /**
     * @return \yii\web\Response
     */
    public function actionRedirect()
    {
		$url = Ad::find()->redirect();
        $this->redirect($url);
    }

    /**
     * @return string
     */
    public function actionPost()
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
    				   
    						if(isset($post['images']) && $post['images']) {
    							$images = explode(',', $post['images']);
    							foreach($images as $k => $image) {
    								if(!ctype_digit($image)) {
    									unset($images[$k]);
    								}
    							}
    		
    							Yii::$app->db->createCommand()->update('ad_images', ["product_id" => $model->id], "`id` IN (" . implode(',', $images) . ") AND user_id = " . Yii::$app->user->id)->execute();
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
}
