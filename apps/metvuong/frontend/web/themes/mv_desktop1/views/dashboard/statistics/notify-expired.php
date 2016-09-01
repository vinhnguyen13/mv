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
                <div class="text-center">
	        		<div class="fs-14 mgB-15 alert-buy-key">
	        			<p class="mgB-5">Thời gian xem thống kê đã hết hạn, bạn cần phải nạp phí để tiếp tục.</p>
	        			<p>Phí để xem thống kê là <span class="font-600 fs-15">15 keys</span>. Thời hạn xem thống kê là <span class="font-600 fs-15">30 ngày</span> tính từ lúc trả phí.</p>
	        		</div>
        		</div>
				<div class="text-center">
					<a href="" class="btn-common btn-bd-radius btn-small">
						<span class="icon-mv mgR-10 fs-18"><span class="icon-coin-dollar"></span></span>Mua Keys
					</a>
				</div>
			</section>
        </div>
    </div>
</div>