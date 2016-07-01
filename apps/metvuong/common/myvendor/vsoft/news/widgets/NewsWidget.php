<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/1/2015 8:49 AM
 */

namespace vsoft\news\widgets;

use vsoft\news\models\CmsShow;
use Yii;
use yii\base\Widget;

class NewsWidget extends Widget
{
    public $view;
    public $title;
    public $limit;
    public $category = [
        'hotnews' => 0,
        'realestate' => 3,
        'finance' => 5,
        'business' => 7,
        'economy' => 8,
    ];

    public function run()
    {
        $result = null;
        $view = $this->view;
        $cat_id = $this->category[$view];
        $limit = $this->limit;
        $offset = 0;
        $order_by = ['updated_at' => SORT_DESC];

        $news = CmsShow::find()->where(['IN', 'language_id', [Yii::$app->language]]);

        if($cat_id > 0)
            $news->andWhere('catalog_id = :catId',[':catId' => $cat_id]);

        if($view == "hotnews")
            $news->andWhere('hot_news = :h',[':h' => 1]);

        $news->andWhere('status = :status', [':status' => 1]); // an tin inactive trong widget

        $result = $news->limit($limit)->offset($offset)->orderBy($order_by)->all();

        $cat_slug = null;
        if($cat_id > 0) {
            $catalog = \vsoft\news\models\CmsCatalog::findOne($cat_id);
            $cat_slug = $catalog->slug;
        }

        return $this->render('realestate', ['news' => $result, 'title' => $this->title, 'cat_id' => $cat_id, 'cat_slug' => $cat_slug]);
    }
}