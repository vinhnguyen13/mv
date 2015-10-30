<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 10/29/2015
 * Time: 1:55 PM
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
?>
<h1>Profit Margin Calculation</h1>
<?php $form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data', 'id'=>'p_profitMarginCalculation'],
    'action' => \yii\helpers\Url::toRoute(['tool/save-step', 'step'=>'profitMarginCalculation'])
]); ?>
<div class="col-lg-12">
    <?=Html::label('Total Project Cost');?>
    <?=Html::input('text','total_project_cost', 2246792608858,['class'=>'form-control form-group number']);?>
</div>
<div class="form-group">
    <label class="col-lg-1 control-label"></label>
    <div class="col-lg-11">
        <?= Html::submitButton('Next', ['class' => 'btn btn-primary']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<script>
    $(document).on("click",'#profitMarginCalculation .btn-primary',function() {
        chart.next('/tool/save-step', 'p_profitMarginCalculation', 'profitMarginCalculation/afterNext');
        return false;
    });
    /**
     * trigger event after success ajax
     */
    $(document).bind( 'profitMarginCalculation/afterNext', function(event, json, string){
        $(".mainConfigSetParams").find(".nav-tabs a[href='#scenario_1']").trigger("click");
        if(json.data.total_project_cost){
            $(".mainConfigSetParams").find('#scenario_1 .total_project_cost').val(json.data.total_project_cost);
        }
    });
</script>