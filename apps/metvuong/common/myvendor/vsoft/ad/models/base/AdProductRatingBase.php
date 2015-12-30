<?php

namespace vsoft\ad\models\base;

use Yii;

/**
 * This is the model class for table "ad_product_rating".
 *
 * @property integer $user_id
 * @property integer $product_id
 * @property string $core
 * @property integer $rating_at
 *
 * @property AdProduct $product
 * @property User $user
 */
class AdProductRatingBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_product_rating';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'product_id', 'rating_at'], 'required'],
            [['user_id', 'product_id', 'rating_at'], 'integer'],
            [['core'], 'number']
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
            'core' => 'Core',
            'rating_at' => 'Rating At',
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
