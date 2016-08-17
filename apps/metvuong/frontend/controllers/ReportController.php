<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 7/26/2016
 * Time: 4:23 PM
 */

namespace frontend\controllers;


use common\components\Acl;
use frontend\components\Controller;
use frontend\models\Report;
use vsoft\express\models\SysEmail;
use yii\helpers\ArrayHelper;
use Yii;
use yii\web\Response;

class ReportController extends Controller
{
    public $layout = '@app/views/layouts/layout';
    public function beforeAction($action)
    {
        $this->checkAccess();
        $permissionName = !empty(Yii::$app->setting->get('aclReport')) ? Yii::$app->setting->get('aclReport') : Acl::ACL_REPORT;
        Acl::me()->checkACL($permissionName);
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $this->view->params = ArrayHelper::merge(['noFooter' => true, 'menuDaily' => true, 'isDashboard' => true], $this->view->params);
        $filter = Yii::$app->request->get("filter", 'week');

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
        $this->view->params = ArrayHelper::merge(['noFooter' => true, 'menuMail' => true, 'isDashboard' => true], $this->view->params);
        $data['sysEmails'] = SysEmail::find()->orderBy(['send_time' => SORT_DESC])->limit(10)->all();
        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('mail/index', $data);
        } else {
            return $this->render('mail/index', $data);
        }

    }

    public function actionClickChart(){
        if(Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_HTML;

            $type = (int)Yii::$app->request->get("type");
            $date = Yii::$app->request->get("date");
            switch($type){
                case Report::TYPE_REGISTER;
                    $viewItem = 'list-user';
                    break;
                case Report::TYPE_LOGIN;
                    $viewItem = 'list-user';
                    break;
                case Report::TYPE_LISTING;
                    $viewItem = 'list-listing';
                    break;
                case Report::TYPE_TRANSACTION;
                    $viewItem = 'list-transaction';
                    break;
            }
            $data = Report::me()->chartDetail($type, $date);
            return $this->renderAjax('default/_partials/'.$viewItem, [
                'data'=>$data,
            ]);
        }
        return false;
    }
}