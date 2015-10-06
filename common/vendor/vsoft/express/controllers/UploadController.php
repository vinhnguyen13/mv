<?php

namespace vsoft\express\controllers;

use yii\web\Controller;
use yii\helpers\Url;
use \Yii;
use yii\web\UploadedFile;
use vsoft\express\components\UploadHelper;

class UploadController extends Controller
{
    public function actionEditorImage()
    {
    	if(\Yii::$app->request->isPost && isset($_FILES["upload"])) {
    		$imageUrl = '';
    		$funcNum = $_GET['CKEditorFuncNum'];
    		$message = '';
    		
    		$valid = $this->isImage($_FILES["upload"]["tmp_name"]) && $this->allowImageType($_FILES["upload"]["name"]);
    		
    		if($valid) {
    			$targetDir = \Yii::getAlias('@store') . '/editor-images';
    			$fileName = uniqid() . '.' . pathinfo($_FILES["upload"]["name"], PATHINFO_EXTENSION);
    			$targetFile = $targetDir . '/' . $fileName;
    			
    			if(move_uploaded_file($_FILES["upload"]["tmp_name"], $targetFile)) {
    				$imageUrl = Url::to('/store/editor-images/') . $fileName;
    			} else {
    				$message = 'upload failed';
    			}
    		} else {
    			$message = 'file is not valid';
    		}
    		echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$imageUrl', '$message');window.parent.refreshCsrf();</script>";
    		exit();
    	}
    }
    
    public function actionBuildingProjectImage() {
    	if($_FILES) {
    		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    		
    		$fieldName = isset($_POST['name']) ? $_POST['name'] : 'upload';
    		$file = UploadedFile::getInstanceByName($fieldName);
    		
    		$fileName = $this->uploadPB($file);
    		
    		$response['files'][] = [
	    		'url'           => Url::to('/store/building-project-images/' . $fileName),
	    		'thumbnailUrl'  => Url::to('/store/building-project-images/' . $fileName),
	    		'name'          => $fileName,
	    		'type'          => $file->type,
	    		'size'          => $file->size,
	    		'deleteUrl'     => Url::to(['gallery-photo/delete']),
	    		'deleteType'    => 'POST',
    		];
    		return $response;
    	}
    }
    
    private function uploadPB($file) {
    	$targetDir = \Yii::getAlias('@store') . '/building-project-images';
    	$fileName = uniqid() . '.' . pathinfo($file->name, PATHINFO_EXTENSION);
    	$targetFile = $targetDir . '/' . $fileName;
    	
    	move_uploaded_file($file->tempName, $targetFile);
    	
    	return $fileName;
    }
    
    public function isImage($pathToImage) {
    	$check = getimagesize($pathToImage);
    	
    	if($check !== false) {
    		return true;
    	}
    	
    	return false;
    }
    
    public function allowImageType($imageFileName) {
    	$imageFileType = pathinfo($imageFileName, PATHINFO_EXTENSION);
    	$allowImageType = ['jpg', 'png', 'jpeg', 'gif'];
    	
    	if(in_array($imageFileType, $allowImageType)) {
    		return true;
    	}
    	
    	return false;
    }
}
