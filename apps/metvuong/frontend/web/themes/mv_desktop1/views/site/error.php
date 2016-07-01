<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
$return_url = Yii::$app->getUser()->getReturnUrl();

?>
<div class="site-error">
	<div class="wrap_404">
		<div>
			<h3 class="title_404">Opps!</h3>
			<span class="line_1_404">Something went wrong.</span>

			<span class="line_2_404"><?= nl2br(Html::encode($message)) ?></span>
			<a href="<?=$return_url;?>" class="readon">Back</a>
		</div>
	</div>
</div>
