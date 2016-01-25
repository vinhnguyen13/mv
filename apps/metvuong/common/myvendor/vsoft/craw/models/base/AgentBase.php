<?php

namespace vsoft\craw\models\base;

use Yii;

/**
 * This is the model class for table "agent".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $mobile
 * @property string $phone
 * @property string $fax
 * @property string $email
 * @property string $website
 * @property string $tax_code
 * @property integer $rating
 * @property string $working_area
 * @property integer $source
 * @property integer $type
 * @property integer $updated_at
 */
class AgentBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agent';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
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
            [['rating', 'source', 'type', 'updated_at'], 'integer'],
            [['name', 'email', 'website'], 'string', 'max' => 255],
            [['address'], 'string', 'max' => 500],
            [['mobile', 'phone', 'fax', 'tax_code'], 'string', 'max' => 32],
            [['working_area'], 'string', 'max' => 2000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('agent', 'ID'),
            'name' => Yii::t('agent', 'Name'),
            'address' => Yii::t('agent', 'Address'),
            'mobile' => Yii::t('agent', 'Mobile'),
            'phone' => Yii::t('agent', 'Phone'),
            'fax' => Yii::t('agent', 'Fax'),
            'email' => Yii::t('agent', 'Email'),
            'website' => Yii::t('agent', 'Website'),
            'tax_code' => Yii::t('agent', 'Tax Code'),
            'rating' => Yii::t('agent', 'Rating'),
            'working_area' => Yii::t('agent', 'Working Area'),
            'source' => Yii::t('agent', 'Source'),
            'type' => Yii::t('agent', 'Type'),
            'updated_at' => Yii::t('agent', 'Updated At'),
        ];
    }
}
