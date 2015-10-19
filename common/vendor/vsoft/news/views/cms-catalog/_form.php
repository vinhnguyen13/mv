<?php

use vsoft\news\models\CmsCatalog;
use vsoft\news\models\YesNo;
use vsoft\news\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model vsoft\news\models\CmsCatalog */
/* @var $form yii\widgets\ActiveForm */

//fix the issue that it can assign itself as parent
//$parentCatalog = ArrayHelper::merge([0 => Module::t('blog', 'Root Catalog')], ArrayHelper::map(CmsCatalog::get(0, CmsCatalog::find()->asArray()->all()), 'id', 'label'));
// get List Catalog of News
$parentCatalog = ArrayHelper::merge([2 => 'News'], ArrayHelper::map(CmsCatalog::get(Yii::$app->params['newsCatID'], CmsCatalog::find()->asArray()->all()), 'id', 'label'));
unset($parentCatalog[$model->id]);

?>

<div class="cms-catalog-form">

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-11\">{input}</div><div class=\"col-lg-1\"></div>\n<div class=\"col-lg-11\">{hint}{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'parent_id')->dropDownList($parentCatalog) ?>

    <input type="hidden" id="cmscatalog-page_type" class="form-control" name="CmsCatalog[page_type]" value="list">

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'content')->widget(\common\widgets\CKEditor::className(), [
        'editorOptions' => [
            'preset' => 'full',
            'inline' => false,
        ],
    ]); ?>

    <?= $form->field($model, 'seo_title')->textInput(['maxlength' => 128]) ?>

    <?= $form->field($model, 'seo_keywords')->textInput(['maxlength' => 128]) ?>

    <?= $form->field($model, 'seo_description')->textInput(['maxlength' => 128]) ?>

    <?= $model->isNewRecord ? $form->field($model, 'banner')->widget(\kartik\file\FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
            'previewFileType' => 'image',
            'showUpload' => false,
            'browseLabel' => '',
            'removeLabel' => '',
            'mainClass' => 'input-group-lg'
        ]
    ]) : $form->field($model, 'banner')->widget(\kartik\file\FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
            'previewFileType' => 'image',
            'showUpload' => false,
            'browseLabel' => '',
            'removeLabel' => '',
            'initialPreview'=>[
                Html::img("/store/news/catalog/".$model->banner, ['class'=>'file-preview-image', 'alt'=>'Banner', 'title'=>$model->banner]),
            ],
            'initialCaption'=> $model->banner,
//            'overwriteInitial'=>false, // used in multi upload case
            'mainClass' => 'input-group-lg'
        ]]) ?>

    <?= $form->field($model, 'is_nav')->dropDownList(YesNo::labels()) ?>

    <?= $form->field($model, 'sort_order')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList(\vsoft\news\models\Status::labels()) ?>


    <div class="form-group">
        <label class="col-lg-1 control-label"></label>
        <div class="col-lg-11">
            <?= Html::submitButton($model->isNewRecord ? Module::t('cms', 'Create') : Module::t('cms', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
