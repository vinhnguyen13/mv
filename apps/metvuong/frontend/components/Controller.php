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