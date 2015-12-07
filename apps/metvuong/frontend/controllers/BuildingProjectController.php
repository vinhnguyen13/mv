<?php
namespace frontend\controllers;

use frontend\components\Controller;
use vsoft\buildingProject\models\BuildingProject;
use yii\data\Pagination;
use common\vendor\vsoft\ad\models\AdBuildingProject;

class BuildingProjectController extends Controller
{
	public $layout = '@app/views/layouts/news';
	
	function actionIndex() {
		$query = AdBuildingProject::find()->where('`status` = ' . AdBuildingProject::STATUS_ENABLED);
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
		$model = AdBuildingProject::find()->where('`slug` = :slug', [':slug' => $slug])->one();
		
		if($model) {
			return $this->render('view', ['model' => $model,]);
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}