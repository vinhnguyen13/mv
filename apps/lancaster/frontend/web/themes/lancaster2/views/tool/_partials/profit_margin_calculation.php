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
        <input type="text" class="form-control text-right lc_cost" name="lc_cost" value="2213588777200" >
        <input type="hidden" class="form-control text-right net_sellable" name="net_sellable" value="78000" >
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
<table class="table marketing_table">
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
    <!-- This is our clonable table line -->
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
    <div class="col-lg-12">
        <table class="table total_project_table">
            <tr>
                <th width="35%">Name</th>
                <th class="text-center">Percent</th>
                <th class="text-center">Amount VND</th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <td>Đơn giá 1 m2 xây dựng tính trên phần diện tích hữu dụng (NSA)</td>
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
                <td>Selling Commission</td>
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
                <td>Target Profit % per Sellable Square Meter</td>
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
            <!-- This is our clonable table line -->
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
            <div class="col-lg-3">
                <b>Sale Price</b>
            </div>
            <div class="col-lg-9">
                <input type="text" class="form-control text-right sale_price" name="sale_price" >
            </div>
        </div>
    </div>
</div>
<div>
    <?= Html::submitButton('Next', ['class' => 'btn btn-primary col-lg-1 pull-right next']) ?>
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
        if(json.data.total_project_cost){
            $(".mainConfigSetParams").find('#scenario_1 .total_project_cost').autoNumeric('init',{vMax: 999999999999999.99, aPad : false});
            $(".mainConfigSetParams").find('#scenario_1 .total_project_cost').autoNumeric('set', json.data.total_project_cost);
            $(".mainConfigSetParams").find('#scenario_1 .total_project_cost').attr('value', json.data.total_project_cost);

            $(".mainConfigSetParams").find('#scenario_1 .net_sellable_area').autoNumeric('init',{vMax: 999999999999999.99, aPad : false});
            $(".mainConfigSetParams").find('#scenario_1 .net_sellable_area').autoNumeric('set', json.data.net_sellable);
            $(".mainConfigSetParams").find('#scenario_1 .net_sellable_area').attr('value', json.data.net_sellable);

            $(".mainConfigSetParams").find('#scenario_1 .sales_price_w_vat').autoNumeric('init',{vMax: 999999999999999.99, aPad : false});
            $(".mainConfigSetParams").find('#scenario_1 .sales_price_w_vat').autoNumeric('set', json.data.sale_price);
            $(".mainConfigSetParams").find('#scenario_1 .sales_price_w_vat').attr('value', json.data.sale_price);
        }
    });

    function getFinanceCost(){
        var percentage = parseFloat($(".percentage").autoNumeric('get'));
        var lc_cost = parseFloat($(".lc_cost").autoNumeric('get'));
        var finance_cost = percentage / 100 * lc_cost;
        $(".finance_cost").autoNumeric('set', finance_cost);
        return finance_cost;
    }

    $(".percentage").blur(function(){
        getFinanceCost();
    });

    $(".lc_cost").change(function(){
        getFinanceCost();
    });

    $(".calc_total_project").click(function(){
        getTotalProjectCost();
    });

    function getTotalProjectCost(){
        var lc_cost = parseFloat($(".lc_cost").autoNumeric('get'));
        var finance_cost = getFinanceCost();
        var market_sale_cost = getMarketSaleCost();
        $("#profitMarginCalculation .sub").show();
        var total_project_cost = lc_cost + finance_cost + market_sale_cost;
        $(".total_project_cost").autoNumeric('init', {vMax: 999999999999999.99, aPad : false});
        $(".total_project_cost").autoNumeric('set', total_project_cost);

        var net_sellable = parseFloat($(".net_sellable").autoNumeric('get'));
        var nsa_amount = total_project_cost / net_sellable;
        $(".nsa_amount").autoNumeric('set', nsa_amount);

        $(".lc_cost").parent('div').addClass('vat');
        $(".finance_cost").parent('div').addClass('vat');
        $(".total_project_cost").parent('div').addClass('total');

    }

    var mark_counter = 1;
    $('.marketing_add').click(function () {
        var $clone = $(".marketing_table").find('tr.hide').clone(true).removeClass('hide table-line');
        $clone.find('input[name=mark_amount]').attr('name', 'mark' + lc_counter + '_amount').addClass('mark' + mark_counter + '_amount');
        $clone.find('input[name=mark_percent]').attr('name', 'mark' + lc_counter + '_percent').addClass('mark' + mark_counter + '_percent');
        $(".marketing_table").append($clone);
        $(".marketing_table input[name$=_percent]:last").focus();
        mark_counter += 1;
    });

    $(".table-remove").click(function () {
        $(this).parents('tr').detach();
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

        getMarketSaleCost();
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
        var value = $(this).autoNumeric('init',{vMax: 999999999999999.99, aPad:false});
        value = value.autoNumeric('get') / 100 * nsa_amount;
        $("input[name="+name+"]").autoNumeric('init',{vMax: 999999999999999.99, aPad:false});
        $("input[name="+name+"]").autoNumeric('set', value);

        var offer_price = getOfferPrice();
        $(".sale_price").autoNumeric('set', offer_price * 1.1);
    });

    function getOfferPrice(){
        var offer_price = 0;
        $(".total_project_table input[name$=_amount]").each(function(){
            $(this).autoNumeric('init', {vMax: 999999999999999.99, aPad : false});
            var tempValue = parseFloat($(this).autoNumeric('get'));
            if(tempValue > 0) {
                offer_price = offer_price + tempValue;
            }
        });
        return offer_price;
    }

</script>