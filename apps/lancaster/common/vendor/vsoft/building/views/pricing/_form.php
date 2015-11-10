<?php

use kartik\money\MaskMoney;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model vsoft\building\models\LcPricing */
/* @var $form yii\widgets\ActiveForm */
date_default_timezone_set('Asia/Ho_Chi_Minh');


$buildings = \vsoft\building\models\LCBuilding::find()->all();
$listBuildings = ArrayHelper::map($buildings, 'id', 'building_name');
?>

<div class="lc-pricing-form">

    <?php $form = ActiveForm::begin();
    $types = \vsoft\building\models\LcApartmentType::find()->all();
    $listData = ArrayHelper::map($types, 'id', 'name');
    ?>

    <?= $form->field($model, 'area')->widget(\yii\widgets\MaskedInput::className(),[
        'name' => 'area',
        'clientOptions' => [
            'alias' =>  'decimal',
            'groupSeparator' => ',',
            'autoGroup' => true
        ],
    ]) ?>

    <?= $form->field($model, 'apart_type_id')->dropDownList($listData,[
        'options' => [$model->apart_type_id => ['selected ' => true]],
    ])->label('Apartment Type') ?>
    
    <?= $form->field($model, 'building_id')->dropDownList($listBuildings,[
        'options' => [$model->building_id => ['selected ' => true]],
    ])->label('Building') ?>

    <?= $form->field($model, 'monthly_rates')->widget(\yii\widgets\MaskedInput::className(),[
        'name' => 'monthly_rates',
        'clientOptions' => [
            'alias' =>  'decimal',
            'groupSeparator' => ',',
            'autoGroup' => true,
        ],
    ]) ?>

    <?= $form->field($model, 'daily_rates')->widget(\yii\widgets\MaskedInput::className(),[
        'name' => 'daily_rates',
        'clientOptions' => [
            'alias' =>  'decimal',
            'groupSeparator' => ',',
            'autoGroup' => true,
            'align' => 'left'
        ],
    ]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput(['readOnly' => true, 'value' => $model->isNewRecord ? date('d F Y H:i A') : $model->created_at]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('pricing', 'Create') : Yii::t('pricing', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
