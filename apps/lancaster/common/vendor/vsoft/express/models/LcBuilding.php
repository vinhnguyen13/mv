<?php

namespace vsoft\express\models;

use vsoft\express\models\base\LcBuildingBase;
use Yii;
use yii\db\Expression;

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
 *
 * @property LcBooking[] $lcBookings
 */
class LcBuilding extends LcBuildingBase
{
    /**
     * TODO: Write function for building
     */

    // Modify Label
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),
            ['isbooking' => 'Booking']);
    }

    public function beforeSave($insert)
    {
        $at = new Expression('NOW()');
        if ($this->isNewRecord || empty($this->created_at) || $this->created_at < 1) {
            $this->created_at = $at;
            $this->created_by = Yii::$app->user->id;
        }
        $this->updated_at = $at;
        $this->updated_by = Yii::$app->user->id;
        return parent::beforeSave($insert);
    }

}
