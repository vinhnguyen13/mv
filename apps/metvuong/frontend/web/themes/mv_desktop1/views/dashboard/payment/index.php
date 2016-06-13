<div class="title-fixed-wrap container">
    <div class="giao-dich">
        <div class="title-top">Giao Dịch</div>
        <div class="wrap-giao-dich">
            <div class="title-gd mgB-15">Thông tin tài khoản</div>
            <div class="mgB-30">
                Số Keys Còn Lại: <span class="d-ib mgL-20 font-700"><span class="icon-mv mgR-5 color-gold fs-20"><span class="icon-coin-dollar"></span></span>10 Keys</span>
                <a href="" class="d-ib btn mgL-20 pdT-5 pdB-5 font-600 fs-13 deposit">Nạp Keys</a>
            </div>
            <div class="title-gd mgB-5">Giao dịch gần đây</div>
            <div class="tbl-wrap clearfix">
                <div class="thead clearfix">
                    <div class="pull-left w-15"><span>Mã GD</span></div>
                    <div class="pull-left w-15"><span>Ngày/Giờ</span></div>
                    <div class="pull-left w-20"><span>Tiêu đề</span></div>
                    <div class="pull-left w-15"><span>Loại giao dịch</span></div>
                    <div class="pull-left w-15"><span>Tình trạng</span></div>
                    <div class="pull-left w-20"><span>Số tiền</span></div>
                </div>
                <?php
                if(count($transactions) > 0) {
                    foreach ($transactions as $transaction) {
                        $amount = $transaction->amount;
                        ?>
                        <div class="clearfix tbl-emu">
                            <div class="pull-left w-15"><span><?=$transaction->id?></span></div>
                            <div class="pull-left w-15"><span><?=date('d/m/Y, H:i')?></span></div>
                            <div class="pull-left w-20"><span><a href="#" class="color-cd"><?=\vsoft\ec\models\EcTransactionHistory::getObjectType($transaction->object_type)." ".Yii::t('ec', 'Transaction')?></a></span></div>
                            <div class="pull-left w-15"><span><?=\vsoft\ec\models\EcTransactionHistory::getActionDetail($transaction->action_detail)?></span></div>
                            <div class="pull-left w-15"><span class="color-cd"><?=\vsoft\ec\models\EcTransactionHistory::getTransactionStatus($transaction->status)?></span></div>
                            <div class="pull-left w-20"><span><?= $amount > 1 ? $amount." Keys" : $amount." Key" ?></span></div>
                        </div>
                    <?php }
                } else {?>
                <div class="clearfix tbl-emu">
                    <div class="text-center"><span>Không có giao dịch.</span></div>
                </div>
                <!-- <div class="wrap-tr-each clearfix">
                    <div class="clearfix tbl-emu">
                        <div class="pull-left w-15"><span>232323</span></div>
                        <div class="pull-left w-15"><span>20/03/2016</span></div>
                        <div class="pull-left w-20"><span>45, Đường Đỗ Xuân Hợp, Phường Phước Long</span></div>
                        <div class="pull-left w-15"><span>Push</span></div>
                        <div class="pull-left w-15"><span class="color-cd">done</span></div>
                        <div class="pull-left w-20"><span>20.000 vnđ</span></div>
                    </div>
                    <div class="clearfix tbl-emu">
                        <div class="pull-left w-15"><span>232323</span></div>
                        <div class="pull-left w-15"><span>20/03/2016</span></div>
                        <div class="pull-left w-20"><span>45, Đường Đỗ Xuân Hợp, Phường Phước Long</span></div>
                        <div class="pull-left w-15"><span>Push</span></div>
                        <div class="pull-left w-15"><span class="color-cd">done</span></div>
                        <div class="pull-left w-20"><span>20.000 vnđ</span></div>
                    </div>
                    <div class="clearfix tbl-emu">
                        <div class="pull-left w-15"><span>232323</span></div>
                        <div class="pull-left w-15"><span>20/03/2016</span></div>
                        <div class="pull-left w-20"><span>45, Đường Đỗ Xuân Hợp, Phường Phước Long</span></div>
                        <div class="pull-left w-15"><span>Push</span></div>
                        <div class="pull-left w-15"><span class="color-cd">done</span></div>
                        <div class="pull-left w-20"><span>20.000 vnđ</span></div>
                    </div>
                    <div class="clearfix tbl-emu">
                        <div class="pull-left w-15"><span>232323</span></div>
                        <div class="pull-left w-15"><span>20/03/2016</span></div>
                        <div class="pull-left w-20"><span>45, Đường Đỗ Xuân Hợp, Phường Phước Long</span></div>
                        <div class="pull-left w-15"><span>Push</span></div>
                        <div class="pull-left w-15"><span class="color-cd">done</span></div>
                        <div class="pull-left w-20"><span>20.000 vnđ</span></div>
                    </div>
                </div> -->
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
    });
</script>