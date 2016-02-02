<form>
	<input type="hidden" name="sort" id="sort" class="value_selected" value="price" />
	<div class="search-subpage clearfix">
		<div class="search-fill">
			<input class="style-click" type="text" placeholder="Tìm kiếm nhanh...">
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
</form>