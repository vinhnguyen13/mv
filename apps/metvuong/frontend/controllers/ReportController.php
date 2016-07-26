<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 7/26/2016
 * Time: 4:23 PM
 */

namespace frontend\controllers;


use frontend\components\Controller;
use yii\helpers\ArrayHelper;
use Yii;

class ReportController extends Controller
{
    public $layout = '@app/views/layouts/layout';
    public function beforeAction($action)
    {
        $this->checkAccess();
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $this->view->params = ArrayHelper::merge(['noFooter' => true, 'menuDashboard' => true, 'isReport' => true], $this->view->params);

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('default/index');
        } else {
            return $this->render('default/index');
        }
    }
}