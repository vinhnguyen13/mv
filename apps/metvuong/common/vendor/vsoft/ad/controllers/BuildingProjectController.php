<?php
namespace vsoft\ad\controllers;

use Yii;
use yii\web\Controller;
use common\vendor\vsoft\ad\models\AdBuildingProject;
use common\vendor\vsoft\ad\models\AdAreaType;
use yii\helpers\Url;
use common\vendor\vsoft\ad\models\AdBuildingProjectSearch;
use common\vendor\vsoft\ad\models\AdInvestor;
use yii\helpers\ArrayHelper;
use common\vendor\vsoft\ad\models\AdCategory;

class BuildingProjectController extends Controller
{
	public function actionIndex()
	{
		$searchModel = new AdBuildingProjectSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	public function actionCreate()
	{
		$model = new AdBuildingProject();
		$model->loadDefaultValues();
		
		$areaTypeMapLabels = AdAreaType::mapLabels();
		$areaTypes = [];
		
		foreach($areaTypeMapLabels as $type => $areaTypeLabel) {
			$areaTypes[$type] = $model->getAdAreaType($type)->loadDefaultValues();
		}
		
		$investors = AdInvestor::find()->all();
		$categories = AdCategory::find()->all();
		
		if(Yii::$app->request->isPost) {
			Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			
			$post = Yii::$app->request->post();
			
			$model->load($post);
			
			$response = ['success' => true];
			
			if($model->validate()) {
    			$model->save(false);
    			$model->saveMultiple($post['BuildingProject'], $investors, 'investors');
    			$model->saveMultiple($post['BuildingProject'], $categories, 'categories');
    			
    			$mapFormName = AdAreaType::mapFormName();
    			foreach ($mapFormName as $type => $formName) {
    				if(array_filter($post[$formName])) {
    					$arrayType = $areaTypes[$type];
    					$arrayType->load($post);
    					$arrayType->building_project_id = $model->id;
    					$arrayType->save(false);
    				}	
    			}
    			
    			$response['redirect'] = Url::to(['view', 'id' => $model->id]);
    		} else {
    			$response['success'] = false;
    			$response['errors'] = $model->getErrors();
    		}
    		
    		return $response;
		}
		
		return $this->render('create', ['model' => $model, 'areaTypeMapLabels' => $areaTypeMapLabels, 'areaTypes' => $areaTypes, 'investors' => $investors, 'categories' => $categories]);
	}
    public function actionView($id)
    {
    	$model = AdBuildingProject::findOne($id);
    	
    	if($model) {
    		return $this->render('view', [
				'model' => $model,
			]);
    	} else {
    		throw new NotFoundHttpException('The requested page does not exist.');
    	}
    }
    
    public function actionUpdate($id)
    {
    	$model = AdBuildingProject::findOne($id);
    	
    	$areaTypeMapLabels = AdAreaType::mapLabels();
    	$areaTypes = [];
    	
    	foreach($areaTypeMapLabels as $type => $areaTypeLabel) {
    		$areaTypes[$type] = $model->getAdAreaType($type);
    	}

    	$investors = AdInvestor::find()->all();
    	$categories = AdCategory::find()->all();
    	
    	if($model) {
    		if(Yii::$app->request->isPost) {
    			Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    			$post = Yii::$app->request->post();
    			
    			$model->load(Yii::$app->request->post());
    			 
    			$response = ['success' => true];
    			 
    			if($model->validate()) {
    				$model->save(false);
    				$model->saveMultiple($post['BuildingProject'], $investors, 'investors');
    				$model->saveMultiple($post['BuildingProject'], $categories, 'categories');
    				
	    			$mapFormName = AdAreaType::mapFormName();
	    			foreach ($mapFormName as $type => $formName) {
	    				$arrayType = $areaTypes[$type];
	    				
	    				if(array_filter($post[$formName])) {
	    					$arrayType->load($post);
	    					$arrayType->building_project_id = $model->id;
	    					$arrayType->save(false);
	    				} else if($arrayType->id) {
	    					$arrayType->delete();
	    				}
	    			}
    				
    					
    				$deleteLater = array_filter(explode(',', Yii::$app->request->post('deleteLater')));
    					
    				foreach ($deleteLater as $orginal) {
    					$pathInfo = pathinfo($orginal);
    					$thumb = $pathInfo['filename'] .  '.thumb.' . $pathInfo['extension'];
    
    					$dir = \Yii::getAlias('@store') . '/building-project-images';
    
    					if(file_exists($dir . '/' . $orginal)) {
    						unlink($dir . '/' . $orginal);
    						unlink($dir . '/' . $thumb);
    					}
    				}
    
    				$response['redirect'] = Url::to(['view', 'id' => $model->id]);
    			} else {
    				$response['success'] = false;
    				$response['errors'] = $model->getErrors();
    			}
    
    			return $response;
    		}
    		 
    		return $this->render('update', ['model' => $model, 'areaTypeMapLabels' => AdAreaType::mapLabels(), 'areaTypes' => $areaTypes, 'investors' => $investors, 'categories' => $categories]);
    	} else {
    		throw new NotFoundHttpException('The requested page does not exist.');
    	}
    }
    

    public function actionDelete($id)
    {
    	Yii::$app->db->createCommand()->delete('ad_investor_building_project', 'building_project_id = :building_project_id', [':building_project_id' => $id])->execute();
    	Yii::$app->db->createCommand()->delete('ad_area_type', 'building_project_id = :building_project_id', [':building_project_id' => $id])->execute();
    	
    	AdBuildingProject::findOne($id)->delete();
    
    	return $this->redirect(['index']);
    }
}