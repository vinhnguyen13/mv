<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/8/2015
 * Time: 10:14 AM
 */

namespace frontend\models;
use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use vsoft\news\models\CmsShow;
use vsoft\ad\models\AdBuildingProject;
use yii\web\Cookie;

class Ads extends Component
{
    /**
     * @return mixed
     */
    public static function find()
    {
        return Yii::createObject(Ads::className());
    }

    /**
     *
     */
    public function redirect(){
        $url = Url::home();
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $searchParams = $post;
            unset($searchParams['_csrf']);
            if(!empty($post['activeSearch'])){
                switch($post['activeSearch']){
                    case 1:
                        $url = Url::to(ArrayHelper::merge(['/ads/index'], $searchParams));
                        break;
                    case 2:
                        $url = Url::to(ArrayHelper::merge(['/ads/post'], $searchParams));
                        break;
                    case 3:
                        if(!empty($post['news']) && $post['news'] == 1){
                            if($arrCats = array_values(Yii::$app->params["news"]["widget-category"])){
                                $detail = CmsShow::find()->where('catalog_id IN ('.implode($arrCats, ',').')')->orderBy('id DESC')->one();
                                $url = Url::to(['news/view', 'id' => $detail->id, 'slug' => $detail->slug, 'cat_id' => $detail->catalog->id, 'cat_slug' => $detail->catalog->slug]);
                            }
                        }else if(!empty($post['news']) && $post['news'] == 2){
                            $model = AdBuildingProject::find()->where([])->one();
                            $url = Url::to(['/building-project/view', 'slug' => $model->slug]);
                        }
                        break;
                }
            }
            $cookie = new Cookie([
                'name' => 'searchParams',
                'value' => json_encode($searchParams),
                'expire' => time() + 60 * 60 * 24 * 30, // 30 days
//            'domain' => '.lancaster.vn' // <<<=== HERE
            ]);
            Yii::$app->getResponse()->getCookies()->add($cookie);
        }
        Yii::$app->getResponse()->redirect($url);
        return $url;
    }
}