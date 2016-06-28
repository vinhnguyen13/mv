<?php 

use vsoft\ad\models\AdProduct;
use yii\helpers\Url;
?>
<div class="notify">
	<div class="notify-post">
		<div class="color-cd fs-20 font-600 mgB-15 text-uper"><?= Yii::t("ad", "Đăng tin thành công") ?></div>
		<p class="mgB-10" style="font-size: 15px; line-height: 22px; font-weight: 600; color: #00a769;"><?= Yii::t("ad", "Cảm ơn bạn đã sử dụng dịch vụ của MetVuong") ?></p>
		<!-- <ul style="font-size: 15px; color: #333; margin-bottom: 20px;">
			<li style="margin-bottom: 8px;"><?= sprintf(Yii::t("ad", "Phí đăng tin: %s keys"), '<strong>' . AdProduct::CHARGE_POST . '</strong>') ?></li>
			<li><?= sprintf(Yii::t("ad", "Số keys còn lại: %s keys"), '<strong>' . $balance->amount . '</strong>') ?></li>
		</ul> -->
		<p class="mgB-10" style="font-size: 15px; line-height: 22px; font-weight: 600; color: #00a769;"><?= Yii::t("ad", "Để tin đăng này đến được với nhiều người mua nhất hãy boost tin.") ?></p>
		<div class="text-center">
			<a class="btn-common mgR-10 boost" href="<?= Url::to(['/ad/boost', 'day' => 3, 'id' => $product->id]) ?>"><?= sprintf(Yii::t("ad", "Boost %s ngày"), "3") ?></a>
			<a class="btn-common mgR-10 boost" href="<?= Url::to(['/ad/boost', 'day' => 1, 'id' => $product->id]) ?>"><?= sprintf(Yii::t("ad", "Boost %s ngày"), "1") ?></a>
		</div>
	</div>
	<div class="notify-boost" style="display: none;">
		<div id="notify-boost-message" class="color-cd fs-20 font-600 mgB-15 text-uper"></div>
		<!-- <div id="notify-boost-key"><?= sprintf(Yii::t("ad", "Số keys còn lại: %s keys"), '<strong class="key">' . $balance->amount . '</strong>') ?></div> -->
		<div id="notify-boost-charge" class="text-center" style="display: none; margin-top: 18px;">
			<a class="btn-common mgR-10" href="<?= Url::to(['payment/index']) ?>"><?= Yii::t("ad", "Nạp thêm keys") ?></a>
			<a class="btn-common mgR-10" href="<?= Url::to(['payment/index']) ?>"><?= Yii::t("ad", "Nhập mã coupon") ?></a>
		</div>
	</div>
</div>
<div class="notify" style="margin-top: 12px;">
	<table class="notify-table" style="font-size: 15px;">
  	<tbody><tr>
      <td class="notify-table-label"><?= Yii::t('ad', 'ID') ?>:</td>
      <td>MV<?= $product->id ?></td>
    </tr>
  	<tr>
      <td class="notify-table-label"><?= Yii::t('ad', 'Link') ?>:</td>
      <td><a href="<?= Url::to(['/mv'.$product->id], true) ?>"><?= Url::to(['/mv'.$product->id], true) ?></a></td>
    </tr>
  	<tr>
      <td class="notify-table-label"><?= Yii::t('ad', 'Địa chỉ') ?>:</td>
      <td><?= $product->getAddress() ?></td>
    </tr>
  </tbody></table>
</div>