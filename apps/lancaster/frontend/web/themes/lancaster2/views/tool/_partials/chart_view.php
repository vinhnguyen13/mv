<?php
use yii\web\View;
$this->registerCssFile(Yii::$app->view->theme->baseUrl . '/resources/chart/chart.css', ['depends' => ''], 'css-chart');
Yii::$app->getView()->registerJsFile('http://code.highcharts.com/highcharts.js', ['position' => View::POS_BEGIN]);
Yii::$app->getView()->registerJsFile('http://code.highcharts.com/modules/exporting.js', ['position' => View::POS_BEGIN]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/chart/chart.js', ['position' => View::POS_END]);
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
                data: [-270, -342, -373, -389, -534, -547, -560, -580, -651, -565, -648, -599, -606, -586, -596, -494, -557, -527, -606, -593, -671, -664, -774, -743, -858, -625, -671, -403, -492, 453, 595, 595]
            }, {
                name: 'Scenario 2',
                color: '#EE863F',
                data: [-270, -342, -373, -318, -456, -462, -468, -480, -544, -303, -378, -226, -222, -89, -75, 142, 147, 297, 218, 281, 203, 260, 150, 75, -41, -85, -131, -168, -256, 455, 597, 597]
            }]
        });



    });

</script>