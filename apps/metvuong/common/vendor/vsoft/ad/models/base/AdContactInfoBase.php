<?php

namespace vsoft\ad\models\base;

use Yii;

/**
 * This is the model class for table "ad_contact_info".
 *
 * @property integer $product_id
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string $mobile
 * @property string $email
 *
 * @property AdProduct $product
 */
class AdContactInfoBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_contact_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'mobile'], 'required'],
            [['product_id'], 'integer'],
            [['name', 'phone', 'mobile'], 'string', 'max' => 32],
            [['address', 'email'], 'string', 'max' => 255],
            [['product_id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'name' => 'Name',
            'address' => 'Address',
            'phone' => 'Phone',
            'mobile' => 'Mobile',
            'email' => 'Email',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(AdProduct::className(), ['id' => 'product_id']);
    }
}
