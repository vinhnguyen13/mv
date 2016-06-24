<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model vsoft\express\models\Metadata */
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

    echo "<i>Facebook Meta Tag: </i><br><br>";

    echo Html::label('Title');
    echo Html::input('text','og:title',$metadata["og:title"],['class'=>'form-control form-group']);

    echo Html::label('Image Url');
    echo Html::input('text','og:image',$metadata["og:image"],['class'=>'form-control form-group']);
//    if($model->isNewRecord) {
//        echo \kartik\file\FileInput::widget([
//            'name' => 'og:image',
//            'options' => ['accept' => 'image/*'],
//            'pluginOptions' => [
//                'previewFileType' => 'image',
//                'showUpload' => false,
//                'browseLabel' => '',
//                'removeLabel' => '',
//                'mainClass' => 'input-group-lg',
//                'resizeImage' => true,
//                'maxImageWidth' => 200,
//                'resizePreference' => 'width',
//            ]
//        ]);
//    } else {
//        echo \kartik\file\FileInput::widget([
//            'name' => 'og:image',
//            'options' => ['accept' => 'image/*'],
//            'pluginOptions' => [
//                'previewFileType' => 'image',
//                'showUpload' => false,
//                'browseLabel' => '',
//                'removeLabel' => '',
//                'initialPreview' => [
//                    Html::img("/store/news/show/" . $metadata["og:image"], ['class' => 'file-preview-image', 'alt' => 'Banner', 'title' => $metadata["og:image"]]),
//                ],
//                'initialCaption' => $metadata["og:image"],
////            'overwriteInitial'=>false, // used in multi upload case
//                'mainClass' => 'input-group-lg',
//                'resizeImage'=> true,
//                'maxImageWidth'=> 200,
//                'resizePreference'=> 'width',
//            ],
//        ]);
//    }
//    echo "<br>";

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
