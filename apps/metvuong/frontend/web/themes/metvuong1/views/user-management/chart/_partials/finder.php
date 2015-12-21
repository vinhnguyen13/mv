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
                name: '57 Tôn Đản',
                data: [2, 8, 5, 11, 17, 22, 24, 24, 20, 14, 8, 2]
            }, {
                name: '23 Pastuer',
                data: [1, 2, 4, 8, 13, 17, 18, 17, 14, 9, 3, 1]
            }, {
                name: '11 Nguyễn Văn Trỗi',
                data: [3, 4, 5, 8, 11, 15, 17, 16, 14, 10, 6, 4]
            }, {
                name: '21 Lê Thánh Tôn',
                data: [7, 6, 9, 14, 18, 21, 25, 26, 23, 18, 13, 9]
            }]
        });
    });
</script>