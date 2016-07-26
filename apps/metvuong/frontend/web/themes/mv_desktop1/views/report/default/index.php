<?php
use yii\web\View;
use yii\helpers\Url;
use yii\db\Query;
use common\components\Util;

$filter = Yii::$app->request->get("filter", 'week');

$arrFilter = [
    'week' => -6,
    '2week' => -13,
    'month' => -30,
    'quarter' => -90,
];
$from = strtotime($arrFilter[$filter].' days');
$to = time();

$dateRange = Util::me()->dateRange( $from, $to,'+1 day', 'd/m/Y');
if(!empty($dateRange)){
    $default = array_map(function($v){
        return ['y'=>0, 'date'=>'#'];
    },$dateRange);
    $dataRegister = $dataLogin = $dataListing = $default;
    /**
     * user register
     */
    $month_year = new \yii\db\Expression("DATE_FORMAT(FROM_UNIXTIME(`created_at`), '%d/%m/%Y')");
    $query = new Query();
    $query->select(['count(*) total', $month_year." today"])->from('user')
        ->where(['>', 'created_at', $from])
        ->andWhere(['<', 'created_at', $to])
        ->andWhere('updated_at > created_at')
        ->groupBy('today')->orderBy('created_at DESC');
    $stats_register = $query->all();
    $totalRegister = 0;
    foreach($stats_register as $item){
        $totalRegister += $item['total'];
        $kDate = array_search($item['today'], $dateRange);
        $dataRegister[$kDate] = ['y'=>intval($item['total']), 'date' => $item['today']];
    }
    /**
     * user login
     */
    $month_year = new \yii\db\Expression("DATE_FORMAT(FROM_UNIXTIME(`updated_at`), '%d/%m/%Y')");
    $query = new Query();
    $query->select(['count(*) total', $month_year." today"])->from('user')
        ->where(['>', 'updated_at', $from])
        ->andWhere(['<', 'updated_at', $to])
        ->andWhere('updated_at > created_at')
        ->groupBy('today')->orderBy('updated_at DESC');
    $stats_login = $query->all();
    $totalLogin = 0;
    foreach($stats_login as $item){
        $totalLogin += $item['total'];
        $kDate = array_search($item['today'], $dateRange);
        $dataLogin[$kDate] = ['y'=>intval($item['total']), 'date' => $item['today']];
    }
    /**
     * listing
     */
    $month_year = new \yii\db\Expression("DATE_FORMAT(FROM_UNIXTIME(`created_at`), '%d/%m/%Y')");
    $query = new Query();
    $query->select(['count(*) total', $month_year." today"])->from('ad_product')
        ->where(['>', 'created_at', $from])
        ->andWhere(['<', 'created_at', $to])
        ->groupBy('today')->orderBy('created_at DESC');
    $stats_listing = $query->all();
    $totalListing = 0;
    foreach($stats_listing as $item){
        $totalListing += $item['total'];
        $kDate = array_search($item['today'], $dateRange);
        $dataListing[$kDate] = ['y'=>intval($item['total']), 'date' => $item['today']];
    }

    /**
     * load data to chart
     */
    $categories = $dateRange;
    $dataChart[0] = [
        'name' => 'Register',
        'data' => $dataRegister
    ];
    $dataChart[1] = [
        'name' => 'Login',
        'data' => $dataLogin
    ];
    $dataChart[2] = [
        'name' => 'Listing',
        'data' => $dataListing
    ];
}
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
        		<div class="summary clearfix">
                    <div class="wrap-chart clearfix">
        				<div class="wrap-img">
                            <div class="wrapChart">
                                <?=$this->render('/report/default/_partials/chart', ['categories'=>$categories, 'dataChart'=>$dataChart]);?>
                            </div>
                        </div>
        			</div>
                    <ul class="option-view-stats clearfix">
                        <li>
                            <a href="#" class="btn-visitor radio-ui active">
                                <span class="icon-mv"><span class="icon-eye-copy"></span></span>
                                <?=Yii::t('statistic','Register')?>
                                <span><?=$totalRegister?></span>
                                <input type="radio" name="toggle-chart" value="1000000">
                                <span class="icon-mv toggle-ui-check"><span class="icon-checkbox"></span></span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="btn-favourite radio-ui">
                                <span class="icon-mv"><span class="icon-heart-icon-listing"></span></span>
                                <?=Yii::t('statistic','Login')?>
                                <span><?=$totalLogin ?></span>
                                <input type="radio" name="toggle-chart" value="1000000">
                                <span class="icon-mv toggle-ui-check"><span class="icon-checkbox"></span></span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="btn-share radio-ui">
                                <span class="icon-mv"><span class="icon-share-social"></span></span>
                                <?=Yii::t('statistic','Listing')?>
                                <span><?=$totalListing?></span>
                                <input type="radio" name="toggle-chart" value="1000000">
                                <span class="icon-mv toggle-ui-check"><span class="icon-checkbox"></span></span>
                            </a>
                        </li>
                    </ul>
        		</div>
        	</section>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        var chart = $('#chartAds').highcharts();
        $('.option-view-stats .radio-ui').radio({
            done: function (item) {
                var index = item.parent().parent().index();
                console.log(chart.series.length)
                chart.series[index].show();
                for ( var i = 0; i < chart.series.length; i++ ) {
                    if ( i != index) {
                        chart.series[i].hide();
                    }
                }
            }
        });
    });
</script>