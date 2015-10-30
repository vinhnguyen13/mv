<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 10/29/2015
 * Time: 1:48 PM
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;
?>
<h1>Scenario 1</h1>
<?php $form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data', 'data-pjax'=>'#p_scenario_1'],
    'action' => \yii\helpers\Url::toRoute(['tool/save-step', 'step'=>'scenario_1'])
]); ?>

    <?=Html::hiddenInput('total_project_cost', 2246792608858,['class'=>'form-control form-group total_project_cost']);?>
    <?php
    $months = 32;
    for($i=1;$i<=$months;$i++){
    ?>
        <fieldset>
            <legend>T<?=$i?></legend>
            <?=$this->render('cashflow_form', [
                ''
            ])?>
        </fieldset>
    <?php }?>
    <div class="form-group">
        <label class="col-lg-1 control-label"></label>
        <div class="col-lg-11">
        <?= Html::submitButton('Next', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>

<div class="cash_result" style="clear: both; padding-top: 20px;">
    <div id="w0">
        <?=$this->render('cashflow_result');?>
    </div>
</div>

<script>
    $(document).on("click",'#scenario_1 .net_cashflow',function() {

        var total = ($('#scenario_1 .total_project_cost').val() * $('#scenario_1 .cashflow').val())/100
        $('#scenario_1 .net_cashflow').html(total);
        $('#scenario_1 .net_cashflow').autoNumeric('init', {aPad: false});
        console.log($('#scenario_1 .total_project_cost').val() + '__' + $(this).val() + '____' + $('#scenario_1 .net_cashflow').val());

    });
</script>