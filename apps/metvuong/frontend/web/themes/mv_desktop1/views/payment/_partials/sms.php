<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 6/16/2016
 * Time: 11:33 AM
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<div id="sms" class="item-payment">
    <div class="title-item"><?=Yii::t('payment', 'Payment by SMS')?></div>
    <div class="w-50 pdT-20">
        <div class="noti-pay">
            <p class="mgB-10"><span class="color-red d-ib pdR-10 font-700 text-decor"><?=Yii::t('payment', 'Note')?>:</span>
                <?=Yii::t('payment', 'The amount of keys added to your account will be after a <b>35%</b> service charge and <b>10%</b> VAT on top. E.g. Buying 100.000 VND through SMS will add 58.000 VND worth of Keys to your account.')?>
            </p>
            <div class="clearfix">
                <ul class="val-payment w-100">
                    <li><span>100,000 VND</span> = <span>54 <?=Yii::t('payment', 'Keys')?></span></li>
                    <li><span>50,000 VND</span> = <span>27 <?=Yii::t('payment', 'Keys')?></span></li>
                </ul>
            </div>
        </div>
        <div class="text-center">
            <p class="mgB-5"><?=Yii::t('payment', 'Construct your Message as follows:')?></p>
            <p class="mgB-5"><span class="color-cd font-700">TT MV</span> [Mã Thành Viên] [Số Tiền] gửi <strong>19001590</strong></p>
            <p>VD: TT MV 1234 10000 gửi 19001590</p>
        </div>
    </div>
</div>