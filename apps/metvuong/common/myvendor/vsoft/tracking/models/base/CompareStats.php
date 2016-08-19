<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 1/14/2016
 * Time: 1:58 PM
 */
namespace vsoft\tracking\models\base;

use yii\mongodb\ActiveRecord;

class CompareStats extends ActiveRecord
{
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'compare_stats';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['alias', 'products'], 'required'],
            [['user_id', 'time', 'count', 'device'], 'integer'],
        ];
    }

    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return ['_id', 'user_id', 'ip', 'alias', 'products', 'time', 'count', 'device'];
    }

}