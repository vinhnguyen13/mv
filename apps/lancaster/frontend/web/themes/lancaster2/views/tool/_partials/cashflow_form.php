<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 10/29/2015
 * Time: 3:29 PM
 */
use yii\helpers\Html;
?>
<div class="col-lg-3">
    <div class="form-group field-cmscatalog-parent_id">
        <?=Html::label('Outgoing Cashflow (%)');?>
        <?=Html::input('text','t1',null,['class'=>'form-control form-group cashflow']);?>
        <div class="hint-block"></div>
    </div>
</div>
<div class="col-lg-3">
    <div class="form-group field-cmscatalog-parent_id">
        <?=Html::label('Sales (%)');?>
        <?=Html::input('text','t1',null,['class'=>'form-control form-group sales']);?>
        <div class="hint-block"></div>
    </div>
</div>
<div class="col-lg-3">
    <div class="form-group field-cmscatalog-parent_id">
        <?=Html::label('Net Accumulative Cashflow');?>
        <?=Html::label('', null, ['class'=>'form-control form-group net_cashflow']);?>
<!--        --><?//=Html::input('text','t1',null,['class'=>'form-control form-group net_cashflow']);?>
        <div class="hint-block"></div>
    </div>
</div>