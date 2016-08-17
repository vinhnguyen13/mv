<?php


?>
<div id="chartAds" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<script>
    $(function () {
        $.getScript( "//code.highcharts.com/highcharts.js" )
        .done(function( script, textStatus ) {
            $('#chartAds').highcharts({
                legend: {
                    enabled: false,
                    floating: true,
                    verticalAlign: 'bottom',
                    align:'right',
                },
                title: {
                    text: '<?=Yii::t('chart', 'The Statistic')?>'
                },
                subtitle: {
                    text: '<?=Yii::t('chart','Source')?>: MetVuong.com'
                },
                xAxis: {
                    categories: <?=json_encode($categories);?>,
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
                        }else {
                            if(this.y > 0) {
                                tooltip = '<a href="#"><b class="chart_result" onclick="clickTooltip(this.point.type, this.point.date)">' + this.y + '</b></a><br/>';
                            }
                            else
                                tooltip = '<b class="chart_result">' + this.y + '</b><br/>';
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
                                    clickTooltip(this.type, this.date);
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
        })
        .fail(function( jqxhr, settings, exception ) {

        });

    });

    function clickTooltip(type, date) {
        $('.wrap-chart').loading({full: false});
        var _this = this;
        var url = '<?=\yii\helpers\Url::to(['report/click-chart'])?>?type='+type+'&date='+date;
        $.ajax({
            type: "get",
            dataType: 'html',
            url: url,
            success: function (data) {
                console.log(data);
                $('#frmListVisit .wrap-modal').html($(data));
                $('#frmListVisit').modal();
                $('body').loading({done:true});
            },
            error: function (error) {
                $('body').loading({done:true});
            }
        });
        return false;
    }
</script>