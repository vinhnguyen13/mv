<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/22/2015 1:07 PM
 */

namespace frontend\components;

use vsoft\express\models\Metadata;
use Yii;
use yii\base\Component;
use yii\helpers\Json;
use yii\helpers\Url;

class MetaExt extends Component
{
//    private $mapping = [
//        'name' => ['keywords', 'description'],
//        'property' => ['og:site_name', 'og:type', 'og:title', 'og:image', 'og:description'],
//    ];

    public function welcome()
    {
        echo 'Welcome to Meta Extension';
    }

    public function getMeta($url)
    {
        $model = Metadata::find()
            ->where('url = :_url', [':_url' => $url])
            ->one();
        return $model;
    }

    public function addMeta($data, $url)
    {
        foreach ($data as $key => $item) {
            /**
             * check key exist in mapping, return key mapping
             */

            if (!empty($item)) {
                if(Url::home(true) == $url){
                    if(Yii::$app->language == 'vi-VN') {
                        if($key == "description" || $key == 'og:description')
                            $item = "MetVuong.com là công cụ giúp cho việc tìm kiếm, mua bán bất động sản dễ dàng hơn, nhanh hơn, hiệu quả hơn. MetVuong.com sử dụng thuật toán để sắp xếp và phân loại tất cả các danh sách đăng tin, do đó bạn luôn có thông tin chính xác và hữu ích nhất.";
                    } else if(Yii::$app->language == 'en-US') {
                        if($key == "description" || $key == 'og:description')
                            $item = "Metvuong.com is the where real estate is easier, faster, more effective. Metvuong.com uses an algorithm to sort and categorise all of our listings, so that you don't deal with clutter.";
                    }
                }
                foreach (Yii::$app->params['meta']['attributes'] as $mapKey => $value) {
                    $checkKey = array_keys($value, $key);
                    if (!empty($checkKey)) {
                        Yii::$app->view->registerMetaTag([
                                $mapKey => $key,
                                'content' => $item
                        ]);
                    }
                }
            }
        }

    }

    public function add($url){
        $meta = $this->getMeta($url);
        if (!empty($meta)) {
            $json_data = Json::decode($meta->metadata, true);
            $this->addMeta($json_data, $url);
        }

    }

}