<?php
use frontend\models\LoginForm;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$model = Yii::createObject(LoginForm::className());
?>
<div class="frmManualLogin" id="frmLogin">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            </div>
            <div class="modal-body">
                <div class="wrap-modal clearfix">
                    <h3>Đăng nhập</h3>
                    <!-- <p class="txt-title">Hãy bắt đầu với một tài khoản miễn phí</p> -->
                    <?= \vsoft\user\widgets\Connect::widget([
                        'baseAuthUrl' => ['/user/security/auth'],
                        'groupTitle' => Yii::t('user', 'Login by social')
                    ]) ?>
                    <?php $form = ActiveForm::begin([
                        'id' => 'login-form',
                        'action' => Url::to(['/member/login']),
                        'options'=>['class' => 'frmIcon']
                    ]); ?>
                        <div class="form-group">
                            <input type="password" style="display:none">
                            <?= $form->field($model, 'login')->textInput(['class'=>'form-control', 'placeholder'=>Yii::t('user', 'Email')])->label(false) ?>
                            <em class="icon-envelope-open"></em>
                        </div>
                        <div class="form-group">
                            <?= $form->field($model, 'password')->passwordInput(['class'=>'form-control', 'placeholder'=>Yii::t('user', 'Password')])->label(false) ?>
                            <em class="icon-lock"></em>
                        </div>
                        <div class="checkbox"> <?= $form->field($model, 'rememberMe')->checkbox() ?> </div>
                        <div class="footer-modal clearfix">
                            <div class="pull-right">
                                <a class="showPopup" href="#" data-toggle="modal" data-target="#frmRegister">Đăng ký</a>
                                <a class="showPopup" href="#" data-toggle="modal" data-target="#frmForgot">Quên mật khẩu ?</a>
                            </div>
                            <button type="button" class="btn btn-primary btn-common btn-login">Đăng nhập</button>
                        </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        var timer = 0;
        $(document).on('click', '.frmManualLogin .btn-login', function(){
            var _this = $(this);
            clearTimeout(timer);
            timer = setTimeout(function() {
                $.ajax({
                    type: "post",
                    dataType: 'json',
                    url: $('#login-form').attr('action'),
                    data: $('#login-form').serializeArray(),
                    success: function(data) {
                        if(data.statusCode == 200){
                            $('a[data-target="#frmRegister"]').parent().remove();
                            $('a[data-target="#frmLogin"]').parent().remove();
                            $('ul.menu-home').prepend('<li><a data-method="post" href="<?=Url::to(['/member/logout'])?>"><em class="icon-user"></em>' + data.parameters.username + '</a></li>');
                            location.href = '<?=Yii::$app->getUser()->getReturnUrl();?>';
                        }else if(data.statusCode == 404){
                            var arr = [];
                            $.each(data.parameters, function(idx, val){
                                var element = 'login-form-'+idx;
                                arr[element] = val;
                            })
                            $('#login-form').yiiActiveForm('updateMessages', arr, true);
                        }else{
                            _this.html('Try again !');
                        }
                    }
                });
            }, 500);
            return false;
        });

        $('.frmManualLogin #login-form input').keypress(function (e) {
            if (e.which == 13) {
                $('.frmManualLogin #login-form .btn-login').click();
            }
        });
    });
</script>