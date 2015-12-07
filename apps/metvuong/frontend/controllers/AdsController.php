<?php

namespace frontend\controllers;
use frontend\components\Controller;
use Yii;
use yii\helpers\Url;
use vsoft\news\models\CmsShow;
use common\vendor\vsoft\ad\models\AdBuildingProject;
use common\vendor\vsoft\ad\models\AdProduct;
use common\vendor\vsoft\ad\models\AdImages;
use common\vendor\vsoft\ad\models\AdProductAdditionInfo;
use common\vendor\vsoft\ad\models\AdContactInfo;
use yii\web\Cookie;

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
        	
        	$products = AdProduct::find()->asArray(true)->all();
        	
        	return $products;
        }
        
        return $this->render('index');
    }

    /**
     * @return \yii\web\Response
     */
    public function actionSearch()
    {
        $url = Url::home();
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
			$searchParams = $post;
            if(!empty($post['news'])){
                switch($post['news']){
                    case 1:
                        if($arrCats = array_values(Yii::$app->params["news"]["widget-category"])){
                            $detail = CmsShow::find()->where('catalog_id IN ('.implode($arrCats, ',').')')
                                ->orderBy('id DESC')->one();
                            $url = Url::to(['news/view', 'id' => $detail->id, 'slug' => $detail->slug, 'cat_id' => $detail->catalog->id, 'cat_slug' => $detail->catalog->slug]);
                        }
						$searchParams = ['sug1'=>[$post['news']=>'Tin Tức']];
                        break;
                    case 2:
                        $model = AdBuildingProject::find()->where([])->one();
                        $url = Url::to(['/building-project/view', 'slug' => $model->slug]);
						$searchParams = ['sug1'=>[$post['news']=>'Dự Án']];
                        break;
                }
            }elseif(!empty($post['city'])){
				$url = Url::to(['/ads/index']);
				$searchParams = ['sug1'=>[$post['city']=>'Hồ Chí Minh'], 'sug2'=>[$post['district']=>'Quận 1'], 'sug3'=>[$post['category']=>'Nhà Phố']];
            }
			$cookie = new Cookie([
				'name' => 'searchParams',
				'value' => json_encode($searchParams),
				'expire' => time() + 60 * 60 * 24 * 30, // 30 days
//            'domain' => '.lancaster.vn' // <<<=== HERE
			]);
			Yii::$app->getResponse()->getCookies()->add($cookie);
        }
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
    	$model->city_id = \Yii::$app->request->post('cityId', 1);
    	$model->district_id = \Yii::$app->request->post('districtId', 22);
    	$model->category_id = \Yii::$app->request->post('categoryId', 1);

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
    			
    			if(isset($post['images'])) {
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
    	$response = Yii::$app->runAction('express/upload/image', ['folder' => 'ads']);
    	if($response) {
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
    		
    		$response = Yii::$app->runAction('express/upload/delete-image', ['orginal' => $image->file_name, 'thumbnail' => $thumb, 'folder' => 'ads']);
    		
    		$image->delete();
    		
    		return $response;
    	}
    }
}
