<?php 
use yii\web\View;
use frontend\models\Transaction;

$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/swiper.jquery.min.js', ['position' => View::POS_END]);
?>
<div class="title-fixed-wrap container">
    <div class="giao-dich">
        <div class="wrap-giao-dich">
            <div class="title-gd mgB-15"><?=Yii::t('payment', 'Your balance')?></div>
            <div class="mgB-30">
                <div class="mgB-20">
                    <?=Yii::t('payment', 'Remaining Keys')?>: <span class="d-ib mgL-20 font-700"><span class="icon-mv mgR-5 fs-20"><span class="icon-coin-dollar"></span></span><?=number_format($this->params['balance'])?> Keys</span>
                    <a href="<?=\yii\helpers\Url::to(['payment/index'])?>" class="d-ib btn mgL-20 pdT-5 pdB-5 font-600 fs-13 deposit"><?=Yii::t('payment', 'Buy Keys')?></a>
                </div>
                <?= $this->render('/coupon/_partials/coupon'); ?>
            </div>
            <div class="title-gd mgB-5"><?=Yii::t('payment', 'Transaction history')?></div>
            <div class="tbl-wrap clearfix">
                <div class="thead clearfix">
                    <div class="pull-left w-10"><span><?=Yii::t('payment', 'ID')?></span></div>
                    <div class="pull-left w-15"><span><?=Yii::t('payment', 'Date/Time')?></span></div>
                    <div class="pull-left w-15"><span><?=Yii::t('payment', 'Type')?></span></div>
                    <div class="pull-left w-15"><span><?=Yii::t('payment', 'Status')?></span></div>
                    <div class="pull-left w-15"><span><?=Yii::t('payment', 'Amount')?></span></div>
                    <div class="pull-left w-30"><span><?=Yii::t('payment', 'Note')?></span></div>
                </div>
                <?php
                if(count($transactions) > 0) {
                    ?>
                        <div class="wrap-tr-each swiper-container">
                            <div class="inner-tr clearfix swiper-wrapper">
                    <?php
                    foreach ($transactions as $transaction) {
                        $amount = $transaction->amount;
                        ?>
                        <div class="clearfix tbl-emu swiper-slide">
                            <div class="w-10"><span><?=$transaction->id?></span></div>
                            <div class="w-15"><span><?=date('d/m/Y, H:i', $transaction->created_at)?></span></div>
                            <div class="w-15"><span><?=Transaction::getObjectType($transaction->object_type)?></span></div>
                            <div class="w-15"><span class="color-cd"><?=Transaction::getTransactionStatus($transaction->status)?></span></div>
                            <div class="w-15"><span><?= $amount > 1 ? $amount." Keys" : $amount." Key" ?></span></div>
                            <div class="w-30"><span>abc</span></div>
                        </div>
                    <?php }
                    ?>
                    </div>
                    </div>
                    <div class="swiper-pagination"></div>
                    <?php
                } else {?>
                <div class="clearfix tbl-emu">
                    <div class="text-center"><span><?=Yii::t('payment', 'No transaction')?>.</span></div>
                </div>
                <?php } ?>

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
                swiper = new Swiper('.wrap-tr-each.swiper-container', {
                    pagination: '.swiper-pagination',
                    paginationClickable: true,
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