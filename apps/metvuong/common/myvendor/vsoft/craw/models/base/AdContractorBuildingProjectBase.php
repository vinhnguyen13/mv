<?php

namespace vsoft\craw\models\base;

use Yii;

/**
 * This is the model class for table "ad_contractor_building_project".
 *
 * @property integer $building_project_id
 * @property integer $contractor_id
 *
 * @property AdBuildingProject $buildingProject
 * @property AdContractor $contractor
 */
class AdContractorBuildingProjectBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_contractor_building_project';
    }

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
            [['building_project_id', 'contractor_id'], 'integer'],
            [['building_project_id', 'contractor_id'], 'unique', 'targetAttribute' => ['building_project_id', 'contractor_id'], 'message' => 'The combination of Building Project ID and Contractor ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'building_project_id' => Yii::t('contractor', 'Building Project ID'),
            'contractor_id' => Yii::t('contractor', 'Contractor ID'),
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
    public function getContractor()
    {
        return $this->hasOne(AdContractor::className(), ['id' => 'contractor_id']);
    }
}
