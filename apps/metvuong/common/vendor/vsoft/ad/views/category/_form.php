<?php

use vsoft\news\models\CmsCatalog;
use vsoft\news\models\Status;
use vsoft\user\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\vendor\vsoft\ad\models\AdInvestor;
use common\widgets\FileUploadUI;
use yii\helpers\Url;
use common\vendor\vsoft\ad\models\AdProduct;

/* @var $this yii\web\View */
/* @var $model funson86\cms\models\CmsShow */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cms-show-form col-md-8">

    <?php $form = ActiveForm::begin([
    	'enableClientValidation' => false,
    	'enableAjaxValidation' => false,
        'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-11\">{input}</div><div class=\"col-lg-1\"></div>\n<div class=\"col-lg-11\">{hint}{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <label class="col-lg-1 control-label"></label>
        <div class="col-lg-11">
        	<?= Html::checkbox('for_sell', $model->apply_to_type & AdProduct::TYPE_FOR_SELL, ['label' => 'Áp dụng cho nhà đất bán', 'value' => AdProduct::TYPE_FOR_SELL]) ?>
        	<div></div>
			<?= Html::checkbox('for_rent', $model->apply_to_type & AdProduct::TYPE_FOR_RENT, ['label' => 'Áp dụng cho nhà đất cho thuê', 'value' => AdProduct::TYPE_FOR_RENT]) ?>    
        </div>
        <label class="col-lg-1 control-label"></label>
        <div class="col-lg-11"><div class="help-block"><?= $model->getErrors('apply_to_type') ? current($model->getErrors('apply_to_type')) : '' ?></div></div>
    </div>
    <div class="form-group">
        <label class="col-lg-1 control-label"></label>
        <div class="col-lg-11">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Cancel'), 'index', ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
