
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
	</div>
</div>

<div class="post-listing">
	<form action="">
		<div class="step-link">
			<ul class="clearfix">
				<li><a data-active-section="tt-chung" class="active" href="#">1</a><span class="icon"></span></li>
				<li><a data-active-section="tt-chitiet" href="#">2</a><span class="icon"></span></li>
				<li><a data-active-section="hinh-anh" href="#">3</a><span class="icon"></span></li>
				<li><a data-active-section="tien-ich" href="#">4</a><span class="icon"></span></li>
				<li><a data-active-section="tt-lienhe" href="#">5</a></li>
			</ul>
		</div>
		<div data-section="select-type" class="section select-type hide">
			<p class="text-center step-txt">4 bước dễ dàng</p>
		
			<ul class="clearfix step-check">
				<li>
					<a class="frm-radio" href="#">
						<span class="radio-ui icon"><input name="banchothue" type="radio" checked></span>
						<span>Bán</span>
					</a>
				</li>
				<li>
					<a class="frm-radio" href="#">
						<span class="radio-ui icon"><input name="banchothue" type="radio"></span>
						<span>Cho Thuê</span>
					</a>
				</li>
				<li>
					<a class="frm-radio" href="#">
						<span class="radio-ui icon"><input data-flag="true" name="agent" type="radio" checked></span>
						<span>Chủ nhà</span>
						<span class="txt-limit">ĐĂNG ĐẾN 3 TIN</span>
					</a>
				</li>
				<li>
					<a class="frm-radio" href="#">
						<span class="radio-ui icon"><input data-flag="true" name="agent" type="radio"></span>
						<span>Môi giới</span>
						<span class="txt-limit">ĐĂNG ĐẾN 10 TIN</span>
					</a>
				</li>
			</ul>
		</div>

		<div data-section="tt-chung" class="tt-chung item-step section hide">
			<div class="title-step">Thông tin chung</div>
			<div class="row">
				<div class="col-xs-6 form-group">
					<select class="form-control" name="" id="">
						<option value="" disabled="" selected="">Tỉnh / Thành</option>
						<option value="">Hồ Chí Minh</option>
						<option value="">Hà Nội</option>
						<option value="">Đồng Nai</option>
					</select>
				</div>
				<div class="col-xs-6 form-group">
					<select class="form-control" name="" id="">
						<option value="" disabled="" selected="">Quận / Huyện</option>
						<option value="">Hồ Chí Minh</option>
						<option value="">Hà Nội</option>
						<option value="">Đồng Nai</option>
					</select>
				</div>
				<div class="col-xs-12 form-group">
					<input type="text" class="form-control" id="" placeholder="Đường">
				</div>
				<div class="col-xs-12 form-group">
					<input type="text" class="form-control" id="" placeholder="Số nhà">
				</div>
				<div class="col-xs-6 form-group">
					<select class="form-control" name="" id="">
						<option value="" disabled="" selected="">Phường</option>
						<option value="">Hồ Chí Minh</option>
						<option value="">Hà Nội</option>
						<option value="">Đồng Nai</option>
					</select>
				</div>
				<div class="col-xs-6 form-group">
					<select class="form-control" name="" id="">
						<option value="" disabled="" selected="">Loại BĐS</option>
						<option value="">Hồ Chí Minh</option>
						<option value="">Hà Nội</option>
						<option value="">Đồng Nai</option>
					</select>
				</div>
				<div class="col-xs-12 form-group txt-unit">
					<input type="text" class="form-control" id="" placeholder="Số nhà">
					<span>m2</span>
				</div>
				<div class="col-xs-12 form-group txt-unit">
					<input type="text" class="form-control" id="" placeholder="Giá">
					<span>VNĐ</span>
				</div>
				<div class="col-xs-6 form-group">
					<select class="form-control" name="" id="">
						<option value="" disabled="" selected="">Phòng ngủ</option>
						<option value="">Hồ Chí Minh</option>
						<option value="">Hà Nội</option>
						<option value="">Đồng Nai</option>
					</select>
				</div>
				<div class="col-xs-6 form-group">
					<select class="form-control" name="" id="">
						<option value="" disabled="" selected="">Phòng tắm</option>
						<option value="">Hồ Chí Minh</option>
						<option value="">Hà Nội</option>
						<option value="">Đồng Nai</option>
					</select>
				</div>
			</div>
		</div>

		<div data-section="tt-chitiet" class="tt-chitiet item-step section hide">
			<div class="title-step">Thông tin chi tiết</div>
			<div class="row">
				<div class="col-xs-12 form-group">
					<input type="text" class="form-control" id="" placeholder="Mặt tiền (m)">
				</div>
				<div class="col-xs-12 form-group">
					<input type="text" class="form-control" id="" placeholder="Đường vào (m)">
				</div>
				<div class="col-xs-6 form-group">
					<select class="form-control" name="" id="">
						<option value="" disabled="" selected="">Tầng cao</option>
						<option value="">Hồ Chí Minh</option>
						<option value="">Hà Nội</option>
						<option value="">Đồng Nai</option>
					</select>
				</div>
				<div class="col-xs-6 form-group">
					<select class="form-control" name="" id="">
						<option value="" disabled="" selected="">Thang máy</option>
						<option value="">Hồ Chí Minh</option>
						<option value="">Hà Nội</option>
						<option value="">Đồng Nai</option>
					</select>
				</div>
				<div class="col-xs-12 form-group">
					<input type="text" class="form-control" id="" placeholder="Chủ đầu tư">
				</div>
				<div class="col-xs-12 form-group">
					<input type="text" class="form-control" id="" placeholder="Nhà thầu xây dựng">
				</div>
				<div class="col-xs-6 form-group">
					<select class="form-control" name="" id="">
						<option value="" disabled="" selected="">Ngày khởi công</option>
						<option value="">Hồ Chí Minh</option>
						<option value="">Hà Nội</option>
						<option value="">Đồng Nai</option>
					</select>
				</div>
				<div class="col-xs-6 form-group">
					<select class="form-control" name="" id="">
						<option value="" disabled="" selected="">Ngày hoàn thiện</option>
						<option value="">Hồ Chí Minh</option>
						<option value="">Hà Nội</option>
						<option value="">Đồng Nai</option>
					</select>
				</div>
				<div class="col-xs-12 form-group txt-unit">
					<input type="text" class="form-control" id="" placeholder="Phí đậu xe gắn máy">
					<span>VNĐ</span>
				</div>
				<div class="col-xs-12 form-group txt-unit">
					<input type="text" class="form-control" id="" placeholder="Phí đậu xe hơi">
					<span>VNĐ</span>
				</div>
				<div class="col-xs-6 form-group">
					<select class="form-control" name="" id="">
						<option value="" disabled="" selected="">Phòng ngủ</option>
						<option value="">Hồ Chí Minh</option>
						<option value="">Hà Nội</option>
						<option value="">Đồng Nai</option>
					</select>
				</div>
				<div class="col-xs-6 form-group">
					<select class="form-control" name="" id="">
						<option value="" disabled="" selected="">Phòng tắm</option>
						<option value="">Hồ Chí Minh</option>
						<option value="">Hà Nội</option>
						<option value="">Đồng Nai</option>
					</select>
				</div>
				<div class="col-xs-12 form-group">
					<textarea class="form-control" rows="3" placeholder="Chính sách ưu đãi (dưới 200 chữ)"></textarea>
				</div>
				<div class="col-xs-12 form-group">
					<textarea class="form-control" rows="3" placeholder="Mô tả dự án (dưới 200 chữ)"></textarea>
				</div>
			</div>
		</div>

		<div data-section="hinh-anh" class="hinh-anh item-step section hide">
			<div class="title-step">Hình ảnh</div>
			<ul class="clearfix upload-img">
				<li>
					<a href="#"><span class="icon"></span><span>Hình ảnh</span></a>
				</li>
				<li>
					<a href="#"><span class="icon"></span><span>Bản vẽ</span></a>
				</li>
			</ul>
		</div>

		<div data-section="tien-ich" class="tien-ich item-step section hide">
			<div class="title-step">Tiện ích</div>
			<ul class="clearfix list-tienich">
				<li>
					<a href="#"><span class="icon"></span><span>24/7 Security</span></a>
				</li>
				<li>
					<a href="#"><span class="icon"></span><span>Mailbox</span></a>
				</li>
				<li>
					<a href="#"><span class="icon"></span><span>24/7 Security</span></a>
				</li>
				<li>
					<a href="#"><span class="icon"></span><span>Swimming Pool</span></a>
				</li>
				<li>
					<a href="#"><span class="icon"></span><span>Gym</span></a>
				</li>
				<li>
					<a href="#"><span class="icon"></span><span>BBQ Area</span></a>
				</li>
			</ul>
		</div>

		<div data-section="tt-lienhe" class="tt-lienhe item-step section hide">
			<div class="title-step">Thông tin liên hệ</div>
			<div class="row">
				<div class="col-xs-12 form-group">
					<input type="text" class="form-control" id="" placeholder="Họ / Tên">
				</div>
				<div class="col-xs-12 form-group">
					<input type="text" class="form-control" id="" placeholder="Điện thoại di động">
				</div>
				<div class="col-xs-12 form-group">
					<input type="text" class="form-control" id="" placeholder="E-mail">
				</div>
				<div class="col-xs-12 form-group">
					<input type="text" class="form-control" id="" placeholder="Công ty môi giới">
				</div>
			</div>
			<div class="agent-avatar">
				<div class="wrap-img"><img src="images/MV-Agent Photo.jpg" alt="" /></div>
				<button class="upload-avatar">Tải hình đại diện khác</button>
			</div>
			<div class="text-center">
				<button class="preview">Preview</button>
				<button class="btn-post">Đăng tin</button>
			</div>
		</div>
	</form>
</div>

<script>
	$(document).ready(function () {
		$('.dropdown-select').dropdown({
			hiddenFillValue: '#sort'
		});

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

		$('.frm-radio').radio({
			done: function (item) {
				/*if ( item.attr('name') == 'agent' ) {
					setTimeout(function() {
						$('#next-screen').trigger('click');
					},250);
				}*/
			}
		});

		$('.post-listing').slideSection({
			active: 0,
			validateFrm: function () {
				//return false => fill khong thoa yeu cau => khong next
				//return true => fill thoa yeu cau => next screen
				return true;
			}
		});
	});
</script> 