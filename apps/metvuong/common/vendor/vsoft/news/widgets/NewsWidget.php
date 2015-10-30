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
        $view = $this->view;
        $cat_id = Yii::$app->params["news"]["widget-category"][$view];
        $limit = Yii::$app->params["news"]["widget-limit"][$view];
        $offset = 0;
        $order_by = ['id' => SORT_DESC];

        $news = CmsShow::find();

//        switch ($view) {
//            case 'hotnews':
//                $limit = 4;
//                break;
//            case 'important':
//                $limit = 6;
////                $cat_id = 3; // bat dong san
//                break;
//            case 'realestate':
//                $limit = 3;
////                $cat_id = 3; // bat dong san
//                break;
//            case 'finance':
//                $limit = 1;
////                $cat_id = 5; // tai chinh
////                $order_by = ['id' => SORT_ASC];
//                break;
//            case 'business':
//                $limit = 1;
////                $cat_id = 7; // doanh nghiep
////                $order_by = ['id' => SORT_ASC];
//                break;
////            case 'economy':
////                $limit = 3;
////                $cat_id = 5; // kinh te 6
////                break;
//            default:
//                break;
//        }
        $where = $cat_id > 0 ? "catalog_id = $cat_id" : "";
        $result = $news->where($where)
            ->limit($limit)->offset($offset)->orderBy($order_by)->all();

        return $this->render($view, ['news' => $result, 'cat_id' => $cat_id]);
    }
}