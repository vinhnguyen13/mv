<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<style>

    .table-editable {
        position: relative;

    .glyphicon {
        font-size: 20px;
    }

    }

    div.exchange_rate {
        display: inline-flex;
    }

    .table input {
        /*border: none;*/
        /*box-shadow: none;*/
        margin: 0 auto;
        display: block;
        width: auto;
    }

    .table tr:hover {
        background-color: beige;
    }

    .table th {
        padding: 0px 10px 0px 8px !important;
        background-color: gainsboro;
    }

    .table td {
        padding: 0px 10px 0px 10px !important;
        vertical-align: middle !important;
    }

    .table-editable a {
        text-decoration: none;
        color: #333;
    }

    .table-remove {
        color: #700;
        cursor: pointer;
        margin: 0 auto;
        display: none;
    }

    .table-remove :hover {
        color: #f0f0f0;
    }

    .table-editable .table-remove {
        color: #700;
        cursor: pointer;
        margin: 0 auto;
        display: table;
    }

    .table-editable .table-remove :hover {
        color: #f0f0f0;
    }

    .table-up, .table-down {
        color: #007;
        cursor: pointer;
    }

    .table-up:hover, .table-down:hover {
        color: #f0ad4e;
    }

    .table-add {
        color: #070;
        cursor: pointer;
        position: relative;
    }

    .table-add:hover {
        color: #00bb00;
    }

    .sub{
        display: none;
    }
    .sub input, .vat input {
        border-color: #a47e3c !important;
        background-color: #FFFFC5;
    }

    .total input {
        border-color: #a47e3c !important;
        background-color: #c3d9ff;
    }

    .exchange_rate {
        margin-bottom: 20px;
    }

    .table {
        margin-bottom: 0px;
    }

