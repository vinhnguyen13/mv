<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ad_facility".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $status
 *
 * @property AdFacilityBuildingProject[] $adFacilityBuildingProjects
 */
class AdFacility extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_facility';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'created_by', 'updated_by', 'status'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 1022],
            [['name'], 'unique']
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
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdFacilityBuildingProjects()
    {
        return $this->hasMany(AdFacilityBuildingProject::className(), ['facility_id' => 'id']);
    }
}
