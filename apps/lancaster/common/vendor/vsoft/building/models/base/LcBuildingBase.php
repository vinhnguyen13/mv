<?php

namespace vsoft\building\models\base;

use vsoft\building\models\LcBooking;
use Yii;
use common\components\ActiveRecordTranslation;

/**
 * This is the model class for table "lc_building".
 *
 * @property integer $id
 * @property string $building_name
 * @property string $address
 * @property string $phone
 * @property string $fax
 * @property string $email
 * @property string $hotline
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $isbooking
 * @property integer $floor
 *
 * @property LcBooking[] $lcBookings
 */
class LcBuildingBase extends ActiveRecordTranslation
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lc_building';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by', 'isbooking', 'floor'], 'integer'],
            [['building_name', 'email'], 'string', 'max' => 60],
            [['address', 'description'], 'string', 'max' => 255],
            [['phone', 'fax', 'hotline'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('building', 'ID'),
            'building_name' => Yii::t('building', 'Building Name'),
            'address' => Yii::t('building', 'Address'),
            'introduction_title' => Yii::t('building', 'Introduction Title'),
            'introduction_content' => Yii::t('building', 'Introduction Content'),
            'phone' => Yii::t('building', 'Phone'),
            'fax' => Yii::t('building', 'Fax'),
            'email' => Yii::t('building', 'Email'),
            'hotline' => Yii::t('building', 'Hotline'),
            'description' => Yii::t('building', 'Description'),
            'created_at' => Yii::t('building', 'Created At'),
            'updated_at' => Yii::t('building', 'Updated At'),
            'created_by' => Yii::t('building', 'Created By'),
            'updated_by' => Yii::t('building', 'Updated By'),
            'isbooking' => Yii::t('building', 'Isbooking'),
            'floor' => Yii::t('building', 'Floor'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLcBookings()
    {
        return $this->hasMany(LcBooking::className(), ['lc_building_id' => 'id']);
    }
}
