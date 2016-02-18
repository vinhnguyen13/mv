<?php
use yii\web\View;
use yii\helpers\Url;

$id = 1;
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
					<span class="pull-right icon"></span>
					</a>
				</h4>
			</div>
			<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
				<div class="panel-body">
					<ul class="clearfix list-item">
                        <?php if(!empty($finders) && count($finders)){
                        foreach($finders as $key => $finder){?>
						<li>
							<em class="fa fa-circle"></em><a href="#"><?=$key?></a>
							<span class="pull-right"><?=$finder?></span>
						</li>
                        <?php }
                        } else { ?>
                        <li>Không có người tìm kiếm</li>
                        <?php }?>
					</ul>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading title-sub" role="tab" id="headingTwo">
				<h4 class="panel-title">
					<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
					<em class="icon-user"></em> visitors
					<span class="pull-right icon"></span>
					</a>
				</h4>
			</div>
			<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
				<div class="panel-body">
					<ul class="clearfix list-item">
                        <?php if(!empty($visitors) && count($visitors)){
                            foreach($visitors as $key => $visitor){?>
                                <li>
                                    <em class="fa fa-circle"></em><a href="#"><?=$key?></a>
                                    <span class="pull-right"><?=$visitor?></span>
                                </li>
                            <?php }
                        }  else { ?>
                            <li>Không có ghé thăm listing</li>
                        <?php }?>
					</ul>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading title-sub" role="tab" id="headingThree">
				<h4 class="panel-title">
					<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
					<em class="icon-heart"></em> favourites
					<span class="pull-right icon"></span>
					</a>
				</h4>
			</div>
			<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
				<div class="panel-body">
					<ul class="clearfix list-item">
						<li>
							<em class="fa fa-circle"></em><a href="#">James Bond</a>
							<span class="pull-right">7</span>
						</li>
						<li>
							<em class="fa fa-circle"></em><a href="#">James Bond</a>
							<span class="pull-right">7</span>
						</li>
						<li>
							<em class="fa fa-circle"></em><a href="#">James Bond</a>
							<span class="pull-right">7</span>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="title-sub">Property's info</div>
	<div class="share-social">
		<ul class="clearfix">
			<li>
				<a href="#">
					<span class="wrap-around"><em class="icon-envelope"></em></span>
					Share With Email
					<span class="pull-right icon arrow-left"></span>
				</a>
			</li>
			<li>
				<a href="#">
					<span class="wrap-around"><em class="fa fa-facebook"></em></span>
					Share With Facebook
					<span class="pull-right icon arrow-left"></span>
				</a>
			</li>
			<li>
				<a href="#">
					<span class="wrap-around"><em class="fa fa-twitter"></em></span>
					Share With Twitter
					<span class="pull-right icon arrow-left"></span>
				</a>
			</li>
			<li>
				<a href="#">
					<span class="wrap-around"><em class="fa fa-instagram"></em></span>
					Share With Instagram
					<span class="pull-right icon arrow-left"></span>
				</a>
			</li>
		</ul>
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