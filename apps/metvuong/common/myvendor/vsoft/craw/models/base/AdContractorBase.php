<?php

namespace vsoft\craw\models\base;

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
class AdContractorBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_contractor';
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
            'id' => Yii::t('contractor', 'ID'),
            'name' => Yii::t('contractor', 'Name'),
            'address' => Yii::t('contractor', 'Address'),
            'phone' => Yii::t('contractor', 'Phone'),
            'fax' => Yii::t('contractor', 'Fax'),
            'website' => Yii::t('contractor', 'Website'),
            'email' => Yii::t('contractor', 'Email'),
            'description' => Yii::t('contractor', 'Description'),
            'created_at' => Yii::t('contractor', 'Created At'),
            'updated_at' => Yii::t('contractor', 'Updated At'),
            'status' => Yii::t('contractor', 'Status'),
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
