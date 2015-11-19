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

    .sub{display: none; margin: -50px 0 0 0;}


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
            <span class="construction_cost_add table-add glyphicon glyphicon-plus col-md-1 text-right pull-right"></span>
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
                            'name' => 'pw_usd',
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
                            'class' => 'text-left',
                            'clientOptions' => [
                                'alias' => 'decimal',
                                'groupSeparator' => ',',
                                'autoGroup' => true
                            ],
                        ]); ?>
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
                    <?php echo MaskedInput::widget([
                        'name' => 'bs_usd',
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
                        'class' => 'text-left',
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
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
                    <?php echo MaskedInput::widget([
                        'name' => 'rp_usd',
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
                    <?php echo MaskedInput::widget([
                        'name' => 'rt_usd',
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
                    <?php echo MaskedInput::widget([
                        'name' => 'ew_usd',
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
                    <?php echo MaskedInput::widget([
                        'name' => 'mep_usd',
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
                    <?php echo MaskedInput::widget([
                        'name' => 'cc_usd',
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
                        'name' => 'cc_vnd',
                        'class' => 'text-left',
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
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
        <div class="sub">
            <div class="col-lg-6 col-center">
                <b>Subtotal Construction Cost</b>
            </div>
            <div class="col-lg-3">
                <?php echo MaskedInput::widget([
                    'name' => 'subtotal_A',
                    'value' => 0,
                    'clientOptions' => [
                        'alias' => 'decimal',
                        'groupSeparator' => ',',
                        'autoGroup' => true
                    ],
                ]); ?>
            </div>
        </div>
        <div class="vat" style="display: none; margin: 0 0 50px 0;">
            <div class="col-lg-6 col-center">
                <b>VAT</b>
            </div>
            <div class="col-lg-3">
                <?php echo MaskedInput::widget([
                    'name' => 'vat',
                    'value' => 0,
                    'clientOptions' => [
                        'alias' => 'decimal',
                        'groupSeparator' => ',',
                        'autoGroup' => true
                    ],
                ]); ?>
            </div>
            <div class="col-lg-3"></div>
        </div>

        <br>

        <div class="row">
            <label class="col-md-11">B. Design & Consultants Cost</label>
            <span class="construction_cost_add table-add glyphicon glyphicon-plus col-md-1 text-right pull-right"></span>

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
                <td><?php echo MaskedInput::widget([
                        'name' => 'design_dev_percent',
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
                </td>
                <td><?php echo MaskedInput::widget([
                        'name' => 'design_dev_amount',
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
                <td>Project Management Cost</td>
                <td><?php echo MaskedInput::widget([
                        'name' => 'pro_mng_cost_percent',
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
                </td>
                <td><?php echo MaskedInput::widget([
                        'name' => 'pro_mng_cost_amount',
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
                <td><?php echo MaskedInput::widget([
                        'name' => 'construction_mng_percent',
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
                </td>
                <td><?php echo MaskedInput::widget([
                        'name' => 'construction_mng_amount',
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
                <td><?php echo MaskedInput::widget([
                        'name' => 'construction_sup_percent',
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
                </td>
                <td><?php echo MaskedInput::widget([
                        'name' => 'construction_sup_amount',
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

                <td><?php echo MaskedInput::widget([
                        'name' => 'qty_sur_percent',
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
                </td>
                <td><?php echo MaskedInput::widget([
                        'name' => 'qty_sur_amount',
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
                <td><?php echo MaskedInput::widget([
                        'name' => 'in_house_pmo_percent',
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
                </td>
                <td><?php echo MaskedInput::widget([
                        'name' => 'in_house_pmo_amount',
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
                <td><?php echo MaskedInput::widget([
                        'name' => 'bim_mng_percent',
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
                </td>
                <td><?php echo MaskedInput::widget([
                        'name' => 'bim_mng_amount',
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
                        'name' => 'sunk_cost_amount',
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
                        'name' => 'dc_percent',
                        'value' => 0,
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                    ]); ?>
                </td>
                <td>
                <?php echo MaskedInput::widget([
                    'name' => 'dc_amount',
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
        <div class="sub" style="margin-bottom: 50px;">
            <div class="col-lg-6 col-center">
                <b>Subtotal Design & Consultants Cost</b>
            </div>
            <div class="col-lg-3">
                <?php echo MaskedInput::widget([
                    'name' => 'subtotal_B',
                    'value' => 0,
                    'clientOptions' => [
                        'alias' => 'decimal',
                        'groupSeparator' => ',',
                        'autoGroup' => true
                    ],
                ]); ?>
            </div>
        </div>

        <br>

        <div class="row">
            <label class="col-md-11">C. Land & Associated Fees Cost</label>
            <span
                class="land_associated_add table-add glyphicon glyphicon-plus col-md-1 text-right pull-right"></span>
        </div>
        <table class="table land_associated_table">
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
                <td><?php echo MaskedInput::widget([
                        'name' => 'land_cost_amount',
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
                        'name' => 'lc_amount',
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
        <div class="sub">
            <div class="col-lg-6 col-center">
                <b>Subtotal Land & Associated Fees Cost</b>
            </div>
            <div class="col-lg-3">
                <?php echo MaskedInput::widget([
                    'name' => 'subtotal_C',
                    'value' => 0,
                    'clientOptions' => [
                        'alias' => 'decimal',
                        'groupSeparator' => ',',
                        'autoGroup' => true
                    ],
                ]); ?>
            </div>
        </div>
        <br>
        <div style="margin-top: 20px;">
            <div class="col-lg-3">
                <?= Html::button('Subtotal', ['class' => 'btn btn-primary center-block subtotal ']) ?>
            </div><div class="col-lg-6">
                <?php echo MaskedInput::widget([
                    'name' => 'subtotal',
                    'value' => 0,
                    'clientOptions' => [
                        'alias' => 'decimal',
                        'groupSeparator' => ',',
                        'autoGroup' => true
                    ],
                ]); ?>
            </div>
            <div class="col-lg-3">
                <?= Html::submitButton('Next', ['class' => 'btn btn-primary pull-right']) ?>
            </div>
        </div>

    </div>


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

    $(".construction_cost_table input[name$=_usd]").change(function() {
        var total_cfa = parseFloat($("input[name=total_cfa]").attr('value'));
        var basement_cfa = parseFloat($("input[name=basement_cfa]").attr('value'));
        var exchange_rate = parseFloat($("input[name=exchange_rate]").attr('value'));

        if($(this).attr('name') === 'pw_usd'){
            var vnd = exchange_rate * parseFloat($(this).val());
            var pw = total_cfa * vnd;
            $("input[name=pw_vnd").val(vnd);
            $(".pw_amount").val(pw);
        }
        else if($(this).attr('name') === 'bs_usd'){
            var vnd = exchange_rate * parseFloat($(this).val());
            var bs = basement_cfa * vnd;
            $("input[name=bs_vnd").val(vnd);
            $(".bs_amount").val(bs);
        }
        else if($(this).attr('name') === 'rp_usd'){
            var retail_podium = parseFloat($("input[name=retail_podium]").attr('value'));
            var vnd = exchange_rate * parseFloat($(this).val());
            var rp = retail_podium * vnd;
            $("input[name=rp_vnd").val(vnd);
            $(".rp_amount").val(rp);
        }
        else if($(this).attr('name') === 'rt_usd'){
            var residential_cfa = parseFloat($("input[name=residential_cfa]").attr('value'));
            var vnd = exchange_rate * parseFloat($(this).val());
            var rt = residential_cfa * vnd;
            $("input[name=rt_vnd").val(vnd);
            $(".rt_amount").val(rt);
        }
        else if($(this).attr('name') === 'ew_usd'){
            var res_pod_cfa = parseFloat($("input[name=res_pod_cfa]").attr('value'));
            var vnd = exchange_rate * parseFloat($(this).val());
            var ew = res_pod_cfa * vnd;
            $("input[name=ew_vnd").val(vnd);
            $(".ew_amount").val(ew);
        }
        else if($(this).attr('name') === 'mep_usd'){
            var mep = total_cfa - (basement_cfa/2);
            var vnd = exchange_rate * parseFloat($(this).val());
            var mep_value = mep * vnd;
            $("input[name=mep_vnd").val(vnd);
            $(".mep_amount").val(mep_value);
        }

        var subtotal_A = 0;
        $(".construction_cost_table input[name$=_amount]").each(function(){
            subtotal_A = subtotal_A + parseFloat($(this).val());
        });

        $("input[name=subtotal_A]").attr('value',subtotal_A);
        var vat = subtotal_A * 0.1;
        $("input[name=vat]").attr('value',vat);

    });

    $(".design_consultant_table input[name$=_percent]").change(function() {
        var total_cfa = parseFloat($("input[name=total_cfa]").attr('value'));
        var basement_cfa = parseFloat($("input[name=basement_cfa]").attr('value'));
        var exchange_rate = parseFloat($("input[name=exchange_rate]").attr('value'));
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

    $(".subtotal").click(function(){
        // + amount cua cac sub
        $(".sub").show();
        // tinh vat cua sub A
        $(".vat").show();
    });

</script>