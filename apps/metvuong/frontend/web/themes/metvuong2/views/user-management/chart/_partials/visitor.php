<?php
use yii\helpers\Url;

$startTime = strtotime('-30 days');
$endTime = strtotime('+1 days');
$pids = [];

$data = \frontend\models\Tracking::find()->parseTracking($startTime, $endTime, $pids);
if(!empty($data)) {
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
                    type: 'line',
//                type: 'area',
                },
                title: {
                    text: 'Lượt người theo dõi tin đăng của bạn',
                    x: -20 //center
                },
                subtitle: {
                    text: 'Nguồn: MetVuong.com',
                    x: -20
                },
                xAxis: {
                    categories: <?=json_encode($categories);?>,
                    max: <?=!empty(count($categories)) ? count($categories) - 1 : 0?>
                },
                yAxis: {
                    title: {
                        text: 'Người theo dõi'
                    },
                    plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }]
                },
                tooltip: {
                    valueSuffix: ' người',
                    useHTML: true,
                    /*formatter: function() {
                     var tooltip;
                     if (this.key == 'last') {
                     tooltip = '<b>Final result is </b> ' + this.y;
                     }
                     else {
                     tooltip =  '<span style="color:' + this.series.color + '">' + this.series.name + '</span>: <b>' + this.y + '</b><br/>';
                     }
                     return tooltip;
                     }*/
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
                                                //$('#frmListVisit').find('h3').html('Thống kê');
                                                $('#frmListVisit').find('.total').html(_this.y);
                                                $('#frmListVisit').find('.totalNext').html(_this.y - 3);
                                                $('#frmListVisit').find('.desTotal').html('Danh sách người theo dõi tin: <b>' + _this.series.name + '</b>');
                                            }
                                        });
                                    }, 500);
                                    $('#frmListVisit').modal();
                                }
                            }
                        }
                    }
                },
                /*chart: {
                 events: {
                 click: function(event) {
                 alert ('x: '+ event.xAxis[0].value +', y: '+
                 event.yAxis[0].value);
                 }
                 }
                 },*/
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle',
                    borderWidth: 0
                },
                series: <?=json_encode($dataChart);?>,
                credits: {
                    enabled: false
                }
            });
        });
    </script>
    <?php
}
?>