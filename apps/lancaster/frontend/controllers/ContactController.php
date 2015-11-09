<?php

namespace frontend\controllers;
use Yii;
use vsoft\building\models\LcContact;

class ContactController extends \yii\web\Controller
{
    public $layout = '@app/views/layouts/layout';
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSendContact(){
        $model = new LcContact();
        if ($model->load(Yii::$app->request->post())) {
            if($model->save()) {
                // call send mail method after click submit button
                $model->sendContactMail($model);
                Yii::$app->getSession()->setFlash('reSent', 'Your contact is sent. Thank you!');
            }
            return $this->redirect(['/contact']);
        }
//        else {
//            return $this->render('create', [
//                'model' => $model,
//            ]);
//        }
    }
}
