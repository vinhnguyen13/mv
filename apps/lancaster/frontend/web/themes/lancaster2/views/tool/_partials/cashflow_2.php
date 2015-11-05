<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 10/29/2015
 * Time: 1:48 PM
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<h1>Scenario 2</h1>
<?php $form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data', 'id' => 'p_scenario_2'],
    'action' => \yii\helpers\Url::toRoute(['tool/save-step', 'step' => 'scenario_2'])
]); ?>

<?= Html::hiddenInput('total_project_cost', 2246792608858, ['class' => 'form-control form-group total_project_cost']); ?>

<fieldset id="1">
    <legend>T1</legend>
    <div class="col-lg-3">
        <div class="form-group field-cmscatalog-parent_id">
            <?= Html::label('Outgoing Cashflow (%)'); ?>
            <?= Html::input('text', 'cashflow[T1]', null, ['class' => 'form-control form-group cashflow']); ?>
            <div class="hint-block"></div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group field-cmscatalog-parent_id">
            <?= Html::label('Sales (%)'); ?>
            <?= Html::input('text', 'sales[T1]', null, ['class' => 'form-control form-group sales']); ?>
            <div class="hint-block"></div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group field-cmscatalog-parent_id">
            <?= Html::label('Net Accumulative Cashflow'); ?>
            <?= Html::label('', null, ['class' => 'form-control form-group net_cashflow']); ?>
            <?= Html::hiddenInput('net_cashflow[T1]', null, ['class' => 'form-control form-group net_cashflow']); ?>
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
        <?= $this->render('cashflow_result'); ?>
    </div>
</div>
<script>
    $(document).on("click",'#scenario_2 .next',function() {
        chart.next('/tool/save-step?step=scenario_2', 'p_scenario_2', 'scenario_2/afterNext');
        return false;
    });

    $(document).bind( 'scenario_2/afterNext', function(event, json, string){
        /**
         * save data of form to json (file)
         */
        console.log(json.data);
    });

    var outgoing_cashflow_accumulative_2 = 0;
    $(document).on("blur",'#scenario_2 .cashflow',function() {
        var fieldset = $(this).closest('fieldset');
        console.log($('.total_project_cost').val());

        var total_project_cost = $('.total_project_cost').val();
        outgoing_cashflow_accumulative_2 = outgoing_cashflow_accumulative_2 + (total_project_cost * fieldset.find('.cashflow').val())/100;

        var incoming_cashflow = 0;
        var accumulative_incoming_cashflow = 0;

        var total = outgoing_cashflow_accumulative_2 + accumulative_incoming_cashflow;
        total = Math.round(total);
        fieldset.find('label.net_cashflow').html(total).autoNumeric('init', {aPad: false});
        fieldset.find('input.net_cashflow').val(total);
    });

    var counter_2 = 1;
    $("#scenario_2 .add").click(function(){
        var fieldset = $('form[id^="p_scenario_2"] fieldset:last');
        counter_2 += 1;
        var f = fieldset.clone().attr("id", counter_2);

        f.find("legend").text("T"+counter_2);
        f.find("input.cashflow").attr("name", "cashflow[T"+counter_2+"]");
        f.find("input.sales").attr("name", "sales[T"+counter_2+"]");
        f.find("input.net_cashflow").attr("name", "net_cashflow[T"+counter_2+"]");
        f.insertAfter(fieldset);
    });
</script>