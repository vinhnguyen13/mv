<?php
namespace vsoft\ad\controllers;

use Yii;
use yii\web\Controller;
use common\vendor\vsoft\ad\models\AdCategory;
use backend\components\Sort;

class CategoryController extends Controller
{
	public function actionIndex() {
		$searchModel = new AdCategory();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	public function actionCreate() {
		$model = new AdCategory();
		
		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());

			if($model->validate()) {
				$model->save(false);
				 
				return $this->redirect('index');
			}
		}
		return $this->render('create', ['model' => $model]);
	}
	
	public function actionUpdate($id) {
		$model = AdCategory::findOne($id);
		
		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
		
			if($model->validate()) {
				$model->save(false);
					
				return $this->redirect('index');
			}
		}
		return $this->render('update', ['model' => $model]);
	}
	
	public function actionDelete($id)
	{
		AdCategory::findOne($id)->delete();
	
		return $this->redirect(['index']);
	}
	
	public function actionSort() {
		$models = AdCategory::find()->all();
		$sort = new Sort($models);
		
		return $this->render('sort', ['sort' => $sort]);
	}
}