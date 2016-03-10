<?php 

use yii\widgets\ActiveForm;
use vsoft\ad\models\AdCity;
use yii\helpers\Url;
use yii\web\Session;
use common\widgets\fileupload\FileUpload;

$city = new AdCity();
$city->name = 'aaaa';
?>
<?= Yii::createObject(Session::className())->getId() ?>
<?php $form = ActiveForm::begin ( [
				'id' => 'frm-post-tin',
				'options' => [ 
					'autocomplete' => 'off',
					'spellcheck' => 'false',
					'class' => 'form-horizontal'
				]]); ?>
				
<?= $form->field($city, 'name')->widget(FileUpload::className(), [
				'fieldOptions' => [
					'name' => 'ddd',
				],
				'files' => [
					[
						'url'           => 'http://a5.mzstatic.com/us/r30/Purple5/v4/5a/2e/e9/5a2ee9b3-8f0e-4f8b-4043-dd3e3ea29766/icon128-2x.png',
						'thumbnailUrl'  => 'http://a5.mzstatic.com/us/r30/Purple5/v4/5a/2e/e9/5a2ee9b3-8f0e-4f8b-4043-dd3e3ea29766/icon128-2x.png',
						'name'          => 'abdc',
						'deleteUrl'     => Url::to(['/ad/delete-img']),
						'deleteType'    => 'DELETE'
					]
				],
						'url' => Url::to(['upload']),
						]) ?>		
<?= FileUpload::widget([
		'files' => [
					[
						'url'           => 'http://a5.mzstatic.com/us/r30/Purple5/v4/5a/2e/e9/5a2ee9b3-8f0e-4f8b-4043-dd3e3ea29766/icon128-2x.png',
						'thumbnailUrl'  => 'http://a5.mzstatic.com/us/r30/Purple5/v4/5a/2e/e9/5a2ee9b3-8f0e-4f8b-4043-dd3e3ea29766/icon128-2x.png',
						'name'          => 'abdc',
						'deleteUrl'     => Url::to(['/ad/delete-img']),
						'deleteType'    => 'DELETE'
					]
				],
	'name' => 'image',
	'url' => Url::to(['upload'])
]) ?>
<?php $form->end()?>