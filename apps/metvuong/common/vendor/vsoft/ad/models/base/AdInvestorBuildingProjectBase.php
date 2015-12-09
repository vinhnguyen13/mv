<?php

namespace vsoft\ad\models\base;

use Yii;

/**
 * This is the model class for table "ad_investor_building_project".
 *
 * @property integer $building_project_id
 * @property integer $investor_id
 *
 * @property AdBuildingProject $buildingProject
 * @property AdInvestor $investor
 */
class AdInvestorBuildingProjectBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_investor_building_project';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['building_project_id', 'investor_id'], 'integer'],
            [['building_project_id', 'investor_id'], 'unique', 'targetAttribute' => ['building_project_id', 'investor_id'], 'message' => 'The combination of Building Project ID and Investor ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'building_project_id' => 'Building Project ID',
            'investor_id' => 'Investor ID',
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
    public function getInvestor()
    {
        return $this->hasOne(AdInvestor::className(), ['id' => 'investor_id']);
    }
}
