<?php

namespace frontend\controllers;
use frontend\components\Controller;
use frontend\models\Ads;
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

class AdsController extends Controller
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
        	
        	$cityId = Yii::$app->request->get('cityId');
        	$districtId = Yii::$app->request->get('districtId');
        	$categoryId = Yii::$app->request->get('categoryId');
        	
        	$query = AdProduct::find();
        	
        	if($cityId) {
        		$query->where('city_id = :city_id', [':city_id' => $cityId]);
        	}
        	
        	if($districtId) {
        		$query->andWhere('district_id = :district_id', [':district_id' => $districtId]);
        	}
        	
        	$products = $query->asArray(true)->all();
        	
        	return $products;
        }
        
        return $this->render('index');
    }
    
    public function actionDetail($id) {
    	$product = AdProduct::findOne($id);
    	return $this->renderPartial('detail', ['product' => $product]); 
    }

    /**
     * @return \yii\web\Response
     */
    public function actionRedirect()
    {
		$url = Ads::find()->redirect();
        $this->redirect($url);
    }

    /**
     * @return string
     */
    public function actionPost()
    {
    	if(Yii::$app->user->isGuest) {
    		return $this->render('/_systems/require_login.php');
    	}
    	
    	$model = new AdProduct();
    	$model->loadDefaultValues();
    	$model->city_id = \Yii::$app->request->get('cityId', 1);
    	$model->district_id = \Yii::$app->request->get('districtId', 22);
    	$model->category_id = \Yii::$app->request->get('categoryId', 6);

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
    	
    	return $this->render('post', ['model' => $model, 'adProductAdditionInfo' => $adProductAdditionInfo, 'adContactInfo' => $adContactInfo]);
    }

    public function actionUpload() {
    	$folder = 'ads';
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
    		
    		$response = Yii::$app->runAction('express/upload/delete-image', ['orginal' => $image->file_name, 'thumbnail' => $thumb, 'folder' => 'ads', 'resizeForAds' => true]);
    		
    		$image->delete();
    		
    		return $response;
    	}
    }
}
