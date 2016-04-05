<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 2/26/2016
 * Time: 9:51 AM
 */
?>
<div class="title-fixed-wrap">
	<div class="container">
		<div class="edit-user-tt">
			<div class="title-top">
				<a href="#">TÀI KHOẢN CỦA BẠN</a>
				<a href="javascript:history.back()" id="prev-page"><span class="icon arrowRight-1"></span></a>
				<a href="#" id="done-page"><span class="icon icon-done"></span></a>
			</div>
			<div class="wrap-edit-tt">
				<div class="title-update-tt">
					THÔNG TIN CÁ NHÂN
					<a href="#edit-ttcn" class="edit-tt"><span class="icon icon-edit-small-1"></span></a>
				</div>
				<div class="list-tt-user wrap-attr-detail">
					<ul class="clearfix">
						<li>
							<span class="attr-right pull-right" data-value="Huan">Huan</span>
							<span>Tên</span>
						</li>
						<li>
							<span class="attr-right pull-right" data-value="Ta">Ta</span>
							<span>Họ</span>
						</li>
						<li>
							<span class="attr-right pull-right" data-value="090 3 80 124124">090 3 80 124124</span>
							<span>Phone Number</span>
						</li>
						<li>
							<span class="attr-right pull-right" data-value="1">Người môi giới</span>
							<span>Tôi là</span>
						</li>
					</ul>
				</div>
				<div class="title-update-tt">
					MÔ TẢ BẢN THÂN
					<a href="#edit-mtbt" class="edit-tt"><span class="icon icon-edit-small-1"></span></a>
				</div>
				<div class="wrap-attr-detail">
					<div class="txt-wrap">
						<p>Phasellus non eros tortor. Ut sodales purus a ipsum fringilla, et pharetra lacus consectetur. Cras interdum sapien ut faucibus ornare. Duis efficitur enim at augue semper, vitae eleifend augue elementum...</p>		
					</div>
				</div>
				<div class="title-update-tt">
					ĐỊA ĐIỂM
					<!-- <a href="#" class="edit-tt"><span class="icon icon-edit-small-1"></span></a> -->
				</div>
				<div class="list-tt-user wrap-attr-detail">
					<ul class="clearfix">
						<li>
							<span class="attr-right pull-right">VietNam</span>
							<span>Country</span>
						</li>
					</ul>
				</div>
				<div class="title-update-tt">
					MẬT KHẨU
					<a href="#edit-changepass" class="edit-tt"><span class="icon icon-edit-small-1"></span></a>
				</div>
				<div class="list-tt-user wrap-attr-detail">
					<ul class="clearfix">
						<li>
							<input type="password" class="attr-right pull-right" value="123456" />
							<span>Password</span>
						</li>
						<li>
							<input type="password" class="attr-right pull-right" value="123456" />
							<span>New Password</span>
						</li>
						<li>
							<input type="password" class="attr-right pull-right" value="123456" />
							<span>Gõ lại Password</span>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="edit-ttcn" class="popup-common hide-popup">
	<div class="wrap-popup">
		<div class="title-popup clearfix text-center">
			Thông tin cá nhân
			<a href="#" class="txt-cancel btn-cancel">Cancel</a>
			<a href="#" class="txt-done btn-done">Done</a>
		</div>
		<div class="inner-popup">
            <div class="list-tt-user wrap-attr-detail">
				<ul class="clearfix">
					<li>
						<span>Tên</span>
						<input type="text" class="attr-right" value="" />
					</li>
					<li>
						<span>Họ</span>
						<input type="text" class="attr-right" value="" />
					</li>
					<li>
						<span>Phone Number</span>
						<input class="attr-right" type="text" value="" />
					</li>
					<li>
						<span>Tôi là</span>
						<select class="attr-right">
							<option value="1" selected>Người môi giới</option>
							<option value="2">Chủ nhà</option>
						</select>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<div id="edit-mtbt" class="popup-common hide-popup">
	<div class="wrap-popup">
		<div class="title-popup clearfix text-center">
			MÔ TẢ BẢN THÂN
			<a href="#" class="txt-cancel btn-cancel">Cancel</a>
			<a href="#" class="txt-done btn-done">Done</a>
		</div>
		<div class="inner-popup">
            <div class="list-tt-user wrap-attr-detail">
				<textarea>Phasellus non eros tortor. Ut sodales purus a ipsum fringilla, et pharetra lacus consectetur. Cras interdum sapien ut faucibus ornare. Duis efficitur enim at augue semper, vitae eleifend augue elementum</textarea>
			</div>
		</div>
	</div>
</div>

<div id="edit-changepass" class="popup-common hide-popup">
	<div class="wrap-popup">
		<div class="title-popup clearfix text-center">
			MẬT KHẨU
			<a href="#" class="txt-cancel btn-cancel">Cancel</a>
			<a href="#" class="txt-done btn-done">Done</a>
		</div>
		<div class="inner-popup">
            <div class="list-tt-user wrap-attr-detail">
				<ul class="clearfix">
					<li>
						<span>Password cũ</span>
						<input type="password" class="attr-right" value="" placeholder="Nhập...">
					</li>
					<li>
						<span>Password mới</span>
						<input type="password" class="attr-right" value="" placeholder="Nhập...">
					</li>
					<li>
						<span>Gõ lại Password mới</span>
						<input type="password" class="attr-right" value="" placeholder="Nhập...">
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function () {
		/*$('#edit-ttcn, #edit-mtbt, #edit-changepass').popupMobi({
			btnClickShow: ".edit-tt",
			closeBtn: '.btn-cancel',
			styleShow: 'full',
			funCallBack: function (item) {
				
			}
		});*/
	});
</script>