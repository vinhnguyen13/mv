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
				<div><?= $message ?></div>
				<div style="margin-top: 12px;"><?= sprintf("Phí để xem thống kê là %s keys. Thời hạn xem thống kê là %s ngày tính từ lúc trả phí.", EcStatisticView::CHARGE, EcStatisticView::LIMIT_DAY) ?></div>
				<div style="margin-top: 12px;">
					<?php if($balance->amount >= EcStatisticView::CHARGE): ?>
					<ul class="show-key" style="margin-bottom: 12px;">
						<li><?= sprintf(Yii::t("ad", "Số keys hiện tại: %s keys"), '<strong class="current-key strong">' . $balance->amount . '</strong>') ?></li>
						<li><?= sprintf(Yii::t("ad", "Số keys sau khi trả phí xem thống kê: %s keys"), '<strong class="after-key strong">' . ($balance->amount - EcStatisticView::CHARGE) . '</strong>') ?></li>
					</ul>
					<a href="<?= Url::to(['accept-view-statistics', 'redirect' => Url::current()]) ?>" class="btn">Chấp nhận</a>
					<?php else: ?>
					<a href="<?= Url::to(['/payment/index', 'redirect' => Url::current()]) ?>" class="btn"><?=Yii::t('payment', 'Buy Keys')?></a>
					<?php endif; ?>
				</div>
        	</section>
        </div>
    </div>
</div>