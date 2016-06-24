<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 6/23/2016
 * Time: 1:28 PM
 */
use vsoft\coupon\models\CouponEvent;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$event = CouponEvent::find()->andWhere(['<','start_date',time()])->andWhere(['>','end_date',time()])->one();
if(!empty($event)) {
    ?>
    <div class="title-box"><?=Yii::t('coupon', 'You have coupon ?')?></div>
    <div class="input-couple mgB-20 clearfix">
        <?php
        $f = ActiveForm::begin([
            'id' => 'frmCoupon',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'action' => ''
        ]);
        ?>
        <p class="mgB-10"><?=Yii::t('coupon', 'Input code')?>:</p>
        <input name="code" type="text" class="d-ib form-control code">
        <button type="submit" class="btn-common submit coupon"><?=Yii::t('coupon', 'Send')?></button>
        <?php $f->end(); ?>
    </div>

    <div id="coupon-dialog" class="modal fade popup-common" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="wrap-popup">
                        <div class="title-popup">
                            <?=Yii::t('coupon', 'Coupon')?>
                        </div>
                        <div class="inner-popup">

                        </div>
                        <div class="bottom-popup">
                            <div class="text-right">
                                <a href="#" class="btn close" data-dismiss="modal" aria-label="Close"><?=Yii::t('coupon', 'OK')?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $(document).on('click', '.input-couple .coupon', function (e) {
                $('.input-couple').loading({full: false});
                $.ajax({
                    type: "post",
                    dataType: 'json',
                    url: '<?=\yii\helpers\Url::to(['/coupon/process'])?>',
                    data: $('#frmCoupon').serialize(),
                    success: function (data) {
                        $('#coupon-dialog .modal-dialog').css('width', '300px');
                        if(data.error_code == 0){
                            $('#coupon-dialog .inner-popup').html(data.result);
                            $('#coupon-dialog').modal('toggle');
                            $('.code').val('');
                        }else if(data.error_code != 0){
                            $('#coupon-dialog .inner-popup').html(data.error_message);
                            $('#coupon-dialog').modal('toggle');
                        }
                        $('.code').focus();
                        $('body').loading({done:true});
                    }
                });
                return false;
            });
        });
    </script>
    <?php
}
?>