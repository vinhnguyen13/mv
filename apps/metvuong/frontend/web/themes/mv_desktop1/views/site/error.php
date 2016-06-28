<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">
	<div class="wrap_404">
		<div>
			<h3 class="title_404">404</h3>
			<span class="line_1_404">Opps, sorry we can't find that page!</span>
			<span class="line_2_404">Either something went wrong or the page doesn't exist anymore.</span>
			<a href="/" class="readon">Home Page</a>
		</div>
	</div>
</div>