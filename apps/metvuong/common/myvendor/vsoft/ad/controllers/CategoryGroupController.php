<?php
namespace vsoft\ad\controllers;

use Yii;
use yii\web\Controller;
use backend\components\Sort;
use vsoft\ad\models\AdCategoryGroup;

class CategoryGroupController extends Controller
{
	public function actionIndex() {
		$searchModel = new AdCategoryGroup();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	public function actionCreate() {
		$model = new AdCategoryGroup();
		
		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			
			if(Yii::$app->request->post('for_sell')) {
				$model->apply_to_type += Yii::$app->request->post('for_sell');
			}
			
			if(Yii::$app->request->post('for_rent')) {
				$model->apply_to_type += Yii::$app->request->post('for_rent');
			}
			
			
			if($model->validate()) {
				$model->save(false);
				 
				return $this->redirect('index');
			}
		}
		
		return $this->render('create', ['model' => $model]);
	}
	
	public function actionUpdate($id) {
		$model = AdCategoryGroup::findOne($id);
		
		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
		
			$model->apply_to_type = 0;
			
			if(Yii::$app->request->post('for_sell')) {
				$model->apply_to_type += Yii::$app->request->post('for_sell');
			}
				
			if(Yii::$app->request->post('for_rent')) {
				$model->apply_to_type += Yii::$app->request->post('for_rent');
			}
			
			if($model->validate()) {
				$model->save(false);
					
				return $this->redirect('index');
			}
		}
		return $this->render('update', ['model' => $model]);
	}
	
	public function actionDelete($id)
	{
		AdCategoryGroup::findOne($id)->delete();
	
		return $this->redirect(['index']);
	}
	
	public function actionSort() {
		$models = AdCategoryGroup::find()->orderBy('order')->all();
		$sort = new Sort($models);
		
		if(Yii::$app->request->isPost) {
			$sort->save(Yii::$app->request->post());
			
			return $this->redirect('index');
		}
		
		return $this->render('sort', ['sort' => $sort]);
	}
}