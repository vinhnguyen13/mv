<?php
namespace frontend\controllers;

use yii\web\Controller;
use vsoft\buildingProject\models\BuildingProject;
use yii\data\Pagination;

class BuildingProjectController extends Controller
{
	public $layout = '@app/views/layouts/news';
	
	function actionIndex() {
		$query = BuildingProject::find()->where('`catalog_id` = ' . \Yii::$app->params['buildingCatID']);
		$countQuery = clone $query;
		$query = $query->orderBy('created_at DESC');
		
		$pages = new Pagination(['totalCount' => $countQuery->count()]);
		
		$models = $query->offset($pages->offset)->limit($pages->limit)->all();

		return $this->render('index', [
			'models' => $models,
			'pages' => $pages,
		]);
	}
	
	function actionView($slug) {
		$model = BuildingProject::find('`slug` => :slug', [':slug' => $slug])->one();
		 
		if($model) {
			return $this->render('view', ['model' => $model,]);
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}