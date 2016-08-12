<?php 
	use vsoft\express\components\StringHelper;
	use vsoft\ad\models\AdProductAdditionInfo;
	use vsoft\ad\models\AdFacility;
	
	$directionList = AdProductAdditionInfo::directionList();
	$facilities = AdFacility::find()->indexBy('id')->all();
?>
<div class="list-compare" id="compare-box">
	<?php foreach ($products as $product): ?>
	<!-- <div class="col-xs-4 item">
		<div class="inner-box">
			<a href="" class="pic-intro"><img src="<?= $product->representImage ?>" alt=""></a>
			<div class="infor">
				<p class="address-listing"><?= $product->address ?></p>
				<ul>
					<li>
						<span class="icon-mv"><span class="icon-page-1-copy"></span></span><?= $product->area ?>m<sup>2</sup>
					</li>
					<li class="price-item">
						<?= StringHelper::formatCurrency($product->price) ?> <span class="txt-unit"><?= Yii::t('ad', 'VND') ?></span>
					</li>
					<li class="price-item">
						<?= StringHelper::formatCurrency(round(($product->price/$product->area) / 10000) * 10000) ?> <span class="txt-unit"><?= Yii::t('ad', 'VND') ?> / </span>m<sup>2</sup>
					</li>
					<li>
						<span class="icon-mv"><span class="icon-bed-search"></span></span><?= $product->adProductAdditionInfo->room_no ?> <?= Yii::t('ad', 'Beds') ?>
					</li>
					<li>
						<span class="icon-mv"><span class="icon-icon-bathroom"></span></span><?= $product->adProductAdditionInfo->toilet_no ?> <?= Yii::t('ad', 'Baths') ?>
					</li>
					<li class="compare-s">
						<?= $product->project_building_id ? Yii::t('ad', 'Floor plan') : Yii::t('ad', 'Number of storeys') ?>: <?= $product->adProductAdditionInfo->floor_no ? $product->adProductAdditionInfo->floor_no : '<span style="opacity: 0.5;">' . Yii::t('ad', 'không xác định') . '</span>' ?>
					</li>
					<li class="compare-fw">
						<?= Yii::t('ad', 'Facade') ?>: <?= $product->adProductAdditionInfo->facade_width ? $product->adProductAdditionInfo->facade_width . 'm' : '<span style="opacity: 0.5;">' . Yii::t('ad', 'không xác định') . '</span>' ?>
					</li>
					<li class="compare-lw">
						<?= Yii::t('ad', 'Entry width') ?>: <?= $product->adProductAdditionInfo->land_width ? $product->adProductAdditionInfo->land_width . 'm' : '<span style="opacity: 0.5;">' . Yii::t('ad', 'không xác định') . '</span>' ?>
					</li>
					<li class="compare-hd">
						<?= Yii::t('ad', 'House direction') ?>: <?= $product->adProductAdditionInfo->home_direction ? $directionList[$product->adProductAdditionInfo->home_direction] : '<span style="opacity: 0.5;">' . Yii::t('ad', 'không xác định') . '</span>' ?>
					</li>
					<li class="compare-bd">
						<?= Yii::t('ad', 'Balcony direction') ?>: <?= $product->adProductAdditionInfo->facade_direction ? $directionList[$product->adProductAdditionInfo->facade_direction] : '<span style="opacity: 0.5;">' . Yii::t('ad', 'không xác định') . '</span>' ?>
					</li>
					<li class="compare-f">
						<?= Yii::t('ad', 'Facilities') ?>: <?= $product->adProductAdditionInfo->facility ? implode(', ', array_map(function($item) use ($facilities) { return $facilities[$item]->name; }, $product->adProductAdditionInfo->facility)) : '<span style="opacity: 0.5;">' . Yii::t('ad', 'không có') . '</span>' ?>
					</li>
				</ul>
			</div>
		</div>
	</div> -->
	<?php endforeach; ?>
	<div class="tbl-wrap">
		<div class="swiper-container">
			<div class="swiper-wrapper">
				<div class="wrap-tr-each swiper-slide">
					<div class="w-16"><span></span></div>
					<div class="w-28">
						<span>
							<a href="" class="img-compare clearfix">
								<div class="pull-left pic-intro"><img src="http://file4.batdongsan.com.vn/resize/745x510/2016/05/10/20160510081518-4d13.jpg" alt=""></div>
								<div class="overflow-all">Đường Trần Khánh Dư, Phường Tân Định, Quận 1, Hồ Chí Minh</div>
							</a>
						</span>
					</div>
					<div class="w-28">
						<span>
							<a href="" class="img-compare clearfix">
								<div class="pull-left pic-intro"><img src="http://file4.batdongsan.com.vn/resize/745x510/2016/05/10/20160510081518-4d13.jpg" alt=""></div>
								<div class="overflow-all">Đường Trần Khánh Dư, Phường Tân Định, Quận 1, Hồ Chí Minh</div>
							</a>
						</span>
					</div>
					<div class="w-28">
						<span>
							<a href="" class="img-compare clearfix">
								<div class="pull-left pic-intro"><img src="http://file4.batdongsan.com.vn/resize/745x510/2016/05/10/20160510081518-4d13.jpg" alt=""></div>
								<div class="overflow-all">Đường Trần Khánh Dư, Phường Tân Định, Quận 1, Hồ Chí Minh</div>
							</a>
						</span>
					</div>
				</div>
				<div class="wrap-tr-each swiper-slide">
					<div class="w-16 font-600"><span><span class="icon-mv mgR-10 fs-18"><span class="icon-pricing"></span></span>Giá</span></div>
					<div class="w-28 text-center bg-2"><span>1 tỷ</span></div>
					<div class="w-28 price-big text-center"><span>2 tỷ</span></div>
					<div class="w-28 text-center bg-2"><span>1.5 tỷ</span></div>
				</div>
				<div class="wrap-tr-each swiper-slide">
					<div class="w-16 font-600"><span><span class="icon-mv fs-18 mgR-10"><span class="icon-page-1-copy"></span></span>Diện tích</span></div>
					<div class="w-28 text-center bg-2"><span>103m2</span></div>
					<div class="w-28 text-center"><span>103m2</span></div>
					<div class="w-28 text-center bg-2"><span>103m2</span></div>
				</div>
				<div class="wrap-tr-each swiper-slide">
					<div class="w-16 font-600"><span><span class="icon-mv fs-18 mgR-10"><span class="icon-bed-search"></span></span>Phòng ngủ</span></div>
					<div class="w-28 text-center bg-2"><span>8 phòng ngủ</span></div>
					<div class="w-28 text-center"><span>8 phòng ngủ</span></div>
					<div class="w-28 text-center bg-2"><span>8 phòng ngủ</span></div>
				</div>
				<div class="wrap-tr-each swiper-slide">
					<div class="w-16 font-600"><span><span class="icon-mv fs-18 mgR-10"><span class="icon-icon-bathroom"></span></span>Phòng tắm</span></div>
					<div class="w-28 text-center bg-2"><span>8 phòng tắm</span></div>
					<div class="w-28 text-center"><span>8 phòng tắm</span></div>
					<div class="w-28 text-center bg-2"><span>8 phòng tắm</span></div>
				</div>
				<div class="wrap-tr-each swiper-slide">
					<div class="w-16 font-600"><span>Số tầng</span></div>
					<div class="w-28 text-center bg-2"><span>5</span></div>
					<div class="w-28 text-center"><span>không xác định</span></div>
					<div class="w-28 text-center bg-2"><span>không xác định</span></div>
				</div>
				<div class="wrap-tr-each swiper-slide">
					<div class="w-16 font-600"><span>Mặt tiền</span></div>
					<div class="w-28 text-center bg-2"><span>5</span></div>
					<div class="w-28 text-center"><span>không xác định</span></div>
					<div class="w-28 text-center bg-2"><span>không xác định</span></div>
				</div>
			</div>
		</div>
	</div>
</div>