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
	
	<div class="top-listing clearfix">
		<div class="pull-right pagi">
			<a href="<?= $pages->page == 0 ? 'javascript:;' : $pages->createUrl($pages->page-1) ?>" class="prev-pagi style-click"><span class="icon"></span></a>
			<a href="<?= $pages->page == $pages->pageCount - 1 ? 'javascript:;' : $pages->createUrl($pages->page+1) ?>" class="next-pagi style-click"><span class="icon"></span></a>
		</div>
		<p><?= $pages->offset + 1 ?> - <?= $pages->offset + count($products) ?> Tin từ <?= $pages->totalCount ?> Tin</p>
	</div>
	<div id="listing-list">
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
	</div>
	<div id="item-loading" style="text-align: center;">
		<img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/loading-listing.gif' ?>" />
	</div>
</div>