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
    'options' => ['enctype' => 'multipart/form-data', 'id'=>'p_scenario_1'],
    'action' => \yii\helpers\Url::toRoute(['tool/save-step', 'step'=>'scenario_1'])
]); ?>

    <?=Html::hiddenInput('total_project_cost', 2246792608858,['class'=>'form-control form-group total_project_cost']);?>

    <fieldset id="1">
        <legend>T1</legend>
        <div class="col-lg-3">
            <div class="form-group field-cmscatalog-parent_id">
                <?=Html::label('Outgoing Cashflow (%)');?>
                <?=Html::input('text','cashflow[T1]',null,['class'=>'form-control form-group cashflow']);?>
                <div class="hint-block"></div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group field-cmscatalog-parent_id">
                <?=Html::label('Sales (%)');?>
                <?=Html::input('text','sales[T1]',null,['class'=>'form-control form-group sales']);?>
                <div class="hint-block"></div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group field-cmscatalog-parent_id">
                <?=Html::label('Net Accumulative Cashflow');?>
                <?=Html::label('', null, ['class'=>'form-control form-group net_cashflow']);?>
                <?=Html::hiddenInput('net_cashflow[T1]', null,['class'=>'form-control form-group net_cashflow']);?>
                <div class="hint-block"></div>
            </div>
        </div>
    </fieldset>

    <div class="form-group">
        <label class="col-lg-1 control-label"></label>
        <div class="col-lg-1">
            <?= Html::button('Add', ['class' => 'btn btn-primary add']) ?>
        </div>
        <div class="col-lg-10">
        <?= Html::submitButton('Next', ['class' => 'btn btn-primary next']) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>

<div class="cash_result" style="clear: both; padding-top: 20px;">
    <div id="w0">
        <?=$this->render('cashflow_result');?>
    </div>
</div>

<script>
    $(document).on("click",'#scenario_1 .next',function() {
        chart.next('/tool/save-step?step=scenario_1', 'p_scenario_1', 'scenario_1/afterNext');
        return false;
    });

    $(document).bind( 'scenario_1/afterNext', function(event, json, string){
        /**
         * save data of form to json (file)
         */
        console.log(json.data);
    });

    $(document).on("blur",'#scenario_1 .cashflow',function() {
        var fieldset = $(this).closest('fieldset');
        console.log($('.total_project_cost').val());
        var total = ($('.total_project_cost').val() * fieldset.find('.cashflow').val())/100;
        fieldset.find('label.net_cashflow').html(total);
        fieldset.find('label.net_cashflow').autoNumeric('init', {aPad: false});
        fieldset.find('input.net_cashflow').val(total);
    });

    var counter = 1;
    $(".add").click(function(){
        var fieldset = $('form[id^="p_scenario_1"] fieldset:last');
        counter += 1;
        var f = fieldset.clone().attr("id", counter);
        f.find("legend").text("T"+counter);
//        console.log(f.find("input"));

        f.find("input.cashflow").attr("name", "cashflow[T"+counter+"]");
        f.find("input.sales").attr("name", "sales[T"+counter+"]");
        f.find("input.net_cashflow").attr("name", "net_cashflow[T"+counter+"]").autoNumeric('init', {aPad: false});
        f.insertAfter(fieldset);
    });
</script>