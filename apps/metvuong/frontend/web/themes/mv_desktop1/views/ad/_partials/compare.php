<?php 
	use vsoft\express\components\StringHelper;
?>
<div class="row list-compare">
	<?php foreach ($products as $product): ?>
	<div class="col-xs-12 col-sm-6 col-lg-4 item">
		<div class="inner-box">
			<a href="" class="pic-intro"><img src="<?= $product->representImage ?>" alt=""></a>
			<div class="infor">
				<p class="address-listing"><?= $product->address ?></p>
				<ul>
					<li class="price-item">
						<?= StringHelper::formatCurrency($product->price) ?> <span class="txt-unit"><?= Yii::t('ad', 'VND') ?></span>
					</li>
					<li>
						<span class="icon-mv"><span class="icon-page-1-copy"></span></span><?= $product->area ?>m<sup>2</sup>
					</li>
					<li>
						<span class="icon-mv"><span class="icon-bed-search"></span></span><?= $product->adProductAdditionInfo->room_no ?> <?= Yii::t('ad', 'Beds') ?>
					</li>
					<li>
						<span class="icon-mv"><span class="icon-icon-bathroom"></span></span><?= $product->adProductAdditionInfo->toilet_no ?> <?= Yii::t('ad', 'Baths') ?>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
</div>