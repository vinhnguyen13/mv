<?php

namespace vsoft\express\models;

use kartik\helpers\Enum;
use vsoft\express\models\base\LcBookingBase;
use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "lc_booking".
 *
 * @property integer $id
 * @property integer $lc_building_id
 * @property string $checkin
 * @property string $checkout
 * @property integer $apart_type
 * @property integer $floorplan
 * @property string $fullname
 * @property string $phone
 * @property string $email
 * @property string $address
 * @property string $passport_no
 * @property string $nationality
 * @property string $info
 * @property string $ip
 * @property string $agent
 * @property string $browser_type
 * @property string $browser_name
 * @property string $browser_version
 * @property string $platform
 * @property string $created_at
 *
 * @property LcBuilding $lcBuilding
 */
class LcBooking extends LcBookingBase
{
    /**
     * TODO: Write function for booking
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                [['checkin','checkout','phone', 'fullname', 'email'], 'required'],
                [['email'], 'email'],
//                [['phone'], 'number'],
//                [['checkin'], 'compare', 'compareAttribute'=>'checkout', 'operator'=>'<', 'skipOnEmpty'=>true],
                [['checkout'], 'compare', 'compareAttribute'=>'checkin', 'operator'=>'>'],
            ]);
    }


    public function beforeSave($insert)
    {
        $at = new Expression('NOW()');
        if ($this->isNewRecord || empty($this->created_at) || $this->created_at < 1) {
            $this->created_at = $at;
//            $this->created_by = Yii::$app->user->id;
        }
        $this->checkin = date("Y-m-d H:i:s", strtotime($this->checkin));
        $this->checkout = date("Y-m-d H:i:s", strtotime($this->checkout));

        $this->updated_at = $at;
//        $this->updated_by = Yii::$app->user->id;

        $this->ip = Yii::$app->request->userIP;
        $this->agent = Yii::$app->request->userAgent;
        $this->browser_type = Enum::getBrowser()['code'];
        $this->browser_name = Enum::getBrowser()['name'];
        $this->browser_version = Enum::getBrowser()['version'];
        $this->platform = Enum::getBrowser()['platform'];
        return parent::beforeSave($insert);
    }

    public function getApartType()
    {
        return $this->hasOne(LcApartmentType::className(), ['id' => 'apart_type']);
    }

    public function sendBookingMail(LcBooking $objContact){
        return Yii::$app->mailer->compose(['text' => 'notifyReceivedEmail-text',], ['contact' => $objContact, 'isbooking' => 'Y'])
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo([$objContact->email])
            ->setSubject('[No-reply] Notify contact email***')
            ->send();
    }

}
