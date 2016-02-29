<?php

namespace vsoft\ad\models;

use frontend\models\User;
use Yii;
use vsoft\ad\models\base\AdContactInfoBase;

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
			[['mobile', 'phone'], 'string', 'length' => [7, 11]],
			['email', 'email'],
			[['product_id'], 'unique']
		];
	}
	
	public function loadDefaultValues($skipIfSet = true) {
		if(!Yii::$app->user->isGuest) {
			$this->name = Yii::$app->user->identity->profile->name;
			$this->email = Yii::$app->user->identity->profile->public_email;
		}
		
		return parent::loadDefaultValues($skipIfSet);
	}

	/**
	 * Auto register user for agent
	 */
	public function getUrl() {

	}

	public function autoRegister() {
		if(!empty($this->email)){
			$user = Yii::createObject(User::className());
			$user->setScenario('register');
			$user->email = $this->email;
			if(empty($this->username)){
				$this->username = $user->generateUsername();
			}
			$user->setAttributes($this->attributes);
		}
	}
}
