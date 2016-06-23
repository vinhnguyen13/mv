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

$cookies = Yii::$app->request->cookies;
$cookie = $cookies->getValue('tutorial');
if(!isset($cookie['copayment']) || empty($cookie['copayment'])){
?>
<script>    
    $(document).ready(function () {
        var txtTour = ["<?=Yii::t('tutorial',"Metvuong.com có đầy đủ các hệ thống thanh toán để tối đa hóa sự tiện lợi của bạn, chỉ cần chọn số chìa khóa bạn muốn mua và phương thức thanh toán. Mua sỉ sẽ có chiết khấu tốt hơn.")?>"];
        var intro = $.hemiIntro({
            debug: false,
            steps: [
                {
                    selector: ".type-payment",
                    placement: "left",
                    content: txtTour[0],
                }
            ],
            onComplete: function (item) {
                $.ajax({
                    type: "get",
                    dataType: 'json',
                    url: '<?=Url::to(['site/set-cookie'])?>'+'?name=copayment',
                    success: function (data) {
                    }
                });
            }
        });

        intro.start();
    });
</script>
<?php
    $this->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/tour-intro.min.js', ['position'=>View::POS_END]);
}
?>
<?php
$profile = Yii::$app->user->identity->profile;
?>
<div class="type-payment w-60">
	<?= $this->render('/coupon/_partials/coupon'); ?>
	<div class="title-box"><?=Yii::t('payment', 'Payment method')?></div>
	<ul class="nav nav-tabs tab-payment" role="tablist">
		<li role="presentation" class="active"><a href="#tab-atm" aria-controls="home" role="tab" data-toggle="tab"><?=Yii::t('payment', 'Banking')?></a></li>
		<li role="presentation" class=""><a href="#tab-thecao" aria-controls="home" role="tab" data-toggle="tab"><?=Yii::t('payment', 'Mobile Card')?></a></li>
		<li role="presentation" class=""><a href="#tab-sms" aria-controls="home" role="tab" data-toggle="tab"><?=Yii::t('payment', 'SMS')?></a></li>
	</ul>
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="tab-atm">
			<?= $this->render('/payment/_partials/bank', ['profile'=>$profile]); ?>
		</div>
		<div role="tabpanel" class="tab-pane" id="tab-thecao">
			<?= $this->render('/payment/_partials/card', ['profile'=>$profile]); ?>
		</div>
		<div role="tabpanel" class="tab-pane" id="tab-sms">
			<?= $this->render('/payment/_partials/sms', ['profile'=>$profile]); ?>
		</div>
	</div>
</div>