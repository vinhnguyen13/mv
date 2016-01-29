<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model vsoft\ad\models\AdArchitect */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ad-architect-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fax')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'website')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 4,'maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->hiddenInput(['value' => time()])->label(false) ?>

    <?= $form->field($model, 'updated_at')->hiddenInput(['value' => time()])->label(false) ?>

    <?= $form->field($model, 'status')->dropDownList(\vsoft\news\models\Status::labels()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('architect', 'Create') : Yii::t('architect', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
