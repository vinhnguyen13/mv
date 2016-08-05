<?php
use yii\helpers\Url;
use yii\helpers\Html;
use frontend\models\M2Event;
use frontend\models\RegistrationForm;
?>
<?php if(!empty(Yii::$app->params['register-in-time']) && (Yii::$app->user->isGuest && empty($_COOKIE['rit']))): ?>
    <div id="popup-campain" class="modal popup-common" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="wrap-popup">
                        <div class="title-popup">
                            Thông báo
                        </div>
                        <div class="inner-popup">
                            <?php $model = Yii::createObject(RegistrationForm::className()); ?>
                            <form id="register-in-time" action="<?= Url::to(['/event/register-in-time']) ?>" method="post">
                                <p class="mgB-15"><?= sprintf(Yii::t('event', 'Đăng ký ngay bây giờ để nhận %s dùng cho việc đăng tin và boost tin lên top.'), '<span class="font-600 text-decor">' . M2Event::REGISTER_COUPON . ' Keys</span>') ?></p>
                                <div class="field-wrap">
                                    <p class="mgB-5 font-600"><?= $model->getAttributeLabel('email') ?></p>
                                    <?= Html::activeTextInput($model, 'email', ['class' => 'form-control', 'placeholder' => Yii::t('user', 'Email')]) ?>
                                    <div class="error"></div>
                                </div>
                                <div class="field-wrap">
                                    <p class="mgB-5 font-600 mgT-15"><?= $model->getAttributeLabel('password') ?></p>
                                    <?= Html::activePasswordInput($model, 'password', ['class' => 'form-control', 'placeholder' => Yii::t('user', 'Password')]) ?>
                                    <div class="error"></div>
                                </div>
                                <div class="field-wrap">
                                    <p class="mgB-5 font-600 mgT-15"><?= Yii::t('user', 'Nhập lại mật khẩu') ?></p>
                                    <input id="register-form-password-confirm" name="passwordConfirm" type="password" class="form-control" placeholder="<?= Yii::t('user', 'Nhập lại mật khẩu') ?>">
                                    <div class="error"></div>
                                </div>
                                <div class="field-wrap">
                                    <p class="mgB-5 font-600 mgT-15"><?= Yii::t('ad', 'Mobile') ?></p>
                                    <input id="register-form-mobile" name="mobile" type="text" class="form-control" placeholder="<?= Yii::t('ad', 'Mobile') ?>" maxlength="11">
                                    <div class="error"></div>
                                </div>
                                <div class="text-center mgT-15">
                                    <button type="button" class="btn-common btn-bd-radius btn-cancel" aria-label="Close">Bỏ Qua</button>
                                    <button type="submit" class="btn-common btn-bd-radius">Nhận Keys</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            var popupCampain = $('#popup-campain');

            popupCampain.modal({backdrop: 'static', keyboard: false});
            popupCampain.find('.btn-cancel').click(function(){
                popupCampain.modal('hide');
                setServerCookie('rit', 1);
            });
            $('#register-in-time').submit(function(e){
                e.preventDefault();
                var self = $(this);

                var emailEl = $('#register-form-email');
                var passwordEl = $('#register-form-password');
                var confirmPasswordEl = $('#register-form-password-confirm');
                var mobileEl = $('#register-form-mobile');

                if(!emailEl.val()) {
                    showError(emailEl, lajax.t('Vui lòng nhập địa chỉ email'));
                } else if(!validateEmail(emailEl.val())) {
                    showError(emailEl, lajax.t('Địa chỉ email không hợp lệ'));
                } else {
                    emailEl.closest('.field-wrap').removeClass('has-error');
                }

                if(!passwordEl.val()) {
                    showError(passwordEl, lajax.t('Vui lòng nhập mật khẩu'));
                } else if(passwordEl.val().length < 6) {
                    showError(passwordEl, lajax.t('Mật khẩu phải chứa ít nhất 6 ký tự'));
                } else {
                    passwordEl.closest('.field-wrap').removeClass('has-error');
                }
                if(confirmPasswordEl.val() != passwordEl.val()) {
                    showError(confirmPasswordEl, '<?= \Yii::t('user', 'Mật khẩu nhập lại không khớp với mật khẩu ở trên') ?>');
                } else {
                    confirmPasswordEl.closest('.field-wrap').removeClass('has-error');
                }

                if(mobileEl.val()) {
                    if(!isDigit(mobileEl.val())) {
                        showError(mobileEl, '<?= \Yii::t('user', 'Số di động không hợp lệ') ?>');
                    } else if(mobileEl.val().length < 7 || mobileEl.val().length > 11) {
                        showError(mobileEl, '<?= \Yii::t('user', 'Số di động phải từ 7 đến 11 số') ?>');
                    } else {
                        mobileEl.closest('.field-wrap').removeClass('has-error');
                    }
                }

                if(popupCampain.find('.has-error').length == 0) {
                    var body = $('body').loading();

                    $.ajax({
                        url: self.attr('action'),
                        data: self.serialize(),
                        method: 'POST',
                        success: function(r) {
                            body.loading({done: true});
                            if(r.statusCode == 200) {
                                setServerCookie('rit', 2);
                                location.reload();
                            } else {
                                for(var i in r.parameters) {
                                    showError($('#register-form-' + i), r.parameters[i][0]);
                                }
                            }
                        }
                    });
                }
            });

            function validateEmail(email) {
                var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            };

            function isDigit(digit) {
                return /^\d+$/.test(digit);
            };

            function showError(el, message) {
                var fieldWrap = el.closest('.field-wrap').addClass('has-error');
                fieldWrap.find('.error').text(message);
            }
        });
    </script>
<?php endif; ?>