<?php
use vsoft\ad\models\AdImages;
use vsoft\express\components\StringHelper;
use vsoft\ad\models\AdCategory;
use yii\helpers\Url;
use vsoft\ad\models\AdProduct;
use yii\web\View;

$this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/listing.js', ['position' => View::POS_END]);

$categories = AdCategory::find ()->indexBy ( 'id' )->asArray ( true )->all ();
$types = AdProduct::getAdTypes ();
?>
<form id="" action="">
	<div class="search-subpage clearfix">
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
			<div class="each-advande choice_price_dt">
				<div class="value-selected price-search style-click">
					Giá
					<div>
						<span class="tu">từ</span> <span class="wrap-min">1 tỷ</span> <span
							class="trolen">trở lên</span> <span class="den">đến</span> <span
							class="wrap-max">4 tỷ</span> <span class="troxuong">trở xuống</span>
					</div>
				</div>
				<div class="item-advande row">
					<div class="col-xs-5">
						<input type="number"
							class="form-control style-click input-min min-max" id=""
							placeholder="Thấp nhất">
					</div>
					<div class="col-xs-2 from-to-value">đến</div>
					<div class="col-xs-5">
						<input type="number"
							class="form-control style-click input-max min-max" id=""
							placeholder="Cao nhất">
					</div>
				</div>
			</div>
			<div class="each-advande choice_price_dt">
				<div class="value-selected dt-search style-click">
					Diện tích
					<div>
						<span class="tu">từ</span> <span class="wrap-min">1 tỷ</span> <span
							class="trolen">trở lên</span> <span class="den">đến</span> <span
							class="wrap-max">4 tỷ</span> <span class="troxuong">trở xuống</span>
					</div>
				</div>
				<div class="item-advande row">
					<div class="col-xs-5">
						<input type="number"
							class="form-control style-click min-max input-min" id=""
							placeholder="Thấp nhất">
					</div>
					<div class="col-xs-2 from-to-value">đến</div>
					<div class="col-xs-5">
						<input type="number"
							class="form-control style-click min-max input-max" id=""
							placeholder="Cao nhất">
					</div>
				</div>
			</div>
			<div class="each-advande clearfix">
				<div class="col-xs-6 num-phongngu">
					<div class="value-selected style-click val-selected"
						data-text-add="Phòng ngủ trở lên">
						<span class="selected">Phòng ngủ</span>
					</div>

					<div class="item-advande item-dropdown item-bed-bath">
						<ul class="clearfix">
							<li><a href="#">1</a></li>
							<li><a href="#">2</a></li>
							<li><a href="#">3</a></li>
							<li><a href="#">4</a></li>
							<li><a href="#">5</a></li>
						</ul>
						<input type="hidden" id="val-bed" class="value_selected" />
					</div>
				</div>
				<div class="col-xs-6 num-phongtam">
					<div class="value-selected style-click val-selected"
						data-text-add="Phòng tắm trở lên">
						<span class="selected">Phòng tắm</span>
					</div>
					<div class="item-advande item-dropdown item-bed-bath">
						<ul class="clearfix">
							<li><a href="#">1</a></li>
							<li><a href="#">2</a></li>
							<li><a href="#">3</a></li>
							<li><a href="#">4</a></li>
							<li><a href="#">5</a></li>
						</ul>
						<input type="hidden" id="val-bath" class="value_selected" />
					</div>
				</div>
			</div>
			<div class="each-advande row">
				<div class="col-xs-12 other-fill">
					<div class="value-selected style-click">Thêm tuỳ chọn</div>
				</div>
			</div>
		</div>
	</div>

	<div class="wrap-listing clearfix">
		<div class="dropdown-select option-show-listing">
			<div class="val-selected style-click">
				Hiển thị tin theo <span class="selected">Tin mới nhất</span>
			</div>
			<div class="item-dropdown hide-dropdown">
				<ul>
					<li><a data-value="score" href="#">Điểm MetVuong cao nhất</a></li>
					<li><a data-value="created_at" href="#">Mới nhất</a></li>
					<li><a data-value="-price" href="#">Giá thấp nhất - cao nhất</a></li>
					<li><a data-value="price" href="#">Giá cao nhất - thấp nhất</a></li>
				</ul>
				<input type="hidden" name="sort" id="sort" class="value_selected"
					value="price" />
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
			if ($image = AdImages::find ()->where ( [ 
					'order' => 0,
					'product_id' => $product->id 
			] )->one ()) {
				$imgUrl = $image->imageMedium;
			} else {
				$imgUrl = '/themes/metvuong2/resources/images/default-ads.jpg';
			}
			?>
			<div class="bgcover img-intro" style="background-image: url(<?= $imgUrl ?>);">
				<a href="<?= Url::to(['/ad/detail', 'id' => $product->id]) ?>"></a>
			</div>
			<p class="infor-by-up">
				<?= ucfirst($categories[$product->category_id]['name']) ?> <?= strtolower($types[$product->type]) ?> bởi <a
					href="#">Môi Giới</a>
			</p>
			<p class="address-listing">
				<a href="<?= Url::to(['/ad/detail', 'id' => $product->id]) ?>"><?= $product->getAddress(true) ?></a>
			</p>
			<p class="attr-home">
				<?= $product->adProductAdditionInfo->room_no ? $product->adProductAdditionInfo->room_no . ' <span class="icon icon-bed"></span> | ' : ''?>
				<?= $product->adProductAdditionInfo->toilet_no ? $product->adProductAdditionInfo->toilet_no . ' <span class="icon icon-bath"></span> | ' : ''?>
				<span class="price"><?= StringHelper::formatCurrency($product->price) ?></span>
			</p>
		</div>
		<?php endforeach; ?>
		<div class="pull-right pagi">
			<a href="<?= $pages->page == 0 ? 'javascript:;' : $pages->createUrl($pages->page-1) ?>" class="prev-pagi style-click"><span class="icon"></span></a>
			<a href="<?= $pages->page == $pages->pageCount - 1 ? 'javascript:;' : $pages->createUrl($pages->page+1) ?>" class="next-pagi style-click"><span class="icon"></span></a>
		</div>
		<div class="pull-right pagi">
			<a href="#" class="prev-pagi style-click"><span class="icon"></span></a>
			<a href="#" class="next-pagi style-click"><span class="icon"></span></a>
		</div>
	</div>
</form>