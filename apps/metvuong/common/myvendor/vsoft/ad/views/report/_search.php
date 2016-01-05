<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model vsoft\ad\models\AdProductReportSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ad-product-report-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'product_id') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'ip') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'report_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
