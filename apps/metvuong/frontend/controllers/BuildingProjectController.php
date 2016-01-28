<?php
namespace frontend\controllers;

use frontend\components\Controller;
use vsoft\buildingProject\models\BuildingProject;
use yii\data\Pagination;
use vsoft\ad\models\AdBuildingProject;
use yii\web\NotFoundHttpException;

class BuildingProjectController extends Controller
{
	public $layout = '@app/views/layouts/layoutFull';
	
	function actionIndex() {
        $models = AdBuildingProject::find()->where('`status` = ' . AdBuildingProject::STATUS_ENABLED)->all();
        return $this->render('index', ['models' => $models]);

//		$model = AdBuildingProject::find()->where('`status` = ' . AdBuildingProject::STATUS_ENABLED)->one();
//		if($model) {
//			return $this->render('index', ['model' => $model]);
//		} else {
//			throw new NotFoundHttpException('The requested page does not exist.');
//		}
//		$countQuery = clone $query;
//		$query = $query->orderBy('created_at DESC');
//
//		$pages = new Pagination(['totalCount' => $countQuery->count()]);
//
//		$models = $query->offset($pages->offset)->limit($pages->limit)->all();
//
//		return $this->render('index', [
//			'models' => $models,
//			'pages' => $pages,
//		]);
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