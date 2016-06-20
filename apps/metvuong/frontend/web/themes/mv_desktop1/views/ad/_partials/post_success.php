<?php 

use vsoft\ad\models\AdProduct;
?>
<div class="color-cd fs-20 font-600 mgB-15 text-uper">Đăng tin thành công</div>
<p class="mgB-10 fs-14">Cảm ơn đã sử dụng dịch vụ của MetVuong</p>
<ul style="margin-bottom: 12px; margin-left: 20px;">
	<li>Phí đăng tin là: <strong><?= AdProduct::CHARGE_POST ?></strong> keys</li>
	<li>Số keys còn lại của bạn: <strong><?= $balance->amount ?></strong> keys</li>
</ul>
<p class="mgB-10 fs-14">Để tin của bạn đến được với nhiều người xem nhất hãy boost tin</p>
<div class="text-center">
	<a class="btn-common mgR-10" href="#">Boost 1 ngày</a>
	<a class="btn-common mgR-10" href="#">Boost 3 ngày</a>
</div>