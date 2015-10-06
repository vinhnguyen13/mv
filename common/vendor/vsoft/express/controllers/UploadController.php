<?php

namespace vsoft\express\controllers;

use yii\web\Controller;
use yii\helpers\Url;
use \Yii;
use yii\web\UploadedFile;
use vsoft\express\components\UploadHelper;
use yii\image\drivers\Image;

class UploadController extends Controller
{
    public function actionEditorImage()
    {
    	if(\Yii::$app->request->isPost && isset($_FILES["upload"])) {
    		$imageUrl = '';
    		$funcNum = $_GET['CKEditorFuncNum'];
    		$message = '';
    		
    		$valid = UploadHelper::isImage($_FILES["upload"]["tmp_name"]) && UploadHelper::allowImageType($_FILES["upload"]["name"]);
    		
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
    		
    		$image = UploadedFile::getInstanceByName('upload');
    		$dir = \Yii::getAlias('@store') . '/building-project-images';
    		$uniqid = uniqid();
    		$extension = pathinfo($image->name, PATHINFO_EXTENSION);
    		
    		$orginal = $uniqid . '.' . $extension;
    		$thumbnail = $uniqid . '.thumb.' . $extension;
    		
    		$orginalPath = $dir . '/' . $orginal;
    		$thumbnailPath = $dir . '/' . $thumbnail;
    		
    		$image->saveAs($orginalPath);
    		
    		$orginalRes = \Yii::$app->image->load($orginalPath);
    		$resizingConstraints = ($orginalRes->width > $orginalRes->height) ? Image::HEIGHT : Image::WIDTH;
    		$orginalRes->resize(120, 120, $resizingConstraints)->crop(120, 120)->save($thumbnailPath);
	    	
    		$response['files'][] = [
	    		'url'           => Url::to('/store/building-project-images/' . $orginal),
	    		'thumbnailUrl'  => Url::to('/store/building-project-images/' . $thumbnail),
	    		'name'          => $orginal,
	    		'type'          => $image->type,
	    		'size'          => $image->size,
	    		'deleteUrl'     => Url::to(['upload/delete-image', 'orginal' => $orginal, 'thumbnail' => $thumbnail]),
	    		'deleteType'    => 'POST',
    		];
    		return $response;
    	}
    }
    public function actionDeleteImage($orginal, $thumbnail) {
    	$dir = \Yii::getAlias('@store') . '/building-project-images';
    	
    	unlink($dir . '/' . $orginal);
    	unlink($dir . '/' . $thumbnail);
    	
    	Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	
    	return ['files' => [
    		"picture1.jpg" => true
    	]];
    }
}
