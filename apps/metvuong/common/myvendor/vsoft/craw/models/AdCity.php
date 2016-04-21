<?php

namespace vsoft\craw\models;

use Yii;

/**
 * This is the model class for table "ad_city".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $geometry
 * @property string $center
 * @property string $color
 * @property integer $order
 * @property integer $status
 *
 * @property AdBuildingProject[] $adBuildingProjects
 * @property AdDistrict[] $adDistricts
 * @property AdProduct[] $adProducts
 */
class AdCity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_city';
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
            [['code', 'name'], 'required'],
            [['geometry'], 'string'],
            [['order', 'status'], 'integer'],
            [['code'], 'string', 'max' => 4],
            [['name', 'center', 'color'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'geometry' => 'Geometry',
            'center' => 'Center',
            'color' => 'Color',
            'order' => 'Order',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdBuildingProjects()
    {
        return $this->hasMany(AdBuildingProject::className(), ['city_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdDistricts()
    {
        return $this->hasMany(AdDistrict::className(), ['city_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdProducts()
    {
        return $this->hasMany(AdProduct::className(), ['city_id' => 'id']);
    }
}
