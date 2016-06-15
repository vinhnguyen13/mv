<?php

namespace vsoft\ec;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'vsoft\ec\controllers';
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return \Yii::t('vsoft/ec/' . $category, $message, $params, $language);
    }

}
