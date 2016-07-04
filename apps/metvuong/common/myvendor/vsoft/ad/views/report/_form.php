<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model vsoft\ad\models\AdProductReport */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ad-product-report-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if($model->isNewRecord) echo $form->field($model, 'user_id')->textInput() ?>

    <?php if($model->isNewRecord) echo $form->field($model, 'product_id')->textInput() ?>

    <?= $form->field($model, 'type')->dropDownList(\vsoft\ad\models\ReportType::getReportName()) ?>

    <?= $form->field($model, 'description')->textarea(['row' => 3]) ?>

    <?= $form->field($model, 'ip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(\vsoft\news\models\Status::labels()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
