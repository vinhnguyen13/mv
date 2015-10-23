<?php

namespace vsoft\express\controllers\frontend;

use yii\web\Controller;

class DefaultController extends Controller
{
    public $layout = '@app/views/layouts/news';
    public function actionIndex()
    {
        return $this->render('index');
    }
}
