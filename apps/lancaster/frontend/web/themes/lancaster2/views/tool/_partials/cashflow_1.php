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
<?=Html::hiddenInput('sales_price_w_vat',  36438367.31032530,['class'=>'form-control form-group sales_price_w_vat']);?>
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

<input type="hidden" class="form-control form-group counter" name="counter" value="3">



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
    <?=$this->render('cashflow_result');?>
</div>

<script>
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });

//    $(".cash_result #scenario1").hide();

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

    var counter = 3;
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
//        if($(".counter").val() > 1){
//            f.find(".delete").show();
//        }
//        else{
//            f.find(".delete").hide();
//        }

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
                $.pjax.reload({container:"#scenario1"});  //Reload GridView
                $(".cash_result #scenario1").show();
            }
        });
    });



</script>