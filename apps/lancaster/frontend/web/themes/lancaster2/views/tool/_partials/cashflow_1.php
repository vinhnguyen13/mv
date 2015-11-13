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
<style>
    .check-payment {
        overflow-y: scroll;
        height: 240px;
    }
    .payment_schedule { margin: 0px 0px 20px 0px; }
    .payment_schedule .row{
        padding: 5px 0 0 15px !important;
    }
</style>
<h1>Scenario 1</h1>
<?php $form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data', 'id'=>'p_scenario_1'],
    'action' => \yii\helpers\Url::toRoute(['tool/save-step', 'step'=>'scenario_1'])
]); ?>

<div class="payment_schedule">
    <h4><b>Payment Schedule (%)</b></h4>
    <div class="row">
        <div class="col-md-3"><label>Reservation Fee</label></div>
        <div class="col-md-3"><input type="text" name="pay_1" value="5" class="form-control"/></div>

        <div class="col-md-3"><label>1st Payment (within 1 month)</label></div>
        <div class="col-md-3"><input type="text" name="pay_2" value="5" class="form-control"/></div>

        <div class="col-md-3"><label>Upon Finish Foundation</label></div>
        <div class="col-md-3"><input type="text" name="pay_3" value="10" class="form-control" /></div>

        <div class="col-md-3"><label>2 months after Foundation</label></div>
        <div class="col-md-3"><input type="text" name="pay_4" value="5" class="form-control"/></div>

        <div class="col-md-3"><label>4 months after Foundation</label></div>
        <div class="col-md-3"><input type="text" name="pay_5" value="5" class="form-control"/></div>

        <div class="col-md-3"><label>6 months after Foundation</label></div>
        <div class="col-md-3"><input type="text" name="pay_6" value="5" class="form-control"/></div>

        <div class="col-md-3"><label>8 months after Foundation</label></div>
        <div class="col-md-3"><input type="text" name="pay_7" value="5" class="form-control"/></div>

        <div class="col-md-3"><label>10 months after Foundation</label></div>
        <div class="col-md-3"><input type="text" name="pay_8" value="5" class="form-control"/></div>

        <div class="col-md-3"><label>12 months after Foundation</label></div>
        <div class="col-md-3"><input type="text" name="pay_9" value="5" class="form-control" /></div>

        <div class="col-md-3"><label>14 months after Foundation</label></div>
        <div class="col-md-3"><input type="text" name="pay_10" value="5" class="form-control"/></div>

        <div class="col-md-3"><label>16 months after Foundation</label></div>
        <div class="col-md-3"><input type="text" name="pay_11" value="5" class="form-control"/></div>

        <div class="col-md-3"><label>18 months after Foundation</label></div>
        <div class="col-md-3"><input type="text" name="pay_12" value="5" class="form-control"/></div>

        <div class="col-md-3"><label>20 months after Foundation</label></div>
        <div class="col-md-3"><input type="text" name="pay_13" value="5" class="form-control"/></div>

        <div class="col-md-3"><label>Building Handover Procedure</label></div>
        <div class="col-md-3"><input type="text" name="pay_14" value="25" class="form-control"/></div>

        <div class="col-md-3"><label>Apartment Ownership Handover</label></div>
        <div class="col-md-3"><input type="text" name="pay_15" value="5" class="form-control"/></div>
    </div>
</div>

<?=Html::hiddenInput('total_project_cost', 2246792608858,['class'=>'form-control form-group total_project_cost']);?>
<?=Html::hiddenInput('sales_price_w_vat', 36438367,['class'=>'form-control form-group sales_price_w_vat']);?>
<?=Html::hiddenInput('net_sellable_area', 78000,['class'=>'form-control form-group net_sellable_area']);?>

<fieldset id="1">
    <legend>T1</legend>
    <div class="col-lg-3 input-percent">
        <?=Html::label('Sales (%)');?>
        <?=Html::input('text','T1_sales',null,['class'=>'form-control form-group sales']);?>
        <?=Html::label('Outgoing Cashflow (%)');?>
        <?=Html::input('text','T1_cashflow',null,['class'=>'form-control form-group cashflow']);?>
    </div>
    <div class="col-lg-4 check-payment">
        <?php
        $list = [
            'pay_1' => 'Reservation Fee', 'pay_2' => '1st Payment (within 1 month)', 'pay_3' => 'Upon Finish Foundation',
            'pay_4' => '2 months after Foundation', 'pay_5' => '4 months after Foundation', 'pay_6' => '6 months after Foundation',
            'pay_7' => '8 months after Foundation', 'pay_8' => '10 months after Foundation', 'pay_9' => '12 months after Foundation',
            'pay_10' => '14 months after Foundation', 'pay_11' => '16 months after Foundation', 'pay_12' => '18 months after Foundation',
            'pay_13' => '20 months after Foundation', 'pay_14' => 'Building Handover Procedure', 'pay_15' => 'Apartment Ownership Handover',
        ];
        echo Html::checkboxList('T1_payment', null, $list);
        ?>
    </div>
