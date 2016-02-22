<?php 

use yii\helpers\Url;
?>
<form id="search-form" action="<?= Url::to(['/ad/index']) ?>" method="get">
	<!-- <div class="list-choice-city" data-prev-section="true">
		<ul class="clearfix"></ul>
		<em class="icon-pencil"></em>
	</div> -->

	<input type="hidden" name="sort" id="sort" class="value_selected" value="<?= ($sort = Yii::$app->request->get('sort')) ? $sort : 'created_at' ?>" />
	<div class="search-subpage clearfix">
		<div class="advande-search">
			<div class="toggle-search hide-dropdown">
				<div class="frm-item">
					<div class="box-dropdown dropdown-common">
						<div class="val-selected style-click">
							<span class="selected" data-placeholder="Bạn ở tỉnh/thành nào?">Bạn ở tỉnh/thành nào?</span>
							<span class="pull-right icon arrowDown"></span>
						</div>
						<div class="item-dropdown hide-dropdown">
							<ul class="clearfix tinh-thanh"></ul>
							<input name="city" type="hidden" id="tinh-thanh" class="">
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
							<input name="district" type="hidden" id="quan-huyen" class="">
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
			
			<button class="btn-submit btn-common" data-flag="true">Tìm kiếm</button>
		</div>
	</div>
</form>
<script>
$(document).ready(function () {
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

	$('.dropdown-common').dropdown({
		txtAdd: true,
		styleShow: 0,
		funCallBack: function (item) {
			var selectedCityList = $('<li data-value="'+item.data('value')+'" data-order="'+item.data('order')+'">'+item.text()+'<span class="icon arrow-left arrow-small"></span></li>');

			if ( item.closest('.tinh-thanh').length > 0 ) {
				var idTT = item.data('value');
				$('.quan-huyen').html('');
				var txtDefault = $('.quan-huyen').closest('.box-dropdown').find('.val-selected').data('placeholder');
				$('.quan-huyen').closest('.box-dropdown').find('.val-selected').text(txtDefault);

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

	$('.choice_price_dt').price_dt();

	$('.advande-search').toggleShowMobi({
        btnEvent: '.btn-submit',
        itemToggle: '.toggle-search'
    });
});
</script>