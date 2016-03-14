<?php
use frontend\models\RecoveryForm;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$model = Yii::createObject([
    'class'    => RecoveryForm::className(),
    'scenario' => 'request',
]);
?>
<div class="frmManualReset page-forgot" id="frmReset">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3><?=Yii::t('user', 'Forgot password')?></h3>
            </div>
            <div class="modal-body">
                <div class="wrap-modal clearfix">

                    <?php $form = ActiveForm::begin([
                        'id' => 'reset-password-form',
                        'action' => Url::to(['/member/forgot']),
                        'options'=>['class' => 'frmIcon']
                    ]); ?>
                        <div class="form-group">
                            <input type="password" style="display:none">
                            <?= $form->field($model, 'email')->textInput(['class'=>'form-control', 'placeholder'=>Yii::t('user', 'Email')])->label(false) ?>
                        </div>
                        <div class="footer-modal clearfix">
                            <button type="button" class="btn btn-primary btn-common btn-reset-password"><?=Yii::t('user', 'Send')?></button>
                        </div>
                    <?php ActiveForm::end(); ?>
<!--                    <div class="regis-login-link">-->
<!--                        <p>New to Met Vuong? <a class="showPopup" href="#" data-toggle="modal" data-target="#frmRegister">Sign up</a></p>-->
<!--                        <p>Already have an account? <a class="showPopup" href="#" data-toggle="modal" data-target="#frmLogin">Sign in here</a></p>-->
<!--                    </div>-->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#recovery-form-email').focus();
        $(document).on('click', '.frmManualReset .btn-reset-password', function(){
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
                            $('ul.menu-home').prepend('<li><a data-method="post" href="<?=Url::to(['/member/logout'])?>"><em class="icon-user"></em>' + data.parameters.username + '</a></li>');
                            location.href = '<?=Yii::$app->getUser()->getReturnUrl();?>';
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