<?php
use frontend\models\RegistrationForm;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$model = Yii::createObject(RegistrationForm::className());
?>
<div class="modal fade frmPopupRegister" id="frmRegister" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="icon"></span>
                </button>
                <h3>Đăng ký</h3>
            </div>
            <div class="modal-body">
                <div class="wrap-modal clearfix">
                    <!-- <p class="txt-title">Hãy bắt đầu với một tài khoản miễn phí</p> -->
                    
                    <?php $form = ActiveForm::begin([
                        'id' => 'signup-form',
                        'action' => Url::to(['/member/signup']),
                        'options'=>['class' => 'frmIcon']
                    ]); ?>
                        <div class="form-group">
                            <input type="password" style="display:none">
                            <?= $form->field($model, 'email')->textInput(['class'=>'form-control', 'placeholder'=>Yii::t('user', 'Email')])->label(false) ?>
                        </div>
                        <div class="form-group forgot-pass">
                            <?= $form->field($model, 'password')->passwordInput(['class'=>'form-control', 'placeholder'=>Yii::t('user', 'Password')])->label(false) ?>
                            <a class="showPopup" href="#" data-toggle="modal" data-target="#frmForgot">Forgot?</a>
                        </div>
                        <div class="checkbox"> <label> <input type="checkbox"> Chấp nhận <a href="#">điều khoản</a> sử dụng của <a href="#">metvuong.com</a></label> </div>
                        <div class="footer-modal clearfix">
                            <button type="button" class="btn btn-primary btn-common btn-register">Đăng ký</button>
                        </div>
                    <?php ActiveForm::end(); ?>
                    <?= \vsoft\user\widgets\Connect::widget([
                        'baseAuthUrl' => ['/user/security/auth'],
                        'groupTitle' => Yii::t('user', '')
                    ]) ?>
                    <div class="login-link">Already have an account? <a class="showPopup" href="#" data-toggle="modal" data-target="#frmLogin">Sign in here</a></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        var timer = 0;
        $(document).on('click', '.frmPopupRegister .btn-register', function(){
            clearTimeout(timer);
            timer = setTimeout(function() {
                $.ajax({
                    type: "post",
                    dataType: 'json',
                    url: $('#signup-form').attr('action'),
                    data: $('#signup-form').serializeArray(),
                    success: function(data) {
                        if(data.statusCode == 200){
                            $('a[data-target="#frmRegister"]').parent().remove();
                            $('a[data-target="#frmLogin"]').parent().remove();
                            $('ul.menu-home').prepend('<li><a data-method="post" href="<?=Url::to(['/member/logout'])?>"><em class="icon-user"></em>' + data.parameters.username + '</a></li>');
                            $('#frmRegister').modal('toggle');
                        }else if(data.statusCode == 404){
                            var arr = [];
                            $.each(data.parameters, function(idx, val){
                                var element = 'register-form-'+idx;
                                arr[element] = val;
                            })
                            $('#signup-form').yiiActiveForm('updateMessages', arr, true);
                        }else{
                            _this.html('Try again !');
                        }
                    }
                });
            }, 500);
            return false;
        });

        $('.frmPopupRegister #signup-form input').keypress(function (e) {
            if (e.which == 13) {
                $('.frmPopupRegister #signup-form .btn-register').click();
            }
        });
    });
</script>