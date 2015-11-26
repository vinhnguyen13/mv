<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 10/29/2015
 * Time: 1:55 PM
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<h1>Profit Margin Calculation</h1>
<?php $form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data', 'id'=>'p_profitMarginCalculation'],
    'action' => \yii\helpers\Url::toRoute(['tool/save-step', 'step'=>'profitMarginCalculation'])
]); ?>

<div class="row">
    <div class="col-lg-3">
        <label>Land + Construction Cost</label>
    </div>
    <div class="col-lg-9">
        <input type="text" class="form-control text-right lc_cost" name="lc_cost" value="0" >
        <input type="hidden" class="form-control text-right net_sellable_area" name="net_sellable_area" value="0" >
    </div>
</div>
<div class="row">
    <div class="col-lg-3">
        <label>Financing Cost (Cost of Capital)</label>
    </div>
    <div class="col-lg-3">
        <label>Percent</label>
        <input type="text" class="form-control text-right percentage" name="percentage" value="0" >
    </div>
    <div class="col-lg-6">
        <label>Cost</label>
        <input type="text" class="form-control text-right finance_cost" name="finance_cost" value="0" >
    </div>
</div>

<div class="row">
    <label class="col-md-11">Marketing and Selling Expenses Breakdown</label>
    <span class="marketing_add table-add glyphicon glyphicon-plus col-md-1 text-right pull-right "></span>
</div>
<table class="table marketing_table table-editable">
    <tr>
        <th width="35%">Name</th>
        <th class="text-center">Percent</th>
        <th class="text-center">Amount VND</th>
        <th></th>
        <th></th>
    </tr>
    <tr>
        <td>Sales Gallery</td>
        <td>
            <input type="text" class="form-control text-right" name="sale_gallery_percent" value="0">
        </td><td>
            <input type="text" class="form-control text-right" name="sale_gallery_amount" value="0">
        </td>
        <td>
            <span class="table-remove glyphicon glyphicon-remove"></span>
        </td>
        <td>
            <span class="table-up glyphicon glyphicon-arrow-up"></span>
            <span class="table-down glyphicon glyphicon-arrow-down"></span>
        </td>
    </tr>
    <tr>
        <td>Marketing Efforts (Online & Offline)</td>
        <td>
            <input type="text" class="form-control text-right" name="mark_effort_percent" value="0">
        </td><td>
            <input type="text" class="form-control text-right" name="mark_effort_amount" value="0">
        </td>
        <td>
            <span class="table-remove glyphicon glyphicon-remove"></span>
        </td>
        <td>
            <span class="table-up glyphicon glyphicon-arrow-up"></span>
            <span class="table-down glyphicon glyphicon-arrow-down"></span>
        </td>
    </tr>
    <tr>
        <td>Overhead</td>
        <td>
            <input type="text" class="form-control text-right" name="overhead_percent" value="0">
        </td><td>
            <input type="text" class="form-control text-right" name="overhead_amount" value="0">
        </td>
        <td>
            <span class="table-remove glyphicon glyphicon-remove"></span>
        </td>
        <td>
            <span class="table-up glyphicon glyphicon-arrow-up"></span>
            <span class="table-down glyphicon glyphicon-arrow-down"></span>
        </td>
    </tr>
    <tr class="hide">
        <td contenteditable="true">Untitled</td>
        <td>
            <input type="text" class="form-control text-right" name="mark_percent" value="0">
        </td>
        <td>
            <input type="text" class="form-control text-right" name="mark_amount" value="0">
        </td>
        <td>
            <span class="table-remove glyphicon glyphicon-remove"></span>
        </td>
        <td>
            <span class="table-up glyphicon glyphicon-arrow-up"></span>
            <span class="table-down glyphicon glyphicon-arrow-down"></span>
        </td>
    </tr>
</table>
<div class="row sub">
    <div class="col-lg-3">
        <b>Marketing & Sales Cost</b>
    </div>
    <div class="col-lg-9">
        <input type="text" class="form-control text-right market_sale_cost" name="market_sale_cost" >
    </div>
