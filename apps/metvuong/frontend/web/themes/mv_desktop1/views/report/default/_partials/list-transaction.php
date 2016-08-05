<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 7/7/2016 1:19 PM
 */

use frontend\models\User;

$count_data = count($data) > 0 ? count($data) : 0;
$last_key = count($data) - 1;
if($count_data > 0) {
?>
    <div class="tbl-wrap">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php
                foreach ($data as $key => $val) {
                    $transaction = Yii::$app->db->cache(function() use($val){
                        return \frontend\models\Transaction::findOne($val["id"]);
                    });
                    $amount = $transaction->amount;
                    ?>
                    <div class="wrap-tr-each">
                        <div class="w-15"><span><?=$transaction->user->profile->getDisplayName()?></span></div>
                        <div class="w-20"><span><?=date('d/m/Y, H:i', $transaction->created_at)?></span></div>
                        <div class="w-15"><span><?=$transaction->getObjectType($transaction->object_type)?></span></div>
                        <div class="w-15"><span class="color-cd"><?=$transaction->getTransactionStatus($transaction->status)?></span></div>
                        <div class="w-15"><span><?= abs($amount) > 1 ? $amount." Keys" : $amount." Key" ?></span></div>
                        <div class="w-20"><span><?=$transaction->getNote($transaction->object_type)?></span></div>
                    </div>
                    <?php
                }?>
            </div>
        </div>
    </div>
<?php } ?>