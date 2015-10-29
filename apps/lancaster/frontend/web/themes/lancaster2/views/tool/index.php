<?php
use yii\web\View;
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
$javascript = <<<EOD
	var row = $('.row');
EOD;

$this->registerJs($javascript, View::POS_END, 'masonry');
?>

