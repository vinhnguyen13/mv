<?php

namespace vsoft\user;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'vsoft\user\controllers';
    public $rememberFor = 1209600; // two weeks

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
