<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model vsoft\ad\models\AdFacility */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ad-facility-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(\vsoft\news\models\Status::labels()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('facility', 'Create') : Yii::t('facility', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
