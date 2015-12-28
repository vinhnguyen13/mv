<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/8/2015
 * Time: 10:14 AM
 */

namespace frontend\models;
use vsoft\ad\models\AdProductSaved;
use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use vsoft\news\models\CmsShow;
use vsoft\ad\models\AdBuildingProject;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

class Ad extends Component
{
    /**
     * @return mixed
     */
    public static function find()
    {
        return Yii::createObject(Ad::className());
    }

    /**
     * @return string
     */
    public function redirect(){
        $url = Url::home();
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $searchParams = $post;
            unset($searchParams['_csrf']);
            unset($searchParams['valSearch']);
            unset($searchParams['activeSearch']);
            $searchParams = array_filter($searchParams, 'strlen');
            if(!empty($post['activeSearch'])){
                switch($post['activeSearch']){
                    case 1:
                        $url = Url::to(ArrayHelper::merge(['/ad/index'], $searchParams));
                        break;
                    case 2:
                        $url = Url::to(ArrayHelper::merge(['/ad/post'], $searchParams));
                        break;
                    case 3:
                        if(!empty($post['newsType']) && $post['newsType'] == 1){
                            if($arrCats = array_values(Yii::$app->params["news"]["widget-category"])){
//                                $detail = CmsShow::find()->where('catalog_id IN ('.implode($arrCats, ',').')')->orderBy('id DESC')->one();
                                $cat_id = empty($post["newsCat"]) == false ? $post["newsCat"] : 0; //implode($arrCats, ',');
                                $detail = CmsShow::find()->where('catalog_id IN ('.$cat_id.')')->orderBy('id DESC')->one();
                                if(!empty($detail))
                                    $url = Url::to(['news/view', 'id' => $detail->id, 'slug' => $detail->slug, 'cat_id' => $detail->catalog->id, 'cat_slug' => $detail->catalog->slug]);
                                else
                                    $url = Url::to(['news/findnotfound']);
                            }
                        }else if(!empty($post['newsType']) && $post['newsType'] == 2){
                            $bp_id = empty($post["project"]) == false ? $post["project"] : 0;
                            $model = AdBuildingProject::find()->andWhere('id = :id',[':id' => $bp_id])->one();
                            if(!empty($model)) {
                                $url = Url::to(['/building-project/view', 'slug' => $model->slug]);
                            }
                            else {
                                $url = Url::to(['news/findnotfound']);
                            }
                        }
                        break;
                }
            }
            $cookie = new Cookie([
                'name' => 'searchParams',
                'value' => json_encode($post),
                'expire' => time() + 60 * 60 * 24 * 30, // 30 days
//            'domain' => '.lancaster.vn' // <<<=== HERE
            ]);
            Yii::$app->getResponse()->getCookies()->add($cookie);
        }
        Yii::$app->getResponse()->redirect($url);
        return $url;
    }

    public function favorites(){

    }

    public function report(){
        if(Yii::$app->user->isGuest){
            throw new NotFoundHttpException();
        }

        if(Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if(!empty($post['id'])){
                if(empty($adSaved = AdProductSaved::findOne(['id'=>$post['id']]))){
                    $adSaved = new AdProductSaved();
                    $adSaved->product_id = $post['id'];
                }
                $adSaved->user_id = Yii::$app->user->id;
            }

        }
        return false;
    }
}