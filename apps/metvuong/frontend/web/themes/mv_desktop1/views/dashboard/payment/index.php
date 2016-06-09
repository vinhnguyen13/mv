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
            <table class="fixed_headers" cellspacing="0" cellpadding="0" border="0">
                <thead>
                    <tr>
                        <th class="w-10"><span>Mã GD</span></th>
                        <th class="w-20"><span>Ngày/Giờ</span></th>
                        <th class="w-20"><span>Tiêu đề</span></th>
                        <th class="w-20"><span>Loại giao dịch</span></th>
                        <th class="w-10"><span>Tình trạng</span></th>
                        <th class="w-20"><span>Số tiền</span></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if(count($transactions) > 0) {
                        foreach ($transactions as $transaction) {
                            $amount = $transaction->amount;
                            ?>
                            <tr>
                                <td><?=$transaction->id?></td>
                                <td><?=date('d/m/Y, H:i')?></td>
                                <td><a href="#" class="color-cd"><?=\vsoft\ec\models\EcTransactionHistory::getObjectType($transaction->object_type)." ".Yii::t('ec', 'Transaction')?></a></td>
                                <td><?=\vsoft\ec\models\EcTransactionHistory::getActionDetail($transaction->action_detail)?></td>
                                <td class="color-cd"><?=\vsoft\ec\models\EcTransactionHistory::getTransactionStatus($transaction->status)?></td>
                                <td><?= $amount > 1 ? $amount." Keys" : $amount." Key" ?> </td>
                            </tr>
                        <?php }
                    } else {?>
                    <tr>
                        <td class="text-center">Không có giao dịch.</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
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
        $('.deposit').click(function () {
            $.ajax({
                type: "get",
                dataType: 'html',
                url: '<?=yii\helpers\Url::to(['dashboard/create-transaction']) ?>',
                success: function (data) {
                    if(data)
                        window.location.reload();
                }
            });
        });
    });
</script>