<?php 
use yii\helpers\Url;
use vsoft\ad\models\AdProduct;
?>
<p class="mgB-10 fs-14">Tin đăng đã được lưu trong dashboard của bạn nhưng chưa thể hiển thị đến người người mua vì số keys trong tài khoản của bạn không đủ</p>

<ul style="margin-bottom: 12px; margin-left: 20px;">
	<li>Phí đăng tin là: <strong><?= AdProduct::CHARGE_POST ?></strong> keys</li>
	<li>Số keys còn lại của bạn: <strong><?= $balance->amount ?></strong> keys</li>
</ul>
<div class="text-center">
	<a class="btn-common mgR-10" href="<?= Url::to(['payment/index', 'redirect' => Url::to(['/ad/update-status', 'id' => $productId, 'url' => Url::to(['/ad/notify-success'])])]) ?>">Nạp thêm keys</a>
</div>
<div style="font-weight: bold; text-align: center; margin: 20px;">Hoặc</div>
<label style="font-weight: normal;">Nhập mã để nhận keys miễn phí</label>
<div style="position: relative; padding-right: 112px">
<input class="form-control" type="text" placeholder="mã" />
<input style="width: 100px; position: absolute; right: 0px; top: 0px; padding: 7px 0;" class="btn-common" type="button" value="Gửi" /></div>