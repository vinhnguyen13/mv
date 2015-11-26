<?php

namespace frontend\controllers;
use Yii;
use yii\helpers\Url;
use vsoft\news\models\CmsShow;
use common\vendor\vsoft\ad\models\AdBuildingProject;

class SearchController extends \yii\web\Controller
{
    public function actionIndex()
    {

    }

    public function actionFind()
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
                            $this->redirect($url);
                        }
                        break;
                    case 2:
                        $model = AdBuildingProject::find()->where([])->one();
                        $url = Url::to(['/building-project/view', 'slug' => $model->slug]);
                        $this->redirect(Url::to(['/building-project/index']));
                        break;
                }
            }
        }
        $this->redirect($url);
    }

}