</fieldset>

<?=Html::hiddenInput('counter', 1,['class'=>'form-control form-group counter']);?>

<div class="form-group command">
    <div class="col-lg-3">
        <?= Html::button('Add', ['class' => 'btn btn-primary add']) ?>
    </div><div class="col-lg-4">
        <?= Html::button('Calculate', ['class' => 'btn btn-primary calculation']) ?>
    </div>
    <div class="col-lg-4">
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
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });

    $(".cash_result #w0").hide();
//    $(".payment_schedule").click(function(){
//        $(".payment_schedule .row").toggle();
//    });

    $(document).on("click",'#scenario_1 .next',function() {
        chart.next('/tool/save-step?step=scenario_1', 'p_scenario_1', 'scenario_1/afterNext');
        return false;
    });

    $(document).bind( 'scenario_1/afterNext', function(event, json, string){
        console.log(json.data);
        $(".mainConfigSetParams").find(".nav-tabs a[href='#scenario_2']").trigger("click");
    });

//    var total_project_cost = $(".total_project_cost").val();
//    var sales_price_w_vat = $(".sales_price_w_vat").val();
//    var net_sellable_area = $(".net_sellable_area").val();

//    var outgoing_cashflow = 0,
//        cumulative_revenue = 0,
//        accumulative_incoming_cashflow = 0;
//    $(document).on("change",'#scenario_1 .cashflow',function() {
//        var fieldset = $(this).closest('fieldset');
//        console.log($('.total_project_cost').val());
//
//        var out_cashflow = (-1 * parseFloat(fieldset.find('.cashflow').val()) * total_project_cost)/100;
//        var revenue = parseFloat(fieldset.find('.sales').val()/100) * sales_price_w_vat * net_sellable_area;
//
//        var total = 0;
//        var hid_cumulative_revenue = parseFloat(fieldset.find('.hid_cumulative_revenue').val());
//        var hid_outgoing_cashflow = parseFloat(fieldset.find('.hid_outgoing_cashflow').val());
//
//        fieldset.find('.hid_cumulative_revenue').val(cumulative_revenue);
//        fieldset.find('.hid_outgoing_cashflow').val(outgoing_cashflow);
//
//        outgoing_cashflow = outgoing_cashflow + out_cashflow;
//        cumulative_revenue = cumulative_revenue + revenue;
//
//        var incoming_cashflow = 0;
//        fieldset.find('input[type=checkbox]').each(function() {
//            if ($(this).is(":checked")) {
//                var name = 'input[name=pay_'+ $(this).attr('value')+']';
//                var value = revenue * parseFloat($(name).val()/100);
//                incoming_cashflow = incoming_cashflow + value;
//            }
//        });
//
//        accumulative_incoming_cashflow = accumulative_incoming_cashflow + incoming_cashflow;
//        total = outgoing_cashflow + accumulative_incoming_cashflow;
//
//        total = Math.round(total);
//        fieldset.find('input.hid_net_accumulative_cashflow').val(total);
//        fieldset.find('input.hid_net_accumulative_cashflow').autoNumeric('init', {aPad: false});
//    });

    var counter = 1;
//    var flash='';
    $("#scenario_1 .add").click(function(){
        var fieldset = $('form[id^="p_scenario_1"] fieldset:last');
        counter += 1;
        var f = fieldset.clone().attr("id", counter);
        f.find("legend").text("T"+counter);
        f.find("input.cashflow").attr("name", "T"+counter+"_cashflow");
        f.find("input.sales").attr("name", "T"+counter+"_sales");
        f.find("input[type=checkbox]").attr("name", "T"+counter+"_payment[]");
        f.insertAfter(fieldset);

        $("input.counter").val(counter);
    });

    $("#scenario_1 .calculation").click(function() {
        $.ajax({
            type: "post",
            dataType: 'json',
            url: '/tool/save-step?step=calculation_1',
            data: ($('#p_scenario_1').serializeArray()),
            success: function(data) {
//                $(".cash_result #w0").show();
                console.log(data.file);
            },
        });
    });

</script>