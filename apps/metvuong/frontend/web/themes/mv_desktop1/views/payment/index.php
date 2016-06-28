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

$profile = Yii::$app->user->identity->profile;
?>
<div class="type-payment w-60">
	<div class="innner-block">
		<div class="title-box"><?=Yii::t('payment', 'Payment method')?></div>
		<ul class="nav nav-tabs tab-payment" role="tablist">
			<li role="presentation" class="active"><a href="#tab-atm" aria-controls="home" role="tab" data-toggle="tab"><?=Yii::t('payment', 'Banking')?></a></li>
			<li role="presentation" class=""><a href="#tab-thecao" aria-controls="home" role="tab" data-toggle="tab"><?=Yii::t('payment','Mobile Credit')?></a></li>
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
</div>

<div id="payment-dialog" class="modal fade popup-common" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<div class="wrap-popup">
					<div class="title-popup">
						<?=Yii::t('payment', 'Payment')?>
					</div>
					<div class="inner-popup">

					</div>
					<div class="bottom-popup">
						<div class="text-right">
							<a href="#" class="btn close" data-dismiss="modal" aria-label="Close"><?=Yii::t('coupon', 'OK')?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php if(Yii::$app->session->hasFlash('popupcancel')): ?>
	<script language="javascript">
		$(document).ready(function () {
			$('#payment-dialog .inner-popup').html(lajax.t('You have to cancel payments. Do you want to pay ?'));
			$('#payment-dialog').modal('toggle');
		});
	</script>
<?php endif; ?>
<?php if(Yii::$app->session->hasFlash('popupsuccess')): ?>
	<script language="javascript">
		$(document).ready(function () {
			$('#payment-dialog .inner-popup').html(lajax.t('You have a successful payment'));
			$('#payment-dialog').modal('toggle');
		});
	</script>
<?php endif; ?>
