<?php

namespace vsoft\express;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'vsoft\express\controllers\frontend';

    protected $_isBackend;

    public function init()
    {
        parent::init();

        if ($this->getIsBackend() === true) {
            $this->setViewPath('@vsoft/express/views/backend');
        } else {
            $this->setViewPath('@vsoft/express/views/frontend');
            $this->setLayoutPath('@vsoft/express/views/frontend/layouts');
        }
    }

    public function getIsBackend()
    {
        if ($this->_isBackend === null) {
            $this->_isBackend = strpos($this->controllerNamespace, 'backend') === false ? false : true;
        }

        return $this->_isBackend;
    }
}
