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
    public $post_id, $view;

    public function run()
    {
        $cat_id = 0;
        $limit = 1;
        $offset = 0;
        $order_by = ['id' => SORT_DESC];

        $news = CmsShow::find();

        switch($this->view){
            case 'sidebar':
                $limit = 2;
                $cat_id = 4; // tai chinh
                break;
            case 'batdongsan':
                $limit = 4;
                $cat_id = 2; // bat dong san
                break;
            case 'quantam':
                $limit = 8;
                $cat_id = 2; // bat dong san
                break;
            case 'taichinh':
                $limit = 1;
                $cat_id = 4; // tai chinh
                $order_by = ['id' => SORT_ASC];
                break;
            case 'doanhnghiep':
                $limit = 1;
                $cat_id = 5; // doanh nghiep
                $order_by = ['id' => SORT_ASC];
                break;
            case 'kinhte':
                $limit = 3;
                $cat_id = 5; // kinh te
                break;
            default:
                break;
        }

        $result = $news->where('catalog_id = :cat_id', [':cat_id' => $cat_id])
                ->limit($limit)->offset($offset)->orderBy($order_by)->all();

        return $this->render($this->view, ['news' => $result]);
    }
}