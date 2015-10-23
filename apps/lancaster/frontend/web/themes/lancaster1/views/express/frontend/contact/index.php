<?php
use vsoft\express\models\LcBuilding;
use yii\helpers\Html;
use yii\helpers\Url;

date_default_timezone_set('Asia/Ho_Chi_Minh');

$this->title = Yii::t('express/contact', 'Contact');
/* @var $this yii\web\View */
?>

<div class="container-fluid contactt">
    <div class="row main_content">
        <span class="btn_back"><img src="<?= Yii::$app->view->theme->baseUrl ?>/resources/IMG/btn_back.png"><a
                href="<?= \yii\helpers\Url::home() ?>"><?=Yii::t('express/contact', 'Back to Lancaster Legacy')?></a></span>

        <h1 class="title"><?= Yii::t('express/contact', 'Contact') ?></h1>

        <div class="btnsociety">
            <span class="facebook"></span>
            <span class="images"></span>
            <span class="youtube"></span>
        </div>
    </div>
    <?php echo '<br>';
    if (Yii::$app->getSession()->hasFlash('reSent')) {
        \yii\bootstrap\Alert::begin([
            'options' => [
                'class' => 'alert alert-success',
            ],
        ]);

        echo Yii::$app->getSession()->getFlash('reSent');
        \yii\bootstrap\Alert::end();
    }
    ?>
    <div class="row main_row">
        <?php
        $buildings = LcBuilding::find()->all();
        foreach ($buildings as $building) {
            ?>
            <div class="blockitem">
                <span class="iconcontact"></span>
                <p class="noticaitalic"><?= $building->building_name ?></p>
                <ul>
                    <li class="litextleft">Address</li>
                    <li class="litextright"><?= $building->address?></li>
                    <li class="litextleft">Phone</li>
                    <li class="litextright"><?= $building->phone?></li>
                    <li class="litextleft">Fax</li>
                    <li class="litextright"><?= $building->fax?></li>
                    <li class="litextleft">Email</li>
                    <li class="litextright"><?= $building->email ? $building->email : 'sales@trungthuygroup.vn'?></li>
                    <li class="litextleft">Hotline</li>
                    <li class="litextright"><b><?= $building->hotline ? $building->hotline : '0903 090 909'?></b></li>
                </ul>
            </div>
            <?php
        }

        $model = new \vsoft\express\models\LcContact();
        $form = \yii\widgets\ActiveForm::begin([
            'id' => 'contact-form',
            'action' => Url::toRoute('/express/contact/send-contact'),
            'options' => [
                'method' => 'post',
            ]
        ]); ?>
        <div class="blockfrom">
            <p>Contact form</p>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'tabIndex' => '1', 'class' => 'name', 'placeholder' => Yii::t('express/contact','Your Name')])->label(false) ?>
            <?= $form->field($model, 'address')->textInput(['maxlength' => true, 'tabIndex' => '2', 'class' => 'address', 'placeholder' => Yii::t('express/contact','Your Email Address')])->label(false) ?>
            <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'tabIndex' => '3', 'class' => 'inputdefault', 'placeholder' => Yii::t('express/contact','Title')])->label(false) ?>
            <?= $form->field($model, 'message')->textarea(['maxlength' => true, 'tabIndex' => '4', 'class' => 'textareadefault', 'placeholder' => Yii::t('express/contact','Your Message'), 'rows' => '6'])->label(false) ?>
            <?= Html::submitButton(Yii::t('express/contact','Submit'), ['class' => 'btnsend']) ?>
        </div>

        <?php \yii\widgets\ActiveForm::end(); ?>



    </div>
</div>
