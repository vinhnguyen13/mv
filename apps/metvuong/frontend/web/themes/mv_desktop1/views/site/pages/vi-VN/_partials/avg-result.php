<?php
if(!empty($data['list_price'])) {
    ?>
    <table class="savings-tbl">
        <tbody>
        <tr class="savings-tlt">
            <td>Điều kiện</td>
            <td>Avg (cũ)</td>
            <td>Avg/m2 (cũ)</td>
            <td>Avg (mới)</td>
            <td>Avg/m2 (mới)</td>
            <td>Listing</td>
        </tr>
        <tr>
            <td class="saving_table saving_table_left"></td>
            <td class="saving_table"><?= number_format($data['average_old']) ?> VND</td>
            <td class="saving_table"><?= number_format(($data['average_m2_old'])) ?> VND</td>
            <td class="saving_table"><?= number_format($data['average_new']) ?> VND</td>
            <td class="saving_table"><?= number_format(($data['average_m2_new'])) ?> VND</td>
            <td class="saving_table"><?= number_format($data['totalListing']) ?></td>
        </tr>
        </tbody>
    </table>
    <div class="row mgT-50 mgB-50">
        <h2 class="title-gd">Thống kê theo giá</h2>
        <div class="col-xs-6">
            <div id="chartBoxplot"></div>
        </div>
        <div class="col-xs-6">
            <div id="chartHistogram"></div>
        </div>
    </div>
    <div class="row mgT-50 mgB-50">
        <h2 class="title-gd">Thống kê theo giá/m<sup>2</sup></h2>
        <div class="col-xs-6">
            <div id="chartBoxplot2"></div>
        </div>
        <div class="col-xs-6">
            <div id="chartHistogram2"></div>
        </div>
    </div>
    <?= $this->render('/site/pages/'.Yii::$app->language.'/_partials/chart',
        [
            'dataChart'=>$data['dataChart'],
            'list_price_new'=>$data['list_price_new'],
            'chartBoxplotID'=>'chartBoxplot',
            'chartHistogramID'=>'chartHistogram',
        ]); ?>
    <?= $this->render('/site/pages/'.Yii::$app->language.'/_partials/chart',
        [
            'dataChart'=>$data['dataChartPM2'],
            'list_price_new'=>$data['list_price_new_PM2'],
            'chartBoxplotID'=>'chartBoxplot2',
            'chartHistogramID'=>'chartHistogram2',
        ]); ?>

    <?php if(YII_DEBUG): ?>
        <p style="max-height: 500px; width: 200px; overflow: scroll;float: left;">
            <label>Price OLD</label><br>
            <?=implode('<br>',$data['list_price'])?>
        </p>
        <p style="max-height: 500px; width: 200px; overflow: scroll;float: left;">
            <label>Price NEW</label><br>
            <?=implode('<br>',$data['list_price_new'])?>
        </p>
        <p style="max-height: 500px; width: 200px; overflow: scroll;float: left;">
            <label>Price/m<sup>2</sup> OLD <?=count($data['list_price_PM2'])?></label><br>
            <?php
            sort($data['list_price_PM2']);
            echo implode('<br>',$data['list_price_PM2'])
            ?>
        </p>
        <p style="max-height: 500px; width: 200px; overflow: scroll;float: left;">
            <label>Price/m<sup>2</sup> NEW <?=count($data['list_price_new_PM2'])?></label><br>
            <?php
            sort($data['list_price_new_PM2']);
            echo implode('<br>',$data['list_price_new_PM2'])
            ?>
        </p>
        <?= number_format($data['average_m2_old_PM2']) ?> VND
        <br>
        <?= number_format($data['average_m2_new_PM2']) ?> VND
    <?php endif?>
    <?php
}
?>