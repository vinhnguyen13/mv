<?php 
use yii\helpers\Url;
use vsoft\ad\models\AdProduct;
?>
<div class="notify">
	<p class="mgB-10" style="font-size: 15px; line-height: 22px; font-weight: 600; color: #00a769;"><?= Yii::t("ad", "Tin đăng đã được lưu trong dashboard của bạn, nhưng chưa thể hiển thị đến người người mua vì số keys trong tài khoản của bạn không đủ") ?></p>
	<ul style="font-size: 15px; color: #333; margin-bottom: 20px;">
		<li style="margin-bottom: 8px;"><?= sprintf(Yii::t("ad", "Số keys của bạn: %s keys"), '<strong>' . $balance->amount . '</strong>') ?></li>
		<li><?= sprintf(Yii::t("ad", "Phí đăng tin: %s keys"), '<strong>' . AdProduct::CHARGE_POST . '</strong>') ?></li>
	</ul>
	<div class="text-center">
		<a class="btn-common mgR-10" href="<?= Url::to(['payment/index', 'redirect' => Url::to(['/ad/update-status', 'id' => $product->id])]) ?>"><?= Yii::t("ad", "Nạp thêm keys") ?></a>
		<a class="btn-common mgR-10" href="<?= Url::to(['payment/index', 'redirect' => Url::to(['/ad/update-status', 'id' => $product->id])]) ?>"><?= Yii::t("ad", "Nhập mã coupon") ?></a>
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