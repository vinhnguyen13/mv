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
    <div id="coupon-dialog" class="modal fade popup-common" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="wrap-popup">
                        <div class="title-popup">
                            <?=Yii::t('coupon', 'Coupon')?>
                        </div>
                        <a href="#" class="btn-close close" data-dismiss="modal" aria-label="Close"><span class="icon icon-close"></span></a>
                        <div class="inner-popup">
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
                                <?php $f->end(); ?>
                            </div>
                        </div>
                        <div class="bottom-popup">
                            <div class="text-right">
                                <a href="#" class="btn btn-close close" data-dismiss="modal" aria-label="Close"><?=Yii::t('coupon', 'Cancel')?></a>
                                <a href="#" class="btn coupon"><?=Yii::t('coupon', 'OK')?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $(document).on('click', '#coupon-dialog .coupon', function (e) {
                $('.input-couple').loading({full: false});
                $('#coupon-dialog .inner-popup .alert').remove();
                $.ajax({
                    type: "post",
                    dataType: 'json',
                    url: '<?=\yii\helpers\Url::to(['/coupon/process'])?>',
                    data: $('#frmCoupon').serialize(),
                    success: function (data) {
                        $('#coupon-dialog .modal-dialog').css('width', '300px');
                        if(data.error_code == 0){
                            $('#coupon-dialog .inner-popup').append('<div class="alert alert-info">'+data.result+'</div>');
                            $('.code').val('');
                            var timer = 0;
                            timer = setTimeout(function () {
                                $('#coupon-dialog').modal('toggle');
                            }, 500);
                        }else if(data.error_code != 0){
                            $('#coupon-dialog .inner-popup').append('<div class="alert alert-info">'+data.error_message+'</div>');
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