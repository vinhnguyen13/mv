<?php

namespace vsoft\building\models;

use vsoft\building\models\base\LcBuildingBase;
use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "lc_building".
 *
 * @property integer $id
 * @property string $building_name
 * @property string $address
 * @property string $phone
 * @property string $fax
 * @property string $email
 * @property string $hotline
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $isbooking
 *
 * @property LcBooking[] $lcBookings
 */
class LcBuilding extends LcBuildingBase
{
    /**
     * TODO: Write function for building
     */
	
	public static function imageLink($imageName) {
    	return '/store/building-project-images/' . $imageName;
    }
    
    public static function sectionArray() {
    	return [
			'apartments' => [
				['livingRoom', 'Living Room'],
				['kitchen', 'Kitchen'],
				['bedroom', 'Bedroom'],
				['bathroom', 'Bathroom'],
			],
			'amenities' => [
				['swimmingPool', 'Swimming Pool'],
				['fitnessCenter', 'Fitness Center'],
				['healthyCare', 'Healthy Care'],
				['skybar', 'Skybar']
			],
			'views' => [
				['north', 'North'],
				['east', 'East'],
				['south', 'South'],
				['west', 'West'],
			]
		];
    }

    // Modify Label
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),
            ['isbooking' => 'Booking']);
    }

    public function beforeSave($insert)
    {
        $at = new Expression('NOW()');
        if ($this->isNewRecord || empty($this->created_at) || $this->created_at < 1) {
            $this->created_at = $at;
            $this->created_by = Yii::$app->user->id;
        }
        $this->updated_at = $at;
        $this->updated_by = Yii::$app->user->id;
        
        $this->apartments = json_encode($this->apartments);
        $this->amenities = json_encode($this->amenities);
        $this->views = json_encode($this->views);
        
        return parent::beforeSave($insert);
    }
    
    public function getSectionTab($section, $sectionTab) {
    	if($this->$section) {
    		$return = json_decode($this->$section, TRUE);
    	} else {
    		$return = [];
    		foreach ($sectionTab as $s) {
    			$return[$s[0]] = ['content' => '', 'image' => ''];
    		}
    	}
    	return $return;
    }

    public function getPricing()
    {
    	return $this->hasMany(LcPricing::className(), ['building_id' => 'id']);
    }
}