</style>
<div class="container">
    <h1>Development Cost Plan</h1>

    <p></p>

    <ul>
        <li>An editable table that exports a hash array. Dynamically compiles rows from headers</li>
        <li>Simple / powerful features such as add row, remove row, move row up/down.</li>
    </ul>

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data', 'id' => 'p_developmentCostPlan'],
        'action' => \yii\helpers\Url::toRoute(['tool/save-step', 'step' => 'developmentCostPlan'])
    ]); ?>


    <div id="table">
        <div class="exchange_rate pull-right">
            <span class="col-md-11">Exchange rate:</span>
            <input type="text" class="form-control text-right" name="exchange_rate" value="22350">
        </div>
        <div class="row">
            <label class="col-md-11">Project Information</label>
            <span class="project_info_add table-add glyphicon glyphicon-plus col-md-1 text-right pull-right hide"></span>
        </div>
        <table class="table project_info_table">
            <tr>
                <th width="35%">Name</th>
                <th width="15%"></th>
                <th class="text-center">Amount</th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <td>Total CFA (Total Construction Floor Area)</td>
                <td></td>
                <td>
                    <input type="text" class="form-control text-right" name="total_cfa" value="150000">
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
                <td>Basement CFA</td>
                <td></td>
                <td>
                    <input type="text" class="form-control text-right" name="basement_cfa" value="26000">
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
                <td>Residential + Podium CFA</td>
                <td></td>
                <td>
                    <input type="text" class="form-control text-right" name="res_pod_cfa" value="124000">
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
                <td>Retail Podium</td>
                <td></td>
                <td>
                    <input type="text" class="form-control text-right" name="retail_podium" value="16500">
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
                <td>Residential CFA</td>
                <td></td>
                <td>
                    <input type="text" class="form-control text-right" name="residential_cfa" value="107500">
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
                <td>Net Sellable Area</td>
                <td></td>
                <td>
                    <input type="text" class="form-control text-right" name="net_sellable_area" value="78000">
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
                <td>Total Unit Per Tower</td>
                <td></td>
                <td>
                    <input type="text" class="form-control text-right" name="total_unit" value="500">
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
                <td></td>
                <td>
                    <input type="text" class="form-control text-right" name="pi" value="0">
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
            <label class="col-md-11">A. Construction Cost</label>
            <span class="construction_cost_add table-add glyphicon glyphicon-plus col-md-1 text-right pull-right hide"></span>
        </div>
        <table class="table construction_cost_table">
            <tr>
                <th width="35%">Name</th>
                <th width="15%" class="text-center">Cost USD</th>
                <th class="text-center">Cost VND</th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <td>Piling Works</td>
                <td>
                    <input type="text" class="form-control text-right" name="pw_usd" >
                </td>
                <td>
                    <input type="text" class="form-control text-right" name="pw_vnd" >
                </td>

                <td>
                    <?=Html::hiddenInput('pw_amount', 0, ['class'=>'pw_amount']);?>
                    <span class="table-remove glyphicon glyphicon-remove"></span>
                </td>
                <td>
                    <span class="table-up glyphicon glyphicon-arrow-up"></span>
                    <span class="table-down glyphicon glyphicon-arrow-down"></span>
                </td>
            </tr>
            <tr>
                <td>Basement + Substructure</td>
                <td>
                    <input type="text" class="form-control text-right" name="bs_usd" >
                </td>
                <td>
                    <input type="text" class="form-control text-right" name="bs_vnd" >
                </td>
                <td>
                    <?=Html::hiddenInput('bs_amount', 0, ['class'=>'bs_amount']);?>
                    <span class="table-remove glyphicon glyphicon-remove"></span>
                </td>
                <td>
                    <span class="table-up glyphicon glyphicon-arrow-up"></span>
                    <span class="table-down glyphicon glyphicon-arrow-down"></span>
                </td>
            </tr>
            <tr>
                <td>Retail Podium</td>
                <td>
                    <input type="text" class="form-control text-right" name="rp_usd" >
                </td>
                <td>
                    <input type="text" class="form-control text-right" name="rp_vnd" >
                </td>
                <td>
                    <?=Html::hiddenInput('rp_amount', 0, ['class'=>'rp_amount']);?>
                    <span class="table-remove glyphicon glyphicon-remove"></span>
                </td>
                <td>
                    <span class="table-up glyphicon glyphicon-arrow-up"></span>
                    <span class="table-down glyphicon glyphicon-arrow-down"></span>
                </td>
            </tr>
            <tr>
                <td>Residential Towers</td>
                <td>
                    <input type="text" class="form-control text-right" name="rt_usd" >
                </td>
                <td>
                    <input type="text" class="form-control text-right" name="rt_vnd" >
                </td>
                <td>
                    <?=Html::hiddenInput('rt_amount', 0, ['class'=>'rt_amount']);?>
                    <span class="table-remove glyphicon glyphicon-remove"></span>
                </td>
                <td>
                    <span class="table-up glyphicon glyphicon-arrow-up"></span>
                    <span class="table-down glyphicon glyphicon-arrow-down"></span>
                </td>
            </tr>
            <tr>
                <td>External Works</td>
                <td>
                    <input type="text" class="form-control text-right" name="ew_usd" >
                </td>
                <td>
                    <input type="text" class="form-control text-right" name="ew_vnd" >
                </td>
                <td>
                    <?=Html::hiddenInput('ew_amount', 0, ['class'=>'ew_amount']);?>
                    <span class="table-remove glyphicon glyphicon-remove"></span>
                </td>
                <td>
                    <span class="table-up glyphicon glyphicon-arrow-up"></span>
                    <span class="table-down glyphicon glyphicon-arrow-down"></span>
                </td>
            </tr>
            <tr>
                <td>MEP Services</td>
                <td>
                    <input type="text" class="form-control text-right" name="mep_usd" >
                </td>
                <td>
                    <input type="text" class="form-control text-right" name="mep_vnd" >
                </td>
                <td>
                    <?=Html::hiddenInput('mep_amount', 0, ['class'=>'mep_amount']);?>
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
                    <input type="text" class="form-control text-right" name="cc_usd" >
                </td>
                <td>
                    <input type="text" class="form-control text-right" name="cc_vnd" >
                </td>
                <td>
                    <?=Html::hiddenInput('cc_amount', 0, ['class'=>'cc_amount']);?>
                    <span class="table-remove glyphicon glyphicon-remove"></span>
                </td>
                <td>
                    <span class="table-up glyphicon glyphicon-arrow-up"></span>
                    <span class="table-down glyphicon glyphicon-arrow-down"></span>
                </td>
            </tr>
        </table>
        <div class="row sub">
            <div class="col-lg-6">
                <b>Subtotal Construction Cost</b>
            </div>
            <div class="col-lg-3">
                <input type="text" class="form-control text-right" name="subtotal_A" >
            </div>
            <div class="col-lg-6">
                <b>VAT</b>
            </div>
            <div class="col-lg-3">
                <input type="text" class="form-control text-right" name="vat" >
            </div>
            <div class="col-lg-3"></div>
        </div>

        <div class="row">
            <label class="col-md-11">B. Design & Consultants Cost</label>
            <span class="construction_cost_add table-add glyphicon glyphicon-plus col-md-1 text-right pull-right hide"></span>
        </div>
        <table class="table design_consultant_table">
            <tr>
                <th width="35%">Name</th>
                <th width="15%" class="text-center">USD or Percent</th>
                <th class="text-center">Amount VND</th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <td>Design Development Cost(USD)</td>
                <td>
                    <input type="text" class="form-control text-right" name="design_dev_usd" >
                </td>
                <td>
                    <input type="text" class="form-control text-right" name="design_dev_amount" >
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
                <td>Project Management Cost</td>
                <td>
                    <input type="text" class="form-control text-right" name="pro_mng_cost_percent" >

                </td>
                <td>
                    <input type="text" class="form-control text-right" name="pro_mng_cost_amount" >
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
                <td>Construction Management</td>
                <td>
                    <input type="text" class="form-control text-right" name="construction_mng_percent" >
                </td>
                <td>
                    <input type="text" class="form-control text-right" name="construction_mng_amount" >
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
                <td>Construction Supervision</td>
                <td>
                    <input type="text" class="form-control text-right" name="construction_sup_percent" >
                </td>
                <td>
                    <input type="text" class="form-control text-right" name="construction_sup_amount" >
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
                <td>Quantity Surveryor (post Construction)</td>

                <td>
                    <input type="text" class="form-control text-right" name="qty_sur_percent" >
                </td>
                <td>
                    <input type="text" class="form-control text-right" name="qty_sur_amount" >
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
                <td>In House PMO</td>
                <td>
                    <input type="text" class="form-control text-right" name="in_house_pmo_percent" >
                </td>
                <td>
                    <input type="text" class="form-control text-right" name="in_house_pmo_amount" >
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
                <td>BIM Management</td>
                <td>
                    <input type="text" class="form-control text-right" name="bim_mng_percent" >
                </td>
                <td>
                    <input type="text" class="form-control text-right" name="bim_mng_amount" >
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
                <td>Sunk Cost</td>
                <td></td>
                <td>
                    <input type="text" class="form-control text-right" name="sunk_cost_amount" value="18000000000">
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
                    <input type="text" class="form-control text-right" name="dc_percent" >
                </td>
                <td>
                    <input type="text" class="form-control text-right" name="dc_amount" >
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
            <div class="col-lg-6 col-center">
                <b>Subtotal Design & Consultants Cost</b>
            </div>
            <div class="col-lg-3">
                <input type="text" class="form-control text-right" name="subtotal_B" >
            </div>
        </div>

        <div class="row">
            <label class="col-md-11">C. Land & Associated Fees Cost</label>
            <span class="land_associated_add table-add glyphicon glyphicon-plus col-md-1 text-right pull-right "></span>
        </div>
        <table class="table land_associated_table table-editable">
            <tr>
                <th width="35%">Name</th>
                <th width="15%"class="text-center"></th>
                <th class="text-center">Amount VND</th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <td>Land Cost</td>
                <td></td>
                <td>
                    <input type="text" class="form-control text-right" name="land_cost_amount" value="250000000000">
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
                <td></td>
                <td>
                    <input type="text" class="form-control text-right" name="lc_amount" >
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
            <div class="col-lg-6 col-center">
                <b>Subtotal Land & Associated Fees Cost</b>
            </div>
            <div class="col-lg-3">
                <input type="text" class="form-control text-right" name="subtotal_C" >
            </div>
        </div>
        <div style="margin-top: 20px;">
            <div class="col-lg-3">
                <?= Html::button('Subtotal', ['class' => 'btn btn-primary subtotal']) ?>
            </div><div class="col-lg-6">
                <input type="text" class="form-control text-right" name="subtotal" >
            </div>
            <div class="col-lg-3">
                <?= Html::submitButton('Next', ['class' => 'btn btn-primary pull-right next']) ?>
            </div>
        </div>

    </div>


    <?php ActiveForm::end(); ?>
