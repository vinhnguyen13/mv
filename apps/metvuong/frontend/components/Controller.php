<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/7/2015
 * Time: 11:16 AM
 */

namespace frontend\components;
use frontend\models\Cache;
use frontend\models\User;
use frontend\models\UserData;
use lajax\translatemanager\helpers\Language;
use Yii;
use yii\helpers\Url;

class Controller extends \yii\web\Controller
{
    protected $username;
    public function init() {
        Language::registerAssets();
        Yii::$app->assetManager->bundles = [
            'yii\bootstrap\BootstrapAsset' => [
                'css' => [],
            ],
            'yii\bootstrap\BootstrapPluginAsset' => [
                'js' => [],
            ],
            'yii\web\JqueryAsset' => [
                'js'=>[],
                'jsOptions' => ['position'=>\yii\web\View::POS_HEAD]
            ],
        ];
        parent::init();
    }

    public function beforeAction($action)
    {
        if(Yii::$app->user->isGuest && !in_array($action->id, ['login', 'register', 'error', 'map-image']) && !Yii::$app->request->isAjax){
            Yii::$app->getUser()->setReturnUrl(Url::current());
        }
        if(in_array($action->id, ['login'])){
            $redirect_url = Yii::$app->request->get('redirect_url');
            if(!empty($redirect_url)){
                Yii::$app->getUser()->setReturnUrl(Url::to($redirect_url));
            }

        }
        if(!Yii::$app->user->isGuest){
            $parseUrl = Yii::$app->urlManager->parseRequest(Yii::$app->request);
            $urlBase = !empty($parseUrl[0]) ? $parseUrl[0] : '';
            if(!empty($urlBase)){
                /**
                 * remove notify if is read
                 */
                switch($urlBase){
                    case 'notification/index':
                    case 'notification/list':
                        UserData::me()->removeAlert(Yii::$app->user->id, UserData::ALERT_OTHER);
                        Yii::$app->session->remove("notifyOther");
                        break;
                    case 'chat/index':
                    case 'chat/with':
                        UserData::me()->removeAlert(Yii::$app->user->id, UserData::ALERT_CHAT);
                        Yii::$app->session->remove("notifyChat");
                        break;
                }
            }
            /**
             * set value notify to view
             */
            $this->view->params['notify_other'] = Yii::$app->session->get("notifyOther") ? Yii::$app->session->get("notifyOther") : 0;
            $this->view->params['notify_chat'] = Yii::$app->session->get("notifyChat") ? Yii::$app->session->get("notifyChat") : 0;
            $this->view->params['notify_total'] = $this->view->params['notify_other'] + $this->view->params['notify_chat'];
            /**
             * clear cache if change language
             */
            if (!empty($_GET['language-change'])) {
                Cache::me()->delete(Cache::PRE_NOTIFICATION.Yii::$app->user->id);
            }
        }
        return parent::beforeAction($action);
    }

    protected function checkAccess()
    {
        if(Yii::$app->user->isGuest) {
            Yii::$app->response->redirect('/member/login');
            Yii::$app->end();
        }
        return true;
    }

    protected function checkIsMe()
    {
        $username = Yii::$app->request->get('username');
        if(!Yii::$app->user->identity->isMeByUsername($username)){
            return $this->goHome();
        }
    }

    protected  function checkAliasAvailable(){
        if(!Yii::$app->request->isAjax && !empty($_GET['username'])) {
            $total = User::find()->where('aliasname = :usrn', [':usrn' => $_GET['username']])->count();
            if (empty($total)) {
                $user = User::find()->where('"[[:<:]]' . $_GET['username'] . '[[:>:]]" RLIKE aliashistory')->one();
                if (!empty($user)) {
                    $url = Url::current();
                    $parseUrl = Yii::$app->urlManager->parseRequest(Yii::$app->request);
                    list ($route, $params) = $parseUrl;
                    !empty($params['username']) ? $params['username'] = $user->getUsername() : '';
                    array_unshift($params, $route);
                    $url = Url::to($params);
                    Yii::$app->response->redirect($url);
                    Yii::$app->end();
                }
            }
        }
    }
}