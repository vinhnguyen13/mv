<?php

namespace vsoft\express\models\base;

use vsoft\express\models\LcBuilding;
use Yii;

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
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property LcBuilding $lcBuilding
 */
class LcBookingBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lc_booking';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lc_building_id', 'apart_type', 'floorplan', 'created_by', 'updated_by'], 'integer'],
            [['checkin', 'checkout', 'created_at', 'updated_at'], 'safe'],
            [['info'], 'string'],
            [['fullname'], 'string', 'max' => 100],
            [['phone', 'passport_no'], 'string', 'max' => 15],
            [['email', 'nationality', 'browser_name', 'platform'], 'string', 'max' => 60],
            [['address', 'agent'], 'string', 'max' => 255],
            [['ip'], 'string', 'max' => 40],
            [['browser_type'], 'string', 'max' => 45],
            [['browser_version'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('express/booking', 'ID'),
            'lc_building_id' => Yii::t('express/booking', 'Lc Building ID'),
            'checkin' => Yii::t('express/booking', 'Checkin'),
            'checkout' => Yii::t('express/booking', 'Checkout'),
            'apart_type' => Yii::t('express/booking', 'Apart Type'),
            'floorplan' => Yii::t('express/booking', 'Floorplan'),
            'fullname' => Yii::t('express/booking', 'Fullname'),
            'phone' => Yii::t('express/booking', 'Phone'),
            'email' => Yii::t('express/booking', 'Email'),
            'address' => Yii::t('express/booking', 'Address'),
            'passport_no' => Yii::t('express/booking', 'Passport No'),
            'nationality' => Yii::t('express/booking', 'Nationality'),
            'info' => Yii::t('express/booking', 'Info'),
            'ip' => Yii::t('express/booking', 'Ip'),
            'agent' => Yii::t('express/booking', 'Agent'),
            'browser_type' => Yii::t('express/booking', 'Browser Type'),
            'browser_name' => Yii::t('express/booking', 'Browser Name'),
            'browser_version' => Yii::t('express/booking', 'Browser Version'),
            'platform' => Yii::t('express/booking', 'Platform'),
            'created_at' => Yii::t('express/booking', 'Created At'),
            'updated_at' => Yii::t('express/booking', 'Updated At'),
            'created_by' => Yii::t('express/booking', 'Created By'),
            'updated_by' => Yii::t('express/booking', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLcBuilding()
    {
        return LcBuilding::findOne($this->lc_building_id);
//        return $this->hasOne(LcBuilding::className(), ['id' => 'lc_building_id']);
    }
}
