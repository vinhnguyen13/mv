<?php

namespace vsoft\ad\models\base;

use Yii;

/**
 * This is the model class for table "ad_architect".
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
 * @property AdArchitectBuildingProject[] $adArchitectBuildingProjects
 */
class AdArchitectBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_architect';
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
            'id' => Yii::t('architect', 'ID'),
            'name' => Yii::t('architect', 'Name'),
            'address' => Yii::t('architect', 'Address'),
            'phone' => Yii::t('architect', 'Phone'),
            'fax' => Yii::t('architect', 'Fax'),
            'website' => Yii::t('architect', 'Website'),
            'email' => Yii::t('architect', 'Email'),
            'description' => Yii::t('architect', 'Description'),
            'created_at' => Yii::t('architect', 'Created At'),
            'updated_at' => Yii::t('architect', 'Updated At'),
            'status' => Yii::t('architect', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdArchitectBuildingProjects()
    {
        return $this->hasMany(AdArchitectBuildingProject::className(), ['architect_id' => 'id']);
    }
}
