<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/22/2015 1:07 PM
 */

namespace frontend\components;


use vsoft\express\models\LcMeta;
use Yii;
use yii\base\Component;
use yii\helpers\Json;

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
        $model = LcMeta::find()
            ->where('url = :_url', [':_url' => $url])
            ->one();
        return $model;
    }

    public function addMeta($data)
    {
        foreach ($data as $key => $item) {
            /**
             * check key exist in mapping, return key mapping
             */
            if (!empty($item)) {
                foreach(Yii::$app->params['meta']['attributes'] as $mapKey => $value) {
                    $checkKey = array_keys($value, $key);
                    if(!empty($checkKey)){
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
            $this->addMeta($json_data);
        }

    }

}