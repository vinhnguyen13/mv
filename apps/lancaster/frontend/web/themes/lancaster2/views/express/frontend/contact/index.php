<?php
use vsoft\express\models\LcBuilding;
use yii\helpers\Html;
use yii\helpers\Url;

date_default_timezone_set('Asia/Ho_Chi_Minh');

$this->registerCssFile('https://fonts.googleapis.com/css?family=Roboto:400,700|Noticia+Text:400italic,400,700,700italic', ['type' => 'text/css']);

$this->title = Yii::t('express/contact', 'Contact');
/* @var $this yii\web\View */
?>
<div id="contact-page">
    <div class="container">
        <h1 class="title"><?= Yii::t('express/contact', 'Contact') ?></h1>
        <div class="social">
            <a href="#" class="icon-social icon-facebook"></a>
            <a href="#" class="icon-social icon-instagram"></a>
            <a href="#" class="icon-social icon-youtube"></a>
        </div>
        <div class="contact-list clear">
            <?php
            $buildings = LcBuilding::find()->all();
            if(!empty($buildings)){
            foreach ($buildings as $building) {
            ?>
                <div class="contact-item">
                <div class="title"><?= $building->building_name ?></div>
                <div class="table">
                    <div class="table-row break">
                        <div class="table-cell label">Address</div>
                        <div class="table-cell"><?= $building->address?></div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell label">Phone</div>
                        <div class="table-cell"><?= $building->phone?></div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell label">Fax</div>
                        <div class="table-cell"><?= $building->fax?></div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell label">Email</div>
                        <div class="table-cell"><?= $building->email ? $building->email : 'sales@trungthuygroup.vn'?></div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell label">Hotline</div>
                        <div class="table-cell"><a href="#" class="phone"><?= $building->hotline ? $building->hotline : '0903 090 909'?></a></div>
                    </div>
                </div>
            </div>
            <?php }
            }?>
            <div class="contact-item">
                <div class="form-title">Contact Form</div>
                <div class="contact-form-wrap">
                    <?php
                    if (Yii::$app->getSession()->hasFlash('reSent')) {
                        \yii\bootstrap\Alert::begin([
                            'options' => [
                                'class' => 'alert alert-success',
                            ],
                        ]);

                        echo Yii::$app->getSession()->getFlash('reSent');
                        \yii\bootstrap\Alert::end();
                    }
                    $model = new \vsoft\express\models\LcContact();
                    $form = \yii\widgets\ActiveForm::begin([
                        'id' => 'contact-form',
                        'action' => Url::toRoute('/express/contact/send-contact'),
                        'options' => [
                            'method' => 'post',
                        ]
                    ]);
                    ?>

                        <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'tabIndex' => '1', 'class' => 'name', 'placeholder' => Yii::t('express/contact','Your Name')])->label(false) ?>
                        <?= $form->field($model, 'address')->textInput(['maxlength' => true, 'tabIndex' => '2', 'class' => 'address', 'placeholder' => Yii::t('express/contact','Your Email Address')])->label(false) ?>
                        <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'tabIndex' => '3', 'class' => 'inputdefault', 'placeholder' => Yii::t('express/contact','Title')])->label(false) ?>
                        <?= $form->field($model, 'message')->textarea(['maxlength' => true, 'tabIndex' => '4', 'class' => 'textareadefault', 'placeholder' => Yii::t('express/contact','Your Message'), 'rows' => '6'])->label(false) ?>
                        <?= Html::input('submit', null, null, ['class' => 'submit']) ?>
                    <?php \yii\widgets\ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
