<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 8/26/2016
 * Time: 1:54 PM
 */

namespace frontend\controllers;


use frontend\components\Controller;
use Yii;
use yii\web\Response;
use frontend\models\Tracking;

class TrackingController extends Controller
{
    /**
     * @param $tr
     * @param int $tp
     * @throws \yii\web\ServerErrorHttpException
     */
    public function actionLogo($tr, $tp=1)
    {
        Tracking::find()->fromLogo($tr, $tp);
        $avatarPath = Yii::getAlias('@webroot').( '/images/logo-white.png');
        $pathinfo = pathinfo($avatarPath);
        $response = Yii::$app->getResponse();
        $response->headers->set('Content-Type', 'image/'.$pathinfo['extension']);
        $response->format = Response::FORMAT_RAW;
        if ( !is_resource($response->stream = fopen($avatarPath, 'r')) ) {
            throw new \yii\web\ServerErrorHttpException('file access failed: permission deny');
        }
        return $response->send();
    }

    /**
     * @param $rd
     * @param $c
     * @param $e
     */
    public function actionMailClick($rd, $c, $e){
        $redirect = Tracking::find()->mailClick($rd, $c, $e);
        if(!empty($redirect)){
            $this->redirect($redirect);
            Yii::$app->end();
        }
        $this->redirect('/');
    }


}