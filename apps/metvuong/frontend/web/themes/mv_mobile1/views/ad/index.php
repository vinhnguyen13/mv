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
	<form id="search-form" action="<?= Url::to(['/ad/index']) ?>" method="get">
		<!-- <div class="list-choice-city" data-prev-section="true">
			<ul class="clearfix"></ul>
			<em class="icon-pencil"></em>
		</div> -->
	
		<input type="hidden" name="sort" id="sort" class="value_selected" value="<?= ($sort = Yii::$app->request->get('sort')) ? $sort : 'created_at' ?>" />
		<div class="search-subpage clearfix">
			<div class="advande-search">
				<div class="toggle-search">
					<div class="frm-item">
						<div class="box-dropdown dropdown-common">
							<div class="val-selected style-click">
								<span class="selected" data-placeholder="Bạn ở tỉnh/thành nào?">Bạn ở tỉnh/thành nào?</span>
								<span class="pull-right icon arrowDown"></span>
							</div>
							<div class="item-dropdown hide-dropdown">
								<ul class="clearfix tinh-thanh"></ul>
								<input name="city" type="hidden" id="tinh-thanh" class="" value="<?= $cityId ?>">
							</div>
						</div>
					</div>
					<div class="frm-item">
						<div class="box-dropdown dropdown-common">
							<div class="val-selected style-click">
								<span class="selected" data-placeholder="Bạn ở quận/huyện nào?">Bạn ở quận/huyện nào?</span>
								<span class="pull-right icon arrowDown"></span>
							</div>
							<div class="item-dropdown hide-dropdown">
								<ul class="clearfix quan-huyen"></ul>
								<input name="district" type="hidden" id="quan-huyen" class="" value="<?= $districtId ?>">
							</div>
						</div>
					</div>
					<div class="frm-item">
						<div class="box-dropdown dropdown-common">
							<div class="val-selected style-click">
								<span class="selected" data-placeholder="Bất động sản bạn đang tìm?">Bất động sản bạn đang tìm?</span>
								<span class="pull-right icon arrowDown"></span>
							</div>
							<div class="item-dropdown hide-dropdown">
								<ul class="clearfix loai-bds"></ul>
								<input name="category" type="hidden" id="loai-bds" class="">
							</div>
						</div>
					</div>
					<div class="frm-item choice_price_dt" data-item-minmax="prices">
						<div class="box-dropdown">
							<div class="val-selected style-click price-search">
								Giá 
								<div>
									<span class="tu">từ</span>
									<span class="wrap-min">1 tỷ</span>
									<span class="trolen">trở lên</span>
									<span class="den">đến</span>
									<span class="wrap-max">4 tỷ</span>
									<span class="troxuong">trở xuống</span>
								</div>
								<span class="pull-right icon arrowDown"></span>
							</div>
							<div class="item-dropdown hide-dropdown wrap-min-max">
								<div class="box-input clearfix">
									<span class="txt-min min-max active min-val" data-value="">Thấp nhất</span>
									<span class="text-center">Đến</span>
									<span class="txt-max min-max max-val" data-value="">Cao nhất</span>
									<input type="hidden" id="priceMin" name="costMin" value="<?= Yii::$app->request->get('costMin') ?>" />
									<input type="hidden" id="priceMax" name="costMax" value="<?= Yii::$app->request->get('costMax') ?>" />
								</div>
								<div class="filter-minmax clearfix">
									<ul data-wrap-minmax="min-val" class="wrap-minmax"></ul>
									<ul data-wrap-minmax="max-val" class="wrap-minmax"></ul>
								</div>
							</div>
						</div>
					</div>
					<div class="frm-item choice_price_dt" data-item-minmax="area">
						<div class="box-dropdown">
							<div class="val-selected style-click dt-search">
								Diện tích
								<div>
									<span class="tu">từ</span>
									<span class="wrap-min">1 tỷ</span>
									<span class="trolen">trở lên</span>
									<span class="den">đến</span>
									<span class="wrap-max">4 tỷ</span>
									<span class="troxuong">trở xuống</span>
								</div>
								<span class="pull-right icon arrowDown"></span>
							</div>
							<div class="item-dropdown hide-dropdown wrap-min-max">
								<div class="box-input clearfix">
									<span class="txt-min min-max active min-val" data-value="">Thấp nhất</span>
									<span class="text-center">Đến</span>
									<span class="txt-max min-max max-val" data-value="">Cao nhất</span>
									<input type="hidden" id="dtMin" name="areaMin" value="<?= Yii::$app->request->get('areaMin') ?>" />
									<input type="hidden" id="dtMax" name="areaMax" value="<?= Yii::$app->request->get('areaMax') ?>" />
								</div>
								<div class="filter-minmax clearfix">
									<ul data-wrap-minmax="min-val" class="wrap-minmax"></ul>
									<ul data-wrap-minmax="max-val" class="wrap-minmax"></ul>
								</div>
							</div>
						</div>
					</div>
					<div class="frm-item clearfix">
						<div class="col-xs-6 num-phongngu">
							<div class="box-dropdown dropdown-common">
								<div class="val-selected style-click" data-text-add="Phòng ngủ trở lên"><span class="selected">Phòng ngủ</span><span class="pull-right icon arrowDown"></span></div>
	
								<div class="item-dropdown item-bed-bath hide-dropdown">
									<ul class="clearfix">
										<li><a href="#">1</a></li>
										<li><a href="#">2</a></li>
										<li><a href="#">3</a></li>
										<li><a href="#">4</a></li>
										<li><a href="#">5</a></li>
									</ul>
									<input type="hidden" id="val-bed" class="value_selected" name="roomNo" value="<?= Yii::$app->request->get('roomNo') ?>" />
								</div>
							</div>
						</div>
						<div class="col-xs-6 num-phongtam">
							<div class="box-dropdown dropdown-common">
								<div class="val-selected style-click" data-text-add="Phòng tắm trở lên"><span class="selected">Phòng tắm</span><span class="pull-right icon arrowDown"></span></div>
								<div class="item-dropdown item-bed-bath hide-dropdown">
									<ul class="clearfix">
										<li><a href="#">1</a></li>
										<li><a href="#">2</a></li>
										<li><a href="#">3</a></li>
										<li><a href="#">4</a></li>
										<li><a href="#">5</a></li>
									</ul>
									<input type="hidden" id="val-bath" class="value_selected" name="toiletNo" value="<?= Yii::$app->request->get('toiletNo') ?>" />
								</div>
							</div>
						</div>
					</div>
					<!-- <div class="frm-item row">
						<div class="col-xs-12 other-fill">
							<div class="value-selected style-click">Thêm tuỳ chọn<span class="pull-right icon arrowDown"></span></div>
						</div>
					</div> -->
				</div>
				
				<button class="btn-submit btn-common active" data-flag="true">Tìm kiếm</button>
			</div>
		</div>
	</form>
	<div class="wrap-listing clearfix">
		<div class="dropdown-select option-show-listing">
			<div class="val-selected style-click clearfix">
				Hiển thị theo <span class="selected">Tin mới nhất</span>
				<span class="icon arrowDown pull-right"></span>
			</div>
			<div class="item-dropdown hide-dropdown">
				<ul>
					<li><a data-value="created_at" href="#">Tin mới nhất</a></li>
					<li><a data-value="-price" href="#">Giá giảm dần</a></li>
					<li><a data-value="price" href="#">Giá tăng dần</a></li>
					<li><a data-value="score" href="#">Điểm cao nhất</a></li>
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
					<div class="bgcover img-intro">
						<div>
							<a class="rippler rippler-default" href="<?= $product->urlDetail(); ?>"><img src="" data-original="<?= $product->representImage ?>"></a>
						</div>
					</div>
					<p class="infor-by-up">
						<?= ucfirst($categories[$product->category_id]['name']) ?> <?= strtolower($types[$product->type]) ?> bởi <a href="#">Môi Giới</a>
					</p>
					<p class="address-listing">
						<span class="icon address-icon"></span><a href="<?= Url::to(['/ad/detail', 'id' => $product->id]) ?>"><?= $product->getAddress(true) ?></a>
					</p>
					<p class="id-duan">ID tin đăng:<span><?= Yii::$app->params['listing_prefix_id'] . $product->id;?></span></p>
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
							<span class="icon icon-bed icon-bed-small"></span> 02
						</li>
						<li>
							<span class="icon icon-pt icon-pt-small"></span> 02
						</li>
					</ul>
					<span class="price"><?= StringHelper::formatCurrency($product->price) ?></span>
					<a href="<?= Url::to(['/ad/detail', 'id' => $product->id, 'slug' => \common\components\Slug::me()->slugify($product->getAddress())]) ?>" class="pull-right view-more">Chi tiết</a>
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