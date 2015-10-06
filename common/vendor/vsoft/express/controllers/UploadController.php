<?php

namespace vsoft\express\controllers;

use yii\web\Controller;
use yii\helpers\Url;

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
//     	if(\Yii::$app->request->isPost) {
//     		if(!empty($_FILES)) {
//     			$fileAttributes = current($_FILES);
//     			$total = count(current($fileAttributes['name']));
//     			$files = [];
    			
//     			for($i = 0; $i < $total; $i++) {
//     				$files[$i] = [];
    				 
//     				foreach ($fileAttributes as $k => $v) {
//     					$v = current($v);
    			
//     					$files[$i][$k] = $v[$i];
//     				}
//     			}
    			
//     			$initialPreview = [];
//     			$initialPreviewConfig = [];
    			
//     			foreach ($files as $file) {
//     				$initial = $this->uploadPB($file);
    				 
//     				$initialPreview[] = $initial['initialPreview'];
//     				$initialPreviewConfig[] = $initial['initialPreviewConfig'];
//     			}
    			
//     			echo json_encode(['initialPreview' => $initialPreview, 'initialPreviewConfig' => $initialPreviewConfig]);
//     		} else {
//     			echo json_encode(['initialPreview' => $initialPreview, 'initialPreviewConfig' => $initialPreviewConfig]);
//     		}
//     	}
    }
    
    private function uploadPB($file) {
    	$targetDir = \Yii::getAlias('@store') . '/building-project-images';
    	$fileName = uniqid() . '.' . pathinfo($file["name"], PATHINFO_EXTENSION);
    	$targetFile = $targetDir . '/' . $fileName;
    	
    	if(move_uploaded_file($file["tmp_name"], $targetFile)) {
    		$imageUrl = Url::to('/store/building-project-images/') . $fileName;
    	} else {
    		return false;
    	}
    	
    	return [
				'initialPreview' => '<img src="' . $imageUrl . '" class="file-preview-image" alt="Desert" title="Desert">',
				'initialPreviewConfig' =>
				[
					'caption' => 'desert.jpg',
					'width' => '120px',
					'url' => 's',
					'key' => '100',
					'extra' => ['id' => '11']
				]	
    		];
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
