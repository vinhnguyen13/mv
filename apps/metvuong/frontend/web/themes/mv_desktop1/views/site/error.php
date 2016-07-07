<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $name;
$return_url = Yii::$app->getUser()->getReturnUrl();
if($message == "Not Found"){
    $return_url = "javascript:history.back()";
}
?>
<div class="site-error">
	<div class="wrap_404">
		<div>
			<h3 class="title_404"><?=Yii::t('general', 'Opps!')?></h3>
			<span class="line_1_404"><?=Yii::t('general', 'Something went wrong.')?></span>

			<span class="line_2_404"><?= nl2br(Html::encode($message)) ?></span>
			<a href="<?=$return_url;?>" class="readon"><?=Yii::t('general', 'Back')?></a>&nbsp;
			<a href="<?=Url::home();?>" class="readon"><?=Yii::t('general', 'Home')?></a>
		</div>
	</div>
</div>
