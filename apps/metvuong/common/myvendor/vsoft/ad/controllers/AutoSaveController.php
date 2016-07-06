<?php
namespace vsoft\ad\controllers;

use yii\web\Controller;
use vsoft\ad\models\AdProductAutoSaveSearch;

class AutoSaveController extends Controller {
	
	public function actionIndex() {
		$searchModel = new AdProductAutoSaveSearch();
		$dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
    public function actionView($id)
    {
    	$model = AdProductAutoSaveSearch::findOne($id);
    	
    	if($model) {
    		return $this->render('view', [
				'model' => $model,
			]);
    	} else {
    		throw new NotFoundHttpException('The requested page does not exist.');
    	}
    }


    public function actionDelete($id)
    {
    	
    	$autoSave = AdProductAutoSaveSearch::findOne($id);
    	
    	if($autoSave) {
    		$images = $autoSave->images;
    		
    		if($images) {
    			foreach ($images as $image) {
    				$image->delete();
    			}
    		}
    		 
    		$autoSave->delete();
    	}
    
    	return $this->redirect(['index']);
    }
}