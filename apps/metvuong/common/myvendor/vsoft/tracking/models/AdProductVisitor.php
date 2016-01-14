<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 1/14/2016
 * Time: 1:58 PM
 */
namespace vsoft\tracking\models;

use yii\mongodb\ActiveRecord;

class AdProductVisitor extends ActiveRecord
{
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'ad_product_visitor';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['user_id', 'product_id'], 'required'],
            [['user_id', 'product_id', 'time', 'count'], 'integer'],
        ];
    }

    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return ['_id', 'user_id', 'product_id', 'time', 'count'];
    }
}