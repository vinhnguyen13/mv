<?php

namespace vsoft\express\controllers\frontend;

use vsoft\express\models\LcContact;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

class ContactController extends \yii\web\Controller
{
    public $layout = '@app/views/layouts/news';
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
