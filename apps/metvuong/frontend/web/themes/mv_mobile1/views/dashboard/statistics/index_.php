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
			<div class="box-statis-num">
				<div class="num-statis">
					<em class="icon-eye"></em> views
					<span class="pull-right">18</span>
				</div>
				<div class="num-statis">
					<em class="icon-user"></em> visitors
					<span class="pull-right">7</span>
				</div>
				<div class="num-statis">
					<em class="icon-heart"></em> favourites
					<span class="pull-right">0</span>
				</div>
			</div>
		</div>
	</section>
	<div class="title-sub">Project's stats</div>
	<section>
		<ul class="clearfix list-duan-stats">
			<li>
				<div class="bgcover" style="background-image:url(<?= Yii::$app->view->theme->baseUrl . '/resources/images/team2.jpg' ?>)"><a href="#"></a></div>
				<div class="stat-item-duan">
					<a href="#" class="name-duan">Lancaster x</a>
					<div class="box-statis-num">
						<div class="num-statis">
							<em class="icon-eye"></em> views
							<span class="pull-right">18</span>
						</div>
						<div class="num-statis">
							<em class="icon-user"></em> visitors
							<span class="pull-right">7</span>
						</div>
						<div class="num-statis">
							<em class="icon-heart"></em> favourites
							<span class="pull-right">0</span>
						</div>
					</div>
				</div>
			</li>
			<li>
				<div class="bgcover" style="background-image:url(<?= Yii::$app->view->theme->baseUrl . '/resources/images/nd_1450536426_k4uZ.jpg' ?>)"><a href="#"></a></div>
				<div class="stat-item-duan">
					<a href="#" class="name-duan">Lancaster x</a>
					<div class="box-statis-num">
						<div class="num-statis">
							<em class="icon-eye"></em> views
							<span class="pull-right">18</span>
						</div>
						<div class="num-statis">
							<em class="icon-user"></em> visitors
							<span class="pull-right">7</span>
						</div>
						<div class="num-statis">
							<em class="icon-heart"></em> favourites
							<span class="pull-right">0</span>
						</div>
					</div>
				</div>
			</li>
			<li>
				<div class="bgcover" style="background-image:url(<?= Yii::$app->view->theme->baseUrl . '/resources/images/team2.jpg' ?>)"><a href="#"></a></div>
				<div class="stat-item-duan">
					<a href="#" class="name-duan">Lancaster x</a>
					<div class="box-statis-num">
						<div class="num-statis">
							<em class="icon-eye"></em> views
							<span class="pull-right">18</span>
						</div>
						<div class="num-statis">
							<em class="icon-user"></em> visitors
							<span class="pull-right">7</span>
						</div>
						<div class="num-statis">
							<em class="icon-heart"></em> favourites
							<span class="pull-right">0</span>
						</div>
					</div>
				</div>
			</li>
			<li>
				<div class="bgcover" style="background-image:url(<?= Yii::$app->view->theme->baseUrl . '/resources/images/nd_1450536426_k4uZ.jpg' ?>)"><a href="#"></a></div>
				<div class="stat-item-duan">
					<a href="#" class="name-duan">Lancaster x</a>
					<div class="box-statis-num">
						<div class="num-statis">
							<em class="icon-eye"></em> views
							<span class="pull-right">18</span>
						</div>
						<div class="num-statis">
							<em class="icon-user"></em> visitors
							<span class="pull-right">7</span>
						</div>
						<div class="num-statis">
							<em class="icon-heart"></em> favourites
							<span class="pull-right">0</span>
						</div>
					</div>
				</div>
			</li>
		</ul>
	</section>
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