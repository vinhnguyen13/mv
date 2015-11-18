<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 10/29/2015
 * Time: 1:55 PM
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

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
        border: none;
        box-shadow: none;
        margin: 0 auto;
        display: block;
        width: auto;
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
        display: table;
    }

    .table-remove :hover {
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
    <div class="row">
        <div class="exchange_rate pull-right">
            <span class="col-md-11">Exchange rate:</span>
            <?php echo MaskedInput::widget([
                'name' => 'exchange_rate',
                'value' => 22350,
                'clientOptions' => [
                    'alias' => 'decimal',
                    'groupSeparator' => ',',
                    'autoGroup' => true
                ],
            ]); ?>
        </div>
    </div>
    <div id="table" class="table-editable">
        <div class="row">
            <label class="col-md-11">Project Information</label>
            <span class="project_info_add table-add glyphicon glyphicon-plus col-md-1 text-right pull-right"></span>
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
                <td><?php echo MaskedInput::widget([
                        'name' => 'total_cfa',
                        'value' => 150000,
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
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
                <td><?php echo MaskedInput::widget([
                        'name' => 'basement_cfa',
                        'value' => 26000,
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
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
                <td><?php echo MaskedInput::widget([
                        'name' => 'res_pod_cfa',
                        'value' => 124000,
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?></td>
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
                <td><?php echo MaskedInput::widget([
                        'name' => 'retail_podium',
                        'value' => 16500,
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?></td>
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
                <td><?php echo MaskedInput::widget([
                        'name' => 'residential_cfa',
                        'value' => 107500,
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?></td>
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
                <td><?php echo MaskedInput::widget([
                        'name' => 'net_sellable',
                        'value' => 78000,
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?></td>
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
                <td><?php echo MaskedInput::widget([
                        'name' => 'total_unit',
                        'value' => 150000,
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?></td>
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
                <td><?php echo MaskedInput::widget([
                        'name' => 'pi',
                        'value' => 0,
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?></td>
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
            <span
                class="construction_cost_add table-add glyphicon glyphicon-plus col-md-1 text-right pull-right"></span>
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
                        <?php echo MaskedInput::widget([
                            'name' => 'pw',
                            'value' => 40,
                            'class' => 'text-left',
                            'clientOptions' => [
                                'alias' => 'decimal',
                                'groupSeparator' => ',',
                                'autoGroup' => true
                            ],
                        ]); ?>
                </td>
                <td>
                        <?php echo MaskedInput::widget([
                            'name' => 'pw_vnd',
                            'value' => 40,
                            'class' => 'text-left',
                            'clientOptions' => [
                                'alias' => 'decimal',
                                'groupSeparator' => ',',
                                'autoGroup' => true
                            ],
                        ]); ?>
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
                <td>Basement + Substructure</td>
                <td>
                    <?php echo MaskedInput::widget([
                        'name' => 'bs',
                        'value' => 40,
                        'class' => 'text-left',
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
                </td>
                <td>
                    <?php echo MaskedInput::widget([
                        'name' => 'bs_vnd',
                        'value' => 40,
                        'class' => 'text-left',
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
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
                <td>
                    <?php echo MaskedInput::widget([
                        'name' => 'rp',
                        'class' => 'text-left',
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
                </td>
                <td>
                    <?php echo MaskedInput::widget([
                        'name' => 'rp_vnd',
                        'class' => 'text-left',
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
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
                <td>Residential Towers</td>
                <td>
                    <?php echo MaskedInput::widget([
                        'name' => 'rt',
                        'class' => 'text-left',
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
                </td>
                <td>
                    <?php echo MaskedInput::widget([
                        'name' => 'rt_vnd',
                        'class' => 'text-left',
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
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
                <td>External Works</td>
                <td>
                    <?php echo MaskedInput::widget([
                        'name' => 'ew',
                        'class' => 'text-left',
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
                </td>
                <td>
                    <?php echo MaskedInput::widget([
                        'name' => 'ew_vnd',
                        'class' => 'text-left',
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
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
                <td>MEP Services</td>
                <td>
                    <?php echo MaskedInput::widget([
                        'name' => 'mep',
                        'class' => 'text-left',
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
                </td>
                <td>
                    <?php echo MaskedInput::widget([
                        'name' => 'mep_vnd',
                        'class' => 'text-left',
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
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
                <td>
                    <?= Html::hiddenInput('mep_services', 0, ['class'=>'mep_services'])?>
                </td>
            </tr>

            <!-- This is our clonable table line -->
            <tr class="hide">
                <td contenteditable="true">Untitled</td>
                <td>
                    <?php echo MaskedInput::widget([
                        'name' => 'mep',
                        'class' => 'text-left',
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
                </td>
                <td>
                    <?php echo MaskedInput::widget([
                        'name' => 'mep_vnd',
                        'class' => 'text-left',
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
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
            <label class="col-md-11">B. Design & Consultants Cost</label>
            <span
                class="construction_cost_add table-add glyphicon glyphicon-plus col-md-1 text-right pull-right"></span>
        </div>
        <table class="table design_consultant_table">
            <tr>
                <th width="35%">Name</th>
                <th width="15%"></th>
                <th class="text-center">Percent/Amount</th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <td>Project Management Cost</td>
                <td></td>
                <td><?php echo MaskedInput::widget([
                        'name' => 'pro_mng_cost',
                        'value' => 0.5,
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
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
                <td></td>
                <td><?php echo MaskedInput::widget([
                        'name' => 'construction_mng',
                        'value' => 0.5,
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
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
                <td></td>
                <td><?php echo MaskedInput::widget([
                        'name' => 'construction_sup',
                        'value' => 0.5,
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
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
                <td></td>
                <td><?php echo MaskedInput::widget([
                        'name' => 'qty_sur',
                        'value' => 0.1,
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
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
                <td></td>
                <td><?php echo MaskedInput::widget([
                        'name' => 'in_house_pmo',
                        'value' => 0.4,
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
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
                <td></td>
                <td><?php echo MaskedInput::widget([
                        'name' => 'bim_mng',
                        'value' => 0.2,
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
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
                <td><?php echo MaskedInput::widget([
                        'name' => 'sunk_cost',
                        'value' => 18000000000,
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
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
                <td><?php echo MaskedInput::widget([
                        'name' => 'dc',
                        'value' => 0,
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
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
            <label class="col-md-11">C. Land & Associated Fees Cost</label>
            <span
                class="land_associated_add table-add glyphicon glyphicon-plus col-md-1 text-right pull-right"></span>
        </div>
        <table class="table land_associated_table">
            <tr>
                <th width="35%">Name</th>
                <th width="15%"class="text-center">Amount USD</th>
                <th class="text-center">Amount VND</th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <td>Land Cost</td>
                <td><?php echo MaskedInput::widget([
                        'name' => 'land_cost',
                        'value' => 11185682.33,
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],

                    ]); ?>
                </td>
                <td><?php echo MaskedInput::widget([
                        'name' => 'land_cost_vnd',
                        'value' => 250000000000,
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
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
                <td><?php echo MaskedInput::widget([
                        'name' => 'lc',
                        'value' => 11185682.33,
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
                </td>
                <td><?php echo MaskedInput::widget([
                        'name' => 'lc_vnd',
                        'value' => 250000000000,
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
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
        $clone.find('input[name=pi]').attr('name', 'pi' + pi_counter);
        $(".project_info_table").append($clone);
    });

    var cc_counter = 1;
    $('.construction_cost_add').click(function () {
        var $clone = $(".construction_cost_table").find('tr.hide').clone(true).removeClass('hide table-line');
        $clone.find('input[name=cc_vnd]').attr('name', 'cc' + cc_counter + '_vnd');
        $clone.find('input[name=cc]').attr('name', 'cc' + cc_counter);
        $(".construction_cost_table").append($clone);
        cc_counter += 1;
    });

    //    $( "input" ).change(function() {
    //        alert( $(this).val() );
    //    });

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

</script>