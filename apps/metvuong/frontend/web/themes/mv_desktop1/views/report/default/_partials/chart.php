<?php


?>
<div id="chartAds" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<script src="//code.highcharts.com/highcharts.js"></script>
<script>
    $(function () {
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
                    }
                    else {
                        if(this.y > 0)
                            tooltip = '<a href="#"><b class="chart_result" onclick="clickTooltip()">' + this.y + '</b></a><br/>';
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
                                clickTooltip();
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

    function clickTooltip() {
        alert('hello!');return false;
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