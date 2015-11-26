<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\CmsShow */

$this->title = Yii::t('ad', 'Sort ') . Yii::t('ad', 'Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('ad', 'Category'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
	<?php
		$form = ActiveForm::begin([
	    	'options' => ['spellcheck' => 'false'],
	    	'enableClientValidation' => false,
	    	'enableAjaxValidation' => false,
		]);
	?>
	<?= $sort->render() ?>
	<div class="form-group">
        <?= Html::submitButton(Yii::t('cms', 'Lưu lại'), ['style' => 'margin-top: 22px;', 'class' => 'btn btn-primary btn-block']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
