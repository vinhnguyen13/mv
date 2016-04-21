<?php
	use yii\web\View;
	
	$this->registerCssFile(Yii::$app->view->theme->baseUrl . '/resources/css/test.css');
	$this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/test.js', ['position' => View::POS_END]);
?>
<div style="padding: 12px;">
	<div class="clearfix nav">
		<a href="#" class="prev disabled">trở lại</a>
		<a href="#" class="next">Tiếp theo</a>
	</div>
	<div class="step active">
		<div class="clearfix">
			<div style="padding: 12px 24px; float: left;"><input id="type-buy" type="radio" name="type" checked="checked" /><label for="type-buy">Mua</label></div>
			<div style="padding: 12px 24px; float: left;"><input id="type-rent" type="radio" name="type" /><label for="type-rent">Bán</label></div>
		</div>
		<div class="clearfix">
			<div style="padding: 12px 24px; float: left;"><input class="owner" id="owner-1" value="0" type="radio" name="owner" checked="checked" /><label for="owner-1">Chủ nhà</label></div>
			<div style="padding: 12px 24px; float: left;"><input class="owner" id="owner-2" value="1" type="radio" name="owner" /><label for="owner-2">Môi giới</label></div>
		</div>
		<div>
			<select id="category" >
				<option value="0">Loại BĐS</option>
				<option value="324">Căn hộ chung cư</option>
				<option value="41">Nhà riêng</option>
				<option value="325">Nhà biệt thự, liền kề</option>
				<option value="163">Nhà mặt phố</option>
				<option value="40">Đất nền dự án</option>
				<option value="283">Bán đất</option>
			</select>
		</div>
	</div>
	<div class="step">
		<h3>Bước 1</h3>
		<h4>Thông tin chung</h4>
		<select id="city"></select>
		<select id="district">
			<option value="">Chọn Quận/Huyện</option>
		</select>
		<select id="ward">
			<option value="">Chọn Phường/Xã</option>
		</select>
		<select id="street">
			<option value="">Chọn Đường</option>
		</select>
		<input type="text" placeholder="Số nhà" />
		<div class="p"><input type="text" placeholder="Diện tích" /><span class="a">m2</span></div>
		<div class="p"><input type="text" placeholder="Giá" /><span class="a">VND</span></div>
		<select id="bed">
			<option>Phòng ngủ</option>
			<option>1+</option>
			<option>2+</option>
			<option>3+</option>
			<option>4+</option>
			<option>5+</option>
			<option>6+</option>
		</select>
		<select id="bath">
			<option>Phòng tắm</option>
			<option>1+</option>
			<option>2+</option>
			<option>3+</option>
			<option>4+</option>
			<option>5+</option>
			<option>6+</option>
		</select>
	</div>
	<div class="step">
		<h3>Bước 2</h3>
		<h4>Thông tin chi tiết</h4>
		<textarea rows="" cols="" placeholder="Nội dung tin đăng"></textarea>
		<div class="show-cho-nha-rieng">
			<div class="p"><input type="text" placeholder="Mặt tiền" /><span class="a">m2</span></div>
			<div class="p"><input type="text" placeholder="Đường vào" /><span class="a">m2</span></div>
			<select>
<option value="">Hướng nhà</option>
<option value="0">KXĐ</option>
<option value="1">Đông</option>
<option value="2">Tây</option>
<option value="3">Nam</option>
<option value="4">Bắc</option>
<option value="5">Đông-Bắc</option>
<option value="6">Tây-Bắc</option>
<option value="7">Đông-Nam</option>
<option value="8">Tây-Nam</option>
</select>
<select>
<option value="">Hướng nhà</option>
<option value="0">KXĐ</option>
<option value="1">Đông</option>
<option value="2">Tây</option>
<option value="3">Nam</option>
<option value="4">Bắc</option>
<option value="5">Đông-Bắc</option>
<option value="6">Tây-Bắc</option>
<option value="7">Đông-Nam</option>
<option value="8">Tây-Nam</option>
</select>
		<input type="text" placeholder="Số tầng" />
		<textarea rows="" cols="" placeholder="Nội thât"></textarea>
		</div>
		
		<div class="show-cho-du-an">
			
		<div class="p">
			<input type="text" placeholder="tầng cao" />
			<textarea rows="" cols="" placeholder="Nội thât"></textarea>
			<input id="du-an-i" type="text" placeholder="Thuộc dự án" />
				<div id="list-du-an">
					<div>dự án 1</div>
					<div>dự án 2</div>
					<div>dự án 3</div>
					<div>dự án 4</div>
				</div>
			</div>
		<div id="thong-tin-du-an" style="display: none;">
			<div>
				<h4>Thông tin dự án</h4>
				<div>Chủ đầu tư: Novaland</div>
				<div>Nhà thầu xây dựng: nhà thầu 1</div>
				<div>Ngày khởi công: 02/23/2002</div>
				<div>Ngày hoàn thiện: 02/23/2002</div>
				<a href="#">Xem chi tiết dự án</a>
			</div>
		</div>
		</div>
		
	</div>
	<div class="step">
		<h3>Bước 3</h3>
		<div style="width: 100%; height: 329px; background: #D3D3DE;" class="p">
			<img style="width: 100%; height: 329px;" id="pl" alt="" src="" />
			<span class="icon-edit icon" style="display: none;"></span>
			<span class="icon-delete icon" style="display: none;"></span>
		</div>
		<input id="tai-anh-len" type="button" value="Tải ảnh lên" />
	</div>
	<div class="step">
		<h3>Bước 4</h3>
		
		<input type="text" placeholder="Họ tên" />
		<input type="text" placeholder="Điện thoại" />
		<input type="text" placeholder="Email" />
		<input id="cty" type="text" placeholder="Công ty môi giới(Nếu có)" style="display: none;" />
		<input type="button" value="Xem trước" />
		<input type="button" value="Đăng tin" />
	</div>
</div>