<?php

namespace vsoft\news;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'vsoft\news\controllers';
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return \Yii::t('funson86/' . $category, $message, $params, $language);
    }

}
