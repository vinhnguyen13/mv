<?php
use yii\web\View;
use yii\helpers\Url;
?>

<div class="statis">
	<div class="title-top">Statistics</div>
	<section>
		<div id="sandbox-container">
			<input type="text" class="form-control" placeholder="Ngày">
			<span class="icon arrowDown"></span>
		</div>
		<div class="summary clearfix">
			SUMMARY
			<span class="pull-right views-stats"><em class="fa fa-square-o"></em>VIEWS</span>
			<div class="wrap-chart">
				<div class="wrap-img"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/chart-demo.jpg' ?>" alt=""></div>
			</div>
		</div>
	</section>
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		<div class="panel panel-default">
			<div class="panel-heading title-sub" role="tab" id="headingOne">
				<h4 class="panel-title">
					<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
					<em class="icon-eye"></em> views
					</a>
				</h4>
			</div>
			<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
				<div class="panel-body">
					
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading title-sub" role="tab" id="headingTwo">
				<h4 class="panel-title">
					<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
					<em class="icon-user"></em> visitors
					</a>
				</h4>
			</div>
			<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
				<div class="panel-body">
					
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading title-sub" role="tab" id="headingThree">
				<h4 class="panel-title">
					<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
					<em class="icon-heart"></em> favourites
					</a>
				</h4>
			</div>
			<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
				<div class="panel-body">
					
				</div>
			</div>
		</div>
	</div>
</div>

<?php 
$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/bootstrap-datepicker.min.css", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'datepicker');
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/bootstrap-datepicker.min.js', ['position'=>View::POS_BEGIN]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/bootstrap-datepicker.vi.min.js', ['position'=>View::POS_BEGIN]);
?>

<script>
	$('#sandbox-container input').datepicker({
	    language: "vi",
	    autoclose: true
	});
</script>