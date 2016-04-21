<?php

namespace vsoft\craw\models;

use Yii;

/**
 * This is the model class for table "ad_category".
 *
 * @property integer $id
 * @property string $name
 * @property integer $apply_to_type
 * @property integer $order
 * @property integer $status
 * @property integer $template
 * @property integer $limit_area
 *
 * @property AdBuildingProjectCategory[] $adBuildingProjectCategories
 * @property AdProduct[] $adProducts
 */
class AdCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_category';
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
            [['name', 'apply_to_type'], 'required'],
            [['apply_to_type', 'order', 'status', 'template', 'limit_area'], 'integer'],
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
            'template' => 'Template',
            'limit_area' => 'Limit Area',
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
