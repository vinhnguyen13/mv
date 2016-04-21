<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ad_area_type".
 *
 * @property integer $id
 * @property integer $building_project_id
 * @property integer $type
 * @property string $floor_plan
 * @property string $payment
 * @property string $promotion
 * @property string $document
 *
 * @property AdBuildingProject $buildingProject
 */
class AdAreaType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_area_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['building_project_id', 'type'], 'required'],
            [['building_project_id', 'type'], 'integer'],
            [['floor_plan', 'payment', 'promotion', 'document'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'building_project_id' => 'Building Project ID',
            'type' => 'Type',
            'floor_plan' => 'Floor Plan',
            'payment' => 'Payment',
            'promotion' => 'Promotion',
            'document' => 'Document',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBuildingProject()
    {
        return $this->hasOne(AdBuildingProject::className(), ['id' => 'building_project_id']);
    }
}
