<?php

namespace vsoft\ad\models\base;

use Yii;

/**
 * This is the model class for table "ad_product_saved".
 *
 * @property integer $user_id
 * @property integer $product_id
 * @property integer $saved_at
 *
 * @property AdProduct $product
 * @property User $user
 */
class AdProductSavedBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_product_saved';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'product_id', 'saved_at'], 'required'],
            [['user_id', 'product_id', 'saved_at'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'product_id' => 'Product ID',
            'saved_at' => 'Saved At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(AdProduct::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
