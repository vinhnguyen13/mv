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
<div class="payment_schedule">
    <h4><b>Payment Schedule (%)</b></h4>
    <div class="row">
        <div class="col-md-3"><label>Reservation Fee</label></div>
        <div class="col-md-3"><input type="text" name="pay_1" value="0" class="form-control"/></div>

        <div class="col-md-3"><label>1st Payment (within 1 month)</label></div>
        <div class="col-md-3"><input type="text" name="pay_2" value="15" class="form-control"/></div>

        <div class="col-md-3"><label>Upon Finish Foundation</label></div>
        <div class="col-md-3"><input type="text" name="pay_3" value="10" class="form-control" /></div>

        <div class="col-md-3"><label>2 months after Foundation</label></div>
        <div class="col-md-3"><input type="text" name="pay_4" value="7.5" class="form-control"/></div>

        <div class="col-md-3"><label>4 months after Foundation</label></div>
        <div class="col-md-3"><input type="text" name="pay_5" value="7.5" class="form-control"/></div>

        <div class="col-md-3"><label>6 months after Foundation</label></div>
        <div class="col-md-3"><input type="text" name="pay_6" value="7.5" class="form-control"/></div>

        <div class="col-md-3"><label>8 months after Foundation</label></div>
        <div class="col-md-3"><input type="text" name="pay_7" value="7.5" class="form-control"/></div>

        <div class="col-md-3"><label>10 months after Foundation</label></div>
        <div class="col-md-3"><input type="text" name="pay_8" value="5" class="form-control"/></div>

        <div class="col-md-3"><label>12 months after Foundation</label></div>
        <div class="col-md-3"><input type="text" name="pay_9" value="5" class="form-control" /></div>

        <div class="col-md-3"><label>14 months after Foundation</label></div>
        <div class="col-md-3"><input type="text" name="pay_10" value="5" class="form-control"/></div>

        <div class="col-md-3"><label>16 months after Foundation</label></div>
        <div class="col-md-3"><input type="text" name="pay_11" value="0" class="form-control"/></div>

        <div class="col-md-3"><label>18 months after Foundation</label></div>
        <div class="col-md-3"><input type="text" name="pay_12" value="0" class="form-control"/></div>

        <div class="col-md-3"><label>20 months after Foundation</label></div>
        <div class="col-md-3"><input type="text" name="pay_13" value="0" class="form-control"/></div>

        <div class="col-md-3"><label>Building Handover Procedure</label></div>
        <div class="col-md-3"><input type="text" name="pay_14" value="25" class="form-control"/></div>

        <div class="col-md-3"><label>Apartment Ownership Handover</label></div>
        <div class="col-md-3"><input type="text" name="pay_15" value="5" class="form-control"/></div>
    </div>
</div>

<?=Html::hiddenInput('total_project_cost', 2246792608858,['class'=>'form-control form-group total_project_cost']);?>
<?=Html::hiddenInput('sales_price_w_vat', 36438367.31032530,['class'=>'form-control form-group sales_price_w_vat']);?>
<?=Html::hiddenInput('net_sellable_area', 78000,['class'=>'form-control form-group net_sellable_area']);?>

<fieldset id="1">
    <legend>T1</legend>
    <div class="col-lg-3 input-percent">
        <label>Sales (%)</label>        <input type="text" class="form-control form-group sales" name="T1_sales" value="0">        <label>Outgoing Cashflow (%)</label>        <input type="text" value="12.02" class="form-control form-group cashflow" name="T1_cashflow">    </div>
    <div class="col-lg-4 check-payment">
        <div><label><input type="checkbox" name="T1_payment[]" value="pay_1" checked="checked"> Reservation Fee</label>
            <label><input type="checkbox" name="T1_payment[]" value="pay_2" checked="checked"> 1st Payment (within 1 month)</label>
            <label><input type="checkbox" name="T1_payment[]" value="pay_3"> Upon Finish Foundation</label>
            <label><input type="checkbox" name="T1_payment[]" value="pay_4"> 2 months after Foundation</label>
            <label><input type="checkbox" name="T1_payment[]" value="pay_5"> 4 months after Foundation</label>
            <label><input type="checkbox" name="T1_payment[]" value="pay_6"> 6 months after Foundation</label>
            <label><input type="checkbox" name="T1_payment[]" value="pay_7"> 8 months after Foundation</label>
            <label><input type="checkbox" name="T1_payment[]" value="pay_8"> 10 months after Foundation</label>
            <label><input type="checkbox" name="T1_payment[]" value="pay_9"> 12 months after Foundation</label>
            <label><input type="checkbox" name="T1_payment[]" value="pay_10"> 14 months after Foundation</label>
            <label><input type="checkbox" name="T1_payment[]" value="pay_11"> 16 months after Foundation</label>
            <label><input type="checkbox" name="T1_payment[]" value="pay_12"> 18 months after Foundation</label>
            <label><input type="checkbox" name="T1_payment[]" value="pay_13"> 20 months after Foundation</label>
            <label><input type="checkbox" name="T1_payment[]" value="pay_14"> Building Handover Procedure</label>
            <label><input type="checkbox" name="T1_payment[]" value="pay_15"> Apartment Ownership Handover</label></div>    </div>
