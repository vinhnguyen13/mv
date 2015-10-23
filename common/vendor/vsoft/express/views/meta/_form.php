<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model vsoft\express\models\LcMeta */
/* @var $form yii\widgets\ActiveForm */

$metadata = \yii\helpers\Json::decode($model->metadata,true);
?>

<div class="lc-meta-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?php
    // Fixed add tags:
    // Site name => 'Lancaster News',
    // Object Type => 'article',
    // Localizations => 'vi_VN' and alter => 'en_US'

    echo Html::label('Keywords');
    echo Html::input('text','keywords',$metadata["keywords"],['class'=>'form-control form-group']);

    echo Html::label('Description');
    echo Html::input('text','description',$metadata["description"],['class'=>'form-control form-group']);

    echo Html::label('Title');
    echo Html::input('text','og:title',$metadata["og:title"],['class'=>'form-control form-group']);

    echo Html::label('Image');
    echo Html::input('text','og:image',$metadata["og:image"],['class'=>'form-control form-group']);

    echo Html::label('Author');
    echo Html::input('text','article:author',$metadata["article:author"],['class'=>'form-control form-group']);

    echo Html::label('Publisher');
    echo Html::input('text','article:publisher',$metadata["article:publisher"],['class'=>'form-control form-group']);

    ?>
    <br>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('meta', 'Create') : Yii::t('meta', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
