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
    <?php $form = ActiveForm::begin();?>
    <div class="col-lg-12">
        <?=Html::label('Total Project Cost');?>
        <?=Html::input('text','keywords', 2246792608858,['class'=>'form-control form-group number']);?>
    </div>
    <div class="col-lg-3">
        <?=Html::label('Keywords');?>
        <?=Html::input('text','keywords',null,['class'=>'form-control form-group']);?>
    </div>
    <div class="col-lg-3">
        <?=Html::label('Keywords');?>
        <?=Html::input('text','keywords',null,['class'=>'form-control form-group']);?>
    </div>
    <div class="col-lg-3">
        <?=Html::label('Keywords');?>
        <?=Html::input('text','keywords',null,['class'=>'form-control form-group']);?>
    </div>
    <div class="col-lg-3">
        <?=Html::label('Keywords');?>
        <?=Html::input('text','keywords',null,['class'=>'form-control form-group']);?>
    </div>
    <?php ActiveForm::end(); ?>
