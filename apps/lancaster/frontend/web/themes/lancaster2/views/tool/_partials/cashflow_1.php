<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 10/29/2015
 * Time: 1:48 PM
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;
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
                <?=Html::label('Incoming Cashflow');?>
                <?=Html::input('text','incoming_cashflow[T1]',0,['class'=>'form-control form-group incoming_cashflow']);?>
                <?=Html::hiddenInput(null, 0,['class'=>'form-control form-group hid_incoming_cashflow']);?>
                <div class="hint-block"></div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group field-cmscatalog-parent_id">
                <?=Html::label('Outgoing Cashflow (%)');?>
                <?=Html::input('text','cashflow[T1]',null,['class'=>'form-control form-group cashflow']);?>
                <?=Html::hiddenInput(null, 0,['class'=>'form-control form-group hid_outgoing_cashflow']);?>
                <?=Html::hiddenInput(null, 0,['class'=>'form-control form-group hid_accum_incoming_cashflow']);?>
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
                <?=Html::label('', 0, ['class'=>'form-control form-group net_cashflow']);?>
                <?=Html::hiddenInput('net_cashflow[T1]', 0,['class'=>'form-control form-group net_cashflow']);?>
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
        console.log(json.data);
        $(".mainConfigSetParams").find(".nav-tabs a[href='#scenario_2']").trigger("click");
    });

    var outgoing_cashflow_accumulative = 0;
    var accumulative_incoming_cashflow = 0;
    var incoming_cashflow = 0;
    $(document).on("change",'#scenario_1 .cashflow',function() {
        var fieldset = $(this).closest('fieldset');
        console.log($('.total_project_cost').val());
        var total_project_cost = $('.total_project_cost').val();
        var cashflow = (-1 * parseFloat(fieldset.find('.cashflow').val()) * total_project_cost)/100;
        var incoming = parseFloat(fieldset.find('.incoming_cashflow').val());
        var total = 0;
        var hid_outgoing_cashflow = parseFloat(fieldset.find('.hid_outgoing_cashflow').val());
        var hid_incoming_cashflow = parseFloat(fieldset.find('.hid_incoming_cashflow').val());
        var hid_accum_incoming_cashflow = parseFloat(fieldset.find('.hid_accum_incoming_cashflow').val());

        if(hid_outgoing_cashflow == 0 && hid_accum_incoming_cashflow == 0) {

            fieldset.find('.hid_outgoing_cashflow').val(outgoing_cashflow_accumulative);
            outgoing_cashflow_accumulative = outgoing_cashflow_accumulative + cashflow;

            fieldset.find('.hid_incoming_cashflow').val(hid_incoming_cashflow);
            incoming_cashflow = incoming_cashflow + incoming;

            fieldset.find('.hid_accum_incoming_cashflow').val(accumulative_incoming_cashflow);
            accumulative_incoming_cashflow = accumulative_incoming_cashflow + incoming_cashflow;

            total = outgoing_cashflow_accumulative + accumulative_incoming_cashflow;
        }
        else {
            fieldset.find('.hid_outgoing_cashflow').val(hid_outgoing_cashflow);
            hid_outgoing_cashflow = hid_outgoing_cashflow + cashflow;

            fieldset.find('hid_incoming_cashflow').val(hid_incoming_cashflow);
            hid_incoming_cashflow = hid_incoming_cashflow + incoming;

            fieldset.find('.hid_accum_incoming_cashflow').val(hid_accum_incoming_cashflow);
            hid_accum_incoming_cashflow = hid_accum_incoming_cashflow + hid_incoming_cashflow;

            total = hid_outgoing_cashflow + hid_accum_incoming_cashflow;
        }

        total = Math.round(total);
        fieldset.find('label.net_cashflow').html(total).autoNumeric('init', {aPad: false});
        fieldset.find('input.net_cashflow').val(total);
    });

    var counter = 1;
    $("#scenario_1 .add").click(function(){
        var fieldset = $('form[id^="p_scenario_1"] fieldset:last');
        counter += 1;
        var f = fieldset.clone().attr("id", counter);
        f.find("legend").text("T"+counter);
//        console.log(f.find("input"));

        f.find('.hid_outgoing_cashflow').attr("value", 0);
        f.find('.hid_accum_incoming_cashflow').attr("value", 0);

        f.find("input.incoming_cashflow").attr("name", "incoming_cashflow[T"+counter+"]");
        f.find("input.cashflow").attr("name", "cashflow[T"+counter+"]");
        f.find("input.sales").attr("name", "sales[T"+counter+"]");
        f.find("input.net_cashflow").attr("name", "net_cashflow[T"+counter+"]");

        f.find("input.cashflow").attr("value", null);
        f.find("input.net_cashflow").attr("value", 0);

        f.insertAfter(fieldset);
    });
</script>