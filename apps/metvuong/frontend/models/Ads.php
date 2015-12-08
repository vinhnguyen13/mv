<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/8/2015
 * Time: 10:14 AM
 */

namespace frontend\models;
use Yii;
use yii\helpers\Url;
use vsoft\news\models\CmsShow;
use common\vendor\vsoft\ad\models\AdBuildingProject;
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
        return $url;
    }
}