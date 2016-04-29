<?php
use yii\helpers\Url;
use frontend\models\Chart;

$data = null;
if($from > 0 && $to > 0)
    $data = Chart::find()->getDataFinder($id, $from, $to);
    $dataChart = $data['dataChart'];
    $categories = $data['categories'];

    ksort($dataChart);
    $dataChart = array_values($dataChart);
    ksort($categories);
    $categories = array_values($categories);

    ?>
    <div id="chartAds" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    <script>
        $(function () {
            $('#chartAds').highcharts({
                chart: {
                    type: 'line'
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
                        pointPadding: 0,
                        borderWidth: 0
                    },
                    series: {
                        cursor: 'pointer',
                        point: {
                            events: {
                                click: function () {
                                    for (var i = 0; i < this.series.data.length; i++) {
                                        this.series.data[i].update({color: '#909090'}, true, false);
                                    }
                                    this.update({color: '#00a769'}, true, false);
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
