<?php
namespace vsoft\ad\controllers;

use yii\web\Controller;
use common\vendor\vsoft\ad\models\AdInvestor;

class InvestorController extends Controller
{
	public function actionCreate()
	{
		$model = new AdInvestor();
		
		return $this->render('create', ['model' => $model]);
	}
}