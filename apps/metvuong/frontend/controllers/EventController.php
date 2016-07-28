<?php
namespace frontend\controllers;

use yii\web\Controller;
use \Yii;
use yii\web\Response;
use frontend\models\RegistrationForm;
use vsoft\coupon\models\CouponHistory;
use frontend\models\Payment;

class EventController extends Controller {
	public $_module;
	
	public function init(){
		$this->_module = Yii::$app->getModule('user');
		parent::init();
	}
	
	public function actionRegisterInTime() {
		if(Yii::$app->request->isAjax && Yii::$app->request->isPost){
			Yii::$app->response->format = Response::FORMAT_JSON;
			$model = Yii::createObject(RegistrationForm::className());
			
			$post = Yii::$app->request->post();
			
			$model->load($post);
		
			$errors = [];
			
			if($model->password != $post['passwordConfirm']) {
				$errors['passwordConfirm'] = [\Yii::t('user', 'Mật khẩu nhập lại không khớp với mật khẩu ở trên')];
			}
			
			if($post['mobile']) {
				if(!ctype_digit($post['mobile'])) {
					$errors['mobile'] = [\Yii::t('user', 'Số di động không hợp lệ')];
				} else if(strlen($post['mobile']) < 7 || strlen($post['mobile']) > 11) {
					$errors['mobile'] = [\Yii::t('user', 'Số di động phải từ 7 đến 11 số')];
				}
			}
			
			if($model->validate()) {
				$user = $model->register();
				if (!empty($user) && Yii::$app->getUser()->login($user, $this->_module->rememberFor)) {

					$res = CouponHistory::checkCoupon(Yii::$app->user->id, 'I641J3');
					if (!empty($res['error_code'] == 0) && !empty($res['result']->couponCode->amount)) {
						Payment::me()->processTransactionByCoupon(Yii::$app->user->id, $res['result']);
					}
					
					return ['statusCode' => 200, 'parameters' => ['username' => !empty(Yii::$app->user->identity->profile->name) ? Yii::$app->user->identity->profile->name : Yii::$app->user->identity->email]];
				}
			} else {
				$errors = array_merge($errors, $model->errors);
			}
			
			return ['statusCode' => 404, 'parameters' => $errors];
		}
	}	
}