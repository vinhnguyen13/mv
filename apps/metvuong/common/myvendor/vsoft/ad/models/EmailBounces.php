<?php

namespace vsoft\ad\models;

use vsoft\ad\models\base\EmailBouncesBase;
use Yii;

/**
 * This is the model class for table "email_bounces".
 *
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class EmailBounces extends EmailBouncesBase
{
    const STATUS_IS_FAKE = 1;
    const STATUS_NOT_FAKE = 0;

    public function tracking($emails, $status = 1){
        if(empty($emails)){
            return false;
        }
        if(is_array($emails)){
            foreach($emails as $email){
                $this->saveToBounces($emails, $status);
            }
        }else{
            $this->saveToBounces($emails, $status);
        }
        return true;
    }

    public function saveToBounces($email, $status = 1){
        $model = $this->findOne(['email'=>$email]);
        if(empty($model)){
            $model = new EmailBounces();
            $model->email = $email;
            $model->status = $status;
            $model->created_at = time();
        }else{
            $model->updated_at = time();
        }
        return $model->save();
    }
}
