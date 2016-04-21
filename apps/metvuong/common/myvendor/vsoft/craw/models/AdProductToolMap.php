<?php

namespace vsoft\craw\models;

use Yii;

/**
 * This is the model class for table "ad_product_tool_map".
 *
 * @property integer $product_main_id
 * @property integer $product_tool_id
 */
class AdProductToolMap extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_product_tool_map';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('dbCraw');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_main_id', 'product_tool_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_main_id' => 'Product Main ID',
            'product_tool_id' => 'Product Tool ID',
        ];
    }
}
