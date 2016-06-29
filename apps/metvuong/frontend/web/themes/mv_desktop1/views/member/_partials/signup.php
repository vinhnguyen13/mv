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
    </div>
    <div class="checkbox"><label> <input type="checkbox"> <?=Yii::t('general', 'Accept the {terms} of use of metvuong.com', ['terms'=>\yii\helpers\Html::a(Yii::t('general', 'Terms and Conditions'), Url::to(['site/page', 'view'=>'terms']))])?></label>
    </div>
    <div class="footer-modal clearfix">
        <button type="button" class="btn-primary btn-common btn-login" id="btn-register"><?=Yii::t('user', 'Sign Up')?></button>
    </div>
<?php ActiveForm::end(); ?>

<?= \vsoft\user\widgets\Connect::widget([
    'baseAuthUrl' => ['/user/security/auth'],
    'groupTitle' => Yii::t('user', '')
]) ?>