</div>
<!---->
<div class="row">
    <div class="col-lg-3">
        <?= Html::button('Total Project Cost', ['class' => 'btn btn-primary calc_total_project ']) ?>
    </div>
    <div class="col-lg-9">
        <input type="text" class="form-control text-right total_project_cost" name="total_project_cost" value="0">
    </div>
    <div class="col-lg-12 total_project_detail">
        <div>
            <label>Offer price</label>
            <span class="total_project_add table-add glyphicon glyphicon-plus col-md-1 text-right pull-right hide"></span>
        </div>
        <table class="table total_project_table">
            <tr>
                <th width="35%">Name</th>
                <th class="text-center">Percent</th>
                <th class="text-center">Amount VND</th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <td contenteditable="true">Đơn giá 1 m2 xây dựng tính trên phần diện tích hữu dụng (NSA)</td>
                <td></td>
                <td>
                    <input type="text" class="form-control text-right nsa_amount" name="nsa_amount" value="0">
                </td>
                <td>
                    <span class="table-remove glyphicon glyphicon-remove"></span>
                </td>
                <td>
                    <span class="table-up glyphicon glyphicon-arrow-up"></span>
                    <span class="table-down glyphicon glyphicon-arrow-down"></span>
                </td>
            </tr>
            <tr>
                <td contenteditable="true">Selling Commission</td>
                <td>
                    <input type="text" class="form-control text-right selling_commission_percent" name="selling_percent" value="0">
                </td>
                <td>
                    <input type="text" class="form-control text-right selling_commission_amount" name="selling_amount" value="0">
                </td>
                <td>
                    <span class="table-remove glyphicon glyphicon-remove"></span>
                </td>
                <td>
                    <span class="table-up glyphicon glyphicon-arrow-up"></span>
                    <span class="table-down glyphicon glyphicon-arrow-down"></span>
                </td>
            </tr>
            <tr>
                <td contenteditable="true">Target Profit % per Sellable Square Meter</td>
                <td>
                    <input type="text" class="form-control text-right target_percent" name="target_percent" value="0">
                </td>
                <td>
                    <input type="text" class="form-control text-right target_amount" name="target_amount" value="0">
                </td>
                <td>
                    <span class="table-remove glyphicon glyphicon-remove"></span>
                </td>
                <td>
                    <span class="table-up glyphicon glyphicon-arrow-up"></span>
                    <span class="table-down glyphicon glyphicon-arrow-down"></span>
                </td>
            </tr>
            <tr class="hide">
                <td contenteditable="true">Untitled</td>
                <td>
                    <input type="text" class="form-control text-right" name="total_percent" value="0">
                </td>
                <td>
                    <input type="text" class="form-control text-right" name="total_amount" value="0">
                </td>
                <td>
                    <span class="table-remove glyphicon glyphicon-remove"></span>
                </td>
                <td>
                    <span class="table-up glyphicon glyphicon-arrow-up"></span>
                    <span class="table-down glyphicon glyphicon-arrow-down"></span>
                </td>
            </tr>
        </table>
        <div class="row">
            <div class="col-lg-3 text-right">
                <b>Sale Price</b> <i style="color: #a47e3c;">(Offer price * 1.1)</i>
            </div>
            <div class="col-lg-9">
                <input type="text" class="form-control text-right sales_price_w_vat" name="sales_price_w_vat" >
            </div>
        </div>
        <div>
            <?= Html::submitButton('Next', ['class' => 'btn btn-primary col-lg-1 pull-right next']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<script>
    $(document).ready(function(){
        $(".row input").each(function(){
            $(this).autoNumeric('init',{vMax: 999999999999999.99, aPad : false});
        });
        $('.marketing_table').find('input[name$=_amount]').each(function () {
            $(this).attr('tabindex', '-1');

        });
        $(".percentage").autoNumeric('init',{aSign: '%', pSign: 's'});
        $(".total_project_detail").hide();
    });

    $(document).on("click",'#profitMarginCalculation .next',function() {
        chart.next('/tool/save-step?step=profitMarginCalculation', 'p_profitMarginCalculation', 'profitMarginCalculation/afterNext');
        return false;
    });
    /**
     * trigger event after success ajax
     */
    $(document).bind( 'profitMarginCalculation/afterNext', function(event, json, string){
        $(".mainConfigSetParams").find(".nav-tabs a[href='#scenario_1']").trigger("click");

        $(".mainConfigSetParams").find('#scenario_1 .total_project_cost').attr('value', $("#profitMarginCalculation .total_project_cost").autoNumeric('get'));
        $(".mainConfigSetParams").find('#scenario_1 .net_sellable_area').attr('value', $("#profitMarginCalculation .net_sellable_area").autoNumeric('get'));
        $(".mainConfigSetParams").find('#scenario_1 .sales_price_w_vat').attr('value', $("#profitMarginCalculation .sales_price_w_vat").autoNumeric('get'));

        $(".mainConfigSetParams").find('#scenario_2 .total_project_cost').attr('value', $("#profitMarginCalculation .total_project_cost").autoNumeric('get'));
        $(".mainConfigSetParams").find('#scenario_2 .net_sellable_area').attr('value', $("#profitMarginCalculation .net_sellable_area").autoNumeric('get'));
        $(".mainConfigSetParams").find('#scenario_2 .sales_price_w_vat').attr('value', $("#profitMarginCalculation .sales_price_w_vat").autoNumeric('get'));
    });

    function getFinanceCost(){
        var percentage = parseFloat($(".percentage").autoNumeric('get'));
        var lc_cost = parseFloat($(".lc_cost").autoNumeric('get'));
        var finance_cost = percentage / 100 * lc_cost;
        $(".finance_cost").autoNumeric('set', finance_cost);
        return finance_cost;
    }

    $(".percentage").blur(function(){
//        getFinanceCost();
        getTotalProjectCost();
    });

    $(".lc_cost").change(function(){
//        getFinanceCost();
        getTotalProjectCost();
    });

    $(".calc_total_project").click(function(){
        getTotalProjectCost();
//        $("#profitMarginCalculation .total_project_detail").hide();
//        $("#profitMarginCalculation .sub").hide();
        $("#profitMarginCalculation .total_project_detail").show("slow");
        $("#profitMarginCalculation .sub").show("slow");
    });

    function getTotalProjectCost(){
        var lc_cost = parseFloat($(".lc_cost").autoNumeric('get'));
        var finance_cost = getFinanceCost();
        var market_sale_cost = getMarketSaleCost();
        var total_project_cost = lc_cost + finance_cost + market_sale_cost;
        $("#profitMarginCalculation .total_project_cost").autoNumeric('init', {vMax: 999999999999999.99, aPad : false});
        $("#profitMarginCalculation .total_project_cost").autoNumeric('set', total_project_cost);
        $("#profitMarginCalculation .total_project_cost").attr('value', total_project_cost);

        var net_sellable_area = parseFloat($(".net_sellable_area").autoNumeric('get'));
        var nsa_amount = total_project_cost / net_sellable_area;
        $(".nsa_amount").autoNumeric('set', nsa_amount);

        $("#profitMarginCalculation .lc_cost").parent('div').addClass('vat');
        $("#profitMarginCalculation .finance_cost").parent('div').addClass('vat');
        $("#profitMarginCalculation .total_project_cost").parent('div').addClass('total');
        getSalePrice();
        $("#profitMarginCalculation .sales_price_w_vat").parent('div').addClass('total');
        $("#profitMarginCalculation .selling_percent").focus();

    }

    var mark_counter = 1;
    $('.marketing_add').click(function () {
        var $clone = $(".marketing_table").find('tr.hide').clone(true).removeClass('hide table-line');
        $clone.find('input[name=mark_amount]').attr('name', 'mark' + mark_counter + '_amount').addClass('mark' + mark_counter + '_amount');
        $clone.find('input[name=mark_percent]').attr('name', 'mark' + mark_counter + '_percent').addClass('mark' + mark_counter + '_percent');
        $(".marketing_table").append($clone);
        $(".marketing_table input[name$=_percent]:last").focus();
        mark_counter += 1;
    });

    var total_counter = 1;
    $('.total_project_add').click(function (e) {
        var $clone = $(".total_project_table").find('tr.hide').clone(true).removeClass('hide table-line');
        $clone.find('input[name=total_amount]').attr('name', 'total' + total_counter + '_amount').addClass('total' + total_counter + '_amount');
        $clone.find('input[name=total_percent]').attr('name', 'total' + total_counter + '_percent').addClass('total' + total_counter + '_percent');
        $(".total_project_table").append($clone);
        $(".total_project_table input[name$=_percent]:last").focus();
        total_counter += 1;
    });

    $(".table-remove").click(function () {
        $(this).parents('tr').detach();
        getTotalProjectCost();
    });

    $(".table-up").click(function () {
        var $row = $(this).parents('tr');
        if ($row.index() === 1) return; // Don't go above the header
        $row.prev().before($row.get(0));
    });

    $(".table-down").click(function () {
        var $row = $(this).parents('tr');
        $row.next().after($row.get(0));
    });

    $(".marketing_table input[name$=_percent]").change(function() {
        var lc_cost = parseFloat($(".lc_cost").autoNumeric('get'));
        var name = $(this).attr('name').replace('_percent','_amount');
        var value = $(this).autoNumeric('init',{vMax: 999999999999999.99, aPad:false});
        value = value.autoNumeric('get') / 100 * lc_cost;
        $("input[name="+name+"]").autoNumeric('init',{vMax: 999999999999999.99, aPad:false});
        $("input[name="+name+"]").autoNumeric('set', value);

//        getMarketSaleCost();
        getTotalProjectCost();
    });

    function getMarketSaleCost(){
        var market_sale_cost = 0;
        $(".marketing_table input[name$=_amount]").each(function(){
            $(this).autoNumeric('init', {vMax: 999999999999999.99, aPad : false});
            var tempValue = parseFloat($(this).autoNumeric('get'));
            if(tempValue > 0) {
                market_sale_cost = market_sale_cost + tempValue;
            }
        });
        $(".market_sale_cost").autoNumeric('set', market_sale_cost);
        return market_sale_cost;
    }

    $(".total_project_table input[name$=_percent]").change(function() {
        var nsa_amount = parseFloat($(".nsa_amount").autoNumeric('get'));
        var name = $(this).attr('name').replace('_percent','_amount');
        var value = $(this).autoNumeric('init',{vMax: 999999999999999.99999, aPad:false});
        value = value.autoNumeric('get') / 100 * nsa_amount;
        $("input[name="+name+"]").autoNumeric('init',{vMax: 999999999999999.99999, aPad:false});
        $("input[name="+name+"]").autoNumeric('set', value);
        getSalePrice();
    });

    $(".total_project_table input[name$=_amount]").change(function() {
        getSalePrice();
    });

    function getSalePrice(){
        var offer_price = 0;
        $(".total_project_table input[name$=_amount]").each(function(){
            $(this).attr('style','border-color: #a47e3c;');
            $(this).autoNumeric('init', {vMax: 999999999999999.99999, aPad : false});
            var tempValue = parseFloat($(this).autoNumeric('get'));
            if(tempValue > 0) {
                offer_price = offer_price + tempValue;
            }
        });
        var sale_price = offer_price * 1.1;
        $("#profitMarginCalculation .sales_price_w_vat").autoNumeric('init', {vMax: 999999999999999.99999, aPad : false});
        $("#profitMarginCalculation .sales_price_w_vat").autoNumeric('set', sale_price);
        return sale_price;
    }

</script>