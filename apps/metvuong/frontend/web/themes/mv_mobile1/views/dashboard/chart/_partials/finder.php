<?php
use yii\helpers\Url;
use frontend\models\Chart;

$data = Chart::find()->getDataFinder($id, $from, $to);

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
                    type: 'column'
                },
                legend: {
                    enabled: false
                },
                title: {
                    text: 'Lượt người tìm kiếm',
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
                    useHTML:true,
                    formatter: function() {
                        var tooltip;
                        if (this.key == 'last') {
                            tooltip = '<b>Final result is </b> ' + this.y;
                        }
                        else {
                            tooltip = '<b>' + this.y + ' người tìm</b><br/>';
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
                                click: function() {
                                    for (var i = 0; i < this.series.data.length; i++) {
                                        this.series.data[i].update({ color: '#909090' }, true, false);
                                    }
                                    this.update({ color: '#00a769' }, true, false);
                                    getDataByClick(this.category);
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
        function getDataByClick(date, categories){
            var timer = 0;
            clearTimeout(timer);
            timer = setTimeout(function () {
                $.ajax({
                    type: "get",
                    dataType: 'html',
                    url: '<?=Url::to(['/dashboard/clickchart','id' => $id])?>' + '&date=' + date + '&view=_partials/finder',
                    success: function (data) {
                        console.log(data);
                        if(data){
                            $('.finder .list-item').html(data);
                        }
                    }
                });
            }, 500);
        }
    </script>
    <?php
} else {
    ?>
    <div class="alert alert-warning">
        <p class="text-center">Chưa có người nào tìm kiếm tin của bạn, bạn hãy <a href="">làm mới</a> tin của mình</p>
    </div>
    <?php
}?>
