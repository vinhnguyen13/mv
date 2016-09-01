<?php
	use vsoft\ec\models\EcStatisticView;
	use yii\helpers\Url;
?>
<div class="title-fixed-wrap">
    <div class="container">
        <div class="statis">
        	<div class="title-top">
                <?=Yii::t('statistic','Statistic')?>
            </div>
        	<section class="clearfix mgB-40">
				<div>Thời gian xem thống kê đã hết hạn, bạn cần phải nạp phí để tiếp tục.</div>
				<div style="margin-top: 12px;">Phí để xem thống kê là 15 keys. Thời hạn xem thống kê là 30 ngày tính từ lúc trả phí.</div>
				<div style="margin-top: 12px;">
				<a href="/payment/index?redirect=%2Fdashboard%2Fstatistics%3Fid%3D79132" class="btn">Mua Keys</a>
				</div>
			</section>
        </div>
    </div>
</div>