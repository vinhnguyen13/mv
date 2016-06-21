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
	<div class="input-couple mgB-20">
		<p class="mgB-10">Nhập Mã Coupon:</p>
		<input type="text" class="d-ib w-30 mgR-15 form-control">
		<button type="submit" class="btn-common">Gửi</button>
	</div>
	<ul class="nav nav-tabs tab-payment" role="tablist">
		<li role="presentation" class="active"><a href="#tab-atm" aria-controls="home" role="tab" data-toggle="tab">ATM</a></li>
		<li role="presentation" class=""><a href="#tab-thecao" aria-controls="home" role="tab" data-toggle="tab">Thẻ cào</a></li>
		<li role="presentation" class=""><a href="#tab-sms" aria-controls="home" role="tab" data-toggle="tab">SMS</a></li>
	</ul>
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="tab-atm">
			<?= $this->render('/payment/_partials/bank'); ?>
		</div>
		<div role="tabpanel" class="tab-pane" id="tab-thecao">
			<?= $this->render('/payment/_partials/card'); ?>
		</div>
		<div role="tabpanel" class="tab-pane" id="tab-sms">
			<div id="sms" class="item-payment">
		        <div class="title-item">Thanh toán bằng tin nhắn SMS</div>
		        <div class="w-30 text-center pd-20">
		            <p class="mgB-5">Soạn tin nhắn với cú pháp</p>
		            <p class="mgB-5"><span class="color-cd font-700">TT MV</span> [Mã Thành Viên] [Số Tiền] gửi <strong>19001590</strong></p>
		            <p>VD: TT MV 1234 10000 gửi 19001590</p>
		        </div>
		    </div>
		</div>
	</div>
</div>