<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model vsoft\express\models\LcContactSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lc-contact-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'address') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'message') ?>

    <?php // echo $form->field($model, 'ip') ?>

    <?php // echo $form->field($model, 'agent') ?>

    <?php // echo $form->field($model, 'browser_type') ?>

    <?php // echo $form->field($model, 'browser_name') ?>

    <?php // echo $form->field($model, 'browser_version') ?>

    <?php // echo $form->field($model, 'platform') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
