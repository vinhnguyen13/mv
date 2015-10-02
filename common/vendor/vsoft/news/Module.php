<?php

namespace vsoft\news;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'vsoft\news\controllers';
    public function init()
    {
        parent::init();
        if (strpos($this->controllerNamespace, 'backend') !== false) {
            $this->setViewPath('@vsoft/news/views/backend');
        } elseif(strpos($this->controllerNamespace, 'frontend') !== false) {
            $this->setViewPath('@vsoft/news/views/frontend');
            $this->setLayoutPath('@vsoft/news/views/frontend/layouts');
        }else{
            $this->setViewPath('@vsoft/news/views');
        }
        // custom initialization code goes here
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return \Yii::t('funson86/' . $category, $message, $params, $language);
    }

}
