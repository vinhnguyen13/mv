<?php 

use vsoft\ad\models\AdProduct;
use yii\helpers\Url;
?>
<div class="notify">
	<div class="color-cd fs-20 font-600 mgB-15 text-uper"><?= Yii::t("ad", "Cập nhật tin thành công") ?></div>
	<p class="mgB-10" style="font-size: 15px; line-height: 22px; font-weight: 600; color: #00a769;"><?= Yii::t("ad", "Cảm ơn bạn đã sử dụng dịch vụ của MetVuong") ?></p>
</div>
<div class="notify" style="margin-top: 12px;">
	<table class="notify-table" style="font-size: 15px;">
  	<tbody><tr>
      <td class="notify-table-label">Mã tin:</td>
      <td>MV<?= $product->id ?></td>
    </tr>
  	<tr>
      <td class="notify-table-label">Đường dẫn:</td>
      <td><a href="<?= Url::to(['/mv'.$product->id], true) ?>"><?= Url::to(['/mv'.$product->id], true) ?></a></td>
    </tr>
  	<tr>
      <td class="notify-table-label">Địa chỉ BĐS:</td>
      <td><?= $product->getAddress() ?></td>
    </tr>
  </tbody></table>
</div>