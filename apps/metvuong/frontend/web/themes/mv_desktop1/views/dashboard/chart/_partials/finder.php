<?php
use yii\web\View;

Yii::$app->getView()->registerJsFile('http://code.highcharts.com/highcharts.js', ['position' => View::POS_HEAD]);

?>
    <div id="chartAds" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    <script>
        $(function () {
            var chart = $('#chartAds').highcharts({
                legend: {
                    enabled: false
                },
                title: {
                    text: '<?=Yii::t('chart', 'The Statistic')?>'
                },
                subtitle: {
                    text: '<?=Yii::t('chart','Source')?>: MetVuong.com'
                },
                xAxis: {
                    categories: <?=json_encode($categories)?>,
                    max: <?=!empty(count($categories)) ? count($categories) - 1 : 0?>,
                    labels: {
                        formatter: function () {
                            var d = this.value.split("-");
                            return d[0] + "-" + d[1];
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
                            if(this.y > 0)
                                tooltip = '<a href="#"><b class="chart_result" onclick="clickTooltip(\''+this.point.url+'\')">' + this.y + ' <?=Yii::t('chart','user')?> </b></a><br/>';
                            else
                                tooltip = '<b class="chart_result">' + this.y + ' <?=Yii::t('chart','user')?></b><br/>';
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
                                    var _this = this;
                                    if(_this.url) {
                                        $('body').loading();
                                        $.ajax({
                                            type: "get",
                                            dataType: 'html',
                                            url: _this.url+'&total='+this.y,
                                            success: function (data) {
                                                console.log(data);
                                                $('body').loading({done: true});
                                                $('#frmListVisit .wrap-modal').html($(data));

                                                $('#frmListVisit').modal();
                                            }
                                        });
                                    }
                                },

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
        function clickTooltip(url) {
            $('#frmListVisit .wrap-modal').html('');
            var _this = this;
            console.log(_this);
            $('body').loading();
            $.ajax({
                type: "get",
                dataType: 'html',
                url: url,
                success: function (data) {
                    console.log(data);
                    $('body').loading({done: true});
                    $('#frmListVisit .wrap-modal').html($(data));

                    $('#frmListVisit').modal();
                }
            });
        }
    </script>
<div class="statistic-info">
    <a href="<?=$urlDetail?>" class="fs-14"><p class="name-post"><span class="icon address-icon"></span><?=$address?></p></a>
    <?php if($from > 0 && $to > 0) {?>
    <p class="date-filter-chart text-center mgT-15 fs-14"><?=Yii::t('chart', 'Statistic from')?> <span class="from"><?=date('d/m/Y', $from)?></span> <?=Yii::t('chart','to')?> <span class="to"><?=date('d/m/Y', $to)?></span></p>
    <?php } ?>
</div>
