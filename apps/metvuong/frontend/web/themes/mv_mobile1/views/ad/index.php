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
						<span class="selected selected_val" data-placeholder="Chọn tỉnh/thành...">Chọn tỉnh/thành...</span>
						<span class="pull-right icon arrowDown"></span>
					</div>
					<div class="item-dropdown hide-dropdown">
						<ul class="clearfix tinh-thanh"></ul>
						<input type="hidden" id="tinh-thanh" class="">
					</div>
				</div>
			</div>
			<div class="frm-item">
				<div class="title-frm color-30a868">Bạn ở quận nào?</div>
				<div class="box-dropdown">
					<div class="val-selected style-click">
						<span class="selected selected_val" data-placeholder="Chọn quận/huyện...">Chọn quận/huyện...</span>
						<span class="pull-right icon arrowDown"></span>
					</div>
					<div class="item-dropdown hide-dropdown">
						<ul class="clearfix quan-huyen"></ul>
						<input type="hidden" id="quan-huyen" class="">
					</div>
				</div>
			</div>
			<div class="frm-item">
				<div class="title-frm color-30a868">Bất động sản bạn đang tìm?</div>
				<div class="box-dropdown">
					<div class="val-selected style-click">
						<span class="selected selected_val" data-placeholder="Loại BĐS...">Loại BĐS...</span>
						<span class="pull-right icon arrowDown"></span>
					</div>
					<div class="item-dropdown hide-dropdown">
						<ul class="clearfix loai-bds"></ul>
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
				if ( $('input#tinh-thanh').val() == '' ) {
					flag = false;
					$('input#tinh-thanh').closest('.frm-item').find('.val-selected').addClass('error');
				}
				return flag;
			},
			funCallBack: function () {
				
			}
		});

		$('.tinh-thanh').html('');
		$('.loai-bds').html('');
		for ( var i in dataCities) {
			var item = $('<li><a href="#" data-value="'+i+'" data-order="1">'+dataCities[i].name+'</a></li>');
			$('.tinh-thanh').append(item);
		}
		for ( var i in dataCategories) {
			var item = $('<li><a href="#" data-value="'+i+'" data-order="3">'+dataCategories[i].name+'</a></li>');
			$('.loai-bds').append(item);
		}

		$('.choice-city .box-dropdown').dropdown({
			styleShow: 0,
			funCallBack: function (item) {
				var selectedCityList = $('<li data-value="'+item.data('value')+'" data-order="'+item.data('order')+'">'+item.text()+'<span class="icon arrow-left arrow-small"></span></li>');

				if ( $('.list-choice-city ul li').length > 0 ) {
					$('.list-choice-city ul li').each(function () {
						var _this = $(this),
							orderThis = _this.data('order'),
							orderAddItem = item.data('order');
						if ( orderAddItem > orderThis ) {
							selectedCityList.insertAfter(_this);
						}else {
							selectedCityList.insertBefore(_this);
						}
					});
				}else {
					$('.list-choice-city ul').append(selectedCityList);
				}

				if ( item.closest('.tinh-thanh').length > 0 ) {
					var idTT = item.data('value');
					$('.quan-huyen').html('');
					var txtDefault = $('.quan-huyen').closest('.box-dropdown').find('.selected_val').data('placeholder');
					$('.quan-huyen').closest('.box-dropdown').find('.selected_val').text(txtDefault);

					for ( var i in dataCities) {
						if ( i == idTT ) {
							for ( var j in dataCities[i].districts ) {
								var item = $('<li><a href="#" data-value="'+j+'" data-order="2">'+dataCities[i].districts[j].name+'</a></li>');
								$('.quan-huyen').append(item);
							}
							break;
						}
					}
				}
			}
		});		
	});
</script>