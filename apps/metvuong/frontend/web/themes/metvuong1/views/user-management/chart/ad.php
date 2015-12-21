<div class="col-xs-9 right-profile quanlytinraoban">
    <div class="wrap-quanly-profile">
        <div class="title-frm">Quản lý thống kê</div>
        <ul class="nav nav-tabs">
            <li class="active"><a class="tab">Người theo dõi</a></li>
            <li><a class="tab">Người tìm kiếm</a></li>
        </ul>
        <div id="chartAds" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    </div>
</div>


<div class="modal fade" id="frmListVisit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="wrap-modal clearfix">
                    <h3>Người theo dõi</h3>
                    <p style="color: #4a933a;">
                        Có <span class="total">5</span> người tìm nhà xung quanh khu vực Phường 3, Quận 1, TpHCM.
                    </p>

                    <?php
                    $yourArray = [
                        0 => [
                            'title' => 'Nguyễn Quang Vinh',
                            'phone' => '0909030605',
                            'time' => date('H:i:s d-m-Y', strtotime('-1days')),
                        ],
                        1 => [
                            'title' => 'Nguyễn Trung Ngạn',
                            'phone' => '0909030605',
                            'time' => date('H:i:s d-m-Y', strtotime('-2days')),
                        ],
                        2 => [
                            'title' => 'Quách Tuấn Lệnh',
                            'phone' => '0909030605',
                            'time' => date('H:i:s d-m-Y', strtotime('-3days')),
                        ],
                        3 => [
                            'title' => 'Quách Tuấn Du',
                            'phone' => '0909030605',
                            'time' => date('H:i:s d-m-Y', strtotime('-5days')),
                        ],

                    ];
                    $provider = new \yii\data\ArrayDataProvider([
                        'allModels' => $yourArray,
                        'sort' => [
                            'attributes' => ['title','net_accumulative_cashflow'],
                        ],
                        'pagination' => [
                            'pageSize' => 15,
                        ],
                    ]);
                    echo \yii\grid\GridView::widget([
                        'dataProvider' => $provider,
                        'summary'=>"",
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'title',
                            'phone',
                            'time',
                        ],
                    ]);?>
                    <p>Và 500 người nữa đang theo dõi tin của bạn. Bạn vui lòng <a href="javascript:alert('Coming soon !');">nạp thêm tiền</a> để có thể xem thêm</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('#chartAds').highcharts({
            title: {
                text: 'Tin đăng của bạn',
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
                    text: 'Người xem'
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
                            click: function() {//alert ('Category: '+ this.category +', value: '+ this.y);
//                                console.log(this);
                                $('#frmListVisit').find('.total').html(this.y);
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
            series: [{
                name: '21 Lê Thánh Tôn',
                data: [7, 6, 9, 14, 18, 21, 25, 26, 23, 18, 13, 9]
            }, {
                name: '57 Tôn Đản',
                data: [2, 8, 5, 11, 17, 22, 24, 24, 20, 14, 8, 2]
            }, {
                name: '23 Pastuer',
                data: [1, 2, 4, 8, 13, 17, 18, 17, 14, 9, 3, 1]
            }, {
                name: '11 Nguyễn Văn Trỗi',
                data: [3, 4, 5, 8, 11, 15, 17, 16, 14, 10, 6, 4]
            }]
        });
    });
</script>
