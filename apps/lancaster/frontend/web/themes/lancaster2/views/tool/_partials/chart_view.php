<?php
$scenario_1 = empty($data["scenario_1"]) == false ? $data["scenario_1"] : [];
$data_1 = [];
if(!empty($scenario_1)){
    foreach($scenario_1 as $key => $value){
        $data_1[] = $value;
    }
}

$scenario_2 = empty($data["scenario_2"]) == false ? $data["scenario_2"] : [];
$data_2 = [];
if(!empty($scenario_2)) {
    foreach ($scenario_2 as $key => $value) {
        $data_2[] = $value;
    }
}
$categories = array_merge($scenario_1, $scenario_2);
$months = array_keys($categories);
?>
<div class="row main_content">
    <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    <div class="container">
        <h2 class="text-center">Cash Flow Data </h2>
        <p></p>
        <div class="table-responsive"">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <?php
                    foreach($data as $k => $d){
                        if($data[$k])
                            echo "<th>".strtoupper($k)."</th>";
                    }
                    ?>
                </tr>
                </thead>
                <tbody>
                <?php
                $i=0;
                foreach($months as $m){
                ?>
                <tr>
                    <th><?=$m ?></th>
                    <td align="right"><?= number_format($data_1[$i] , 2 , "." , "," ) ?></td>
                    <td align="right"><?= number_format($data_2[$i] , 2 , "." , "," ) ?></td>
                </tr>
                <?php
                $i++;
                } ?>
                </tbody>
            </table>
        </div>
    </div>
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
                categories: <?= \yii\helpers\Json::encode($months)?>
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
            scrollbar: {
                enabled: true
            },
            series: [{
                name: 'Scenario 1',
                color: '#5698D3',
                data: <?=json_encode($data_1, JSON_NUMERIC_CHECK )?>
            }, {
                name: 'Scenario 2',
                color: '#EE863F',
                data: <?=json_encode($data_2, JSON_NUMERIC_CHECK )?>
            }]
        });



    });

</script>
