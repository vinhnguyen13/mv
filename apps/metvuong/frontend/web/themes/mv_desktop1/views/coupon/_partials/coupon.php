<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 6/23/2016
 * Time: 1:28 PM
 */
use vsoft\coupon\models\CouponEvent;
$event = CouponEvent::find()->andWhere(['<','start_date',time()])->andWhere(['>','end_date',time()])->one();
if(!empty($event)) {
    ?>
    <div class="title-box"><?=Yii::t('coupon', 'You have coupon ?')?></div>
    <div class="input-couple mgB-20">
        <p class="mgB-10"><?=Yii::t('coupon', 'Input code')?>:</p>
        <input type="text" class="d-ib w-30 mgR-15 form-control code">
        <button type="submit" class="btn-common submit coupon"><?=Yii::t('coupon', 'Send')?></button>
    </div>
    <script>
        $(document).ready(function () {
            $(document).on('click', '.input-couple .coupon', function (e) {
                if($('.code').val()){
                    $('.input-couple').loading({full: false});
                    $.ajax({
                        type: "post",
                        dataType: 'json',
                        url: chat_url + '?username=' + user,
                        success: function (data) {

                        }
                    });
                }
            });
        });
    </script>
    <?php
}
?>