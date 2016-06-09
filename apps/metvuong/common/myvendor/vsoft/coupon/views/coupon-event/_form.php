<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model vsoft\coupon\models\CouponEvent */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="coupon-event-form">

    <?php $form = ActiveForm::begin(
        [
            'id' => 'coupon-event-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => false,
        ]
    ); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'start_date')->widget(\yii\jui\DatePicker::classname(), [
//        'language' => 'vi',
//        'dateFormat' => 'dd-MM-yyyy',
    ]) ?>
    <?= $form->field($model, 'end_date')->widget(\yii\jui\DatePicker::classname(), [
//        'language' => 'vi',
//        'dateFormat' => 'dd-MM-yyyy',
    ]) ?>
    <?= $form->field($model, 'status')->dropDownList(\vsoft\news\models\Status::labels()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('coupon', 'Create') : Yii::t('coupon', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
