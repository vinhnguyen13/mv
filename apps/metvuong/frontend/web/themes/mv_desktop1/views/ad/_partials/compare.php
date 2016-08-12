<?php 
	use vsoft\express\components\StringHelper;
	use vsoft\ad\models\AdProductAdditionInfo;
	use vsoft\ad\models\AdFacility;
	
	$directionList = AdProductAdditionInfo::directionList();
	$facilities = AdFacility::find()->indexBy('id')->all();
?>
<div class="row list-compare" id="compare-box">
	<?php foreach ($products as $product): ?>
	<div class="col-xs-12 col-sm-6 col-lg-4 item">
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
	</div>
	<?php endforeach; ?>
</div>