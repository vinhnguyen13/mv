<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model vsoft\express\models\LcPricingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lc-pricing-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'apart_type_id') ?>

    <?= $form->field($model, 'area') ?>

    <?= $form->field($model, 'monthly_rates') ?>

    <?= $form->field($model, 'daily_rates') ?>

    <?php echo $form->field($model, 'description') ?>

    <?php echo $form->field($model, 'created_at') ?>

    <?php echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('pricing', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('pricing', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
