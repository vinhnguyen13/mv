<?php
use yii\web\View;
use frontend\models\Transaction;

$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/swiper.jquery.min.js', ['position' => View::POS_END]);
?>
<div class="title-fixed-wrap container">
    <div class="giao-dich">
        <div class="title-top">Payment</div>
        <div class="wrap-giao-dich">
            <div class="title-gd mgB-10"><?=Yii::t('report', 'Mail history')?></div>
            <div class="tbl-wrap swiper-container">
                <div class="thead wrap-tr-each">
                    <div class="w-10"><span><?=Yii::t('report', 'No')?></span></div>
                    <div class="w-15"><span><?=Yii::t('report', 'From')?></span></div>
                    <div class="w-15"><span><?=Yii::t('report', 'To')?></span></div>
                    <div class="w-15"><span><?=Yii::t('report', 'Send')?></span></div>
                    <div class="w-15"><span><?=Yii::t('report', 'Read')?></span></div>
                    <div class="w-30"><span><?=Yii::t('report', 'Note')?></span></div>
                </div>
                <?php
                if(!empty($sysEmails)) {
                    $i = 1;
                    foreach ($sysEmails as $sysEmail) {
                        $description = $sysEmail->getDescription();
                        $send_time = !empty($sysEmail->send_time) ? date('d-m-Y H:i:s', $sysEmail->send_time) : '';
                        $read_time = !empty($sysEmail->read_time) ? date('d-m-Y H:i:s', $sysEmail->read_time) : '';
                ?>
                <div class="wrap-tr-each swiper-slide">
                    <div class="w-10"><span><?=$i?></span></div>
                    <div class="w-15"><span><?=$sysEmail->from_email;?></span></div>
                    <div class="w-15"><span><?=$sysEmail->to_email;?></span></div>
                    <div class="w-15"><span><?=$send_time?></span></div>
                    <div class="w-15"><span><?=$read_time?></span></div>
                    <div class="w-30"><span><?=$description?></span></div>
                </div>
                <?php $i++; } }else {?>
                <div class="wrap-tr-each swiper-slide">
                    <div class="text-center"><span><?=Yii::t('payment', 'No transaction')?>.</span></div>
                </div>
                <?php } ?>
            </div>
            <br>
            <nav class="text-center">
                <?php
//                echo yii\widgets\LinkPager::widget([
//                    'pagination' => $pagination
//                ]);
                ?>
            </nav>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var swiper = undefined;

    });
</script>