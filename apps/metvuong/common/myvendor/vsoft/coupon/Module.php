<?php

namespace vsoft\coupon;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'vsoft\coupon\controllers';
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return \Yii::t('vsoft/coupon/' . $category, $message, $params, $language);
    }

}
