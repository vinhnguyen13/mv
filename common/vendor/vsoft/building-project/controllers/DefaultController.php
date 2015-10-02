<?php

namespace vsoft\buildingProject\controllers;

use yii\web\Controller;
use yii\helpers\Url;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionCreate() {
    	return $this->render('create');
    }
}
