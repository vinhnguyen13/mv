<?php
use frontend\models\LoginForm;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$model = Yii::createObject(LoginForm::className());
?>

<!-- <p class="txt-title">Hãy bắt đầu với một tài khoản miễn phí</p> -->
<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'action' => Url::to(['/member/login']),
    'options' => ['class' => 'frmIcon']
]); ?>
<div class="form-group">
    <input type="password" style="display:none">
    <?= $form->field($model, 'login')->textInput(['class' => 'form-control', 'placeholder' => Yii::t('user', 'Email')])->label(false) ?>
</div>
<div class="form-group forgot-pass">
    <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control', 'placeholder' => Yii::t('user', 'Password')])->label(false) ?>
    <a class="showPopup" href="<?=Url::to(['/member/forgot'])?>"><?=Yii::t('user', 'Forgot ?')?></a>
</div>
<div class="checkbox"> <?= $form->field($model, 'rememberMe')->checkbox() ?> </div>
<div class="footer-modal clearfix">
    <button type="button" class="btn btn-primary btn-common btn-login" id="btn-login"><?=Yii::t('user', 'Sign In')?></button>
</div>
<?php ActiveForm::end(); ?>

<?= \vsoft\user\widgets\Connect::widget([
    'baseAuthUrl' => ['/user/security/auth'],
    'groupTitle' => Yii::t('user', '')
]) ?>

