<?php
use frontend\models\LoginForm;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$model = Yii::createObject(LoginForm::className());
?>
<div class="signin-signup">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#tab-signin" aria-controls="tab-signin" role="tab" data-toggle="tab">Đăng nhập</a><span class="line-center"></span></li>
        <li role="presentation"><a href="#tab-signup" aria-controls="tab-signup" role="tab" data-toggle="tab">Đăngký</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="tab-signin">
            <div class="signin">
                <?= $this->render('/member/_partials/login'); ?>
                <p class="pdT-10">Bạn đã có tài khoản chưa ? <a class="cl000 text-deco link-regis"> Đăng ký ngay</a></p>

            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="tab-signup">
            <div class="signup">
                <?= $this->render('/member/_partials/signup'); ?>
                <p class="pdT-10">Bạn đã có tài khoản rồi ? <a class="cl000 text-deco link-login" href="#"> Đăng nhập ngay</a></p>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        var timer = 0;
        $(document).on('click', '.signin .btn-login', function () {
            var _this = $(this);
            clearTimeout(timer);
            timer = setTimeout(function () {
                $.ajax({
                    type: "post",
                    dataType: 'json',
                    url: $('#login-form').attr('action'),
                    data: $('#login-form').serializeArray(),
                    success: function (data) {
                        if (data.statusCode == 200) {
                            $('a[data-target="#frmRegister"]').parent().remove();
                            $('a[data-target="#frmLogin"]').parent().remove();
                            $('ul.menu-home').prepend('<li><a data-method="post" href="<?=Url::to(['/user-management/index'])?>"><em class="icon-user"></em>' + data.parameters.username + '</a></li><li><a data-method="post" href="<?=\yii\helpers\Url::to(['/member/logout'])?>"><em class="icon-logout"></em><?=Yii::t('user', 'Logout')?></a></li>');
                            location.href = '<?=Yii::$app->getUser()->getReturnUrl();?>';
                        } else if (data.statusCode == 404) {
                            var arr = [];
                            $.each(data.parameters, function (idx, val) {
                                var element = 'login-form-' + idx;
                                arr[element] = val;
                            })
                            $('#login-form').yiiActiveForm('updateMessages', arr, true);
                        } else {
                            _this.html('Try again !');
                        }
                    }
                });
            }, 500);
            return false;
        });

        $('.signin #login-form input').keypress(function (e) {
            if (e.which == 13) {
                $('.signin #login-form .btn-login').click();
            }
        });
    });
</script>