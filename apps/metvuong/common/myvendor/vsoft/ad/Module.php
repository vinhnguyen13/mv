<?php

namespace vsoft\ad;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'vsoft\ad\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return \Yii::t('vsoft/ad/' . $category, $message, $params, $language);
    }
}
