<?php
use frontend\models\RegistrationForm;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$model = Yii::createObject(RegistrationForm::className());
?>
<?php $form = ActiveForm::begin([
    'id' => 'signup-form',
    'action' => Url::to(['/member/signup']),
    'options' => ['class' => 'frmIcon']
]); ?>
    <div class="form-group">
        <input type="password" style="display:none">
        <?= $form->field($model, 'email')->textInput(['class' => 'form-control', 'placeholder' => Yii::t('user', 'Email')])->label(false) ?>
    </div>
    <div class="form-group forgot-pass">
        <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control', 'placeholder' => Yii::t('user', 'Password')])->label(false) ?>
        <a class="showPopup" href="#" data-toggle="modal" data-target="#frmForgot">Forgot?</a>
    </div>
    <div class="checkbox"><label> <input type="checkbox"> Chấp nhận <a href="#">điều khoản</a> sử dụng của <a href="#">metvuong.com</a></label>
    </div>
    <div class="footer-modal clearfix">
        <button type="button" class="btn btn-primary btn-common btn-login" id="btn-register">Đăng ký</button>
    </div>
<?php ActiveForm::end(); ?>
<?= \vsoft\user\widgets\Connect::widget([
    'baseAuthUrl' => ['/user/security/auth'],
    'groupTitle' => Yii::t('user', '')
]) ?>