<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/7/2015
 * Time: 11:16 AM
 */

namespace frontend\components;
use Yii;
use yii\helpers\Url;

class Controller extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        if(Yii::$app->user->isGuest && !in_array($action->id, ['login', 'register', 'error', 'map-image']) && !Yii::$app->request->isAjax){
            Yii::$app->getUser()->setReturnUrl(Url::current());
        }
        if(!Yii::$app->user->isGuest){
            $parseUrl = Yii::$app->urlManager->parseRequest(Yii::$app->request);
            $urlBase = !empty($parseUrl[0]) ? $parseUrl[0] : '';
            if(!empty($urlBase)){
                /**
                 * remove notify if is read
                 */
                switch($urlBase){
                    case 'dashboard/notification':
                        Yii::$app->session->remove("notifyOther");
                        break;
                    case 'chat/index':
                    case 'chat/with':
                        Yii::$app->session->remove("notifyChat");
                        break;
                }
            }
            /**
             * test
             * @todo: remove if done
             */
            if($urlBase == 'site/index'){
                Yii::$app->session->set("notifyOther",rand(1, 20));
                Yii::$app->session->set("notifyChat",rand(1, 20));
            }
            /**
             * set value notify to view
             */
            $this->view->params['notify_other'] = Yii::$app->session->get("notifyOther") ? Yii::$app->session->get("notifyOther") : 0;
            $this->view->params['notify_chat'] = Yii::$app->session->get("notifyChat") ? Yii::$app->session->get("notifyChat") : 0;
            $this->view->params['notify_total'] = $this->view->params['notify_other'] + $this->view->params['notify_chat'];
        }
        return parent::beforeAction($action);
    }

    protected function checkAccess()
    {
        if(Yii::$app->user->isGuest) {
            $this->redirect('/member/login');
        }
        return true;
    }
}