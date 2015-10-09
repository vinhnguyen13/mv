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

    public function run()
    {
        $result = null;
        $cat_id = 0;
        $limit = 1;
        $offset = 0;
        $order_by = ['id' => SORT_ASC];
        $view = $this->view;

        $news = CmsShow::find();

        switch ($view) {
            case 'sidebar':
                $limit = 2;
                $cat_id = 4; // tai chinh
                break;
            case 'batdongsan':
                $limit = 4;
                $cat_id = 2; // bat dong san
                break;
            case 'quantam':
                $limit = 6;
                $cat_id = 2; // bat dong san
                break;
            case 'hotnews':
                $limit = 6;
                $where = "";
                $view = 'quantam'; // tam thoi lay tin moi nhat
                break;
            case 'taichinh':
                $limit = 1;
                $cat_id = 4; // tai chinh
//                $order_by = ['id' => SORT_ASC];
                break;
            case 'doanhnghiep':
                $limit = 1;
                $cat_id = 5; // doanh nghiep
//                $order_by = ['id' => SORT_ASC];
                break;
            case 'kinhte':
                $limit = 3;
                $cat_id = 5; // kinh te 6
                break;
            default:
                break;
        }
        $where = $cat_id >0 ? "catalog_id = $cat_id" : "";
        $result = $news->where($where)
            ->limit($limit)->offset($offset)->orderBy($order_by)->all();

        return $this->render($view, ['news' => $result, 'cat_id' => $cat_id]);
    }
}