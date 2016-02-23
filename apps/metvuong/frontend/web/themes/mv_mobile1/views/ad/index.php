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

<div class="result-listing clearfix">
	<?= $this->render('_partials/search-form') ?>
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
			</div>
		</div>
		<div id="content-holder">
			<?php if(count($products) > 0): ?>
			<div class="top-listing clearfix">
				<p><?= $pages->offset + 1 ?> - <span id="count-to"><?= $pages->offset + count($products) ?></span> Tin từ <?= $pages->totalCount ?> Tin</p>
			</div>
			<div id="listing-list" class="wrap-lazy">
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

					<div class="bgcover img-intro">
						<div>
							<a href="<?= Url::to(['/ad/detail', 'id' => $product->id]) ?>"><img src="" data-original="<?= $imgUrl ?>"></a>
						</div>
					</div>
					<p class="infor-by-up">
						<?= ucfirst($categories[$product->category_id]['name']) ?> <?= strtolower($types[$product->type]) ?> bởi <a
							href="#">Môi Giới</a>
					</p>
					<p class="address-listing">
						<span class="icon address-icon"></span><a href="<?= Url::to(['/ad/detail', 'id' => $product->id]) ?>"><?= $product->getAddress(true) ?></a>
					</p>
					<!-- <p class="attr-home">
						<?= $product->adProductAdditionInfo->room_no ? $product->adProductAdditionInfo->room_no . ' <span class="icon icon-bed"></span> | ' : ''?>
						<?= $product->adProductAdditionInfo->toilet_no ? $product->adProductAdditionInfo->toilet_no . ' <span class="icon icon-bath"></span> | ' : ''?>
						<span class="price"><?= StringHelper::formatCurrency($product->price) ?></span>
					</p> -->
					<ul class="clearfix list-attr-td">
						<li>
							<span class="icon icon-dt icon-dt-small"></span>80m2
						</li>
						<li>
							<span class="icon icon-bed icon-bed-small"></span> 2
						</li>
						<li>
							<span class="icon icon-pt icon-pt-small"></span> 2
						</li>
					</ul>
					<span class="price"><?= StringHelper::formatCurrency($product->price) ?></span>
					<a href="<?= Url::to(['/ad/detail', 'id' => $product->id]) ?>" class="pull-right view-more">Chi tiết</a>
				</div>
				<?php endforeach; ?>
			</div>
			<?php else: ?>
			<div id="no-result">Chưa có tin đăng theo tìm kiếm của bạn, <a href="#">đăng ký nhận thông báo khi có tin đăng phù hợp</a>.</div>
			<?php endif; ?>
		</div>
		<div id="item-loading" style="text-align: center;" class="<?php if($pages->page >= $pages->pageCount - 1) echo 'hide' ?>">
			<img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/loading-listing.gif' ?>" />
		</div>
	</div>
</div>