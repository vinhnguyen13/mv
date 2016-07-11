<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace common\components;

use frontend\models\Token;
use frontend\models\User;
use vsoft\ad\models\EmailBounces;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Mailer.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Mailer extends \yii\swiftmailer\Mailer
{
    public $transport;
    public function beforeSend($message)
    {
        if(!empty(Yii::$app->params['sender']) && !empty(Yii::$app->params['transport'])) {
            $sender = Yii::$app->params['sender'];
            $k = array_rand($sender);
            $transport = ArrayHelper::merge(
                Yii::$app->params['transport'],
                $sender[$k]
            );
            $this->transport = $transport;
            $this->setTransport($transport);
        }
        $address = $message->getTo();
        if(!empty($address)){
            if(is_array($address)){
                foreach($address as $email=>$name){
                    $exist = EmailBounces::findOne(['email'=>$email]);
                    if($exist){
                        unset($address[$email]);
                    }
                }
            }else{
                $exist = EmailBounces::findOne(['email'=>$address]);
                if($exist){
                    unset($address);
                }
            }
            $message->setTo($address);
        }
        return parent::beforeSend($message);
    }

    public function send($message)
    {
        try {
            if (!$this->beforeSend($message)) {
                return false;
            }
            $address = $message->getTo();
            if(empty($address)){
                return false;
            }
            if (is_array($address)) {
                $address = implode(', ', array_keys($address));
            }
            Yii::info('Sending email "' . $message->getSubject() . '" to "' . $address . '"', __METHOD__);
            if ($this->useFileTransport) {
                $isSuccessful = $this->saveMessage($message);
            } else {
                $isSuccessful = $this->sendMessage($message);
            }
            $this->afterSend($message, $isSuccessful);
            return $isSuccessful;
        } catch (\Swift_TransportException $ex) {
            $email = new EmailBounces();
            $email->tracking($address, 1);
            return false;
        }
    }
}
