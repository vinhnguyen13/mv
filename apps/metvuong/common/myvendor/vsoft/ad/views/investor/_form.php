<?php

use vsoft\news\models\CmsCatalog;
use vsoft\news\models\Status;
use vsoft\user\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vsoft\ad\models\AdInvestor;
use common\widgets\FileUploadUI;
use yii\helpers\Url;

?>

<div class="cms-show-form">

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-11\">{input}</div><div class=\"col-lg-1\"></div>\n<div class=\"col-lg-11\">{hint}{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>
    
	<?php
    if($model->logo) {
        $pathInfo = pathinfo($model->logo);
        if(!isset($pathInfo['extension']))
            $model->logo = null;

    }
        echo $form->field($model, 'logo')->widget(FileUploadUI::className(), [
            'url' => Url::to('/express/upload/image'),
            'clientOptions' => ['maxNumberOfFiles' => 1]]);
    ?>

    <?= $model->isNewRecord ? $form->field($model, 'name')->textInput(['maxlength' => true]) : $form->field($model, 'name')->textInput(['maxlength' => true, 'disabled' => true]) ?>
    
    <?= $form->field($model, 'address')->textarea(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'fax')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'website')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'status')->dropDownList(AdInvestor::labels()) ?>

    <div class="form-group">
        <label class="col-lg-1 control-label"></label>
        <div class="col-lg-11">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Cancel'), 'index', ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
