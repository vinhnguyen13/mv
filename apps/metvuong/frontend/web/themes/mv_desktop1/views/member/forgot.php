<?php
use frontend\models\RecoveryForm;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$model = Yii::createObject([
    'class'    => RecoveryForm::className(),
    'scenario' => 'request',
]);
?>
<div class="title-fixed-wrap container">
    <div class="forgot-pass clearfix">
        <div class="title-top"><?=Yii::t('user', 'Forgot password')?></div>
        <div class="frm-forgot">
            <?php $form = ActiveForm::begin([
                'id' => 'reset-password-form',
                'action' => Url::to(['/member/forgot']),
                'options'=>['class' => 'frmIcon']
            ]); ?>
                <div class="form-group">
                    <p class="fs-14">
                        <?=Yii::t('user','Để lấy lại mật khẩu bạn vui lòng nhập địa chỉ email của mình vào đây.')?>
                    </p>
                    <br>
                    <?= $form->field($model, 'email')->textInput(['class'=>'form-control', 'placeholder'=>Yii::t('user', 'Email')])->label(false) ?>
                </div>
                <div class="footer-modal clearfix">
                    <button type="button" class="btn-common btn-reset-password"><?=Yii::t('user', 'Send')?></button>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#recovery-form-email').focus();
        $(document).on('click', '.btn-reset-password', function(){
            var _this = $(this);
            var recovery_email = $('#recovery-form-email').val();
            $('body').loading();
            if(recovery_email !== '') {
                $.ajax({
                    type: "post",
                    dataType: 'json',
                    url: $('#reset-password-form').attr('action'),
                    data: $('#reset-password-form').serializeArray(),
                    success: function (data) {
                        if (data.statusCode == 200) {
                            $('body').loading({done: true});
                            setTimeout(function () {
                                $('body').alertBox({
                                    txt: "<?=Yii::t('send_email', 'Email has been sent to ')?> "+recovery_email+""
                                });
                            },500);
                        } else if (data.statusCode == 404) {
                            var arr = [];
                            $.each(data.parameters, function (idx, val) {
                                var element = 'reset-password-form-' + idx;
                                arr[element] = val;
                            });
                            $('#reset-password-form').yiiActiveForm('updateMessages', arr, true);
                        } else {
                            _this.html('Try again !');
                        }
                    }
                });
            } else {
                $('body').loading({done: true});
                $('#recovery-form-email').focus();
            }
            return false;
        });

        $('.frmManualReset #reset-password-form input').keypress(function (e) {
            if (e.which == 13) {
                $('.frmManualReset #reset-password-form .btn-reset-password').click();
            }
        });
    });
</script>