</div>
<script>
    $(document).ready(function(){

        $("input[name=exchange_rate]").autoNumeric('init', {vMax: 999999999999999.99, aPad: false});

        $('.project_info_table').find('input').each(function () {
            $(this).autoNumeric('init', {vMax: 999999999999999.99, aPad: false});
        });

        $('.construction_cost_table input').each(function () {
            $(this).autoNumeric('init', {vMax: 999999999999999.99, aPad: false});
            var name = $(this).attr('name');
            if(name.lastIndexOf('_vnd') > 0){
                $(this).attr('tabindex', '-1');
            }
        });

        $('.design_consultant_table input').each(function () {
            $(this).autoNumeric('init', {vMax: 999999999999999.99, aPad: false});
            var name = $(this).attr('name');
            if(name.lastIndexOf('_amount') > 0){
                $(this).attr('tabindex', '-1');
            }
        });

        $('.land_associated_table').find('input').each(function () {
            $(this).autoNumeric('init', {vMax: 999999999999999.99, aPad: false});
        });

//        $('.design_consultant_table').find('input[name$=_amount]').each(function () {
//            $(this).attr('tabindex', '-1');
//
//        });
//        $('.construction_cost_table').find('input[name$=_vnd]').each(function () {
//            $(this).attr('tabindex', '-1');
//        });

        $("input[name=sunk_cost_amount]").removeAttr('tabindex');
    });

    $(document).on("click",'#developmentCostPlan .next',function() {
        chart.next('/tool/save-step?step=developmentCostPlan', 'p_developmentCostPlan', 'developmentCostPlan/afterNext');
        return false;
    });
    /**
     * trigger event after success ajax
     */
    $(document).bind( 'developmentCostPlan/afterNext', function(event, json, string){
        $(".mainConfigSetParams").find(".nav-tabs a[href='#profitMarginCalculation']").trigger("click");
//        console.log(json.data);
        var subtotal = $("#developmentCostPlan input[name=subtotal]").autoNumeric('get');
        var net_sellable_area = $("#developmentCostPlan input[name=net_sellable_area]").autoNumeric('get');

        $(".mainConfigSetParams").find('#profitMarginCalculation .lc_cost').autoNumeric('set', subtotal);
        $(".mainConfigSetParams").find('#profitMarginCalculation .lc_cost').attr('value', subtotal);
        $(".mainConfigSetParams").find('#profitMarginCalculation .net_sellable_area').attr('value', net_sellable_area);
    });

