<?php
use yii\web\View;
use yii\helpers\Url;
?>
<div class="title-fixed-wrap">
    <div class="container">
        <div class="statis">
            <div class="title-top">
                <?=Yii::t('report','Report')?>
            </div>
        	<section class="clearfix mgB-40">
                <div class="pull-right fs-13 mgB-15">
                    <div class="clearfix d-ib ver-c">
                        <a href="<?= Url::to(['report/index', 'filter'=>'week'], true) ?>" class="show-view-chart<?=($filter=='week' ? ' active' : '')?>"><?=Yii::t('statistic','Week')?></a>
                        <a href="<?= Url::to(['report/index', 'filter'=>'2week'], true) ?>" class="show-view-chart<?=($filter=='2week' ? ' active' : '')?>"><?=Yii::t('statistic','Two weeks')?></a>
                        <a href="<?= Url::to(['report/index', 'filter'=>'month'], true) ?>" class="show-view-chart<?=($filter=='month' ? ' active' : '')?>"><?=Yii::t('statistic','Month')?></a>
                    </div>
                </div>
                <div class="clearfix"></div>
        		<div class="summary clearfix report-boss">
                    <div class="wrap-chart clearfix">
        				<div class="wrap-img">
                            <div class="wrapChart">
                                <?=$this->render('/report/default/_partials/chart', ['categories'=>$categories, 'dataChart'=>$dataChart]);?>
                            </div>
                        </div>
        			</div>
                    <ul class="option-view-stats clearfix">
                        <li class="chk_register">
                            <label for="register"><input type="checkbox" name="toggle-chart" value="" id="register" checked><?=Yii::t('report','Register')?></label>
                        </li>
                        <li class="chk_login">
                            <label for="login"><input type="checkbox" name="toggle-chart" value="" id="login" checked><?=Yii::t('report','Login')?></label>
                        </li>
                        <li class="chk_listing">
                            <label for="listing"><input type="checkbox" name="toggle-chart" value="" id="listing" checked><?=Yii::t('report','Listing')?></label>
                        </li>
                        <li class="chk_transaction">
                            <label for="transaction"><input type="checkbox" name="toggle-chart" value="" id="transaction" checked><?=Yii::t('report','Transaction')?></label>
                        </li>
                        <li class="chk_favorite">
                            <label for="favorite"><input type="checkbox" name="toggle-chart" value="" id="favorite" checked><?=Yii::t('report','Favorite')?></label>
                        </li>
                    </ul>
        		</div>
        	</section>
        </div>
    </div>
</div>

<div class="modal fade popup-common tkReport" id="frmListVisit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header title-popup clearfix">
                <?=Yii::t('statistic','Statistic')?>
                <a href="#" class="btn-close close" data-dismiss="modal" aria-label="Close"><span class="icon icon-close"></span></a>
            </div>
            <div class="modal-body">
                <div class="wrap-modal clearfix">
                </div>
            </div>
        </div>
    </div>
</div>

<!--<script src="//code.highcharts.com/highcharts.js"></script>-->
<script>
    $(document).ready(function () {
        $(document).bind('highcharts/afterLoad', function (event, data) {
            doSomethingAfterLoadFile();
        });
    });

    function doSomethingAfterLoadFile(){
        var chart = $('#chartAds').highcharts();
        $(document).on('click', '.option-view-stats input', function (e) {
            for ( var i = 0; i < chart.series.length; i++ ) {
                chart.series[i].hide();
            }
            $('.option-view-stats input[type=checkbox]').each(function () {
                if (this.checked) {
                    var index = $(this).parent().parent().index();
                    chart.series[index].show();
                }
            });

        });
    }
</script>