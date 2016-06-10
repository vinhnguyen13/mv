<?php
use yii\helpers\Url;
use frontend\models\Chart;

$data = null;
$filter = Yii::$app->request->get("filter", "week");
$date = Yii::$app->request->get("date");

$finders = Chart::find()->getFinderWithLastTime($id, $date, $filter);
$visitors = Chart::find()->getVisitorWithLastTime($id, $date, $filter);
$shares = Chart::find()->getShareWithLastTime($id, $date, $filter);
$favourites = Chart::find()->getSavedWithLastTime($id, $date, $filter);

$finderFrom = (!empty($finders) && isset($finders["from"])) ? $finders["from"] : 0;
$finderTo = (!empty($finders) && isset($finders["to"])) ? $finders["to"] : 0;

$visitorFrom = (!empty($visitors) && isset($visitors["from"])) ? $visitors["from"] : 0;
$visitorTo = (!empty($visitors) && isset($visitors["to"])) ? $visitors["to"] : 0;

$favouriteFrom = (!empty($favourites) && isset($favourites["from"])) ? $favourites["from"] : 0;
$favouriteTo = (!empty($favourites) && isset($favourites["to"])) ? $favourites["to"] : 0;

$shareFrom = (!empty($shares) && isset($shares["from"])) ? $shares["from"] : 0;
$shareTo = (!empty($shares) && isset($shares["to"])) ? $shares["to"] : 0;

if($from > 0 && $to > 0)
    $data = Chart::find()->getDataFinder($id, $from, $to);
    ksort($data['dataChart']);
    $dataChart = array_values($data['dataChart']);
    ksort($data['categories']);
    $categories = array_values($data['categories']);

    $data2 = Chart::find()->getDataVisitor($id, $visitorFrom, $visitorTo);
    ksort($data2['dataChart']);
    $dataChart2 = array_values($data2['dataChart']);
    ksort($data2['categories']);
    $categories2 = array_values($data2['categories']);

    $data3 = Chart::find()->getDataSaved($id, $favouriteFrom, $favouriteTo);
    ksort($data3['dataChart']);
    $dataChart3 = array_values($data3['dataChart']);
    ksort($data3['categories']);
    $categories3 = array_values($data3['categories']);

    $data4 = Chart::find()->getDataShare($id, $shareFrom, $shareTo);
    ksort($data4['dataChart']);
    $dataChart4 = array_values($data4['dataChart']);
    ksort($data4['categories']);
    $categories4 = array_values($data4['categories']);

    $dataChart = \yii\helpers\ArrayHelper::merge($dataChart, $dataChart2);
    $dataChart = \yii\helpers\ArrayHelper::merge($dataChart, $dataChart3);
    $dataChart = \yii\helpers\ArrayHelper::merge($dataChart, $dataChart4);
//    $categories = array_merge($categories, $categories2, $categories3, $categories4);
$categories = $categories3;

    ?>
    <div id="chartAds" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    <script>
        $(function () {
            $('#chartAds').highcharts({
                chart: {
                    type: 'column'
                },
                legend: {
                    enabled: false
                },
                title: {
                    text: '<?=Yii::t('chart', 'The searching user')?>',
                    x: -20 //center
                },
                subtitle: {
                    text: '<?=Yii::t('chart','Source')?>: MetVuong.com',
                    x: -20
                },
                xAxis: {
                    categories: <?=json_encode($categories)?>,
                    max: <?=!empty(count($categories)) ? count($categories) - 1 : 0?>,
                    labels: {
                        formatter: function () {
                            var d = this.value.split("/");
                            return d[0] + "/" + d[1];
                        }
                    }
                },
                yAxis: {
                    allowDecimals: false,
                    opposite: true,
                    title: {
                        text: null
                    },
                    plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }]
                },
                tooltip: {
                    useHTML: true,
                    formatter: function () {
                        var tooltip;
                        if (this.key == 'last') {
                            tooltip = '<b>Final result is </b> ' + this.y;
                        }
                        else {
                            tooltip = '<b>' + this.y + ' <?=Yii::t('chart','user')?></b><br/>';
                        }
                        return tooltip;
                    }
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    },
                    series: {
                        cursor: 'pointer',
                        point: {
                            events: {
                                click: function () {
                                    $('#frmListVisit .wrap-modal').html('');
                                    var timer = 0;
                                    var _this = this;
                                    clearTimeout(timer);
                                    timer = setTimeout(function () {
                                        $.ajax({
                                            type: "get",
                                            dataType: 'html',
                                            url: _this.url,
                                            success: function (data) {
                                                $('#frmListVisit .wrap-modal').html($(data));
                                            }
                                        });
                                    }, 500);
                                    $('#frmListVisit').modal();
                                }
                            }
                        }
                    }
                },
                series: <?=json_encode($dataChart);?>,
                credits: {
                    enabled: false
                }
            });
        });

    </script>
<div class="statistic-info">
    <a href="<?=$urlDetail?>" class="fs-14"><p class="name-post"><span class="icon address-icon"></span><?=$address?></p></a>
    <?php if($from > 0 && $to > 0) {?>
    <p class="date-filter-chart text-center mgT-15 fs-14"><?=Yii::t('chart', 'Statistic searching from')?> <span class="from"><?=date('d/m/Y', $from)?></span> - <span class="to"><?=date('d/m/Y', $to)?></span></p>
    <?php } ?>
</div>
