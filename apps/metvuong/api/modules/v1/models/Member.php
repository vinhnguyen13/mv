<?php
namespace api\modules\v1\models;
use dektrium\user\models\LoginForm;
use Yii;
use dektrium\user\models\Profile;
use dektrium\user\models\User;
use OAuth2\Storage\UserCredentialsInterface;

/**
 * Country Model
 *
 * @author Budi Irawan <deerawan@gmail.com>
 */
class Member extends User implements UserCredentialsInterface
{
	public function getUserDetails($username)
	{
		$user = self::findOne(['username'=>$username]);
		if(!empty($user)){
			return ['user_id'=>$user->id];
		}
		return false;
	}

	public function checkUserCredentials($username, $password)
	{
		$data = [
			'login-form' => [
				'login' => $username,
				'password' => $password,
			]
		];
		$model = Yii::createObject(LoginForm::className());
		$model->load($data);
		if ($model->login()) {
			return ['code'=>200, 'message'=>'Login Success', 'token'=>Yii::$app->user->identity->getAuthKey()];
		}
		return false;


	}


}
