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
	<div class="section hide choice-city">
		<div class="select-first-search">
			<div class="frm-item">
				<div class="title-frm color-30a868">Bạn ở thành phố nào?</div>
				<div class="box-dropdown">
					<div class="val-selected style-click">
						<span class="selected selected_val">Chọn thành phố...</span>
						<span class="pull-right icon arrowDown"></span>
					</div>
					<div class="item-dropdown hide-dropdown">
						<ul class="clearfix">
							<li><a href="#">Hồ Chí Minh</a></li>
							<li><a href="#">Hà Nội</a></li>
							<li><a href="#">Đà Nẵng</a></li>
						</ul>
						<input type="hidden" id="tinh-thanh" class="">
					</div>
				</div>
			</div>
			<div class="frm-item">
				<div class="title-frm color-30a868">Bạn ở quận nào?</div>
				<div class="box-dropdown">
					<div class="val-selected style-click">
						<span class="selected selected_val">Chọn quận...</span>
						<span class="pull-right icon arrowDown"></span>
					</div>
					<div class="item-dropdown hide-dropdown">
						<ul class="clearfix">
							<li><a href="#">Quận 1</a></li>
							<li><a href="#">Quận 2</a></li>
							<li><a href="#">Tân Bình</a></li>
							<li><a href="#">Tân Phú</a></li>
							<li><a href="#">Quận 3</a></li>
						</ul>
						<input type="hidden" id="quan-huyen" class="">
					</div>
				</div>
			</div>
			<div class="frm-item">
				<div class="title-frm color-30a868">Bất động sản bạn đang tìm?</div>
				<div class="box-dropdown">
					<div class="val-selected style-click">
						<span class="selected selected_val">Loại BĐS...</span>
						<span class="pull-right icon arrowDown"></span>
					</div>
					<div class="item-dropdown hide-dropdown">
						<ul class="clearfix">
							<li><a href="#">Nhà Riêng</a></li>
							<li><a href="#">Chung Cư</a></li>
							<li><a href="#">Nhà Phố</a></li>
						</ul>
						<input type="hidden" id="loai-bds" class="">
					</div>
				</div>
			</div>
			<div class="text-center">
				<button data-next-section="true" class="btn-common">tiếp theo</button>
			</div>
		</div>
	</div>
	<div class="section hide">
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

						<div class="bgcover img-intro">
							<div>
								<a href="<?= Url::to(['/ad/detail', 'id' => $product->id]) ?>"><img src="<?= $imgUrl ?>"></a>
							</div>
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
				<?php else: ?>
				<div id="no-result">Chưa có tin đăng theo tìm kiếm của bạn, <a href="#">đăng ký nhận thông báo khi có tin đăng phù hợp</a>.</div>
				<?php endif; ?>
			</div>
			<div id="item-loading" style="text-align: center;" class="<?php if($pages->page >= $pages->pageCount - 1) echo 'hide' ?>">
				<img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/loading-listing.gif' ?>" />
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function () {
		$('.result-listing').slideSection({
			active: 0,
			navi: false,
			validateFrm: function () {
				//return false => fill khong thoa yeu cau => khong next
				//return true => fill thoa yeu cau => next screen
				var flag = true;
				$('.choice-city').find('input[type=hidden]').each(function () {
					if ( $(this).val() == '' ) {
						flag = false;
						$(this).closest('.frm-item').find('.val-selected').addClass('error');
					}else {
						$(this).closest('.frm-item').find('.val-selected').removeClass('error');
					}
				});
				return flag;
			},
			callBackAjax: function () {
				$('body').loading();
				$.ajax({
					url: "https://dl.dropboxusercontent.com/u/43486987/test.txt", 
					success: function(result){
				        $('body').loading({done: true});
				    }
				});
			}
		});

		$('.box-dropdown').dropdown({
			styleShow: 0
		});
	});
</script>