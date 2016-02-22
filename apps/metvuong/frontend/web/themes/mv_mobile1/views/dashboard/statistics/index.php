<?php
use yii\web\View;
use yii\helpers\Url;

Yii::$app->getView()->registerJsFile('http://code.highcharts.com/highcharts.js', ['position' => View::POS_BEGIN]);
Yii::$app->getView()->registerJsFile('http://code.highcharts.com/modules/exporting.js', ['position' => View::POS_BEGIN]);
?>

<div class="statis">
	<div class="title-top">Statistics</div>
	<section>
		<div id="sandbox-container">
			<input type="text" class="form-control picker" placeholder="Ngày">
			<span class="icon arrowDown"></span>
		</div>
		<div class="summary clearfix">
			SUMMARY
			<span class="pull-right views-stats"><em class="fa fa-square-o"></em>
                <select class="chart_stats">
                    <option class="tab" value="visitor" data-url="<?=\yii\helpers\Url::to(['/dashboard/chart', 'view'=>'_partials/visitor', 'id' => $id, 'from' => $from, 'to' => $to])?>">Visitor</option>
                    <option class="tab" value="finder" data-url="<?=\yii\helpers\Url::to(['/dashboard/chart', 'view'=>'_partials/finder', 'id' => $id, 'from' => $from, 'to' => $to])?>">Finder</option>
                    <option class="tab" value="favourite" data-url="<?=\yii\helpers\Url::to(['/dashboard/chart', 'view'=>'_partials/saved', 'id' => $id, 'from' => $from, 'to' => $to])?>">Favourite</option>
                </select>
            </span>
			<div class="wrap-chart">
				<div class="wrap-img">
                    <div class="wrapChart">
                        <?=$this->render('/dashboard/chart/'.$view, ['id' => $id, 'from' => $from, 'to' => $to]);?>
                    </div>
                    <div class="loading text-center" style="display: none;" >
                        <img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/loading-listing.gif" alt="Loading..." />
                    </div>
                </div>
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
                            <li>Không có người ghé thăm</li>
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
                        <?php if(!empty($favourites) && count($favourites)){
                            foreach($favourites as $key => $favourite){?>
                                <li>
                                    <em class="fa fa-circle"></em><a href="#"><?=$key?></a>
                                </li>
                            <?php }
                        }  else { ?>
                            <li>Không có tin đăng được thích </li>
                        <?php } ?>

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

    $(document).ready(function () {
        var params = getUrlVars();
        if(params["date"] !== undefined){
            var arrDate = params["date"].split("-");
            var useDate = arrDate[2]+"/"+arrDate[1]+"/"+arrDate[0];
            $('.picker').attr('placeholder', ""+useDate);
        }
    });

	$('#sandbox-container input').datepicker({
	    language: "vi",
        autoclose: true,
        onSelect: function() {
            return $(this).trigger('change');
        }
	});

    $('#sandbox-container input').change(function(){
        var theDate = $(this).datepicker().val();
        var arrDate = theDate.split("/");
        var useDate = arrDate[2]+"-"+arrDate[1]+"-"+arrDate[0];
        if(useDate) {
            window.location = '<?=Url::to(['/dashboard/statistics','id' => $id])?>' + '&date=' + useDate;
        }
    });

    function getUrlVars()
    {
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++)
        {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    }

    $('.chart_stats').change(function () {
        var timer = 0;
        clearTimeout(timer);
        var url = '';
        $( "select option:selected" ).each(function() {
            url = $(this).attr('data-url');
            $('.wrapChart').html('');
            $('.loading').show();
        });
        if(url != '') {
            timer = setTimeout(function () {
                $.ajax({
                    type: "get",
                    dataType: 'html',
                    url: url,
                    success: function (data) {
                        $('.loading').hide();
                        $('.wrapChart').html(data);
                    }
                });
            }, 500);
        }
        return false;
    });
</script>