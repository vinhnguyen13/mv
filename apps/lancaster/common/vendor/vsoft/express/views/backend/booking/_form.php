<?php

//use kartik\datetime\DateTimePicker;
use vsoft\express\models\LcBuilding;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model vsoft\express\models\LcBooking */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lc-booking-form">

    <?php
        $form = ActiveForm::begin();
    $buildings = \vsoft\express\models\LcBuilding::find()->all();
    $listData = ArrayHelper::map($buildings, 'id', 'building_name');
    ?>

    <?= $form->field($model, 'lc_building_id')->dropDownList($listData,[
        'options' => [$model->lc_building_id => ['selected ' => true]],
    ])->label('Building Name') ?>

    <?= $form->field($model, 'checkin')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'checkout')->textInput(['maxlength' => true]) ?>

    <?php
    $apart_type = \vsoft\express\models\LcApartmentType::find()->all();
    $apart_data = ArrayHelper::map($apart_type, 'id', 'name');
    echo $form->field($model, 'apart_type')->dropDownList(
        $apart_data,
        [
            'options' => [$model->apart_type => ['selected ' => true]],
//                                    'class' => 'form-control medium-width'
        ]
    );
    ?>

    <?= $form->field($model, 'floorplan')->textInput() ?>

    <?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'passport_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nationality')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'info')->textarea(['rows' => 6]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
