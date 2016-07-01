<?php
use yii\helpers\Url;
?>
<div class="container page-s">
	<div class="title-top">Dịch vụ</div>
	<div class="wrap-content-page">
		<ul class="tabs-s nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#mtbds" aria-controls="mtbds" role="tab" data-toggle="tab">Cho người mua / thuê</a></li>
			<li role="presentation"><a href="#tbbds" aria-controls="tbbds" role="tab" data-toggle="tab">Cho chủ nhà / mô giới</a></li>
			<li role="presentation"><a href="#htcd" aria-controls="htcd" role="tab" data-toggle="tab">Chức năng chính của metvuong</a></li>
		</ul>
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane fade in active" id="mtbds">
				<p><strong>1. Tìm kiếm:</strong></p>
				<p>Việc tìm kiếm các sản phẩm bất động sản trên Metvuong.com chưa bao giờ dễ dàng, nhanh chóng và hiệu quả đến vậy. Trong thanh Tìm kiếm nhanh trên trang chủ, bạn có thể gõ vào bất cứ tên đường, phường, quận, thành phố, hoặc một dự án bất kì, bạn sẽ có ngay kết quả của hàng trăm bất động sản rao bán hoặc cho thuê. Tìm kiếm bằng cách gõ mã số MV (dãy số) hoặc dãy số bạn biết cũng sẽ ngay lập tức đưa bạn đến sản phẩm mong muốn</p>
				<p>
					<img class="w-70" src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/s-2.jpg' ?>" alt="">
				</p>
				<p><strong>2. Yêu thích:</strong></p>
				<p>Trong loạt danh sách tin đăng bạn tìm kiếm sẽ có rất nhiều sản phẩm phù hợp hoặc gần nhất với yêu cầu, để dễ dàng theo dõi sản phẩm bạn hài lòng, hãy dùng tính năng Yêu thích của chúng tôi để lưu các sản phẩm đó, và bạn có thể xem lại sau.</p>
				<p><strong>3 Liên hệ với người đăng tin</strong></p>
				<p>Nếu bạn tìm thấy một tin đăng mà bạn hài lòng, bạn có thể click vào thông tin của người đăng tin và liên hệ với họ bằng email hoặc chat</p>
				<p>
					<img class="w-70" src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/s-3.jpg' ?>" alt="">
				</p>
				<p class="font-600">Email</p>
				<p>
					<img class="w-70" src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/s-4.jpg' ?>" alt="">
				</p>
				<p class="font-600">Chat</p>
				<p>
					<img class="w-70" src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/s-5.jpg' ?>" alt="">
				</p>
				<p><strong>4 Chia sẻ:</strong></p>
				<p>Đôi khi bạn cần có ý kiến từ người khác về bất động sản mà bạn muốn mua hoặc thuê, hay bạn muốn chỉ rõ cho người đăng tin biết bạn hài lòng về sản phẩm nào trong danh sách tin của họ, đơn giản bạn chỉ cần dùng tính năng Chia sẻ bằng Facebook, bằng Email hoặc sao chép đường dẫn tin hoặc cho mã số tin đăng cho người bạn muốn chia sẻ.</p>
				<p>
					<img class="w-70" src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/s-6.jpg' ?>" alt="">
				</p>
				<p class="font-600">Chia sẻ qua Facebook</p>
				<p>
					<img class="w-70" src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/s-7.jpg' ?>" alt="">
				</p>
				<p class="font-600">Chia sẻ qua Email</p>
				<p>
					<img class="w-70" src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/s-8.jpg' ?>" alt="">
				</p>
			</div>
			<div role="tabpanel" class="tab-pane fade in" id="tbbds">
				<p><strong>1. Đăng tin.</strong></p>
				<p style="margin-left:40px"><span class="font-700">a.</span> Không giống như hầu hết các trang mạng đăng tin bất động sản truyền thống khác , Metvuong.com đã xây dựng cách đăng tin rõ ràng, chi tiết và hệ thống đánh giá tin đăng của bạn với hàng trăm ngàn dữ liệu để đảm bảo tin là chính xác. Chúng tôi cũng có thang điểm cho tin đăng nhằm xác định vị trí và tần suất xuất hiện của tin trên kết quả tìm kiếm. Tại MetVuong.com, chúng tôi có các nhà khoa học dữ liệu làm việc trên cơ sở so sánh rất nhiều kho dữ liệu của chúng tôi để đảm bảo rằng bạn tìm được sản phẩm phù hợp nhất.</p>
				<p>
					<img class="w-70" src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/s-9.jpg' ?>" alt="">
				</p>
				<p><strong>2. Boost tin đăng</strong></p>
				<p style="margin-left:40px"><span class="font-700">a.</span> Theo thứ tự sắp xếp, tin đăng cũ sẽ bị trôi dần xuống dưới của danh sách và giảm dần điểm, để thiết lập lại vị trí dẫn đầu và đạt số điểm cao, bạn hãy boost tin đăng theo hướng dẫn để tin của bạn luôn có nhiều khách hàng quan tâm</p>
				<p style="margin-left:40px"><span class="font-700">b.</span> Tin đăng boost sẽ được xuất hiện trên đầu danh sách và hiển thị trong nhiều kết quả tìm kiếm có liên quan của người dùng</p>
				<p style="margin-left:40px"><span class="font-700">c.</span> Tin đăng boost sẽ được làm nổi bật trên danh sách, nhằm thu hút người tìm kiếm.</p>
				<p style="margin-left:40px"><span class="font-700">d.</span> Tin đăng boost sẽ xuất hiện trên trang chủ dưới dạng "CÁC TIN ĐƯỢC TÌM KIẾM NHIỀU NHẤT".</p>
				<p>
					<img class="w-70" src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/s-10.jpg' ?>" alt="">
				</p>
				<p><strong>3. Dashboard</strong></p>
				<p>Dashboard cung cấp cho bạn các số liệu khách hàng đã tương tác với tin đăng của bạn như: a) Có bao nhiêu người tìm kiếm danh sách của bạn b) Có bao nhiêu người yêu thích danh sách của bạn c) Có bao nhiêu người chia sẻ danh sách của bạn... Tất cả những dữ liệu quan trọng này bao gồm thông tin của khách hàng như email, điện thoại sẽ giúp bạn liên hệ với các khách hàng tiềm năng một cách nhanh chóng.</p>
				<p>
					<img class="w-70" src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/s-11.jpg' ?>" alt="">
				</p>
			</div>
			<div role="tabpanel" class="tab-pane fade in" id="htcd">
				<p><strong>Hệ thống chấm điểm</strong></p>
				<p>Mỗi tin đăng trên MetVuong.com được hệ thống chấm số điểm tương ứng và cao nhất là 100, các số điểm được sắp xếp theo thứ tự nhằm đảm bảo cho việc tìm kiếm tin chất lượng. Những tin đăng đạt số điểm cao sẽ luôn được hiển thị trong nhóm đầu, rất dễ dàng cho khách hàng tìm kiếm, những tin chưa chất lượng có điểm số thấp hoặc tin cũ sẽ trôi dần xuống. Vì vậy, nếu bạn là một Môi giới uy tín (được đánh giá nhiều sao, nhiều người yêu thích) và tin đăng đầy đủ, chính xác thì sản phẩm của bạn đạt điểm tối ưu và nằm trong nhóm dẫn đầu của danh sách tin.</p>
				<p><strong>Mã số (ID) tin:&nbsp;</strong></p>
				<p>Mỗi tin đăng trên MetVuong.com có một mã số duy nhất, được kí hiệu bằng MV(dãy số), mã số này được sử dụng để tìm kiếm tin nhanh nhất bằng các cách:</p>
				<p>- Dán vào trình duyệt đường dẫn<span style="color:#00a769"><u> www.metvuong.com/MV12345</u></span></p>
				<p>- Gõ vào thanh tìm kiếm: MV12345 hoặc 12345.</p>
				<p>Điều này giúp việc tìm kiếm trở nên dễ dàng hơn và nhanh hơn cho bạn.</p>
				<p><strong>Chìa khóa</strong></p>
				<p>
					Trên metvuong.com, tất cả những chức năng trên Metvuong.com sẽ được thanh toán bằng chìa khóa, <strong>1 chìa = 1000 VND</strong>, và bạn có thể dể dàng nạp thêm chìa qua nhiều cách khác nhau trên metvuong.com
				</p>
				<p>
					<img class="w-70" src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/s-1.jpg' ?>" alt="">
				</p>
			</div>
		</div>
	</div>
</div>