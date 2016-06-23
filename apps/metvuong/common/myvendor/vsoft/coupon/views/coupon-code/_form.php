<?php

use vsoft\news\models\Status;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model vsoft\coupon\models\CouponCode */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coupon-code-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?php
    $events = \yii\helpers\ArrayHelper::map(\vsoft\coupon\models\CouponEvent::find()->where(['status' => Status::STATUS_ACTIVE])->all(), 'id', 'name');
    echo $form->field($model, 'cp_event_id')->dropDownList($events, [
        'options' => [$model->cp_event_id => ['Selected ' => true]],
        'prompt' => ''
    ]);?>

    <?= $form->field($model, 'status')->dropDownList(Status::labels()) ?>

    <?= $form->field($model, 'type')->dropDownList(\vsoft\coupon\models\CouponCode::getTypes()) ?>

    <?= $form->field($model, 'amount')->textInput() ?>
    <?= $form->field($model, 'amount_type')->dropDownList(\vsoft\coupon\models\CouponCode::getAmountTypes(),[
        'options' => [$model->amount_type => ['Selected ' => true]]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('coupon', 'Create') : Yii::t('coupon', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
