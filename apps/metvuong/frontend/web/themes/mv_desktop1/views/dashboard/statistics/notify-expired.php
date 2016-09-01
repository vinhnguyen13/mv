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
        		<div class="wrap-img img-demo">
                    <table>
                        <tbody>
                            <tr>
                                <td><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/img-dash.jpg' ?>" alt=""></td>
                                <td><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/img-popup-dash.jpg' ?>" alt=""></td>
                            </tr>
                            <tr>
                                <td class="text-center fs-13 font-italic pdT-5">Bảng thống kê (Dashboard)</td>
                                <td class="text-center fs-13 font-italic pdT-5">Thống kê người dùng</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php if($balance->amount >= EcStatisticView::CHARGE): ?>
                <div class="text-center">
	        		<div class="fs-14 mgB-15 alert-buy-key">
	        			<p class="mgB-5"><?= Yii::t('dashboard', 'Thời gian xem thống kê đã hết hạn.')  ?></p>
	        			<p><?= sprintf("Phí gia hạn thống kê là %s keys. Thời hạn xem thống kê là %s ngày tính từ lúc trả phí.", '<span class="font-600 fs-15">' . EcStatisticView::CHARGE . '</span>', '<span class="font-600 fs-15">' . EcStatisticView::LIMIT_DAY . '</span>') ?></p>
	        		</div>
        		</div>
				<div class="text-center">
					<a href="<?= Url::to(['accept-view-statistics', 'redirect' => Url::current()]) ?>" class="btn-common btn-bd-radius btn-small">
						<span class="icon-mv mgR-10 fs-18"><span class="icon-coin-dollar"></span></span><?= Yii::t('payment', 'Gia hạn xem thống kê') ?>
					</a>
				</div>
				<?php else: ?>
                <div class="text-center">
	        		<div class="fs-14 mgB-15 alert-buy-key">
	        			<p class="mgB-5"><?= Yii::t('dashboard', 'Thời gian xem thống kê đã hết hạn, bạn cần phải nạp phí để tiếp tục.')  ?></p>
	        			<p><?= sprintf("Phí để xem thống kê là %s keys. Thời hạn xem thống kê là %s ngày tính từ lúc trả phí.", '<span class="font-600 fs-15">' . EcStatisticView::CHARGE . '</span>', '<span class="font-600 fs-15">' . EcStatisticView::LIMIT_DAY . '</span>') ?></p>
	        		</div>
        		</div>
				<div class="text-center">
					<a href="<?= Url::to(['/payment/index', 'redirect' => Url::current()]) ?>" class="btn-common btn-bd-radius btn-small">
						<span class="icon-mv mgR-10 fs-18"><span class="icon-coin-dollar"></span></span><?= Yii::t('payment', 'Buy Keys') ?>
					</a>
				</div>
				<?php endif; ?>
			</section>
        </div>
    </div>
</div>