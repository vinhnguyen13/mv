<?php

namespace vsoft\express\models;

use kartik\helpers\Enum;
use vsoft\express\models\base\LcContactBase;
use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "lc_contact".
 *
 * @property integer $lc_contact_id
 * @property string $name
 * @property string $address
 * @property string $title
 * @property string $message
 * @property string $ip
 * @property string $agent
 * @property string $browser_type
 * @property string $browser_name
 * @property string $browser_version
 * @property string $platform
 * @property string $created_at
 */
class LcContact extends LcContactBase
{
    /**
     * TODO: Write function for contact
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                [['name', 'address', 'title'], 'required'],
                [['address'], 'email']
            ]);
    }

    public function beforeSave($insert)
    {
        $at = new Expression('NOW()');
        if ($this->isNewRecord || empty($this->created_at)  || $this->created_at < 1) {
            $this->created_at = $at;
            $this->created_by = Yii::$app->user->id;
        }
        $this->updated_at = $at;
        $this->updated_by = Yii::$app->user->id;

        $this->ip = Yii::$app->request->userIP;
        $this->agent = Yii::$app->request->userAgent;
        $this->browser_type = Enum::getBrowser()['code'];
        $this->browser_name = Enum::getBrowser()['name'];
        $this->browser_version = Enum::getBrowser()['version'];
        $this->platform = Enum::getBrowser()['platform'];
        return parent::beforeSave($insert);
    }

    public function sendContactMail(LcContact $objContact){
        return Yii::$app->mailer->compose(['text' => 'notifyReceivedEmail-text',], ['contact' => $objContact])
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo([$objContact->address])
            ->setSubject('[No-reply]Notify contact email')
            ->send();
    }

}
