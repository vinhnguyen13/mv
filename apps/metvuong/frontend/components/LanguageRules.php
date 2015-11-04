<?php
namespace frontend\components;
use yii\base\BootstrapInterface;
class LanguageRules implements BootstrapInterface
{
    public function bootstrap($app)
    {
//        return true;
        if($app->language == 'en-US'){
            $app->getUrlManager()->addRules([
                'news' => 'news/index',
                'news/<cat_id:\d+>-<cat_slug>/<id:\d+>-<slug>' => 'news/view',
            ], false);
        }elseif($app->language == 'vi-VN'){
            $app->getUrlManager()->addRules([
                'tin-tuc' => 'news/index',
                'tin-tuc/<cat_id:\d+>-<cat_slug>/<id:\d+>-<slug>' => 'news/view',
            ], false);
        }
    }
}