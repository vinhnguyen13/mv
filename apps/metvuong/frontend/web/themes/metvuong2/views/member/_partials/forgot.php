<?php

?>
<div class="modal fade" id="frmForgot" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="icon"></span>
                </button>
                <h3>Lấy lại mật khẩu</h3>
            </div>
            <div class="modal-body">
                <div class="wrap-modal clearfix">
                    <form class="frmIcon" action="">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Email">
                        </div>
                        <div class="footer-modal clearfix">
                            <button type="button" class="btn btn-primary btn-common btn-reset-pass">Reset Pasword</button>
                        </div>
                    </form>
                    <?= \vsoft\user\widgets\Connect::widget([
                        'baseAuthUrl' => ['/user/security/auth'],
                        'groupTitle' => Yii::t('user', '')
                    ]) ?>
                    <div class="regis-login-link">
                        <p>New to Met Vuong? <a class="showPopup" href="#" data-toggle="modal" data-target="#frmRegister">Sign up</a></p>
                        <p>Already have an account? <a class="showPopup" href="#" data-toggle="modal" data-target="#frmLogin">Sign in here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

