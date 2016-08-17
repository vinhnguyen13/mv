<?php 
	use vsoft\express\components\StringHelper;
	use vsoft\ad\models\AdProductAdditionInfo;
	use vsoft\ad\models\AdFacility;
	
	$directionList = AdProductAdditionInfo::directionList();
	$facilities = AdFacility::find()->indexBy('id')->all();
?>
<div class="list-compare" id="compare-box">
	<div class="tbl-wrap">
		<div class="swiper-container">
			<div class="swiper-wrapper">
				<div class="wrap-tr-each swiper-slide">
					<div class="w-16"><span></span></div>
					<?php foreach ($products as $product): ?>
					<div class="w-28">
						<span>
							<a href="<?= $product->urlDetail() ?>" class="img-compare clearfix">
								<div class="pull-left pic-intro"><img src="<?= $product->representImage ?>" alt=""></div>
								<div class="overflow-all"><?= $product->address ?></div>
							</a>
						</span>
					</div>
					<?php endforeach; ?>
				</div>
				<div class="wrap-tr-each swiper-slide">
					<div class="w-16 font-600"><span><span class="icon-mv fs-18 mgR-10"><span class="icon-page-1-copy"></span></span><?= Yii::t('ad', 'Home size') ?></span></div>
					<?php foreach ($products as $k => $product): ?>
					<div class="w-28 text-center<?= $k%2 == 0 ? ' bg-2' : '' ?>"><span><?= $product->area ?>m<sup>2</sup></span></div>
					<?php endforeach; ?>
				</div>
				<div class="wrap-tr-each swiper-slide">
					<div class="w-16 font-600"><span><span class="icon-mv mgR-10 fs-18"><span class="icon-pricing"></span></span><?= Yii::t('ad', 'Price') ?></span></div>
					<?php foreach ($products as $k => $product): ?>
					<div class="w-28 price-big text-center<?= $k%2 == 0 ? ' bg-2' : '' ?>"><span><?= StringHelper::formatCurrency($product->price) ?> <span class="txt-unit"><?= Yii::t('ad', 'VND') ?></span></span></div>
					<?php endforeach; ?>
				</div>
				<div class="wrap-tr-each swiper-slide">
					<div class="w-16 font-600"><span><span class="icon-mv mgR-10 fs-18"><span class="icon-pricing"></span></span><?= Yii::t('ad', 'Price') ?>/m<sup>2</sup></span></div>
					<?php foreach ($products as $k => $product): ?>
					<div class="w-28 text-center<?= $k%2 == 0 ? ' bg-2' : '' ?>"><span><?= StringHelper::formatCurrency(round(($product->price/$product->area) / 10000) * 10000) ?> <span class="txt-unit"><?= Yii::t('ad', 'VND') ?> / </span>m<sup>2</sup></span></div>
					<?php endforeach; ?>
				</div>
				<div class="wrap-tr-each swiper-slide">
					<div class="w-16 font-600"><span><span class="icon-mv fs-18 mgR-10"><span class="icon-bed-search"></span></span><?= Yii::t('ad', 'Beds') ?></span></div>
					<?php foreach ($products as $k => $product): ?>
					<div class="w-28 text-center<?= $k%2 == 0 ? ' bg-2' : '' ?>"><span><?= $product->adProductAdditionInfo->room_no ?></span></div>
					<?php endforeach; ?>
				</div>
				<div class="wrap-tr-each swiper-slide">
					<div class="w-16 font-600"><span><span class="icon-mv fs-18 mgR-10"><span class="icon-icon-bathroom"></span></span><?= Yii::t('ad', 'Baths') ?></span></div>
					<?php foreach ($products as $k => $product): ?>
					<div class="w-28 text-center<?= $k%2 == 0 ? ' bg-2' : '' ?>"><span><?= $product->adProductAdditionInfo->toilet_no ?></span></div>
					<?php endforeach; ?>
				</div>
				<div class="wrap-tr-each swiper-slide compare-s">
					<div class="w-16 font-600"><span><?= $product->project_building_id ? Yii::t('ad', 'Floor plan') : Yii::t('ad', 'Number of storeys') ?></span></div>
					<?php foreach ($products as $k => $product): ?>
					<div class="w-28 text-center<?= $k%2 == 0 ? ' bg-2' : '' ?>"><span><?= $product->adProductAdditionInfo->floor_no ? $product->adProductAdditionInfo->floor_no : '<span style="opacity: 0.5;">' . Yii::t('ad', 'không xác định') . '</span>' ?></span></div>
					<?php endforeach; ?>
				</div>
				<div class="wrap-tr-each swiper-slide compare-fw">
					<div class="w-16 font-600"><span><?= Yii::t('ad', 'Facade') ?></span></div>
					<?php foreach ($products as $k => $product): ?>
					<div class="w-28 text-center<?= $k%2 == 0 ? ' bg-2' : '' ?>"><span><?= $product->adProductAdditionInfo->facade_width ? $product->adProductAdditionInfo->facade_width . 'm' : '<span style="opacity: 0.5;">' . Yii::t('ad', 'không xác định') . '</span>' ?></span></div>
					<?php endforeach; ?>
				</div>
				<div class="wrap-tr-each swiper-slide compare-lw">
					<div class="w-16 font-600"><span><?= Yii::t('ad', 'Entry width') ?></span></div>
					<?php foreach ($products as $k => $product): ?>
					<div class="w-28 text-center<?= $k%2 == 0 ? ' bg-2' : '' ?>"><span><?= $product->adProductAdditionInfo->land_width ? $product->adProductAdditionInfo->land_width . 'm' : '<span style="opacity: 0.5;">' . Yii::t('ad', 'không xác định') . '</span>' ?></span></div>
					<?php endforeach; ?>
				</div>
				<div class="wrap-tr-each swiper-slide compare-hd">
					<div class="w-16 font-600"><span><?= Yii::t('ad', 'House direction') ?></span></div>
					<?php foreach ($products as $k => $product): ?>
					<div class="w-28 text-center<?= $k%2 == 0 ? ' bg-2' : '' ?>"><span><?= $product->adProductAdditionInfo->home_direction ? $directionList[$product->adProductAdditionInfo->home_direction] : '<span style="opacity: 0.5;">' . Yii::t('ad', 'không xác định') . '</span>' ?></span></div>
					<?php endforeach; ?>
				</div>
				<div class="wrap-tr-each swiper-slide compare-bd">
					<div class="w-16 font-600"><span><?= Yii::t('ad', 'Balcony direction') ?></span></div>
					<?php foreach ($products as $k => $product): ?>
					<div class="w-28 text-center<?= $k%2 == 0 ? ' bg-2' : '' ?>"><span><?= $product->adProductAdditionInfo->facade_direction ? $directionList[$product->adProductAdditionInfo->facade_direction] : '<span style="opacity: 0.5;">' . Yii::t('ad', 'không xác định') . '</span>' ?></span></div>
					<?php endforeach; ?>
				</div>
				<div class="wrap-tr-each swiper-slide compare-f">
					<div class="w-16 font-600"><span><?= Yii::t('ad', 'Facilities') ?></span></div>
					<?php foreach ($products as $k => $product): ?>
					<div class="w-28 text-center<?= $k%2 == 0 ? ' bg-2' : '' ?>"><span><?= $product->adProductAdditionInfo->facility ? implode(', ', array_map(function($item) use ($facilities) { return Yii::t('ad', $facilities[$item]->name); }, $product->adProductAdditionInfo->facility)) : '<span style="opacity: 0.5;">' . Yii::t('ad', 'không xác định') . '</span>' ?></span></div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>