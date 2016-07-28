<?php
if(!empty($data['total']) && !empty($data['list_price'])) {
    ?>
    <table class="savings-tbl">
        <tbody>
        <tr class="savings-tlt">
            <td>Điều kiện</td>
            <td>Giá Trung Bình</td>
            <td>Giá Trung Bình/m2</td>
            <td>Listing</td>
        </tr>
        <tr>
            <td class="saving_table saving_table_left"></td>
            <td class="saving_table"><?= number_format($data['sum'] / $data['total']) ?> VND</td>
            <td class="saving_table"><?= number_format($data['sum'] / $data['sum_area']) ?> VND</td>
            <td class="saving_table"><?= number_format($data['totalListing']) ?></td>
        </tr>
        </tbody>
    </table>
    <div id="chartBoxplot" style="width: 400px; height: 400px; margin: 0 auto"></div>
    <script>
        $(function () {
            loadChart();
            function loadChart() {
                $('#chartBoxplot').highcharts({
                    chart: {
                        type: 'boxplot'
                    },
                    title: {
                        text: 'MetVuong Box Plot'
                    },
                    subtitle: {
                        text: 'Lưu ý: giá x 1.000.000',
                        x: 25
                    },
                    legend: {
                        enabled: false
                    },
                    xAxis: {
                        categories: ['1'],
                        title: {
                            text: 'No.'
                        }
                    },
                    yAxis: {
                        title: {
                            text: 'Observations'
                        }
                    },
                    plotOptions: {
                        boxplot: {
                            fillColor: '#F0F0E0',
                            lineWidth: 2,
                            medianColor: '#0C5DA5',
                            medianWidth: 3,
                            stemColor: '#A63400',
                            stemDashStyle: 'dot',
                            stemWidth: 1,
                            whiskerColor: '#3D9200',
                            whiskerLength: '20%',
                            whiskerWidth: 3
                        }
                    },
                    series: [{
                        name: 'Observations',
                        data: [<?=json_encode($data['dataChart'])?>]
                    }]

                });
            }
        });
    </script>
    <div id="chartHistogram" style="width: 400px; height: 400px; margin: 0 auto"></div>

    <script>
        var d = new Date();
        var pointStart = d.getTime();
        var chart,
            binnedData,
            rawData = [<?=$data['list_price_new']?>];
        binnedData = binData(rawData);
        console.log(binnedData);
        $(function() {
            $('#chartHistogram').highcharts({
                chart: {
                    type: 'column',
                    margin: [60, 10, 40, 40]
                },
                colors: [
                    'rgba( 0,   154, 253, 0.9 )', //bright blue
                    'rgba( 253, 99,  0,   0.9 )', //bright orange
                    'rgba( 40,  40,  56,  0.9 )', //dark
                    'rgba( 253, 0,   154, 0.9 )', //bright pink
                    'rgba( 154, 253, 0,   0.9 )', //bright green
                    'rgba( 145, 44,  138, 0.9 )', //mid purple
                    'rgba( 45,  47,  238, 0.9 )', //mid blue
                    'rgba( 177, 69,  0,   0.9 )', //dark orange
                    'rgba( 140, 140, 156, 0.9 )', //mid
                    'rgba( 238, 46,  47,  0.9 )', //mid red
                    'rgba( 44,  145, 51,  0.9 )', //mid green
                    'rgba( 103, 16,  192, 0.9 )' //dark purple
                ],
                title: {
                    text: 'MetVuong Histogram Chart',
                    x: 25
                },
                subtitle: {
                    text: 'Lưu ý: giá x 1.000.000',
                    x: 25
                },
                legend: {
                    enabled: false
                },
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: false
                },
                tooltip: {},
                plotOptions: {
                    series: {
                        pointPadding: 0,
                        groupPadding: 0,
                        borderWidth: 0.5,
                        borderColor: 'rgba(255,255,255,0.5)',
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                xAxis: {
                    title: {
                        text: 'Price'
                    }
                },
                yAxis: {
                    title: {
                        text: ''
                    }
                }
            });
            chart = $('#chartHistogram').highcharts();
            chart.addSeries({
                name: 'Distribution',
                data: binnedData
            });

        });

        //-------------------------------------------------------
        function binData(data) {

            var hData = new Array(), //the output array
                size = data.length, //how many data points
                bins = Math.round(Math.sqrt(size)); //determine how many bins we need
            bins = bins > 50 ? 50 : bins; //adjust if more than 50 cells
            var max = Math.max.apply(null, data), //lowest data value
                min = Math.min.apply(null, data), //highest data value
                range = max - min, //total range of the data
                width = range / bins, //size of the bins
                bin_bottom, //place holders for the bounds of each bin
                bin_top;

            //loop through the number of cells
            for (var i = 0; i < bins; i++) {

                //set the upper and lower limits of the current cell
                bin_bottom = min + (i * width);
                bin_top = bin_bottom + width;

                //check for and set the x value of the bin
                if (!hData[i]) {
                    hData[i] = new Array();
                    hData[i][0] = bin_bottom + (width / 2);
                }

                //loop through the data to see if it fits in this bin
                for (var j = 0; j < size; j++) {
                    var x = data[j];

                    //adjust if it's the first pass
                    i == 0 && j == 0 ? bin_bottom -= 1 : bin_bottom = bin_bottom;

                    //if it fits in the bin, add it
                    if (x > bin_bottom && x <= bin_top) {
                        !hData[i][1] ? hData[i][1] = 1 : hData[i][1]++;
                    }
                }
            }
            $.each(hData, function(i, point) {
                if (typeof point[1] == 'undefined') {
                    hData[i][1] = 0;
                }
            });
            return hData;
        }
    </script>
    <?php if(YII_DEBUG): ?>
    <p>
        <?=$data['list_price']?>
    </p>
    <p>
        <?=$data['list_price_new']?>
    </p>
    <?php endif?>
    <?php
}
?>