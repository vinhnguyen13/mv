<?php

/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 4/15/2016 1:44 PM
 */
namespace vsoft\ad\widgets;

use vsoft\ad\models\AdImages;
use vsoft\ad\models\AdProduct;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

class ListingWidget extends Widget
{
    public $user_id;
    public $view;
    public $title;
    public $limit;
    public $city_id;
    public $district_id;
    public $type;

    public function run()
    {
        if(empty($this->city_id))
            $city_id = 1;

        if(empty($this->district_id))
            $district_id = 10;

        if(empty($this->view))
            $view = 'listing';

        if(empty($this->user_id))
            $user_id = null;

        $result = null;
        $limit = $this->limit;
        $offset = 0;
//        $order_by = ['id' => SORT_DESC];

        $products = AdProduct::find()->innerJoin(AdImages::tableName(), "ad_product.id = ad_images.product_id")
                    ->where(["city_id" => $city_id, "district_id" => $district_id]);

        if(!empty($this->type))
            $products->andWhere(["type" => $this->type]);

        if(!empty($user_id))
            $products->andWhere('user_id != :uid',[':uid' => $user_id]);

        $result = $products->limit($limit)->offset($offset)->orderBy("RAND()")->all();

        return $this->render($view, ['products' => $result, 'title' => $this->title]);
    }
}