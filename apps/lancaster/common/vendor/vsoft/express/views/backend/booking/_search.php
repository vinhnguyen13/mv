<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model vsoft\express\models\LcBookingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lc-booking-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'lc_building_id') ?>

    <?= $form->field($model, 'checkin') ?>

    <?= $form->field($model, 'checkout') ?>

    <?= $form->field($model, 'apart_type') ?>

    <?php // echo $form->field($model, 'floorplan') ?>

    <?php // echo $form->field($model, 'fullname') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'passport_no') ?>

    <?php // echo $form->field($model, 'nationality') ?>

    <?php // echo $form->field($model, 'info') ?>

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
