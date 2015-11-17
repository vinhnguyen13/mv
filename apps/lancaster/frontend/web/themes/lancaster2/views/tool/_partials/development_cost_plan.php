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
<style>

    .table-editable {
        position: relative;

    .glyphicon {
        font-size: 20px;
    }

    }

    .table input {
        border: none;
        text-align: right;
        width: 80%;
        padding: 0 20px;
    }

    .table-editable a {
        text-decoration: none;
        color: #333;
    }

    .table-remove {
        color: #700;
        cursor: pointer;
    }

    .table-remove :hover {
        color: #f0f0f0;
    }


    .table-up, .table-down {
        color: #007;
        cursor: pointer;
    }

    .table-up:hover, .table-down:hover  {
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

</style>
<div class="container">
    <h1>Development Cost Plan</h1>

    <p></p>

    <ul>
        <li>An editable table that exports a hash array. Dynamically compiles rows from headers</li>
        <li>Simple / powerful features such as add row, remove row, move row up/down.</li>
    </ul>
    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data', 'id' => 'p_profitMarginCalculation'],
        'action' => \yii\helpers\Url::toRoute(['tool/save-step', 'step' => 'profitMarginCalculation'])
    ]); ?>
    <div id="table" class="table-editable">
        <label>Project Information</label>
        <span class="project_info_add table-add glyphicon glyphicon-plus pull-right"></span>
        <table class="table project_info_table">
            <tr>
                <th>Name</th>
                <th class="text-center">Value</th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <td>Total CFA (Total Construction Floor Area)</td>
                <td><input name="total_cfa" value="150000"></td>
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
                <td><input name="basement_cfa" value="26000"></td>
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
                <td><input name="residential_podium" value="124000"></td>
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
                <td><input name="retail_podium" value="16500"></td>
                <td>
                    <span class="table-remove glyphicon glyphicon-remove"></span>
                </td>
                <td>
                    <span class="table-up glyphicon glyphicon-arrow-up"></span>
                    <span class="table-down glyphicon glyphicon-arrow-down"></span>
                </td>
            </tr>
            <tr>
                <td>Residential_CFA</td>
                <td><input name="residential_cfa" value="107500"></td>
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
                <td><input name="net_sellable_area" value="78000"></td>
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
                <td><input name="total_unit" value="500"></td>
                <td>
                    <span class="table-remove glyphicon glyphicon-remove"></span>
                </td>
                <td>
                    <span class="table-up glyphicon glyphicon-arrow-up"></span>
                    <span class="table-down glyphicon glyphicon-arrow-down"></span>
                </td>
            </tr>
            <tr>
                <td>Exchange Rate</td>
                <td><input name="ex_rate" value="22350"></td>
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
                <td><input name="pi" value="undefined"></td>
                <td>
                    <span class="table-remove glyphicon glyphicon-remove"></span>
                </td>
                <td>
                    <span class="table-up glyphicon glyphicon-arrow-up"></span>
                    <span class="table-down glyphicon glyphicon-arrow-down"></span>
                </td>
            </tr>
        </table>


        <label>A. Construction Cost</label>
        <span class="construction_cost_add table-add glyphicon glyphicon-plus pull-right"></span>
        <table class="table construction_cost_table">
            <tr>
                <th>Name</th>
                <th class="text-center">Cost USD</th>
                <th class="text-center">Cost VND</th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <td>Piling Works</td>
                <td><input name="pw" value="40"></td>
                <td><input name="pw_vnd" value="894000"></td>
                <td>
                    <span class="table-remove glyphicon glyphicon-remove"></span>
                </td>
                <td>
                    <span class="table-up glyphicon glyphicon-arrow-up"></span>
                    <span class="table-down glyphicon glyphicon-arrow-down"></span>
                </td>
            </tr>
            <tr>
                <td>Basement + Substructure</td>
                <td><input name="bs"></td>
                <td><input name="bs_vnd"></td>
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
                <td><input name="rp"></td>
                <td><input name="rp_vnd"></td>
                <td>
                    <span class="table-remove glyphicon glyphicon-remove"></span>
                </td>
                <td>
                    <span class="table-up glyphicon glyphicon-arrow-up"></span>
                    <span class="table-down glyphicon glyphicon-arrow-down"></span>
                </td>
            </tr>
            <tr>
                <td>Residential Towers</td>
                <td><input name="rt"></td>
                <td><input name="rt_vnd"></td>
                <td>
                    <span class="table-remove glyphicon glyphicon-remove"></span>
                </td>
                <td>
                    <span class="table-up glyphicon glyphicon-arrow-up"></span>
                    <span class="table-down glyphicon glyphicon-arrow-down"></span>
                </td>
            </tr>
            <tr>
                <td>External Works</td>
                <td><input name="ew"></td>
                <td><input name="ew_vnd"></td>
                <td>
                    <span class="table-remove glyphicon glyphicon-remove"></span>
                </td>
                <td>
                    <span class="table-up glyphicon glyphicon-arrow-up"></span>
                    <span class="table-down glyphicon glyphicon-arrow-down"></span>
                </td>
            </tr>
            <tr>
                <td>MEP Services</td>
                <td><input name="mep"></td>
                <td><input name="mep_vnd"></td>
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
                <td><input name="cc"></td>
                <td><input name="cc_vnd"></td>
                <td>
                    <span class="table-remove glyphicon glyphicon-remove"></span>
                </td>
                <td>
                    <span class="table-up glyphicon glyphicon-arrow-up"></span>
                    <span class="table-down glyphicon glyphicon-arrow-down"></span>
                </td>
            </tr>
        </table>

        <label>B. Design & Consultants Cost</label>
        <span class="construction_cost_add table-add glyphicon glyphicon-plus pull-right"></span>
        <table class="table construction_cost_table">
            <tr>
                <th>Name</th>
                <th class="text-center">%</th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <td>Exchange Rate</td>
                <td><input name="ex_rate" value="22350"></td>
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
                <td><input name="pi" value="undefined"></td>
                <td>
                    <span class="table-remove glyphicon glyphicon-remove"></span>
                </td>
                <td>
                    <span class="table-up glyphicon glyphicon-arrow-up"></span>
                    <span class="table-down glyphicon glyphicon-arrow-down"></span>
                </td>
            </tr>
        </table>
        <label>D. Land & Associated Fees Cost</label>
        <span class="construction_cost_add table-add glyphicon glyphicon-plus pull-right"></span>
        <table class="table construction_cost_table">
            <tr>
                <th>Name</th>
                <th class="text-center">Amount</th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <td>Exchange Rate</td>
                <td><input name="ex_rate" value="22350"></td>
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
                <td><input name="pi" value="undefined"></td>
                <td>
                    <span class="table-remove glyphicon glyphicon-remove"></span>
                </td>
                <td>
                    <span class="table-up glyphicon glyphicon-arrow-up"></span>
                    <span class="table-down glyphicon glyphicon-arrow-down"></span>
                </td>
            </tr>

        </table>

    </div>

    <?= Html::submitButton('Next', ['class' => 'btn btn-primary center-block']) ?>
    <!--    <p id="export"></p>-->
    <?php ActiveForm::end(); ?>
