<?php
use yii\web\View;
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'My Yii Application';

$this->registerCssFile(Yii::$app->view->theme->baseUrl . '/resources/chart/chart.css', ['depends' => ''], 'css-chart');
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/chart/autoNumeric-min.js', ['position' => View::POS_END]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/chart/chart.js', ['position' => View::POS_END]);
$items[] = [
	'label'=>'Development Cost Plan',
	'options' => ['id' => 'developmentCostPlan'],
	'content'=>$this->render('_partials/development_cost_plan')
];
//$items = null;
$items[] = [
	'label'=>'Profit Margin Calculation',
	'options' => ['id' => 'profitMarginCalculation'],
	'content'=>$this->render('_partials/profit_margin_calculation')
];
//$items = null;
$items[] = [
	'label'=>'Cashflow (Scenario 1)',
	'options' => ['id' => 'scenario_1'],
	'content'=>$this->render('_partials/cashflow_1', ['total_project_cost'=>!empty($total_project_cost) ? $total_project_cost : 0])
];
$items[] = [
	'label'=>'Cashflow (Scenario 2)',
	'options' => ['id' => 'scenario_2'],
	'content'=>$this->render('_partials/cashflow_2')
];

?>
<div class="mainConfigSetParams">
	<a href="<?= \yii\helpers\Url::to(['tool/chart'])?>" class="btn btn-primary pull-right">View Chart</a>
	<?php
	echo \yii\bootstrap\Tabs::widget([
		'items' => $items,
		'options' => ['tag' => 'div'],
		'itemOptions' => ['tag' => 'div'],
		'headerOptions' => ['class' => 'my-class'],
		'clientOptions' => ['collapsible' => false],
	]);
	?>
</div>

<script>
	$(function () {
		$('.mainConfigSetParams').find('input.number').autoNumeric('init', {aPad: false});
	})
</script>

