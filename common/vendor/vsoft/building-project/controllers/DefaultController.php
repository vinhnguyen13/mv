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
    	
    	if(Yii::$app->request->isPost) {
    		
    	}
    	
    	
//     	if($buildingProject->load(Yii::$app->request->post()) && $buildingProject->save()) {
//             return $this->redirect(['view', 'id' => $model->id]);
//         }
        
    	return $this->render('create', ['buildingProject' => $buildingProject]);
    }
}
