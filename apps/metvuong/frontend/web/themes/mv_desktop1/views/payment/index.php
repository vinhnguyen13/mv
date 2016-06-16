<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 2/26/2016
 * Time: 9:51 AM
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<div class="type-payment w-50">
	<h3>Chọn phương thức thanh toán</h3>
	<?= $this->render('/payment/_partials/bank'); ?>
	<?= $this->render('/payment/_partials/card'); ?>
</div>