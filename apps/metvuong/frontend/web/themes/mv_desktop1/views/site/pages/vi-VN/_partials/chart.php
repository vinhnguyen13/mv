<?php
if(!empty($data['total']) && !empty($data['list_price'])) {
    $dataChart = explode(',', $data['list_price']);
    $dataChart = \frontend\models\Avg::me()->calculation($dataChart);

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
    <div id="chartAds" style="width: 400px; height: 400px; margin: 0 auto"></div>
    <script>
        $(function () {
            var list_price = <?=json_encode($dataChart)?>;
            var ArrayData = $.map(list_price, function (value) {
                return parseInt(value, 10);
                // or return +value; which handles float values as well
            });
            loadChart([ArrayData]);
            function loadChart(data) {
                $('#chartAds').highcharts({
                    chart: {
                        type: 'boxplot'
                    },
                    title: {
                        text: 'MetVuong Box Plot Chart'
                    },
                    legend: {
                        enabled: false
                    },
                    xAxis: {
                        categories: ['1'],
                        title: {
                            text: 'Experiment No.'
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
//                        data: data
                        data: [<?=json_encode($dataChart)?>]
                    }]

                });
            }
        });
    </script>
    <?php
}
?>