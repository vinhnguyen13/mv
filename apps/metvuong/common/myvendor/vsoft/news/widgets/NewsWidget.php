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
        $order_by = ['id' => SORT_DESC];
        if($view == "hotnews")
            $order_by = ['click' => SORT_DESC, 'id' => SORT_DESC];
        $news = CmsShow::find();
        $where = $cat_id > 0 ? "catalog_id = $cat_id" : "";
        $result = $news->where($where)
            ->limit($limit)->offset($offset)->orderBy($order_by)->all();

        $cat_slug = null;
        if($cat_id > 0) {
            $catalog = \vsoft\news\models\CmsCatalog::findOne($cat_id);
            $cat_slug = $catalog->slug;
        }

        return $this->render('realestate', ['news' => $result, 'title' => $this->title, 'cat_id' => $cat_id, 'cat_slug' => $cat_slug]);
    }
}