<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use lajax\translatemanager\models\Language;

/* @var $this yii\web\View */
/* @var $model vsoft\express\models\LcBuilding */
/* @var $form yii\widgets\ActiveForm */
date_default_timezone_set('Asia/Ho_Chi_Minh');

$this->registerJsFile(Yii::getAlias('@web') . '/js/building.js', ['depends' => ['yii\web\YiiAsset']]);

$languages = Language::getLanguageNames(true);
?>

<div class="lc-building-form">

    <?php $form = ActiveForm::begin([
    		'options' => ['spellcheck' => 'false'],
    		'enableClientValidation' => false,
    		'enableAjaxValidation' => false
    ]); ?>
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
