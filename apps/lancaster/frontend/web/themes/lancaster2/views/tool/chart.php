<?php
use yii\web\View;
Yii::$app->getView()->registerCssFile(Yii::$app->view->theme->baseUrl . '/resources/chart/chart.css', ['depends' => ''], 'css-chart');
Yii::$app->getView()->registerJsFile('http://code.highcharts.com/highcharts.js', ['position' => View::POS_BEGIN]);
Yii::$app->getView()->registerJsFile('http://code.highcharts.com/modules/exporting.js', ['position' => View::POS_BEGIN]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/chart/chart.js', ['position' => View::POS_END]);
Yii::$app->view->title = 'Chart Tool';
?>
<div class="chart_result">

</div>

<script>
    $(function () {
        $.ajax({
            type: "get",
            dataType: 'html',
            url: '/tool/get-chart',
            success: function(data) {
                $('.chart_result').html(data);
            },
        });
    });
</script>