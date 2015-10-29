<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 10/29/2015
 * Time: 1:48 PM
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;
$this->registerJs(
    '$("document").ready(function(){
        $("#scenario_2").on("pjax:end", function() {
            $(".mainConfigSetParams").find(".nav-tabs a[href=\"#scenario_2\"]").trigger("click");
        });
    });'
);

?>
<?php Pjax::begin([
    'enableReplaceState'=>false,
    'enablePushState'=>false,
    'id' => 'scenario_2',
    'clientOptions'=>[
        'container'=>'p_scenario_2',
    ]
]); ?>
<?php $form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data', 'data-pjax'=>'#p_scenario_2'],
    'action' => \yii\helpers\Url::toRoute(['tool/save-step', 'step'=>'scenario_2'])
]); ?>
<div class="col-lg-12">
    <?=Html::label('Total Project Cost');?>
    <?=Html::input('text','keywords', 2246792608858,['class'=>'form-control form-group number']);?>
</div>

<?php
$months = 32;
for($i=1;$i<=$months;$i++){
    $items[] = [
        'label'=>'T'.$i,
        'options' => ['id' => 'T'.$i],
        'content'=>$this->render('cashflow_form', [
            ''
        ])
    ];
}
echo \yii\bootstrap\Tabs::widget([
    'items' => $items,
    'options' => ['tag' => 'div'],
    'itemOptions' => ['tag' => 'div'],
    'headerOptions' => ['class' => 'my-class'],
    'clientOptions' => ['collapsible' => false],
]);
?>

<div class="form-group">
    <label class="col-lg-1 control-label"></label>
    <div class="col-lg-11">
        <?= Html::submitButton('View Chart', ['class' => 'btn btn-primary']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
<?php Pjax::end(); ?>


