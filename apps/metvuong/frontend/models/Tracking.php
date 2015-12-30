<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/8/2015
 * Time: 10:14 AM
 */

namespace frontend\models;
use vsoft\ad\models\AdProduct;
use vsoft\ad\models\AdProductRating;
use vsoft\ad\models\AdProductSaved;
use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use vsoft\news\models\CmsShow;
use vsoft\ad\models\AdBuildingProject;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class Tracking extends Component
{
    /**
     * @return mixed
     */
    public static function find()
    {
        return Yii::createObject(Ad::className());
    }

    public function productVisitor(){
        $this->checkLogin();
        if(Yii::$app->request->isPost && Yii::$app->request->isAjax) {

        }
    }
}