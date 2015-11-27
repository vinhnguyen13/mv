<?php

namespace common\vendor\vsoft\ad\models\base;

use Yii;

/**
 * This is the model class for table "ad_building_project_category".
 *
 * @property integer $building_project_id
 * @property integer $category_id
 *
 * @property AdBuildingProject $buildingProject
 * @property AdCategory $category
 */
class AdBuildingProjectCategoryBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_building_project_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['building_project_id', 'category_id'], 'required'],
            [['building_project_id', 'category_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'building_project_id' => 'Building Project ID',
            'category_id' => 'Category ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBuildingProject()
    {
        return $this->hasOne(AdBuildingProject::className(), ['id' => 'building_project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(AdCategory::className(), ['id' => 'category_id']);
    }
}
