<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/8/2015
 * Time: 10:14 AM
 */

namespace frontend\models;
use vsoft\ad\models\AdImages;
use vsoft\ad\models\AdProduct;
use vsoft\ad\models\AdProductRating;
use vsoft\ad\models\AdProductSaved;
use frontend\models\UserActivity;
use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use vsoft\news\models\CmsShow;
use vsoft\ad\models\AdBuildingProject;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;
use yii\web\Response;

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

    private function checkLogin(){
        if(Yii::$app->user->isGuest){
            throw new NotFoundHttpException('You must login !');
        }
        return true;
    }

    public function favorite(){
        $this->checkLogin();
        if(Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $post = Yii::$app->request->post();
            if(!empty($post['id'])){
                if(($adSaved = AdProductSaved::findOne(['product_id'=>$post['id'], 'user_id'=>Yii::$app->user->id])) === null){
                    $adSaved = new AdProductSaved();
                    $adSaved->product_id = $post['id'];
                    $adSaved->user_id = Yii::$app->user->id;
                    $adSaved->saved_at = time();
                }else{
                    $adSaved->saved_at = !empty($post['stt']) ? time() : 0;
                }
                $adSaved->validate();
                if(!$adSaved->hasErrors()){
                    $adSaved->save();
                    if(Yii::$app->user->id != $adSaved->product->user_id) {
                        UserActivity::me()->saveActivity(UserActivity::ACTION_AD_FAVORITE, [
                            'owner' => Yii::$app->user->id,
                            'product' => $adSaved->product_id,
                            'buddy' => $adSaved->product->user_id,
                            'saved_at' => $adSaved->saved_at,
                        ], $adSaved->product_id);
                    }
                }
                return ['statusCode'=>200, 'parameters'=>['msg'=>Yii::$app->session->getFlash('notify_other')]];
            }
        }
        return ['statusCode'=>404, 'parameters'=>['msg'=>'']];
    }

    public function report(){
        $this->checkLogin();
        if(Yii::$app->request->isPost && Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $post = Yii::$app->request->post();
            if(!empty($post['user_id'])){
                return ['statusCode'=>200, 'parameters'=>['msg'=>'']];
            }
        }
        return ['statusCode'=>404, 'parameters'=>['msg'=>'user is not found']];
    }

    public function rating(){
        $this->checkLogin();
        if(Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $post = Yii::$app->request->post();
            if(($adProduct = AdProduct::findOne(['id'=>$post['id']])) !== null && !empty($post['core'])){
                if(($adProductRating = AdProductRating::findOne(['user_id'=>Yii::$app->user->id, 'product_id'=>$adProduct->id])) === null){
                    $adProductRating = Yii::createObject(['class' => AdProductRating::className(),
                        'user_id'=>Yii::$app->user->id,
                        'product_id'=>$adProduct->id,
                        'core'=>$post['core'],
                        'rating_at'=>time(),
                    ]);
                    $adProductRating->validate();
                    if(!$adProductRating->hasErrors()){
                        $adProductRating->save();
                        $_rating = $adProductRating->core;
                        $core = AdProductRating::findBySql('SELECT AVG(core) as avgCore FROM '.AdProductRating::tableName().' WHERE product_id = '.$adProduct->id)->one();
                        if(!empty($core->avgCore)){
                            $_rating = $core->avgCore;
                        }
                        $adProduct->updateAttributes(['rating'=>$_rating]);
                    }
                    return ['statusCode'=>200, 'parameters'=>['msg'=>'Rating successs', 'data'=>round($_rating)]];
                };
                return ['statusCode'=>404, 'parameters'=>['msg'=>'You rated']];
            }
            return ['statusCode'=>404, 'parameters'=>['msg'=>'Missing data']];
        }
    }

    public function homePageRandom(){
        $query = AdProduct::find();//->leftJoin('ad_images', 'ad_images.order = 0 AND ad_images.product_id = ad_product.id');
//        $where = ['ad_product.status' => 1];
//        $query->leftJoin('ad_product_addition_info', '`ad_product_addition_info`.`product_id` = `ad_product`.`id`');
//        $query->with('adProductAdditionInfo');
        $query->innerJoin(AdImages::tableName(), "ad_product.id = ad_images.product_id");
        $products = $query->limit(6)->orderBy(['id' => SORT_DESC])->all();
//        $products = $query->limit(6)->orderBy("RAND()")->all();
        return $products;
    }
}