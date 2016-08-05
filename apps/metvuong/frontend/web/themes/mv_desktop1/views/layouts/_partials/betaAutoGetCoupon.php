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
if(!empty($event) && !Yii::$app->user->isGuest) {
    $code = CouponCode::find()->where(['cp_event_id'=>$event->id])->one();
    $history = CouponHistory::find()->where(['cp_code_id' => $code->id, 'user_id' => Yii::$app->user->id])->asArray()->one();
    if(empty($history) && ($code->check())) {
        ?>
        <div id="coupon-dialog-auto" class="modal fade popup-common" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="wrap-popup">
                            <div class="title-popup">
                                <?= Yii::t('coupon', 'Coupon') ?>
                            </div>
                            <div class="inner-popup">
                                <div class="wrap-coupon bg-warning">
                                    <p class="mgB-5"><?=Yii::t('coupon', 'MetVuong launch Beta, the system will give you {amount} Keys. You can use Keys to: Post listing, Boost listing, View Dashboard', ['amount'=>'<b>'.intval($code->amount).'</b>'])?></p>
                                </div>
                                <div class="input-couple mgB-20 clearfix" style="display: none;">
                                    <?php
                                    $f = ActiveForm::begin([
                                        'id' => 'frmCouponAuto',
                                        'enableAjaxValidation' => false,
                                        'enableClientValidation' => true,
                                        'action' => ''
                                    ]);
                                    ?>
                                    <input name="code" type="text" class="d-ib form-control code" value="<?=$code->code?>">
                                    <?php $f->end(); ?>
                                </div>
                            </div>
                            <div class="bottom-popup">
                                <div class="text-right">
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
                $('#coupon-dialog-auto .inner-popup .alert').remove();
                $('#coupon-dialog-auto').modal('toggle');
                //$('#coupon-dialog-auto .modal-dialog').css('width', '700px');
                $(document).on('click', '#coupon-dialog-auto .coupon', function (e) {
                    $('.input-couple').loading({full: false});
                    $('#coupon-dialog-auto .inner-popup .alert').remove();
                    $.ajax({
                        type: "post",
                        dataType: 'json',
                        url: '<?=\yii\helpers\Url::to(['/coupon/process'])?>',
                        data: $('#frmCouponAuto').serialize(),
                        success: function (data) {
                            if (data.error_code == 0) {
                                var timer = 0;
                                timer = setTimeout(function () {
                                    $('#coupon-dialog-auto').modal('toggle');
                                    location.reload();
                                }, 1000);
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