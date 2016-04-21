<?php
use frontend\models\LoginForm;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$model = Yii::createObject(LoginForm::className());
?>
<div class="frmManualLogin page-login" id="">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Đăng nhập</h3>
            </div>
            <div class="modal-body">
                <div class="wrap-modal clearfix">
                    <!-- <p class="txt-title">Hãy bắt đầu với một tài khoản miễn phí</p> -->
                    <?php $form = ActiveForm::begin([
                        'id' => 'login-form',
                        'action' => Url::to(['/member/login']),
                        'options'=>['class' => 'frmIcon']
                    ]); ?>
                        <div class="form-group">
                            <input type="password" style="display:none">
                            <?= $form->field($model, 'login')->textInput(['class'=>'form-control', 'placeholder'=>Yii::t('user', 'Email')])->label(false) ?>
                        </div>
                        <div class="form-group forgot-pass">
                            <div class="form-group">
                                <?= $form->field($model, 'password')->passwordInput(['class'=>'form-control', 'placeholder'=>Yii::t('user', 'Password')])->label(false) ?>
                            </div>
                            <a class="showPopup" href="#" data-toggle="modal" data-target="#frmForgot">Quên mật khẩu ?</a>
                        </div>
                        <div class="checkbox"> <?= $form->field($model, 'rememberMe')->checkbox() ?> </div>
                        <div class="footer-modal clearfix">
                            <button type="button" class="btn btn-primary btn-common btn-login">Đăng nhập</button>
                        </div>
                    <?php ActiveForm::end(); ?>
                    <?= \vsoft\user\widgets\Connect::widget([
                        'baseAuthUrl' => ['/user/security/auth'],
                        'groupTitle' => Yii::t('user', '')
                    ]) ?>
                    <div class="regis-link">New to Met Vuong? <a class="showPopup" href="#" data-toggle="modal" data-target="#frmRegister">Create an account</a></div>
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
                    url: $('.frmManualLogin #login-form').attr('action'),
                    data: $('.frmManualLogin #login-form').serializeArray(),
                    success: function(data) {
                        if(data.statusCode == 200){
                            $('a[data-target="#frmRegister"]').parent().remove();
                            $('a[data-target="#frmLogin"]').parent().remove();
                            $('ul.menu-home').prepend('<li><a data-method="post" href="<?=Url::to(['/user-management/index'])?>"><em class="icon-user"></em>' + data.parameters.username + '</a></li><li><a data-method="post" href="<?=\yii\helpers\Url::to(['/member/logout'])?>"><em class="icon-logout"></em><?=Yii::t('user', 'Logout')?></a></li>');
                            location.href = '<?=Yii::$app->getUser()->getReturnUrl();?>';
                        }else if(data.statusCode == 404){
                            var arr = [];
                            $.each(data.parameters, function(idx, val){
                                var element = 'login-form-'+idx;
                                arr[element] = val;
                            })
                            $('.frmManualLogin #login-form').yiiActiveForm('updateMessages', arr, true);
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