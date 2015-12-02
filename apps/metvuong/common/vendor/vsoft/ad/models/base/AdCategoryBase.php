<?php

namespace common\vendor\vsoft\ad\models\base;

use Yii;

/**
 * This is the model class for table "ad_category".
 *
 * @property integer $id
 * @property string $name
 * @property integer $apply_to_type
 * @property integer $order
 * @property integer $status
 *
 * @property AdBuildingProjectCategory[] $adBuildingProjectCategories
 * @property AdProduct[] $adProducts
 */
class AdCategoryBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'apply_to_type'], 'required'],
            [['apply_to_type', 'order', 'status'], 'integer'],
            [['name'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'apply_to_type' => 'Apply To Type',
            'order' => 'Order',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdBuildingProjectCategories()
    {
        return $this->hasMany(AdBuildingProjectCategory::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdProducts()
    {
        return $this->hasMany(AdProduct::className(), ['category_id' => 'id']);
    }
}