</fieldset>
<fieldset id="2">
    <legend>T2</legend>
    <div class="col-lg-3 input-percent">
        <label>Sales (%)</label>        <input type="text" class="form-control form-group sales" name="T2_sales" value="0">        <label>Outgoing Cashflow (%)</label>        <input type="text" class="form-control form-group cashflow" name="T2_cashflow" value="3.23">    </div>
    <div class="col-lg-4 check-payment">
        <div><label><input type="checkbox" name="T2_payment[]" value="pay_1" checked="checked"> Reservation Fee</label>
            <label><input type="checkbox" name="T2_payment[]" value="pay_2" checked="checked"> 1st Payment (within 1 month)</label>
            <label><input type="checkbox" name="T2_payment[]" value="pay_3" > Upon Finish Foundation</label>
            <label><input type="checkbox" name="T2_payment[]" value="pay_4"> 2 months after Foundation</label>
            <label><input type="checkbox" name="T2_payment[]" value="pay_5"> 4 months after Foundation</label>
            <label><input type="checkbox" name="T2_payment[]" value="pay_6"> 6 months after Foundation</label>
            <label><input type="checkbox" name="T2_payment[]" value="pay_7"> 8 months after Foundation</label>
            <label><input type="checkbox" name="T2_payment[]" value="pay_8"> 10 months after Foundation</label>
            <label><input type="checkbox" name="T2_payment[]" value="pay_9"> 12 months after Foundation</label>
            <label><input type="checkbox" name="T2_payment[]" value="pay_10"> 14 months after Foundation</label>
            <label><input type="checkbox" name="T2_payment[]" value="pay_11"> 16 months after Foundation</label>
            <label><input type="checkbox" name="T2_payment[]" value="pay_12"> 18 months after Foundation</label>
            <label><input type="checkbox" name="T2_payment[]" value="pay_13"> 20 months after Foundation</label>
            <label><input type="checkbox" name="T2_payment[]" value="pay_14"> Building Handover Procedure</label>
            <label><input type="checkbox" name="T2_payment[]" value="pay_15"> Apartment Ownership Handover</label></div>    </div>
</fieldset>
<fieldset id="3">
    <legend>T3</legend>
    <div class="col-lg-3 input-percent">
        <label>Sales (%)</label>        <input type="text" class="form-control form-group sales" name="T3_sales" value="0">        <label>Outgoing Cashflow (%)</label>        <input type="text" class="form-control form-group cashflow" name="T3_cashflow" value="1.38">    </div>
    <div class="col-lg-4 check-payment">
        <div><label><input type="checkbox" name="T3_payment[]" value="pay_1" checked="checked"> Reservation Fee</label>
            <label><input type="checkbox" name="T3_payment[]" value="pay_2" checked="checked"> 1st Payment (within 1 month)</label>
            <label><input type="checkbox" name="T3_payment[]" value="pay_3"> Upon Finish Foundation</label>
            <label><input type="checkbox" name="T3_payment[]" value="pay_4"> 2 months after Foundation</label>
            <label><input type="checkbox" name="T3_payment[]" value="pay_5"> 4 months after Foundation</label>
            <label><input type="checkbox" name="T3_payment[]" value="pay_6"> 6 months after Foundation</label>
            <label><input type="checkbox" name="T3_payment[]" value="pay_7"> 8 months after Foundation</label>
            <label><input type="checkbox" name="T3_payment[]" value="pay_8"> 10 months after Foundation</label>
            <label><input type="checkbox" name="T3_payment[]" value="pay_9"> 12 months after Foundation</label>
            <label><input type="checkbox" name="T3_payment[]" value="pay_10"> 14 months after Foundation</label>
            <label><input type="checkbox" name="T3_payment[]" value="pay_11"> 16 months after Foundation</label>
            <label><input type="checkbox" name="T3_payment[]" value="pay_12"> 18 months after Foundation</label>
            <label><input type="checkbox" name="T3_payment[]" value="pay_13"> 20 months after Foundation</label>
            <label><input type="checkbox" name="T3_payment[]" value="pay_14"> Building Handover Procedure</label>
            <label><input type="checkbox" name="T3_payment[]" value="pay_15"> Apartment Ownership Handover</label></div>    </div>
</fieldset>

<input type="hidden" class="form-control form-group counter_2" name="counter_2" value="3">


<div class="form-group command">
    <div class="col-lg-3">
        <?= Html::button('Add', ['class' => 'btn btn-primary add']) ?>
    </div><div class="col-lg-4">
        <?= Html::button('Calculate', ['class' => 'btn btn-primary calculate_2']) ?>
    </div>
    <div class="col-lg-4">
        <?= Html::submitButton('Next', ['class' => 'btn btn-primary next']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<div class="cash_result" style="clear: both; padding-top: 20px;">
    <?=$this->render('cashflow_result',['scenario'=> 2]);?>
</div>
<script>
    $(document).on("click",'#scenario_2 .next',function() {
        chart.next('/tool/save-step?step=scenario_2', 'p_scenario_2', 'scenario_2/afterNext');
        $.pjax.reload({container:"#scenario2"});
        return false;
    });

    $(document).bind( 'scenario_2/afterNext', function(event, json, string){
        /**
         * after save data to continue
         */
        console.log(json.data);
    });


    var counter_2 = 3;
    $("#scenario_2 .add").click(function(){
        var fieldset = $('form[id^="p_scenario_2"] fieldset:last');
        counter_2 += 1;
        var f = fieldset.clone().attr("id", counter_2);
        f.find("legend").text("T"+counter_2);
        f.find("input.cashflow").attr("name", "T"+counter_2+"_cashflow");
        f.find("input.sales").attr("name", "T"+counter_2+"_sales");
        f.find("input[type=checkbox]").attr("name", "T"+counter_2+"_payment[]");
        f.insertAfter(fieldset);

        $("input.counter_2").val(counter_2);
    });

    $("#scenario_2 .calculate_2").click(function() {
        $.ajax({
            type: "post",
            dataType: 'json',
            url: '/tool/save-step?step=calculation_2',
            data: ($('#p_scenario_2').serializeArray()),
            success: function(data) {
                $.pjax.reload({container:"#scenario2"});  //Reload GridView
            }
        });
    });
</script>