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
            /*tooltip: {
                valueSuffix: ' người',
                useHTML:true,
                formatter: function() {
                    var tooltip;
                    if (this.key == 'last') {
                        tooltip = '<b>Final result is </b> ' + this.y;
                    }
                    else {
                        tooltip =  '<span style="color:' + this.series.color + '">' + this.series.name + '</span>: <b>' + this.y + '</b><br/>';
                    }
                    return tooltip;
                }
            },*/
            tooltip: {
                useHTML: true,
                borderRadius: 8,
                backgroundColor:'rgba(255, 255, 255, 0.9)',
                headerFormat:'<div style="color:#36454d; font-size:16px">{series.name}</div><br>',
                // pointFormat: '{point.x} {point.y:,.0f} <br>{series.name} produced <b></b><br/>warheads in {point.x}'
                formatter: function() {
                    return  '<div class="tooltipCover"><b  style="color:#36454d; font-size:16px">' + this.series.name +'</b><br/>' +
                        Highcharts.dateFormat('%e - %b - %Y',
                            new Date(this.x))
                        +'<br>Всего постов: 20 000<br><div class="actionLine"><span class="likeCount">525 000</span><br><span class="shareCount">525 000</span><br><span class="commentsCount">525 000</span><br><a href="#" class="showComments">Show</a></div></div>';
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: '21 Lê Thánh Tôn',
                data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
            }, {
                name: '57 Tôn Đản',
                data: [-0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5]
            }, {
                name: '23 Pastuer',
                data: [-0.9, 0.6, 3.5, 8.4, 13.5, 17.0, 18.6, 17.9, 14.3, 9.0, 3.9, 1.0]
            }, {
                name: '11 Nguyễn Văn Trỗi',
                data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
            }]
        });
    });
</script>
