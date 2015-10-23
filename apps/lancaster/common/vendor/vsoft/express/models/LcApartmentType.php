<?php

namespace vsoft\express\models;

use vsoft\express\models\base\LcApartmentTypeBase;
use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "lc_apartment_type".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property LcPricing[] $lcPricings
 */
class LcApartmentType extends LcApartmentTypeBase
{
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
