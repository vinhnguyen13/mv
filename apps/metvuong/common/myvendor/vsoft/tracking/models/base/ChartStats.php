<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 1/14/2016
 * Time: 1:58 PM
 */
namespace vsoft\tracking\models\base;

use yii\mongodb\ActiveRecord;

class ChartStats extends ActiveRecord
{
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'chart_stats';
    }
    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['date', 'product_id'], 'required'],
            [['product_id', 'search', 'visit', 'favorite', 'share', 'created_at'], 'integer'],
            [['date'], 'string'],
        ];
    }
    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return ['_id', 'date', 'product_id', 'search', 'visit', 'favorite', 'share', 'created_at'];
    }

}