<?php

namespace frontend\controllers;
use Yii;
use yii\helpers\Url;
use vsoft\news\models\CmsShow;
use common\vendor\vsoft\ad\models\AdBuildingProject;
use common\vendor\vsoft\ad\models\AdProduct;
use common\vendor\vsoft\ad\models\AdImages;

class AdsController extends \yii\web\Controller
{
    public $layout = '@app/views/layouts/layout';

    /**
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = '@app/views/layouts/search';
        return $this->render('index');
    }

    /**
     * @return \yii\web\Response
     */
    public function actionSearch()
    {
        $url = '/';
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            if(!empty($post['news'])){
                switch($post['news']){
                    case 1:
                        if($arrCats = array_values(Yii::$app->params["news"]["widget-category"])){
                            $detail = CmsShow::find()->where('catalog_id IN ('.implode($arrCats, ',').')')
                                ->orderBy('id DESC')->one();
                            $url = Url::to(['news/view', 'id' => $detail->id, 'slug' => $detail->slug, 'cat_id' => $detail->catalog->id, 'cat_slug' => $detail->catalog->slug]);
                        }
                        break;
                    case 2:
                        $model = AdBuildingProject::find()->where([])->one();
                        $url = Url::to(['/building-project/view', 'slug' => $model->slug]);
                        break;
                }
            }elseif(!empty($post['city'])){
                return $this->redirect(Url::to(['/ads/index']));
            }
        }
        $this->redirect($url);
    }

    /**
     * @return string
     */
    public function actionPost()
    {
    	$model = new AdProduct();
    	$model->city_id = \Yii::$app->request->post('cityId', 1);
    	$model->district_id = \Yii::$app->request->post('cityId', 1);
    	$model->category_id = \Yii::$app->request->post('categoryId', 1);
    	
        return $this->render('post', ['model' => $model]);
    }

    public function actionUpload() {
    	$response = Yii::$app->runAction('express/upload/image', ['folder' => 'ads']);
    	if($response) {
    		$image = new AdImages();
    		$image->file_name = $response['files'][0]['name'];
    		$image->uploaded_at = time();
    		$image->user_id = Yii::$app->user->id;
    		$image->save(false);
    		$response['files'][0]['name'] = $image->id;
    		
    		return $response;
    	}
    }
}
