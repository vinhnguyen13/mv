<?php 
	use vsoft\ad\models\AdImages;
	use vsoft\express\components\StringHelper;
use vsoft\ad\models\AdCategory;
use yii\helpers\Url;
use vsoft\ad\models\AdProduct;

	$categories = AdCategory::find()->indexBy('id')->asArray(true)->all();
	$types = AdProduct::getAdTypes();
?>
<div class="search-subpage clearfix">
	<form id="" action="">
		<div class="search-fill">
			<input class="style-click" type="text"
				placeholder="Tìm kiếm nhanh...">
			<button type="submit" id="btn-search" class="style-click">
				<span class="icon"></span>
			</button>
			<a href="#" class="advande-search-btn style-click"><span
				class="bd-left"></span><span class="bd-right"></span></a>
		</div>
		<div class="advande-search">
			<div class="each-advande">
				<div class="value-selected price-search style-click">Giá</div>
				<div class="item-advande row">
					<div class="col-xs-5">
						<input type="text" class="form-control style-click" id=""
							placeholder="Thấp nhất">
					</div>
					<div class="col-xs-2">đến</div>
					<div class="col-xs-5">
						<input type="text" class="form-control style-click" id=""
							placeholder="Cao nhất">
					</div>
				</div>
			</div>
			<div class="each-advande">
				<div class="value-selected dt-search style-click">Diện tích</div>
				<div class="item-advande row">
					<div class="col-xs-5">
						<input type="text" class="form-control style-click" id=""
							placeholder="Thấp nhất">
					</div>
					<div class="col-xs-2">đến</div>
					<div class="col-xs-5">
						<input type="text" class="form-control style-click" id=""
							placeholder="Cao nhất">
					</div>
				</div>
			</div>
			<div class="each-advande row">
				<div class="col-xs-6 num-phongngu">
					<div class="value-selected style-click">Phòng ngủ</div>
				</div>
				<div class="col-xs-6 num-phongtam">
					<div class="value-selected style-click">Phòng tắm</div>
				</div>
				<div class="item-advande">
					<ul class="clearfix">
						<li><a class="style-click" href="#" data-value="1">1</a></li>
						<li><a class="style-click" href="#" data-value="2">2</a></li>
						<li><a class="style-click" href="#" data-value="3">3</a></li>
						<li><a class="style-click" href="#" data-value="4">4+</a></li>
					</ul>
				</div>
			</div>
			<div class="each-advande row">
				<div class="col-xs-12 other-fill">
					<div class="value-selected style-click">Thêm tuỳ chọn</div>
				</div>
			</div>
		</div>
	</form>
</div>

<div class="wrap-listing clearfix">
	<div class="dropdown-select option-show-listing">
		<div class="val-selected style-click">
			Hiển thị tin theo <span class="selected">Tin mới nhất</span>
		</div>
		<div class="item-dropdown">
			<ul>
				<li><a href="#">Điểm MetVuong cao nhất</a></li>
				<li><a href="#">Mới nhất</a></li>
				<li><a href="#">Giá thấp nhất - cao nhất</a></li>
				<li><a href="#">Giá cao nhất - thấp nhất</a></li>
			</ul>
		</div>
	</div>
	<div class="top-listing clearfix">
		<div class="pull-right pagi">
			<a href="<?= $pages->page == 0 ? 'javascript:;' : $pages->createUrl($pages->page-1) ?>" class="prev-pagi style-click"><span class="icon"></span></a>
			<a href="<?= $pages->page == $pages->pageCount - 1 ? 'javascript:;' : $pages->createUrl($pages->page+1) ?>" class="next-pagi style-click"><span class="icon"></span></a>
		</div>
		<p><?= $pages->offset + 1 ?> - <?= $pages->offset + count($products) ?> Tin từ <?= $pages->totalCount ?> Tin</p>
	</div>
	<?php foreach ($products as $product): ?>
	<div class="item-listing">
		<?php 
			if($image = AdImages::find()->where(['order' => 0, 'product_id' => $product->id])->one()) {
				$imgUrl = $image->imageMedium;
			} else {
				$imgUrl = '/themes/metvuong2/resources/images/default-ads.jpg';
			}
		?>
		<div class="bgcover img-intro" style="background-image: url(<?= $imgUrl ?>);">
			<a href="<?= Url::to(['/ad/detail', 'id' => $product->id]) ?>"></a>
		</div>
		<p class="infor-by-up">
			<?= ucfirst($categories[$product->category_id]['name']) ?> <?= strtolower($types[$product->type]) ?> bởi <a href="#">Môi Giới</a>
		</p>
		<p class="address-listing"><a href="<?= Url::to(['/ad/detail', 'id' => $product->id]) ?>"><?= $product->getAddress(true) ?></a></p>
		<p class="attr-home">
			<?= $product->adProductAdditionInfo->room_no ? $product->adProductAdditionInfo->room_no . ' <span class="icon icon-bed"></span> | ' : '' ?>
			<?= $product->adProductAdditionInfo->toilet_no ? $product->adProductAdditionInfo->toilet_no . ' <span class="icon icon-bath"></span> | ' : '' ?>
			<span class="price"><?= StringHelper::formatCurrency($product->price) ?></span>
		</p>
	</div>
	<?php endforeach; ?>
	<div class="pull-right pagi">
		<a href="<?= $pages->page == 0 ? 'javascript:;' : $pages->createUrl($pages->page-1) ?>" class="prev-pagi style-click"><span class="icon"></span></a>
		<a href="<?= $pages->page == $pages->pageCount - 1 ? 'javascript:;' : $pages->createUrl($pages->page+1) ?>" class="next-pagi style-click"><span class="icon"></span></a>
	</div>
</div>