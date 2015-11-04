<?php
$scenario_1 = $data["scenario_1"];
$categories = '';
$data_1 = '';
foreach($scenario_1 as $key => $value){
    $categories = $categories . $key . ',';
    $data_1 = $data_1 . $value . ',';
}
$categories = [ rtrim($categories, ',') ];
$data_1 = '[' .rtrim($data_1, ',') . ']';

?>
<div class="row main_content">
    <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
</div>

<script>
    $(function () {
        chart.themes();
        $('#container').highcharts({
            title: {
                text: 'Timeline Money Chart',
                x: -20 //center
            },
            subtitle: {
                text: 'trungthuygroup.vn',
                x: -20
            },
            xAxis: {
                categories: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12', 'T13', 'T14', 'T15', 'T16', 'T17', 'T18', 'T19', 'T20', 'T21', 'T22', 'T23', 'T24', 'T25', 'T26', 'T27', 'T28', 'T29', 'T30', 'T31', 'T32']
            },
            yAxis: {
                title: {
                    text: 'Money $'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: ' VND'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: 'Scenario 1',
                color: '#5698D3',
                data: <?=$data_1?>
            }, {
                name: 'Scenario 2',
                color: '#EE863F',
                data: [-270, -342, -373, -318, -456, -462]
            }]
        });



    });

</script>
