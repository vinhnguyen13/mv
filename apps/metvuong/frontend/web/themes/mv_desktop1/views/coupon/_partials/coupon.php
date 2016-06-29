<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 6/23/2016
 * Time: 1:28 PM
 */
use vsoft\coupon\models\CouponEvent;
use vsoft\coupon\models\CouponCode;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\View;

Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/clipboard.min.js', ['position'=>View::POS_END]);

$event = CouponEvent::find()->andWhere(['<','start_date',time()])->andWhere(['>','end_date',time()])->one();
if(!empty($event)) {
    $code = CouponCode::find()->where(['cp_event_id'=>$event->id, 'type'=>CouponCode::TYPE_MANY])->one();
    ?>
    <div class="wrap-coupon">
        MetVuong hiện đang chạy Beta, hệ thống sẽ tặng <b><?=intval($code->amount);?></b> Keys <br/>
        <a data-toggle="tooltip" data-placement="bottom" title="Sao chép đường dẫn" data-title-success="Đã sao chép" class="btn-copy" data-clipboard-text="<?=$code->code;?>">Copy mã của bạn <b><?=$code->code;?></b></a><br/>
        Sau đó <a href="javascript:;" class="btn-coupon"><b>click vào đây</b></a>, dán mã của bạn vào để nhận Keys miễn phí từ MetVuong. <br/>
    </div>
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
            var clipboard = new Clipboard('.btn-copy');

            clipboard.on('success', function(e) {
                var txtSuccess = $(e.trigger).data('titleSuccess');
                $('.btn-copy').tooltip("show");
                $('.tooltip .tooltip-inner').text(txtSuccess);
                setTimeout(function () {
                    $('.btn-copy').tooltip("destroy");
                },500);
            });

            $('.btn-coupon').click(function () {
                $('#coupon-dialog .inner-popup .alert').remove();
                $('#coupon-dialog').modal('toggle');
            });
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