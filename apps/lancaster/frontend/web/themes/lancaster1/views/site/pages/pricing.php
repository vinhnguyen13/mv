<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 9/23/2015
 * Time: 2:14 PM
 */
?>
<div class="container-fluid layoutpricing">
    <div class="row main_content">
        <img src="<?=Yii::$app->view->theme->baseUrl?>/resources/IMG/05.png">
    </div>
    <div class="blocktable table-responsive">
        <table class="table table-bordered">
            <tr class="bgtitle">
                <th>Area (sqm)</th>
                <th>Kind of apartment</th>
                <th>Monthly Rates ( VND)</th>
                <th>Daily Rates ( VND)</th>
            </tr>
            <?php
//            $object = new \vsoft\express\models\LcPricing();
            $pricings = \vsoft\express\models\LcPricing::getAllPricing();
            foreach($pricings as $pricing) {
            ?>
            <tr>
                <td><?=Yii::$app->formatter->asDecimal($pricing['area'],0) ?></td>
                <td><?=$pricing['name'] ?></td>
                <td><?=number_format($pricing['monthly_rates'])?></td>
                <td><?=number_format($pricing['daily_rates']) ?></td>
            </tr>
            <?php } ?>
        </table>
        <div class="btn_booknow">
            <button class="btn btn-primary btn-lg" type="button" onclick="window.location='<?=\yii\helpers\Url::toRoute('/express/booking/index')?>'">booknow</button>
            <span class="noticaitalic"><i class="glyphicon glyphicon-earphone icon"></i>Call <b>0903 090 909 </b>for more infomation</span>
        </div>
    </div>

</div>
