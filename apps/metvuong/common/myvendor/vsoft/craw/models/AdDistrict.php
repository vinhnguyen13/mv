<?php

namespace vsoft\craw\models;

use Yii;

/**
 * This is the model class for table "ad_district".
 *
 * @property integer $id
 * @property integer $city_id
 * @property string $name
 * @property string $geometry
 * @property string $center
 * @property string $color
 * @property string $pre
 * @property integer $order
 * @property integer $status
 *
 * @property AdBuildingProject[] $adBuildingProjects
 * @property AdCity $city
 * @property AdProduct[] $adProducts
 * @property AdStreet[] $adStreets
 * @property AdWard[] $adWards
 */
class AdDistrict extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_district';
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
            [['city_id', 'name'], 'required'],
            [['city_id', 'order', 'status'], 'integer'],
            [['geometry'], 'string'],
            [['name', 'center', 'color', 'pre'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'city_id' => 'City ID',
            'name' => 'Name',
            'geometry' => 'Geometry',
            'center' => 'Center',
            'color' => 'Color',
            'pre' => 'Pre',
            'order' => 'Order',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdBuildingProjects()
    {
        return $this->hasMany(AdBuildingProject::className(), ['district_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(AdCity::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdProducts()
    {
        return $this->hasMany(AdProduct::className(), ['district_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdStreets()
    {
        return $this->hasMany(AdStreet::className(), ['district_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdWards()
    {
        return $this->hasMany(AdWard::className(), ['district_id' => 'id']);
    }
    
    public function getFullName() {
    	return $this->pre ? $this->pre . ' ' . $this->name : $this->name;
    }
}
