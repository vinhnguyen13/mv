<?php
	$this->registerCssFile(Yii::$app->view->theme->baseUrl . '/resources/css/post-listing.css');
?>
<div class="title-fixed-wrap container">
	<div class="post-listing">
		<?= $this->render('_partials/' . $template, ['balance' => $balance, 'product' => $product]) ?>
	</div>
</div>