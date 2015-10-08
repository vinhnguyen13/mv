<?php

namespace frontend\controllers;

class NewsController extends \yii\web\Controller
{
    public $layout = 'news';
    public function actions()
    {
        return [
            'review'=>[
                'class'=>'yii\web\ViewAction',
            ],
        ];
    }
    public function actionIndex()
    {
        return $this->render('index');
    }

}
