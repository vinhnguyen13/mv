<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 7/29/2016
 * Time: 10:49 AM
 */

namespace common\components;
use Yii;
use yii\base\Component;
use yii\web\ForbiddenHttpException;


class Acl extends Component
{
    const ACL_ADMIN = 'Admin';
    const ACL_REPORT = 'Report';

    public static function me()
    {
        return Yii::createObject(self::className());
    }

    public function checkACL($permissionName)
    {
        $parseUrl = Yii::$app->urlManager->parseRequest(Yii::$app->request);
        $urlBase = !empty($parseUrl[0]) ? $parseUrl[0] : '';
        if(!Yii::$app->user->isGuest && !in_array($urlBase, ['site/logout'])) {
            if (Yii::$app->user->can($permissionName)) {
                return true;
            }
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

}