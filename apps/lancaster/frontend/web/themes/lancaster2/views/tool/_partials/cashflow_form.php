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
        <?=Html::input('text','t1',null,['class'=>'form-control form-group']);?>
        <div class="hint-block"></div>
    </div>
</div>
<div class="col-lg-3">
    <div class="form-group field-cmscatalog-parent_id">
        <?=Html::label('Sales (%)');?>
        <?=Html::input('text','t1',null,['class'=>'form-control form-group']);?>
        <div class="hint-block"></div>
    </div>
</div>
<div class="col-lg-12">
    <div class="form-group field-cmscatalog-parent_id">
        <?=Html::label('Net Accumulative Cashflow');?>
        <?=Html::input('text','t1',null,['class'=>'form-control form-group']);?>
        <div class="hint-block"></div>
    </div>
</div>