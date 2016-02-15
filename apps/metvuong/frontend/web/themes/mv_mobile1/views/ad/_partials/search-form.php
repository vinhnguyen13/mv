<?php 

use yii\helpers\Url;
?>
<form id="search-form" action="<?= Url::to(['/ad/index']) ?>" method="get">
	<input type="hidden" name="sort" id="sort" class="value_selected" value="price" />
	<input type="hidden" name="page" id="page" value="<?= ($page = Yii::$app->request->get('page')) ? $page : 2 ?>" />
	<div class="search-subpage clearfix">
					<div class="search-fill">
						<div class="fillter-fast">
							<div class="val-selected style-click"><span class="selected selected_val">Tìm kiếm nhanh...</span></div>
							<div class="item-dropdown hide-dropdown">
								<strong>Bạn hãy chọn quận</strong>
								<ul class="clearfix">
									<li><a href="#">Quận 1</a></li>
									<li><a href="#">Quận 2</a></li>
									<li><a href="#">Quận 3</a></li>
									<li><a href="#">Quận 4</a></li>
									<li><a href="#">Quận 5</a></li>
									<li><a href="#">Quận 6</a></li>
									<li><a href="#">Quận 7</a></li>
								</ul>
								<input type="hidden" id="quan-huyen" class="">
							</div>
						</div>
						<!-- <a href="#" class="advande-search-btn style-click"><span class="bd-left"></span><span class="bd-right"></span></a> -->
					</div>
					<div class="advande-search">
						<div class="each-advande choice_price_dt" data-item-minmax="prices">
							<div class="value-selected price-search style-click">
								Giá 
								<div>
									<span class="tu">từ</span>
									<span class="wrap-min">1 tỷ</span>
									<span class="trolen">trở lên</span>
									<span class="den">đến</span>
									<span class="wrap-max">4 tỷ</span>
									<span class="troxuong">trở xuống</span>
								</div>
							</div>

							<div class="item-advande">
								<div class="box-input clearfix">
									<input readonly="readonly" class="min-max active min-val" name="price-min" id="" type="text" placeholder="Thấp nhất">
									<span class="text-center">Đến</span>
									<input readonly="readonly" class="min-max max-val" name="price-max" id="" type="text" placeholder="Cao nhất">
									<input type="hidden" id="priceMin" />
									<input type="hidden" id="priceMax" />
								</div>
								<div class="filter-minmax clearfix">
									<ul data-wrap-minmax="min-val" class="wrap-minmax"></ul>
									<ul data-wrap-minmax="max-val" class="wrap-minmax"></ul>
								</div>
							</div>
						</div>
						<div class="each-advande choice_price_dt" data-item-minmax="area">
							<div class="value-selected dt-search style-click">
								Diện tích
								<div>
									<span class="tu">từ</span>
									<span class="wrap-min">1 tỷ</span>
									<span class="trolen">trở lên</span>
									<span class="den">đến</span>
									<span class="wrap-max">4 tỷ</span>
									<span class="troxuong">trở xuống</span>
								</div>
							</div>
							<div class="item-advande">
								<div class="box-input clearfix">
									<input readonly="readonly" class="min-max active min-val" name="price-min" id="" type="text" placeholder="Thấp nhất">
									<span class="text-center">Đến</span>
									<input readonly="readonly" class="min-max max-val" name="price-max" id="" type="text" placeholder="Cao nhất">
									<input type="hidden" id="dtMin" />
									<input type="hidden" id="dtMax" />
								</div>
								<div class="filter-minmax clearfix">
									<ul data-wrap-minmax="min-val" class="wrap-minmax"></ul>
									<ul data-wrap-minmax="max-val" class="wrap-minmax"></ul>
								</div>
							</div>
						</div>
						<div class="each-advande clearfix">
							<div class="col-xs-6 num-phongngu">
								<div class="value-selected style-click val-selected" data-text-add="Phòng ngủ trở lên"><span class="selected">Phòng ngủ</span></div>

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
								<div class="value-selected style-click val-selected" data-text-add="Phòng tắm trở lên"><span class="selected">Phòng tắm</span></div>
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
						<button class="btn-submit btn-common">Tìm kiếm</button>
					</div>
				</div>
</form>
<script>
$(document).ready(function () {
	$('.fillter-fast').dropdown({
		hiddenFillValue: '#quan-huyen',
		styleShow: 0
	});
	
	$('.num-phongngu').dropdown({
		txtAdd: true,
		styleShow: 0,
		hiddenFillValue: '#val-bed'
	});
	
	$('.num-phongtam').dropdown({
		txtAdd: true,
		styleShow: 0,
		hiddenFillValue: '#val-bath'
	});

	$('.search-subpage').toggleShowMobi();
	
	$('.choice_price_dt').price_dt();
});
</script>