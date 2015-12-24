<?php
use yii\helpers\Url;
$data = [
    [
        'name' => '21 Lê Thánh Tôn',
        'data' => [
            ['y' => 2,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 8,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 5,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 11,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 17,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 22,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 24,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 24,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 20,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 14,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 8,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 3,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
        ],
    ],[
        'name' => '57 Tôn Đản',
        'data' => [
            ['y' => 1,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 2,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 4,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 7,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 14,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 17,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 14,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 9,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 3,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 2,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 11,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 25,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
        ],
    ],[
        'name' => '23 Pastuer',
        'data' => [
            ['y' => 4,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 5,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 6,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 9,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 11,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 14,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 19,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 18,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 15,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 16,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 21,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 11,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
        ],
    ],[
        'name' => '11 Nguyễn Văn Trỗi',
        'data' => [/*7, 6, 9, 14, 18, 21, 25, 26, 23, 18, 13, 9*/
            ['y' => 5,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 18,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 31,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 21,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 22,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 26,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 29,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 22,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 32,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 12,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 28,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
            ['y' => 24,'url' => Url::to(['/user-management/chart', 'view'=>'_partials/listContact'])],
        ],
    ],
];
?>
<div id="chartAds" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<script>
    $(function () {
        $('#chartAds').highcharts({
            title: {
                text: 'Lượt người tìm kiếm tin đăng của bạn',
                x: -20 //center
            },
            subtitle: {
                text: 'Nguồn: MetVuong.com',
                x: -20
            },
            xAxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            yAxis: {
                title: {
                    text: 'Người tìm kiếm'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: ' người',
                useHTML:true,
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
                            click: function() {//alert ('Category: '+ this.category +', value: '+ this.y);
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
                                            $('#frmListVisit').find('h3').html('Thống kê');
                                            $('#frmListVisit').find('.total').html(_this.y);
                                            $('#frmListVisit').find('.totalNext').html(_this.y - 3);
                                            $('#frmListVisit').find('.desTotal').html('Danh sách người tìm kiếm tin: <b>'+_this.series.name+'</b>');
                                        }
                                    });
                                }, 500);
                                $('#frmListVisit').modal();
                            }
                        }
                    }
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: <?=json_encode($data);?>,
            credits: {
                enabled: false
            }
        });
    });
</script>