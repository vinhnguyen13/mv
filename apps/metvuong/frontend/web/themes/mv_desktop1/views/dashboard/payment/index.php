<?php 
use yii\web\View;
$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/swiper.jquery.min.js', ['position' => View::POS_END]);
$balance = Yii::$app->user->identity->getBalance();
$balanceValue = !empty($balance->amount) ? $balance->amount : 0;
?>
<div class="title-fixed-wrap container">
    <div class="giao-dich">
        <div class="title-top">Giao Dịch</div>
        <div class="wrap-giao-dich">
            <div class="title-gd mgB-15">Thông tin tài khoản</div>
            <div class="mgB-30">
                Số Keys Còn Lại: <span class="d-ib mgL-20 font-700"><span class="icon-mv mgR-5 color-gold fs-20"><span class="icon-coin-dollar"></span></span><?=number_format($balanceValue)?> Keys</span>
                <a href="<?=\yii\helpers\Url::to(['payment/index'])?>" class="d-ib btn mgL-20 pdT-5 pdB-5 font-600 fs-13 deposit">Nạp Keys</a>
            </div>
            <div class="title-gd mgB-5">Giao dịch gần đây</div>
            <div class="tbl-wrap clearfix">
                <div class="thead clearfix">
                    <div class="pull-left w-10"><span>Mã GD</span></div>
                    <div class="pull-left w-15"><span>Ngày/Giờ</span></div>
                    <div class="pull-left w-30"><span>Loại giao dịch</span></div>
                    <div class="pull-left w-20"><span>Tình trạng</span></div>
                    <div class="pull-left w-25"><span>Số tiền</span></div>
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
                            <div class="pull-left w-10"><span><?=$transaction->id?></span></div>
                            <div class="pull-left w-15"><span><?=date('d/m/Y, H:i')?></span></div>
                            <div class="pull-left w-30"><span><?=\vsoft\ec\models\EcTransactionHistory::getObjectType($transaction->object_type)?></span></div>
                            <div class="pull-left w-20"><span class="color-cd"><?=\vsoft\ec\models\EcTransactionHistory::getTransactionStatus($transaction->status)?></span></div>
                            <div class="pull-left w-25"><span><?= $amount > 1 ? $amount." Keys" : $amount." Key" ?></span></div>
                        </div>
                    <?php }
                    ?>
                    </div>
                    </div>
                    <?php
                } else {?>
                <div class="clearfix tbl-emu">
                    <div class="text-center"><span>Không có giao dịch.</span></div>
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
//        $('.deposit').click(function () {
//            $.ajax({
//                type: "get",
//                dataType: 'html',
//                url: '<?//=yii\helpers\Url::to(['dashboard/create-transaction']) ?>//',
//                success: function (data) {
//                    if(data)
//                        window.location.reload();
//                }
//            });
//        });

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