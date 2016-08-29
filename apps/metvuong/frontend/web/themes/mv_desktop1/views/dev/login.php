<?php
use frontend\models\LoginForm;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$model = Yii::createObject(LoginForm::className());
?>

<!-- <p class="txt-title">Hãy bắt đầu với một tài khoản miễn phí</p> -->
<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'options' => ['class' => 'frmIcon']
]); ?>
<div class="form-group">
    <input type="password" style="display:none">
    <?=\yii\helpers\Html::textInput('username')?>
</div>
<div class="form-group forgot-pass">
    <button type="submit" class="btn-primary btn-common btn-login"><?=Yii::t('user', 'Sign In')?></button>
</div>
<?php ActiveForm::end(); ?>

