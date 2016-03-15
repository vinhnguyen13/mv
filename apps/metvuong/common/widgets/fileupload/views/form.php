<?php
/** @var \dosamigos\fileupload\FileUploadUI $this */
use yii\helpers\Html;
use yii\base\Widget;
$context = $this->context;
?>
<?= Html::beginTag('div', $context->options); ?>
	<ul class="files"></ul>
	<div class="fileinput-button-wrap">
	    <span class="btn btn-success fileinput-button">
			<i class="glyphicon glyphicon-plus"></i>
			<span><?= Yii::t('ad', 'Add images') ?>...</span>
			<?php
				$options = $context->fieldOptions;
			
				if(($context->model instanceof \yii\base\Model && $context->attribute !== null)) {
					$name = array_key_exists('name', $options) ? $options['name'] : Html::getInputName($context->model, $context->attribute);
				} else {
					$name = $context->name;
				}
				
				if(!array_key_exists('id', $options)) {
					$options['id'] = ($context->model instanceof \yii\base\Model && $context->attribute !== null) ? Html::getInputId($context->model, $context->attribute) : $this->context->getId();
				}
				
				$options['ref'] = $name;
			?>
			
			<?= Html::input('file', $this->context->inputFileName, null, $options) ?>
		</span>
	</div>
<?= Html::endTag('div');?>