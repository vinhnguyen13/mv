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
			<?= $this->render('/payment/_partials/sms'); ?>
		</div>
	</div>
</div>
<script>
    $(document).ready(function () {
        var intro = $.hemiIntro({
            debug: false,
            steps: [
                {
                    selector: ".type-payment",
                    placement: "left",
                    content: "Metvuong.com caters to a variety of payment system to maximize your convenience, simply select the amount of keys you want to buy, and your method of payment. THe more you buy, the cheaper it is.",
                }
            ]
        });

        intro.start();
    });
</script>