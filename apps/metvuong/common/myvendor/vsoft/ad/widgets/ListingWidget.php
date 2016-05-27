<?php

/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 4/15/2016 1:44 PM
 */
namespace vsoft\ad\widgets;

use frontend\models\AdProductSearch;
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
    public $pid;
    public $loadAjax;

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
        $model = new AdProductSearch();
        $query = $model->search(\Yii::$app->request->get());
        $query->addSelect('ad_product.created_at, ad_product.updated_at, ad_product.category_id, ad_product.type, ad_images.file_name, ad_images.folder');
        $query->leftJoin('ad_images', 'ad_images.order = 0 AND ad_images.product_id = ad_product.id')->groupBy('ad_product.id');

        if(!empty($this->type))
            $query->andWhere(["type" => $this->type]);

        if(!empty($user_id))
            $query->andWhere('user_id != :uid',[':uid' => $user_id]);

        if(!empty($this->pid)) // load nhung product khac ko phai product hien tai
            $query->andWhere('`ad_product`.`id` != :id',[':id' => $this->pid]);

        $result = $query->limit($limit)->offset($offset)->orderBy(['ad_product.id' => SORT_DESC])->all();

        return $this->render($view, ['products' => $result, 'title' => $this->title]);
    }
}