//    var $table = $("#table");
    var lc_counter = 1;
    $('.land_associated_add').on('click', function () {
        var $clone = $('.land_associated_table tr.hide').clone().removeClass('hide table-line');
        $clone.find('input[name=lc_amount]').val(0).attr('name', 'lc' + lc_counter + '_amount').attr('id','lc' + lc_counter + '_amount');
        $('table.land_associated_table').append($clone);
        $('.land_associated_table').find('input:last').focus();
        lc_counter += 1;
    });

    $(".construction_cost_table input[name$=_usd]").change(function() {
        var here = $(this).autoNumeric('init', {vMax: 999999999999999.99, aPad: false});

        var total_cfa = $("input[name=total_cfa]").autoNumeric('init', {vMax: 999999999999999.99, aPad: false});
        total_cfa = $("input[name=total_cfa]").autoNumeric('get');

        var basement_cfa = $("input[name=basement_cfa]").autoNumeric('init',{vMax: 999999999999999.99, aPad:false});
        basement_cfa = $("input[name=basement_cfa]").autoNumeric('get');

        var exchange_rate = $("input[name=exchange_rate]").autoNumeric('init', {vMax: 999999999999999.99, aPad: false});
        exchange_rate = $("input[name=exchange_rate]").autoNumeric('get');

        if($(this).attr('name') === 'pw_usd'){
            var vnd = exchange_rate * here.autoNumeric('get');
            var pw = total_cfa * vnd;
            $("input[name=pw_vnd").autoNumeric('set', vnd);
            $(".pw_amount").val(pw);
        }
        else if($(this).attr('name') === 'bs_usd'){
            var vnd = exchange_rate * here.autoNumeric('get');
            var bs = basement_cfa * vnd;
            $("input[name=bs_vnd").autoNumeric('set', vnd);
            $(".bs_amount").val(bs);
        }
        else if($(this).attr('name') === 'rp_usd'){
            var retail_podium = $("input[name=retail_podium]").autoNumeric('get');
            var vnd = exchange_rate * here.autoNumeric('get');
            var rp = retail_podium * vnd;
            $("input[name=rp_vnd").autoNumeric('set', vnd);
            $(".rp_amount").val(rp);
        }
        else if($(this).attr('name') === 'rt_usd'){
            var residential_cfa = $("input[name=residential_cfa]").autoNumeric('get');
            var vnd = exchange_rate * here.autoNumeric('get');
            var rt = residential_cfa * vnd;
            $("input[name=rt_vnd").autoNumeric('set', vnd);
            $(".rt_amount").val(rt);
        }
        else if($(this).attr('name') === 'ew_usd'){
            var res_pod_cfa = $("input[name=res_pod_cfa]").autoNumeric('get');
            var vnd = exchange_rate * here.autoNumeric('get');
            var ew = res_pod_cfa * vnd;
            $("input[name=ew_vnd").autoNumeric('set', vnd);
            $(".ew_amount").val(ew);
        }
        else if($(this).attr('name') === 'mep_usd'){
            var mep = total_cfa - (basement_cfa/2);
            var vnd = exchange_rate * here.autoNumeric('get');
            var mep_value = mep * vnd;
            $("input[name=mep_vnd").autoNumeric('set', vnd);
            $(".mep_amount").val(mep_value);
        }

    });

    $(".design_consultant_table input[name$=_percent]").change(function() {
        var subtotal_A = getSubtotalA();
        var name = $(this).attr('name').replace('_percent','_amount');
        var value = $(this).autoNumeric('init',{vMax: 999999999999999.99, aPad:false});
        value = value.autoNumeric('get') / 100 * subtotal_A;
        $("input[name="+name+"]").autoNumeric('init',{vMax: 999999999999999.99, aPad:false});
        $("input[name="+name+"]").autoNumeric('set', value);
    });

    $(".design_consultant_table input[name=design_dev_usd]").change(function() {
        var total_cfa = parseFloat($("input[name=total_cfa]").attr('value'));
        var exchange_rate = parseFloat($("input[name=exchange_rate]").attr('value'));
        var value = $(this).autoNumeric('init',{vMax: 999999999999999.99, aPad : false});
        var name = $(this).attr('name').replace('_usd','_amount');
        value = total_cfa * value.autoNumeric('get') * exchange_rate;
        $("input[name="+name+"]").autoNumeric('init',{vMax: 999999999999999.99, aPad:false});
        $("input[name="+name+"]").autoNumeric('set', value);
    });

    $('.table-remove').click(function () {
        $(this).parents('tr').detach();
    });

    $(".land_associated_table .table-remove").click(function () {
        $(this).parents('tr').detach();
    });

    $('.table-up').click(function () {
        var $row = $(this).parents('tr');
        if ($row.index() === 1) return; // Don't go above the header
        $row.prev().before($row.get(0));
    });

    $('.table-down').click(function () {
        var $row = $(this).parents('tr');
        $row.next().after($row.get(0));
    });

    $(".subtotal").click(function(){
        var subtotal_A = getSubtotalA();
        $("input[name=subtotal_A]").autoNumeric('init', {vMax: 999999999999999.99, aPad:false});
        $("input[name=subtotal_A]").autoNumeric('set', subtotal_A);
        var vat = subtotal_A * 0.1;
        $("input[name=vat]").autoNumeric('init', {vMax: 999999999999999.99, aPad:false});
        $("input[name=vat]").autoNumeric('set',vat);

        var subtotal_B = getSubtotalB();

        var subtotal_C = getSubtotalC();

        var subtotalAll = subtotal_A + vat + subtotal_B + subtotal_C;

        $("input[name=subtotal]").autoNumeric('init', {vMax: 999999999999999.99, aPad:false});
        $("input[name=subtotal]").autoNumeric('set', subtotalAll);
        $("input[name=subtotal]").attr('value', subtotalAll);

        $("#developmentCostPlan .sub").show();
        $(".vat").show();
        $("input[name=subtotal]").parent('div').addClass('total');

        $("input[name=subtotal]").focus();
    });

    function getSubtotalA(){
        var subtotal_A = 0;
        $(".construction_cost_table input[name$=_amount]").each(function(){
            $(this).autoNumeric('init', {vMax: 999999999999999.99, aPad:false});
            var tempValue =  parseFloat($(this).autoNumeric('get'));
            if(tempValue > 0) {
                subtotal_A = subtotal_A + tempValue;
            }
        });
        return subtotal_A;
//        return 1710132600000;
    }

    function getSubtotalB(){
        var subtotal_B = 0;

        $(".design_consultant_table input[name$=_amount]").each(function(){
            $(this).autoNumeric('init', {vMax: 999999999999999.99, aPad:false});
            var tempValue =  parseFloat($(this).autoNumeric('get'));
            if(tempValue > 0) {
                subtotal_B = subtotal_B + tempValue;
            }
        });
        $("input[name=subtotal_B]").autoNumeric('init', {vMax: 999999999999999.99, aPad:false});
        $("input[name=subtotal_B]").autoNumeric('set', subtotal_B);
        return subtotal_B;
    }

    function getSubtotalC(){
        var subtotal_C = 0;
        $(".land_associated_table input[name$=_amount]").each(function(){
            $(this).autoNumeric('init', {vMax: 999999999999999.99, aPad:false});
            var tempValue =  parseFloat($(this).autoNumeric('get'));
            if(tempValue > 0) {
                subtotal_C = subtotal_C + tempValue;
            }
        });
        $("input[name=subtotal_C]").autoNumeric('init', {vMax: 999999999999999.99, aPad:false});
        $("input[name=subtotal_C]").autoNumeric('set', subtotal_C);
        return subtotal_C;
    }

    $(".land_associated_table input").change(function(){
        getSubtotalC();
    });

    $("input[name=exchange_rate]").change(function() {
        $(".construction_cost_table input").each(function(){
            $(this).val(0);
            $(this).attr('value');
        });
        $(".design_consultant_table input").each(function(){
            $(this).val(0);
            $(this).attr('value',0);
        });
        $(".land_associated_table input").each(function(){
            $(this).val(0);
            $(this).attr('value',0);
        });

        $("input[name^=subtotal]").val(0);
        $("input[name^=subtotal]").attr('value',0);
    });

    $(".project_info_table input").change(function(){
        $(".construction_cost_table input").each(function(){
            $(this).val(0);
            $(this).attr('value');
        });
        $(".design_consultant_table input").each(function(){
            $(this).val(0);
            $(this).attr('value',0);
        });
        $(".land_associated_table input").each(function(){
            $(this).val(0);
            $(this).attr('value',0);
        });

        $("input[name^=subtotal]").val(0);
        $("input[name^=subtotal]").attr('value',0);
    });

    $(".construction_cost_table input").change(function() {
        $(".design_consultant_table input[name$=amount]").each(function(){
            $(this).val(0);
            $(this).attr('value',0);
        });
    });

</script>