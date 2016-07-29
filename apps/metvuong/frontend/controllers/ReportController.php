<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 7/26/2016
 * Time: 4:23 PM
 */

namespace frontend\controllers;


use frontend\components\Controller;
use frontend\models\Report;
use vsoft\express\models\SysEmail;
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
        $filter = Yii::$app->request->get("filter", 'week');
        $this->view->params = ArrayHelper::merge(['noFooter' => true, 'menuDashboard' => true, 'isReport' => true], $this->view->params);

        $chart = Report::me()->chart($filter);
        $data = ArrayHelper::merge($chart, ['filter'=>$filter]);
        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('default/index', $data);
        } else {
            return $this->render('default/index', $data);
        }
    }

    public function actionMail()
    {
        $this->view->params = ArrayHelper::merge(['noFooter' => true, 'menuDashboard' => true, 'isReport' => true], $this->view->params);
        $data['sysEmails'] = SysEmail::find()->limit(10)->all();
        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('mail/index', $data);
        } else {
            return $this->render('mail/index', $data);
        }

    }
}