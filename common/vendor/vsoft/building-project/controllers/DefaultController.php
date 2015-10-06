<?php

namespace vsoft\buildingProject\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use vsoft\buildingProject\models\BuildingProject;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionCreate() {
    	$buildingProject = new BuildingProject();
    	$buildingProject->loadDefaultValues();
    	$buildingProject->isNewRecord = true;
    	
    	if(Yii::$app->request->isPost) {
    		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    		
    		$buildingProject->load(Yii::$app->request->post());
    		
    		var_dump($buildingProject->bpLocation);
    		
    		if($buildingProject->validate()) {
    			
    		} else {
    			var_dump($buildingProject->getErrors());
    		}
    		
    		return [];
    	}
        
    	return $this->render('create', ['model' => $buildingProject]);
    }
}
