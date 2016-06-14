<?php
use frontend\models\RecoveryForm;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$model = Yii::createObject([
    'class'    => RecoveryForm::className(),
    'scenario' => 'reset',
]);
?>
<div class="title-fixed-wrap container">
    <div class="reset-pass clearfix">
        <div class="title-top">Lấy lại mật khẩu</div>
        <div class="frm-reset">
            <?php $form = ActiveForm::begin([
                'id' => 'recover-form',
                'action' => Url::current(),
                'options'=>['class' => 'frmIcon']
            ]); ?>
            <div class="form-group">
                <p class="fs-14">
                    <?=Yii::t('profile','New password')?>
                </p>
                <br>
                <?= $form->field($model, 'password')->passwordInput(['class'=>'form-control', 'placeholder'=>Yii::t('user', 'Password')])->label(false) ?>
                <em class="icon-envelope-open"></em>
            </div>
            <div class="footer-modal clearfix">
                <button type="button" class="btn-common btn-recover"><?=Yii::t('user','Update')?></button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        var timer = 0;
        $(document).on('click', '.btn-recover', function(){
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
                            location.href = '/';
                        }else if(data.statusCode == 404){
                            var arr = [];
                            $.each(data.parameters, function(idx, val){
                                var element = 'recover-form-'+idx;
                                arr[element] = val;
                            });
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