<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 2/26/2016
 * Time: 9:51 AM
 */
?>
<div class="type-payment">
	<div class="">
	    <h1 class="color-cd">Chào bạn,</h1>
	    <p class="mgB-20">Ghi nhận ý kiến đóng góp của các thành viên về việc đăng tin spam, tin lừa đảo hiện nay diễn ra quá nhiều đã làm giảm chất lượng tin đăng cũng như hiệu quả phục vụ của website. Vì vậy, metvuong.com sẽ tiến hành thu phí đăng tin lên website , điều này sẽ góp phần nâng cao chất lượng tin đăng trên website, tránh tình trạng đăng tin spam, tin lừa đảo gây khó khăn và thiệt hại cho người bán, mua hoặc thuê.</p>
	    <p class="mgB-5 font-600 fs-18 color-cd">Các phương thức thanh toán:</p>
	    <p class="color-red mgB-10"><span class="font-700 d-ib mgR-10">Lưu ý:</span>1 KEYS = 1.000 VNĐ</p>
	    <div class="tabs-payment">
	    	<ul class="clearfix">
	    		<li><a href="#the-cao" class="active">Thẻ Cào</a></li>
	    		<li><a href="#sms">SMS</a></li>
	    		<li><a href="#chuyen-khoan">Chuyển khoản</a></li>
	    		<li><a href="#credit-card">Credit Card</a></li>
	    	</ul>
	    </div>
	    <div id="the-cao" class="item-payment active">
	        <div class="title-item">Thanh toán bằng thẻ cào điện thoại</div>
	        <div class="w-40 pd-20">
	        
	            <div class="form-group">
	                <label class="fs-13 mgB-5">Nhà mạng</label>
	                <select class="form-control">
	                    <option value="">Viettel</option>
	                    <option value="">Mobifone</option>
	                </select>
	            </div>
	            <div class="form-group">
	                <label class="fs-13 mgB-5">Mã thẻ cào</label>
	                <input type="text" class="form-control">
	            </div>
	            <div class="form-group">
	                <label class="fs-13 mgB-5">Số seri thẻ</label>
	                <input type="text" class="form-control">
	            </div>
	            <div class="text-left">
	                <button class="btn-common">Nạp Keys</button>
	            </div>
	        </div>
	    </div>
	    <div id="sms" class="item-payment">
	        <div class="title-item">Thanh toán bằng tin nhắn SMS</div>
	        <div class="w-30 text-center pd-20">
	            <p class="mgB-5">Soạn tin nhắn với cú pháp</p>
	            <p class="mgB-5"><span class="color-cd font-700">TT MV</span> [Mã Thành Viên] [Số Tiền] gửi <strong>19001590</strong></p>
	            <p>VD: TT MV 1234 10000 gửi 19001590</p>
	        </div>
	    </div>
	    <div id="chuyen-khoan" class="item-payment">
	        <div class="title-item">THANH TOÁN CHUYỂN KHOẢN QUA NGÂN HÀNG ATM</div>
	        <div class="pd-20">
	            <div class="bank_item">
	                <div class="row">
	                    <div class="col-md-3 col-sm-6 col-xs-12">
	                        <div class="bank_item_info">
	                            <div class="bank_title2"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/vietcombank_big.jpg' ?>"></div>
	                        </div>
	                    </div>
	                    <div class="col-md-3 col-sm-6 col-xs-12">
	                        <div class="bank_item_info">
	                            <div class="bank_title">Chủ tài khoản</div>
	                            <div class="bank_title2">Trần Thị A</div>
	                        </div>
	                    </div>
	                    <div class="col-md-3 col-sm-6 col-xs-12">
	                        <div class="bank_item_info">
	                            <div class="bank_title">Số tài khoản</div>
	                            <div class="bank_title2">0123212320123</div>
	                        </div>
	                    </div>
	                    <div class="col-md-3 col-sm-6 col-xs-12">
	                        <div class="bank_item_info">
	                            <div class="bank_title">Chi nhánh</div>
	                            <div class="bank_title2">PGD Hàm Nghi</div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <div class="bank_item">
	                <div class="row">
	                    <div class="col-md-3 col-sm-6 col-xs-12">
	                        <div class="bank_item_info">
	                            <div class="bank_title2"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/vietcombank_big.jpg' ?>"></div>
	                        </div>
	                    </div>
	                    <div class="col-md-3 col-sm-6 col-xs-12">
	                        <div class="bank_item_info">
	                            <div class="bank_title">Chủ tài khoản</div>
	                            <div class="bank_title2">Trần Thị A</div>
	                        </div>
	                    </div>
	                    <div class="col-md-3 col-sm-6 col-xs-12">
	                        <div class="bank_item_info">
	                            <div class="bank_title">Số tài khoản</div>
	                            <div class="bank_title2">00000000000</div>
	                        </div>
	                    </div>
	                    <div class="col-md-3 col-sm-6 col-xs-12">
	                        <div class="bank_item_info">
	                            <div class="bank_title">Chi nhánh</div>
	                            <div class="bank_title2">PGD Hàm Nghi</div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <p class="fs-14"><span style="color: red; text-transform: uppercase; font-weight: bold;">Quý khách lưu ý:</span> sau khi chuyển khoản xong, quý khách vui lòng nhắn tin thông báo theo cú pháp: <strong>PT <span style="font-size: 12px; color: #999;">_</span> 18882 <span style="font-size: 12px; color: #999;">_</span> SỐTIỀN </strong>và gửi đến số điện thoại <strong>0123456789</strong> <br>Để chúng tôi kích hoạt số KEYS cho quý khách. Xin cảm ơn!</p>
	        </div>
	    </div>
	    <div id="credit-card" class="item-payment">
	        <div class="title-item">Thanh toán bằng thẻ Creadit Card</div>
	        <div class="pdT-20 pdB-40 pdL-50 pdR-50">
	            <div class="method_napcard_inner">
	                
	                <div class="row">
	                    <div class="col-md-4 col-sm-6 col-xs-12">
	                        <div class="vip_wrapper vip-9722">
	                            <h3 class="vip_title">Package - 1500 Keys</h3>
	                            <div class="vip_content">
	                            Giá 1.000.000 VNĐ                                               </div>
	                            <div class="vip_button">
	                                <a type="button" class="btn-common" href="#">Nạp Keys</a>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="col-md-4 col-sm-6 col-xs-12">
	                        <div class="vip_wrapper vip-9721">
	                            <h3 class="vip_title">Package - 600 Keys</h3>
	                            <div class="vip_content">
	                            Giá 500.000 VNĐ                                             </div>
	                            <div class="vip_button">
	                                <a type="button" class="btn-common" href="#">Nạp Keys</a>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="col-md-4 col-sm-6 col-xs-12">
	                        <div class="vip_wrapper vip-9720">
	                            <h3 class="vip_title">Package -  250 Keys</h3>
	                            <div class="vip_content">
	                            Giá 200.000 VNĐ                                             </div>
	                            <div class="vip_button">
	                                <a type="button" class="btn-common" href="#">Nạp Keys</a>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="col-md-4 col-sm-6 col-xs-12">
	                        <div class="vip_wrapper vip-9719">
	                            <h3 class="vip_title">Package - 120 Keys</h3>
	                            <div class="vip_content">
	                            Giá 100.000 VNĐ                                             </div>
	                            <div class="vip_button">
	                                <a type="button" class="btn-common" href="#">Nạp Keys</a>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="col-md-4 col-sm-6 col-xs-12">
	                        <div class="vip_wrapper vip-9718">
	                            <h3 class="vip_title">Package - 55 Keys</h3>
	                            <div class="vip_content">
	                            Giá 50.000 VNĐ                                              </div>
	                            <div class="vip_button">
	                                <a type="button" class="btn-common" href="#">Nạp Keys</a>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="col-md-4 col-sm-6 col-xs-12">
	                        <div class="vip_wrapper vip-9717">
	                            <h3 class="vip_title">Package - 10 Keys</h3>
	                            <div class="vip_content">
	                            Giá 10.000 VNĐ                                              </div>
	                            <div class="vip_button">
	                                <a type="button" class="btn-common" href="#">Nạp Keys</a>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	                <p style="font-size: 20px; color: #fff; background: red; display: block; padding: 5px 10px;"><span>Lưu ý:</span> Bạn phải CHỜ 10 GIÂY để nhận kết quả xử lý từ Ngân Lượng. TUYỆT ĐỐI KHÔNG ĐÓNG TRÌNH DUYỆT. Khi giao dịch được xác định thành công, bạn sẽ được tự động chuyển về web metvuong, lúc đó chúng tôi sẽ trả Keys cho bạn. </p>
	                <br>
	                <p>Hỗ trợ các ngân hàng:</p>
	                <img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/banks.jpg' ?>">
	            </div>
	        </div>
	    </div>
    </div>
</div>
<script>
	$(function() {
		$('.tabs-payment li a').on('click', function (e) {
			e.preventDefault();
			var _this = $(this),
				id = _this.attr('href');
			
			$('.tabs-payment li a').removeClass('active');
			_this.addClass('active');
			$('.item-payment').hide();
			$(id).addClass('active').fadeIn();
		});
	});
</script>