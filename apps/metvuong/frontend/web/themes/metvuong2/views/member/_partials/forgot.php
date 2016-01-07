<?php

?>
<div class="modal fade" id="frmForgot" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="wrap-modal clearfix">
                    <h3>Lấy lại mật khẩu</h3>
                    <?= \vsoft\user\widgets\Connect::widget([
                        'baseAuthUrl' => ['/user/security/auth'],
                        'groupTitle' => Yii::t('user', 'Login by social')
                    ]) ?>
                    <form class="frmIcon" action="">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Email">
                            <em class="icon-envelope-open"></em>
                        </div>
                        <div class="footer-modal clearfix">
                            <div class="pull-right">
                                <a class="showPopup" href="#" data-toggle="modal" data-target="#frmRegister">Đăng ký</a>
                                <a class="showPopup" href="#" data-toggle="modal" data-target="#frmLogin">Đăng nhập</a>
                            </div>
                            <button type="button" class="btn btn-primary btn-common">Reset Pasword</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>