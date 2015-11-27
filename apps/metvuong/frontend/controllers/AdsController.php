<?php

namespace frontend\controllers;
use Yii;
use yii\helpers\Url;
use vsoft\news\models\CmsShow;
use common\vendor\vsoft\ad\models\AdBuildingProject;

class AdsController extends \yii\web\Controller
{
    public $layout = '@app/views/layouts/search';
    public function actionIndex()
    {
        return $this->render('index');
    }

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

    public function actionPost()
    {
        return $this->render('post');
    }

}
