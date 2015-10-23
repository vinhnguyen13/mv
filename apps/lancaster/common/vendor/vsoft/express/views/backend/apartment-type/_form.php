<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model vsoft\express\models\LcApartmentType */
/* @var $form yii\widgets\ActiveForm */
date_default_timezone_set('Asia/Ho_Chi_Minh');
?>

<div class="lc-apartment-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput(['readOnly' => true, 'value' => $model->isNewRecord ? date('d F Y H:i A') : $model->created_at]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('apartment_type', 'Create') : Yii::t('apartment_type', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
