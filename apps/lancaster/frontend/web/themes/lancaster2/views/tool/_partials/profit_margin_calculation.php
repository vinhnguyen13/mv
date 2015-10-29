<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 10/29/2015
 * Time: 1:55 PM
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
$this->registerJs(
    '$("document").ready(function(){
        $("#profitMarginCalculation").on("pjax:end", function() {
            $(".mainConfigSetParams").find(".nav-tabs a[href=\"#scenario_1\"]").trigger("click");
        });
    });'
);
?>
<?php Pjax::begin([
    'enableReplaceState'=>false,
    'enablePushState'=>false,
    'id' => 'profitMarginCalculation',
    'clientOptions'=>[
        'container'=>'p_profitMarginCalculation',
    ]
]); ?>
<?php $form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data', 'data-pjax'=>'#p_profitMarginCalculation'],
    'action' => \yii\helpers\Url::toRoute(['tool/save-step', 'step'=>'profitMarginCalculation'])
]); ?>
<div class="col-lg-12">
    <?=Html::label('Total Project Cost');?>
    <?=Html::input('text','total_project_cost', 2246792608858,['class'=>'form-control form-group number']);?>
</div>
<div class="form-group">
    <label class="col-lg-1 control-label"></label>
    <div class="col-lg-11">
        <?= Html::submitButton('Next', ['class' => 'btn btn-primary']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php Pjax::end(); ?>
