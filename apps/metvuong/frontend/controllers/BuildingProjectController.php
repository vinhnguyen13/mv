<?php
namespace frontend\controllers;

use yii\web\Controller;

class BuildingProjectController extends Controller
{
	public $layout = '@app/views/layouts/news';
	
	function actionIndex() {
		return $this->render('index');
	}
	
	function actionView() {
		return $this->render('view');
	}
}