<?php
namespace vsoft\ad\controllers;

use yii\web\Controller;
use common\vendor\vsoft\ad\models\AdBuildingProject;

class DefaultController extends Controller
{
	public function actionIndex()
	{
		
	}
	public function actionCreate()
	{
		$model = new AdBuildingProject();
		
		return $this->render('create', ['model' => $model]);
	}
}