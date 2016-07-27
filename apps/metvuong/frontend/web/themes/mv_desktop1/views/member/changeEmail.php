<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;

$model = Yii::createObject([
    'class'    => \frontend\models\ProfileForm::className(),
    'scenario' => 'updateprofile',
]);

$model->public_email = $new_email;
?>
<div class="title-fixed-wrap container">
    <div class="forgot-pass clearfix">
        <div class="title-top"><?=Yii::t('user', 'Change user email')?></div>
        <div class="frm-forgot">
            <?php $form = ActiveForm::begin([
                'id' => 'change-email-form',
                'action' => Url::to(['/member/confirm-change-email']),
                'options'=>['class' => 'frmIcon']
            ]); ?>
            <div class="form-group">
                <p class="fs-14">
                    <?=Yii::t('user','Email đăng nhập sẽ được thay đổi bằng')?>
                </p>
                <br>
                <?= $form->field($model, 'public_email')->textInput(['class'=>'form-control public_email', 'placeholder'=>Yii::t('user', 'Email')])->label(false) ?>
            </div>

            <div class="footer-modal clearfix">
                <button type="button" class="btn-common btn-change-email"><?=Yii::t('user', 'Ok')?></button>
            </div>
            <div class="footer-modal clearfix">
                <button type="button" class="btn-common btn-cancel"><?=Yii::t('user', 'Cancel')?></button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('.public_email').focus();
        $('.btn-change-email').click(function(){
            var _this = $(this);
            var new_email = $('#profile-form-public_email').val();
            l(new_email);
            $('body').loading();
            if(new_email) {
                $.ajax({
                    type: "post",
                    dataType: 'json',
                    url: $('#change-email-form').attr('action'),
                    data: $('#change-email-form').serializeArray(),
                    success: function (data) {
                        if (data.statusCode == 200) {
                            $('body').loading({done: true});
                            $('body').alertBox({
                                txt: "<?=Yii::t('profile', 'Email đăng nhập đã được thay đổi thành')?> "+new_email,
                                duration: 3000
                            });

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
        $('.btn-cancel').click(function(){
            window.location = '/';
        });

    });
</script>