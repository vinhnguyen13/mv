<?php

namespace vsoft\express\models;

use vsoft\express\models\base\LcPricingBase;
use Yii;
use yii\db\Expression;
use yii\db\Query;

/**
 * This is the model class for table "lc_pricing".
 *
 * @property integer $id
 * @property integer $apart_type_id
 * @property integer $area
 * @property string $monthly_rates
 * @property string $daily_rates
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property LcApartmentType $apartType
 */
class LcPricing extends LcPricingBase
{

    public function beforeSave($insert)
    {
        // remove ','
        $this->area = str_replace(',', '', $this->area);
        $this->monthly_rates = str_replace(',', '', $this->monthly_rates);
        $this->daily_rates = str_replace(',', '', $this->daily_rates);

        $at = new Expression('NOW()');
        if ($this->isNewRecord || empty($this->created_at) || $this->created_at < 1) {
            $this->created_at = $at;
            $this->created_by = Yii::$app->user->id;
        }
        $this->updated_at = $at;
        $this->updated_by = Yii::$app->user->id;
        return parent::beforeSave($insert);
    }

    public function rules(){
        return array_merge(
            parent::rules(),
            [
                [['area', 'apart_type_id','monthly_rates', 'daily_rates'], 'required'],
//                [['myAt'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/'],
            ]);
    }

    public function getAllPricing() {
        $query = new Query;
        $query->select(['area', 'apart_type_id','monthly_rates', 'daily_rates', 'name'])
            ->from('lc_pricing p')
            ->leftJoin('lc_apartment_type a', 'p.apart_type_id = a.id')
//            ->where('area > :a', [':a' => 100]);
            ->orderBy('p.id');
//            ->limit(2);
        $command = $query->createCommand();
        $pricings = $command->queryAll();
        return $pricings;
    }

}
