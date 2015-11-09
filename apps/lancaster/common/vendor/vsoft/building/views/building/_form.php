<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use lajax\translatemanager\models\Language;
use common\widgets\FileUploadUI;
use yii\helpers\Url;
use common\widgets\CKEditor;

/* @var $this yii\web\View */
/* @var $model vsoft\building\models\LcBuilding */
/* @var $form yii\widgets\ActiveForm */
date_default_timezone_set('Asia/Ho_Chi_Minh');

$this->registerJsFile(Yii::getAlias('@web') . '/js/building.js', ['depends' => ['yii\web\YiiAsset']]);

$languages = Language::getLanguageNames(true);

$loop = [
	'apartments' => [
		['livingRoom', 'Living Room'],
		['kitchen', 'Kitchen'],
		['bedroom', 'Bedroom'],
		['bathroom', 'Bathroom'],
	],
	'amenities' => [
		['swimmingPool', 'Swimming Pool'],
		['fitnessCenter', 'Fitness Center'],
		['healthyCare', 'Healthy Care'],
		['skybar', 'Skybar']
	],
	'views' => [
		['north', 'North'],
		['east', 'East'],
		['south', 'South'],
		['west', 'West'],
	]
];
?>

<div class="lc-building-form">

    <?php $form = ActiveForm::begin([
    		'options' => ['spellcheck' => 'false'],
    		'enableClientValidation' => false,
    		'enableAjaxValidation' => false
    ]); ?>
    <?= $model->isNewRecord ? '' : Html::hiddenInput('deleteLater', '', ['id' => 'delete-later']) ?>
	<div class="form-group">
		<label class="control-label" for="lcbuilding-building_name">Chọn ngôn ngữ</label>
		<?= Html::dropDownList('language', Yii::$app->sourceLanguage, $languages, ['class' => 'form-control', 'id' => 'language-switch']) ?>
	</div>
	
    <?php foreach ($languages as $languageCode => $languageName): ?>
    <div class="language-fields <?= $languageCode ?>" <?php if($languageCode != Yii::$app->sourceLanguage) echo 'style="display:none;"' ?>>
    	<?= $model->translateField($form, $languageCode, 'building_name')->textInput(['maxlength' => true]) ?>
    	
    	<?= $model->translateField($form, $languageCode, 'address')->textInput(['maxlength' => true]) ?>
    	
    	<?= $model->translateField($form, $languageCode, 'introduction_title')->textArea() ?>
    	
    	<?= $model->translateField($form, $languageCode, 'introduction_content')->textArea() ?>
    </div>
    <?php endforeach; ?>

    <?= $form->field($model, 'main_background')->widget(FileUploadUI::className(), ['url' => Url::to('/express/upload/image'), 'clientOptions' => ['maxNumberOfFiles' => 1]]) ?>

    <?php foreach($loop as $section => $value): ?>
    	<?php $sectionValue = json_decode($model->$section); ?>
    	<div class="panel panel-default">
			<div class="panel-heading"><?= $model->getAttributeLabel($section) ?></div>
			<div class="panel-body">
				<ul class="nav nav-tabs">
					<?php foreach ($value as $k => $v): ?>
					<li<?= $k == 0 ? ' class="active"' : '' ?>><a data-toggle="tab" href="<?= '#' . $section . $v[0] ?>"><?= $v[1] ?></a></li>
					<?php endforeach; ?>
				</ul>
				<div class="tab-content">
					<?php foreach ($value as $k => $v): ?>
					<?php
						$field = $v[0];
						
						if($sectionValue) {
							$tabValue = $sectionValue->$field;
							$tabValueContent = $tabValue->content;
							$tabValueImage = $tabValue->image;
						} else {
							$tabValueContent = '';
							$tabValueImage = '';
						}
					?>
					<div id="<?= $section . $v[0] ?>" class="tab-pane fade in <?= $k == 0 ? 'active' : '' ?>">
						<div class="form-group">
							<label class="control-label" for="<?= 'content-' . $section . $v[0] ?>">Nội dung</label>
							<?= CKEditor::widget([
								'id' => 'content-' . $section . $v[0],
					    		'value' => $tabValueContent,
								'name' => 'LcBuilding[' . $section . '][' . $v[0] . '][content]',
								'editorOptions' => [
									'preset' => 'basic',
									'inline' => false,
									'height' => 150,
									'resize_enabled' => true,
									'removePlugins' => '',
								]
							]); ?>
							<div class="help-block"></div>
						</div>
						<div class="form-group">
							<label class="control-label" for="<?= 'image-' . $section . $v[0] ?>">Ảnh</label>
							<?= FileUploadUI::widget([
								'id' => 'image-' . $section . $v[0],
								'name' => 'LcBuilding[' . $section . '][' . $v[0] . '][image]',
								'url' => Url::to('/express/upload/image'),
								'fieldOptions' => ['values' => $tabValueImage],
								'clientOptions' => ['maxNumberOfFiles' => 1]
							]) ?>
							<div class="help-block"></div>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
    <?php endforeach; ?>
    
    <?= $form->field($model, 'floor')->textInput(['maxlength' => 3]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fax')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hotline')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'isbooking')->checkbox(); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
