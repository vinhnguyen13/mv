<style>
.modal-body {
	padding: 2px;
	border-radius: 0px;
}
.modal-dialog {
	width: 840px;
}
.dt-left-col h1 {
	margin-bottom: 0px;
}
.edit-able {
	border: 1px dashed;
    padding: 6px 12px 6px 6px;
    position: relative;
    cursor: pointer;
}
span.edit-able {
	display: inline-block;
}
.edit-able .fa-pencil {
	position: absolute;
	top: -6px;
    right: -6px;
    font-size: 12px;
}
.dt-left-col table th, .dt-left-col table td {
	padding: 10px 10px 10px 0;
}
.detail-post {
	margin-left: 0px;
	margin-right: 0px;
}
.dt-left-col {
	margin-left: 0px;
	margin-right: 0px;
	padding-left: 0px;
	padding-right: 20px;
}
.dt-right-col {
	margin-right: 0;
    padding-right: 0;
    padding-left: 0;
}
.u1 {
    text-align: justify;
    margin: 16px 0px;
}
.u1:after {
	content: '';
	display: inline-block;
	width: 100%;
}
.u1 > span {
	display: inline-block;
}
.u1 > span label {
    font-size: 14px;
    margin-right: 8px;
}
.place-holder {
	color: #4A4A4A;
}
.addition-info th {
	width: 1px;
	white-space: nowrap;
	padding-right: 8px;
}
.addition-info th, .addition-info td {
    padding-top: 6px !important;
    padding-bottom: 6px !important;
}
.btn-primary.active, .btn-primary.focus, .btn-primary:active, .btn-primary:focus, .btn-primary:hover, .open>.dropdown-toggle.btn-primary {
	    background-color: #286090;
	    height: auto;
}
.contact-person > div {
	overflow: visible;
}
.u2 {
	padding-left: 0px;
}
.u2 th {
	    padding: 12px 8px 12px 8px;
    padding-left: 0px;
    padding-right: 12px;
}
.upload {
    font-size: 28px;
    background: rgba(0, 0, 0, 0.35);
    float: left;
    width: 60px;
    height: 60px;
    text-align: center;
    padding-top: 18px;
    margin-bottom: 10px;
	color: #43434A;
	background-image: url(http://saskatoon.cs.rit.edu/min_instructions/img/upload.png);
	background-size: 100% 100%;
}
.dit {
	display: none;
}
.edit-able input {
	display: none;
}
.edit {
	border: 1px solid transparent;
}
.edit input {
	display: inline-block;
}
.edit .dit {
	display: block;
	position: absolute;
}
.edit .fa-pencil {
	display: none;
}
.sadasd {
	position: absolute;
	left: 0px;	
	display: none;
}
.edit .sadasd {
	display: inline-block;
}
</style>
<script>
	$(document).ready(function(){
		$('.edit-able:not(.address-edit-able)').not($('.assddd')).click(function(){
			if($(this).hasClass('edit')) {
				return ;
			}
			var width = $(this).width();
			$(this).addClass('edit').find('.place-holder').css({visibility: 'hidden'});
			var input = $('<input style="position:absolute; left:0px; width: ' + width + 'px" type="text" />');
			$(this).append(input);
			var self = $(this);
			input.blur(function(){
				self.removeClass('edit').find('.place-holder').css({visibility: 'visible'}).text($(this).val());
				});
		});
		$('.address-edit-able').click(function(){
			$(this).addClass('edit').find('.title-dt').css({visibility: 'hidden'});
		});

		$('.assddd').click(function(){
			$(this).addClass('edit').find('.ccc').css({visibility: 'hidden'});
		});

		$('.sadasd').change(function(){
			$('.xczcxz').text($('.sadasd').val());
			$('.assddd').removeClass('edit').find('.ccc').css({visibility: 'visible'});
		});
		
		$('#aaaaaaa').blur(function(){
			$('.aaaaaaaaac').text($('#aaaaaaa').val() + ', Đường Nguyễn Trung Trực, Phường 8');
			$('.address-edit-able').removeClass('edit').find('.title-dt').css({visibility: 'visible'});
			});

		$('.upload').click(function(){
			$(this).before('<div style="float: left; width: 60px;"><img style="width: 100%; height: auto" src="http://www.keenthemes.com/preview/metronic/theme/assets/global/plugins/jcrop/demos/demo_files/image1.jpg" /></div>');
			$('.ussspload').append('<img style="float: left" src="http://www.keenthemes.com/preview/metronic/theme/assets/global/plugins/jcrop/demos/demo_files/image1.jpg" />');
		});
	});
</script>
<div style="position: relative;" class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-body">
			<div class="wrap-modal clearfix">
				<div class="clearfix">
					<div style="overflow-x: hidden;"><div class="ussspload" style="width: 10000px"></div></div>
					<div class="upload"></div>
				</div>
				<div class="row detail-post">
					<div class="col-sm-8 dt-left-col">
						<div class="edit-able address-edit-able"><div class="dit">
							<select><option>Chọn Phường/Xã</option><option value="Phường 8">Phường 8</option></select>
							<select><option>Chọn Đường</option><option value="Đường Nguyễn Trung Trực">Đường Nguyễn Trung Trực</option></select><input id="aaaaaaa" type="text" placeholder="Số nhà" /></div>
							
						<h1 class="title-dt"><span class="aaaaaaaaac" style="    color: #4A4A4A;
    font-size: 22px;
    font-weight: normal;
    line-height: 32px;">Địa chỉ nhà cần bán</span></h1><i class="fa fa-pencil"></i></div>
						<div class="u1">
							<span><span class="edit-able assddd"> 
								<select class="sadasd"><option value="Nhà bán">Nhà bán</option><option value="Nhà cho thuê">Nhà cho thuê</option></select>
							<span class="ccc"><em class="fa fa-circle for-rent" style="margin-right: 4px;"></em><span class="xczcxz">NHÀ BÁN</span><i class="fa fa-pencil"></i></span></span></span>
							<span><label>Giá</label><span class="edit-able"><span class="place-holder">click để nhập</span><i class="fa fa-pencil"></i></span></span>
							<span><label>Diện tích</label><span class="edit-able"><span class="place-holder">click để nhập</span><span style="display: none;">12m<sup style="font-size: 10px;">2</span></sup><i class="fa fa-pencil"></i></span></span>
						</div>
						<p class="ttmt" style="margin-top: 22px;">Thông tin mô tả</p>
						<div class="wrap-ttmt">
							<div class="edit-able"><span class="place-holder">click để nhập</span><i class="fa fa-pencil"></i></div>
						</div>
						<p class="ttmt" style="margin-top: 36px;">Thông tin thêm</p>
						<table class="addition-info" style="width: 100%;">
							<tbody>
								<tr>
									<th>Mặt tiền</th>
									<td style="width: 100%;"><span class="edit-able"><span class="place-holder">click để nhập</span><i class="fa fa-pencil"></i></span></td>
									<th>Đường vào</th>
									<td style="white-space: nowrap;"><span class="edit-able"><span class="place-holder">click để nhập</span><i class="fa fa-pencil"></i></span></td>
								</tr>
								<tr>
									<th>Hướng nhà</th>
									<td><span class="edit-able"><span class="place-holder">click để nhập</span><i class="fa fa-pencil"></i></span></td>
									<th>Hướng ban công</th>
									<td><span class="edit-able"><span class="place-holder">click để nhập</span><i class="fa fa-pencil"></i></span></td>
								</tr>
								<tr>
									<th>Số tầng</th>
									<td><span class="edit-able"><span class="place-holder">click để nhập</span><i class="fa fa-pencil"></i></span></td>
									<th>Số phòng ngủ</th>
									<td><span class="edit-able"><span class="place-holder">click để nhập</span><i class="fa fa-pencil"></i></span></td>
								</tr>
								<tr>
									<th>Số toilet</th>
									<td><span class="edit-able"><span class="place-holder">click để nhập</span><i class="fa fa-pencil"></i></span></td>
								</tr>
								<tr>
									<th>Nội thất</th>
									<td colspan="3"><div class="edit-able"><span class="place-holder">click để nhập</span><i class="fa fa-pencil"></i></div></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-sm-4 dt-right-col">
						<div class="contact-wrapper">
							<p class="title-contact">Liên hệ</p>
							<div class="contact-person clearfix">
								<div class="u2">
									<table>
									<tr>
										<th>Họ tên</th>
										<td><span class="edit-able"><span class="place-holder">click để nhập</span><i class="fa fa-pencil"></i></span></td>
									</tr>	
									<tr>
										<th>Số điện thoại</th>
										<td><span class="edit-able"><span class="place-holder">click để nhập</span><i class="fa fa-pencil"></i></span></td>
									</tr>	
									<tr>
										<th>Di động</th>
										<td><span class="edit-able"><span class="place-holder">click để nhập</span><i class="fa fa-pencil"></i></span></td>
									</tr>	
									<tr>
										<th>Email</th>
										<td><span class="edit-able"><span class="place-holder">click để nhập</span><i class="fa fa-pencil"></i></span></td>
									</tr>	
									<tr>
										<th>Địa chỉ</th>
										<td><span class="edit-able"><span class="place-holder">click để nhập</span><i class="fa fa-pencil"></i></span></td>
									</tr>	
								</table>
								</div>
							</div>
							<form style="display: none;" action="" id="frm-contact-person">
								<div class="form-group">
									<input type="text" class="form-control" placeholder="Tên bạn">
								</div>
								<div class="form-group">
									<input type="text" class="form-control" placeholder="Email">
								</div>
								<div class="form-group mgB-0">
									<input type="text" class="form-control"
										placeholder="Điện thoại">
								</div>
								<p class="alert">Tin nhắn này sẽ được đọc khi người này online</p>
								<div class="checkbox">
									<label><input type="checkbox"> Hiện thông báo khi người này đọc
										tin nhắn</label>
								</div>
								<div class="text-center">
									<button type="button" class="btn btn-primary">Gửi tin nhắn cho
										người này</button>
								</div>
							</form>
						</div>
						<div style="margin-top: 22px;">
							<a style="display: block;" class="btn btn-primary">Đăng tin</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>