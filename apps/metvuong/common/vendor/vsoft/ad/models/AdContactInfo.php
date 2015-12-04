<?php

namespace common\vendor\vsoft\ad\models;

use Yii;
use common\vendor\vsoft\ad\models\base\AdContactInfoBase;

/**
 * This is the model class for table "ad_contact_info".
 *
 * @property integer $product_id
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string $mobile
 * @property string $email
 *
 * @property AdProduct $product
 */
class AdContactInfo extends AdContactInfoBase
{

	public function rules()
	{
		return [
		[['mobile'], 'required'],
		[['product_id'], 'integer'],
		[['name', 'phone', 'mobile'], 'string', 'max' => 32],
		[['address', 'email'], 'string', 'max' => 255],
		[['product_id'], 'unique']
		];
	}
	
	public function loadDefaultValues($skipIfSet = true) {
		$this->name = Yii::$app->user->identity->profile->name;
		$this->email = Yii::$app->user->identity->profile->public_email;
		
		return parent::loadDefaultValues($skipIfSet);
	}
}
