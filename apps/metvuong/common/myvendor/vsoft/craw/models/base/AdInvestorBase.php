<?php

namespace vsoft\craw\models\base;

use Yii;

/**
 * This is the model class for table "ad_investor".
 *
 * @property integer $id
 * @property string $name
 * @property string $logo
 * @property string $address
 * @property string $phone
 * @property string $fax
 * @property string $website
 * @property string $email
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 *
 * @property AdInvestorBuildingProject[] $adInvestorBuildingProjects
 */
class AdInvestorBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_investor';
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
            [['logo', 'phone', 'fax'], 'string', 'max' => 32]
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
            'logo' => 'Logo',
            'address' => 'Address',
            'phone' => 'Phone',
            'fax' => 'Fax',
            'website' => 'Website',
            'email' => 'Email',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdInvestorBuildingProjects()
    {
        return $this->hasMany(AdInvestorBuildingProject::className(), ['investor_id' => 'id']);
    }
}
