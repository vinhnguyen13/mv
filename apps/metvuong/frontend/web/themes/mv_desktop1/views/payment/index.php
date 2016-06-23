<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 2/26/2016
 * Time: 9:51 AM
 */
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$request = Yii::$app->request;
$cookie = $request->cookies['copayment'];
if(empty($cookie)){
    Yii::$app->response->cookies->add(new \yii\web\Cookie([
        'name' => 'copayment',
        'value' => true,
        'expire' => time() + (10 * 365 * 24 * 60 * 60)
    ]));
?>
<script>    
    $(document).ready(function () {
        var txtTour = ["<p class='mgB-5'>Here you can clearly track the popularity of your listing, based on metrics such as the amount of views and favorites, as well as see this as a function of time.</p><p>You can also reach out to customers who may have searched for or favorited this listing, simply click on their account to send them a message</p>"];
        var intro = $.hemiIntro({
            debug: false,
            steps: [
                {
                    selector: ".statis section",
                    placement: "left",
                    content: txtTour[0],
                }
            ],
            onComplete: function (item) {
                
            }
        });

        intro.start();
    });
</script>
<?php
    $this->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/tour-intro.min.js', ['position'=>View::POS_END]);
}
?>
<div class="type-payment w-50">
	
	<div class="title-box">Bạn có mã khuyến mãi ?</div>
	<div class="input-couple mgB-20">
		<p class="mgB-10">Nhập Mã Coupon:</p>
		<input type="text" class="d-ib w-30 mgR-15 form-control">
		<button type="submit" class="btn-common">Gửi</button>
	</div>

	<div class="title-box">Chọn phương thức thanh toán</div>
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