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
    		$buildingProject->load(Yii::$app->request->post());
    		
    		if($buildingProject->validate()) {
    			
    		} else {
    			var_dump($buildingProject->getErrors());
    		}
    	}
    	
//     	if($buildingProject->load(Yii::$app->request->post()) && ) {
//     		if($buildingProject->save()) {
//     			return $this->redirect(['view', 'id' => $model->id]);
//     		} else {
//     			echo json_encode($buildingProject->getErrors());
//     			exit();
//     		}
//         }
        
    	return $this->render('create', ['model' => $buildingProject]);
    }
}
