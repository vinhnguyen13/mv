<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 6/23/2016
 * Time: 1:28 PM
 */
use vsoft\coupon\models\CouponEvent;
use vsoft\coupon\models\CouponCode;
use vsoft\coupon\models\CouponHistory;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\View;

Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/clipboard.min.js', ['position'=>View::POS_HEAD]);

$event = CouponEvent::find()->where(['type'=>CouponEvent::TYPE_PUBLIC])->andWhere(['<','start_date',time()])->andWhere(['>','end_date',time()])->one();
if(!empty($event)) {
    $code = CouponCode::find()->where(['cp_event_id'=>$event->id])->one();
    $history = CouponHistory::find()->where(['cp_code_id' => $code->id, 'user_id' => Yii::$app->user->id])->asArray()->one();
    if(empty($history) && ($code->check())) {
        ?>
        <a href="javascript:;" class="d-ib btn pdT-5 pdB-5 font-600 fs-13 btn-coupon"><?=Yii::t('coupon', 'Coupon')?></a>
        <div id="coupon-dialog" class="modal fade popup-common" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="wrap-popup">
                            <div class="title-popup">
                                <?= Yii::t('coupon', 'Coupon') ?>
                            </div>
                            <a href="#" class="btn-close close" data-dismiss="modal" aria-label="Close"><span class="icon icon-close"></span></a>
                            <div class="inner-popup">
                                <div class="input-couple mgB-20 clearfix">
                                    <div class="wrap-coupon bg-warning">
                                        <p class="mgB-5"><?=Yii::t('coupon', 'MetVuong launch Beta, the system will give you {amount} Keys', ['amount'=>'<b>'.intval($code->amount).'</b>'])?></p>
                                        <p class="mgB-5">
                                            <?=Yii::t('coupon', 'Copy and paste your code {code} and paste below to get Keys from MetVuong.', ['code'=>'<a href="#" data-toggle="tooltip" data-placement="bottom" title="Sao chép key" data-title-success="Đã sao chép" class="btn-copy" data-clipboard-text="'.$code->code.'"><b>'.$code->code.'</b></a>'])?>
                                        </p>
                                    </div>
                                    <?php
                                    $f = ActiveForm::begin([
                                        'id' => 'frmCoupon',
                                        'enableAjaxValidation' => false,
                                        'enableClientValidation' => true,
                                        'action' => ''
                                    ]);
                                    ?>
                                    <input name="code" type="text" class="d-ib form-control code">
                                    <?php $f->end(); ?>
                                </div>
                            </div>
                            <div class="bottom-popup">
                                <div class="text-right">
                                    <a href="#" class="btn btn-cancel btn-close close" data-dismiss="modal"
                                       aria-label="Close"><?= Yii::t('coupon', 'Cancel') ?></a>
                                    <a href="#" class="btn coupon"><?= Yii::t('coupon', 'OK') ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function () {

                $('.popup-common').appendTo('body');

                var clipboard = new Clipboard('.btn-copy');

                clipboard.on('success', function (e) {
                    var txtSuccess = $(e.trigger).data('titleSuccess');
                    $('.btn-copy').tooltip("show");
                    $('.tooltip .tooltip-inner').text(txtSuccess);
                    setTimeout(function () {
                        $('.btn-copy').tooltip("destroy");
                    }, 500);
                });

                $('.btn-coupon').click(function () {
                    $('#coupon-dialog .inner-popup .alert').remove();
                    $('#coupon-dialog').modal('toggle');
                    //$('#coupon-dialog .modal-dialog').css('width', '700px');
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
                            if (data.error_code == 0) {
                                $('#coupon-dialog .inner-popup').append('<div class="alert alert-info">' + data.result + '</div>');
                                $('.code').val('');
                                var timer = 0;
                                timer = setTimeout(function () {
                                    $('#coupon-dialog').modal('toggle');
                                    location.reload();
                                }, 1000);
                            } else if (data.error_code != 0) {
                                $('#coupon-dialog .inner-popup').append('<div class="alert alert-info">' + data.error_message + '</div>');
                            }
                            $('.code').focus();
                            $('body').loading({done: true});
                        }
                    });
                    return false;
                });
            });
        </script>
        <?php
    }
}
?>