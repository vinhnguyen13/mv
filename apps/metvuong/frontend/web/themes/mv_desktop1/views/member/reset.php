<?php
use frontend\models\RecoveryForm;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$model = Yii::createObject([
    'class'    => RecoveryForm::className(),
    'scenario' => 'reset',
]);
?>
<div class="frmManualForgot" id="frmForgot">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            </div>
            <div class="modal-body">
                <div class="wrap-modal clearfix">
                    <h3>Lấy lại mật khẩu</h3>
                    <?php $form = ActiveForm::begin([
                        'id' => 'recover-form',
                        'action' => Url::current(),
                        'options'=>['class' => 'frmIcon']
                    ]); ?>
                    <div class="form-group">
                        <input type="password" style="display:none">
                        <?= $form->field($model, 'password')->passwordInput(['class'=>'form-control', 'placeholder'=>Yii::t('user', 'Password')])->label(false) ?>
                        <em class="icon-envelope-open"></em>
                    </div>
                    <div class="footer-modal clearfix">
                        <div class="pull-right">
                            <a class="showPopup" href="#" data-toggle="modal" data-target="#frmRegister">Đăng ký</a>
                            <a class="showPopup" href="#" data-toggle="modal" data-target="#frmLogin">Đăng nhập</a>
                        </div>
                        <button type="button" class="btn btn-primary btn-common btn-recover">Send</button>
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
        $(document).on('click', '.frmManualForgot .btn-recover', function(){
            var _this = $(this);
            clearTimeout(timer);
            timer = setTimeout(function() {
                $.ajax({
                    type: "post",
                    dataType: 'json',
                    url: $('#recover-form').attr('action'),
                    data: $('#recover-form').serializeArray(),
                    success: function(data) {
                        if(data.statusCode == 200){
                            $('ul.menu-home').prepend('<li><a data-method="post" href="<?=Url::to(['/member/logout'])?>"><em class="icon-user"></em>' + data.parameters.username + '</a></li>');
                            location.href = '/';
                        }else if(data.statusCode == 404){
                            var arr = [];
                            $.each(data.parameters, function(idx, val){
                                var element = 'recover-form-'+idx;
                                arr[element] = val;
                            })
                            $('#recover-form').yiiActiveForm('updateMessages', arr, true);
                        }else{
                            _this.html('Try again !');
                        }
                    }
                });
            }, 500);
            return false;
        });

        $('.frmManualForgot #recover-form input').keypress(function (e) {
            if (e.which == 13) {
                $('.frmManualForgot #recover-form .btn-recover').click();
            }
        });
    });
</script>