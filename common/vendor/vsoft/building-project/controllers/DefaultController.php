<?php

namespace vsoft\buildingProject\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use vsoft\buildingProject\models\BuildingProject;
use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionCreate()
    {
    	$buildingProject = new BuildingProject();
    	$buildingProject->loadDefaultValues();
    	
    	if(Yii::$app->request->isPost) {
    		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    		
    		$buildingProject->load(Yii::$app->request->post());
    		
    		$response = ['success' => true];
    		
    		if($buildingProject->validate()) {
    			$buildingProject->save(false);
    			$response['redirect'] = Url::to(['view', 'id' => $buildingProject->id]);
    		} else {
    			$response['success'] = false;
    			$response['errors'] = $buildingProject->getErrors();
    		}
    		
    		return $response;
    	}
        
    	return $this->render('create', ['model' => $buildingProject]);
    }
    public function actionUpdate($id)
    {
    	$buildingProject = BuildingProject::findOne($id);
    	
    	if($buildingProject) {
    		if(Yii::$app->request->isPost) {
    			Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    			 
    			$buildingProject->load(Yii::$app->request->post());
    			 
    			if($buildingProject->validate()) {
    				$buildingProject->save(false);
    				 
    				$deleteLater = array_filter(explode(',', Yii::$app->request->post('deleteLater')));
    				 
    				foreach ($deleteLater as $orginal) {
    					$pathInfo = pathinfo($orginal);
    					$thumb = $pathInfo['filename'] .  '.thumb.' . $pathInfo['extension'];
    		
    					$dir = \Yii::getAlias('@store') . '/building-project-images';
    					 
    					unlink($dir . '/' . $orginal);
    					unlink($dir . '/' . $thumb);
    				}
    			} else {
    				var_dump($buildingProject->getErrors());
    			}
    			 
    			return [];
    		}
    		 
    		return $this->render('update', ['model' => $buildingProject]);
    	} else {
    		throw new NotFoundHttpException('The requested page does not exist.');
    	}
    }
    /**
     * Displays a single CmsShow model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
    	$buildingProject = BuildingProject::findOne($id);
    	
    	if($buildingProject) {
    		return $this->render('view', [
				'model' => $buildingProject,
			]);
    	} else {
    		throw new NotFoundHttpException('The requested page does not exist.');
    	}
    }
}
