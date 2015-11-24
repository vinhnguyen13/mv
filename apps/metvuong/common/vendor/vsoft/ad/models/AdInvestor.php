<?php

namespace common\vendor\vsoft\ad\models;

use Yii;
use common\vendor\vsoft\ad\models\base\AdInvestorBase;

/**
 * This is the model class for table "ad_investor".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string $fax
 * @property string $website
 * @property string $email
 *
 * @property AdInvestorBuildingProject[] $adInvestorBuildingProjects
 */
class AdInvestor extends AdInvestorBase
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_investor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'phone', 'fax'], 'string', 'max' => 32],
            [['address', 'website', 'email'], 'string', 'max' => 255]
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
