<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ad_contractor".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string $fax
 * @property string $website
 * @property string $email
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 *
 * @property AdContractorBuildingProject[] $adContractorBuildingProjects
 */
class AdContractor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_contractor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'created_at'], 'required'],
            [['created_at', 'updated_at', 'status'], 'integer'],
            [['name', 'address', 'website', 'email'], 'string', 'max' => 255],
            [['phone', 'fax'], 'string', 'max' => 32],
            [['description'], 'string', 'max' => 1022]
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
            'address' => 'Address',
            'phone' => 'Phone',
            'fax' => 'Fax',
            'website' => 'Website',
            'email' => 'Email',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdContractorBuildingProjects()
    {
        return $this->hasMany(AdContractorBuildingProject::className(), ['contractor_id' => 'id']);
    }
}
