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
        $permissionName = !empty(Yii::$app->setting->get('aclAdmin')) ? Yii::$app->setting->get('aclAdmin') : 'Admin';
        if (Yii::$app->user->can($permissionName)) {
            return true;
        }
        throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
    }
}