<?php 
use yii\web\View;
use frontend\models\Transaction;

$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/swiper.jquery.min.js', ['position' => View::POS_END]);
?>
<div class="title-fixed-wrap container">
    <div class="giao-dich">
        <div class="title-top">Payment</div>
        <div class="wrap-giao-dich">
            <div class="title-gd mgB-15"><?=Yii::t('payment', 'Your balance')?></div>
            <div class="mgB-30">
                <div class="mgB-20">
                    <?=Yii::t('payment', 'Remaining Keys')?>: <span class="d-ib mgL-10 mgR-10 font-700"><span class="icon-mv mgR-5 fs-20"><span class="icon-coin-dollar"></span></span><?=number_format($this->params['balance'])?> Keys</span>
                    <a href="<?=\yii\helpers\Url::to(['payment/index'])?>" class="d-ib btn pdT-5 pdB-5 font-600 fs-13 deposit"><?=Yii::t('payment', 'Buy Keys')?></a>
                    <?= $this->render('/coupon/_partials/coupon'); ?>
                </div>
            </div>
            <div class="title-gd mgB-10"><?=Yii::t('payment', 'Transaction history')?></div>
            <div class="tbl-wrap">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <div class="thead wrap-tr-each swiper-slide">
                            <div class="w-10"><span><?=Yii::t('payment', 'ID')?></span></div>
                            <div class="w-15"><span><?=Yii::t('payment', 'Date/Time')?></span></div>
                            <div class="w-15"><span><?=Yii::t('payment', 'Type')?></span></div>
                            <div class="w-15"><span><?=Yii::t('payment', 'Status')?></span></div>
                            <div class="w-15"><span><?=Yii::t('payment', 'Amount')?></span></div>
                            <div class="w-30"><span><?=Yii::t('payment', 'Note')?></span></div>
                        </div>
                        <?php
                        if(count($transactions) > 0) {
                            foreach ($transactions as $transaction) {
                                $amount = $transaction->amount;
                        ?>
                        <div class="wrap-tr-each swiper-slide">
                            <div class="w-10"><span><?=$transaction->id?></span></div>
                            <div class="w-15"><span><?=date('d/m/Y, H:i', $transaction->created_at)?></span></div>
                            <div class="w-15"><span><?=$transaction->getObjectType($transaction->object_type)?></span></div>
                            <div class="w-15"><span class="color-cd"><?=$transaction->getTransactionStatus($transaction->status)?></span></div>
                            <div class="w-15"><span><?= abs($amount) > 1 ? $amount." Keys" : $amount." Key" ?></span></div>
                            <div class="w-30"><span><?=$transaction->getNote($transaction->object_type)?></span></div>
                        </div>
                        <?php } }else {?>
                        <div class="wrap-tr-each swiper-slide">
                            <div class="text-center"><span><?=Yii::t('payment', 'No transaction')?>.</span></div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <br>
            <nav class="text-center">
                <?php
                echo yii\widgets\LinkPager::widget([
                    'pagination' => $pagination
                ]);
                ?>
            </nav>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var swiper = undefined;
        $(window).on('resize', function () {
             var wWindow = $(window).outerWidth();
            if ( wWindow <= 500 && swiper == undefined ) {
                swiper = new Swiper('.tbl-wrap .swiper-container', {
                    pagination: '.swiper-pagination',
                    paginationClickable: true,
                    nextButton: '.swiper-button-next',
                    prevButton: '.swiper-button-prev',
                    spaceBetween: 0
                });        
            }else if ( wWindow > 500 && swiper != undefined ) {
                swiper.destroy();
                swiper = undefined;
                $('.swiper-wrapper').removeAttr('style');
                $('.swiper-slide').removeAttr('style');   
            }
        }).trigger('resize');
    });
</script>