</div>
<script>
    //    var $TABLE = $('#table');
    var $BTN = $('#export-btn');
    var $EXPORT = $('#export');

    var pi_counter = 1;
    $('.project_info_add').click(function () {
        var $clone = $(".project_info_table").find('tr.hide').clone(true).removeClass('hide table-line');
        $clone.find('input[name=pi]').attr('name', 'pi'+pi_counter);
        $(".project_info_table").append($clone);
    });

    var cc_counter = 1;
    $('.construction_cost_add').click(function () {
        var $clone = $(".construction_cost_table").find('tr.hide').clone(true).removeClass('hide table-line');
        $clone.find('input[name=cc_vnd]').attr('name', 'cc'+cc_counter+'_vnd');
        $clone.find('input[name=cc]').attr('name', 'cc'+cc_counter);
        $(".construction_cost_table").append($clone);
        cc_counter += 1;
    });

    $( "input" ).change(function() {
        alert( "Hello! " + $(this).attr('name') ); // jQuery 1.3+
    });

    $('.table-remove').click(function () {
        $(this).parents('tr').detach();
    });

    $('.table-up').click(function () {
        var $row = $(this).parents('tr');
//        if ($row.index() === 1) return; // Don't go above the header
        $row.prev().before($row.get(0));
    });

    $('.table-down').click(function () {
        var $row = $(this).parents('tr');
        $row.next().after($row.get(0));
    });

    // A few jQuery helpers for exporting only
    //    jQuery.fn.pop = [].pop;
    //    jQuery.fn.shift = [].shift;
    //
    //    $BTN.click(function () {
    //        var $rows = $TABLE.find('tr:not(:hidden)');
    //        var headers = [];
    //        var data = [];
    //
    //        // Get the headers (add special header logic here)
    //        $($rows.shift()).find('th:not(:empty)').each(function () {
    //            headers.push($(this).text().toLowerCase());
    //        });
    //
    //        // Turn all existing rows into a loopable array
    //        $rows.each(function () {
    //            var $td = $(this).find('td');
    //            var h = {};
    //
    //            // Use the headers from earlier to name our hash keys
    //            headers.forEach(function (header, i) {
    //                h[header] = $td.eq(i).text();
    //            });
    //            data.push(h);
    //        });
    //
    //        // Output the result
    ////        $EXPORT.text(JSON.stringify(data));
    //    });
    //

</script>