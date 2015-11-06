<?php
use yii\web\View;
use yii\helpers\Url;

	$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/jquery.bxslider.js', [ 
			'depends' => [ 
					'yii\web\YiiAsset' 
			] 
	] );
	$this->registerJs ( "$('.bxslider').bxSlider({ mode: 'fade', auto: true, autoHover: true });", View::POS_READY, 'bxslider' );
?>

<div class="row">
	<div class="col-sm-8 col-lg-9 col-right-home duan-page">
		<div class="news-hot box-danb-slide bxslider">
			<div class="">
				<div class="wrap-img bgcover"
					style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/galaxy9.jpg);">
					<a href="#"></a>
				</div>
				<div class="content-news-hot">
					<h2>9 dự án đang và sẽ thay đổi diện mạo Quận 4 1</h2>
					<p>ngày đầu tiên là tên một mới được bổ nhiệm vào vị trí mới được
						bổ nhiệm vào vị trí mới được bổ nhiệm vào vị trí mới được bổ nhiệm
						vào vị trí mới được bổ nhiệm vào vị trí...</p>
				</div>
			</div>
			<div class="">
				<div class="wrap-img bgcover"
					style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/2013080515PDR.jpg);">
					<a href="#"></a>
				</div>
				<div class="content-news-hot">
					<h2>9 dự án đang và sẽ thay đổi diện mạo Quận 4 2</h2>
					<p>ngày đầu tiên là tên một trong bổ nhiệm vào vị trí mới được bổ
						nhiệm vào vị trí mới được bổ nhiệm vào vị trí mới được bổ nhiệm
						vào vị trí...</p>
				</div>
			</div>
			<div class="">
				<div class="wrap-img bgcover"
					style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/87bbds2.jpg);">
					<a href="#"></a>
				</div>
				<div class="content-news-hot">
					<h2>9 dự án đang và sẽ thay đổi diện mạo Quận 4 3</h2>
					<p>ngày đầu tiên là tên một trong những cuốn sách trên bàn làm việc
						của Nguyễn Trung Tín, mới được bổ nhiệm vào vị trí mới được vị
						trí...</p>
				</div>
			</div>
			<div class="">
				<div class="wrap-img bgcover"
					style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/galaxy9.jpg);">
					<a href="#"></a>
				</div>
				<div class="content-news-hot">
					<h2>9 dự án đang và sẽ thay đổi diện mạo Quận 4 4</h2>
					<p>ngày đầu tiên là tên một trong những cuốn sách trên bàn làm việc
						của Nguyễn Trung Tín, mới được bổ nhiệm vào vị trí mới được bổ
						nhiệm ...</p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4 item-list col-sm-6 col-xs-6">
				<div>
					<a href="<?= Url::to(['/building-project/view']) ?>" class="wrap-img-duan"> <span class="wrap-img bgcover"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/img283x190-1.jpg);"></span> <span
						class="name-duan">Sunrise Riverside</span> <span>Khu căn hộ -
							H.Nhà Bè, TP. HCM</span>
					</a> <a href="<?= Url::to(['/building-project/view']) ?>" class="mask"> <strong>VỊ TRÍ DỰ ÁN</strong> <span>Nguyễn
							Hữu Thọ, Xã Phước Kiển, Huyện Nhà Bè, TP.HCM</span> <strong>DIỆN
							TÍCH KHU ĐẤT</strong> <span>39.305m²</span> <strong>SỐ LƯỢNG CĂN
							HỘ</strong> <span>Khoảng 2200 căn</span> <strong>SỐ TẦNG</strong>
						<span>20-25 tầng</span>
					</a>
				</div>
			</div>
			<div class="col-md-4 item-list col-sm-6 col-xs-6">
				<div>
					<a href="<?= Url::to(['/building-project/view']) ?>" class="wrap-img-duan"> <span class="wrap-img bgcover"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/22311_Khai-truong-Pearl-Plaza-8.jpg);"></span>
						<span class="name-duan">Sunrise Riverside</span> <span>Khu căn hộ
							- H.Nhà Bè, TP. HCM</span>
					</a> <a href="<?= Url::to(['/building-project/view']) ?>" class="mask"> <strong>VỊ TRÍ DỰ ÁN</strong> <span>Nguyễn
							Hữu Thọ, Xã Phước Kiển, Huyện Nhà Bè, TP.HCM</span> <strong>DIỆN
							TÍCH KHU ĐẤT</strong> <span>39.305m²</span> <strong>SỐ LƯỢNG CĂN
							HỘ</strong> <span>Khoảng 2200 căn</span> <strong>SỐ TẦNG</strong>
						<span>20-25 tầng</span>
					</a>
				</div>
			</div>
			<div class="item-list col-md-4 col-sm-6 col-xs-6">
				<div>
					<a href="<?= Url::to(['/building-project/view']) ?>" class="wrap-img-duan"> <span class="wrap-img bgcover"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/22311_Khai-truong-Pearl-Plaza-10.jpg);"></span>
						<span class="name-duan">Sunrise Riverside</span> <span>Khu căn hộ
							- H.Nhà Bè, TP. HCM</span>
					</a> <a href="<?= Url::to(['/building-project/view']) ?>" class="mask"> <strong>VỊ TRÍ DỰ ÁN</strong> <span>Nguyễn
							Hữu Thọ, Xã Phước Kiển, Huyện Nhà Bè, TP.HCM</span> <strong>DIỆN
							TÍCH KHU ĐẤT</strong> <span>39.305m²</span> <strong>SỐ LƯỢNG CĂN
							HỘ</strong> <span>Khoảng 2200 căn</span> <strong>SỐ TẦNG</strong>
						<span>20-25 tầng</span>
					</a>
				</div>
			</div>
			<div class="item-list col-md-4 col-sm-6 col-xs-6">
				<div>
					<a href="<?= Url::to(['/building-project/view']) ?>" class="wrap-img-duan"> <span class="wrap-img bgcover"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/21311_Khai-truong-Pearl-Plaza-4.jpg);"></span>
						<span class="name-duan">Sunrise Riverside</span> <span>Khu căn hộ
							- H.Nhà Bè, TP. HCM</span>
					</a> <a href="<?= Url::to(['/building-project/view']) ?>" class="mask"> <strong>VỊ TRÍ DỰ ÁN</strong> <span>Nguyễn
							Hữu Thọ, Xã Phước Kiển, Huyện Nhà Bè, TP.HCM</span> <strong>DIỆN
							TÍCH KHU ĐẤT</strong> <span>39.305m²</span> <strong>SỐ LƯỢNG CĂN
							HỘ</strong> <span>Khoảng 2200 căn</span> <strong>SỐ TẦNG</strong>
						<span>20-25 tầng</span>
					</a>
				</div>
			</div>
			<div class="item-list col-md-4 col-sm-6 col-xs-6">
				<div>
					<a href="<?= Url::to(['/building-project/view']) ?>" class="wrap-img-duan"> <span class="wrap-img bgcover"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/img283x190-1.jpg);"></span> <span
						class="name-duan">Sunrise Riverside</span> <span>Khu căn hộ -
							H.Nhà Bè, TP. HCM</span>
					</a> <a href="<?= Url::to(['/building-project/view']) ?>" class="mask"> <strong>VỊ TRÍ DỰ ÁN</strong> <span>Nguyễn
							Hữu Thọ, Xã Phước Kiển, Huyện Nhà Bè, TP.HCM</span> <strong>DIỆN
							TÍCH KHU ĐẤT</strong> <span>39.305m²</span> <strong>SỐ LƯỢNG CĂN
							HỘ</strong> <span>Khoảng 2200 căn</span> <strong>SỐ TẦNG</strong>
						<span>20-25 tầng</span>
					</a>
				</div>
			</div>
			<div class="item-list col-md-4 col-sm-6 col-xs-6">
				<div>
					<a href="<?= Url::to(['/building-project/view']) ?>" class="wrap-img-duan"> <span class="wrap-img bgcover"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/23311_Khai-truong-Pearl-Plaza-11.jpg);"></span>
						<span class="name-duan">Sunrise Riverside</span> <span>Khu căn hộ
							- H.Nhà Bè, TP. HCM</span>
					</a> <a href="<?= Url::to(['/building-project/view']) ?>" class="mask"> <strong>VỊ TRÍ DỰ ÁN</strong> <span>Nguyễn
							Hữu Thọ, Xã Phước Kiển, Huyện Nhà Bè, TP.HCM</span> <strong>DIỆN
							TÍCH KHU ĐẤT</strong> <span>39.305m²</span> <strong>SỐ LƯỢNG CĂN
							HỘ</strong> <span>Khoảng 2200 căn</span> <strong>SỐ TẦNG</strong>
						<span>20-25 tầng</span>
					</a>
				</div>
			</div>
			<div class="item-list col-md-4 col-sm-6 col-xs-6">
				<div>
					<a href="<?= Url::to(['/building-project/view']) ?>" class="wrap-img-duan"> <span class="wrap-img bgcover"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/21311_Khai-truong-Pearl-Plaza-2.jpg);"></span>
						<span class="name-duan">Sunrise Riverside</span> <span>Khu căn hộ
							- H.Nhà Bè, TP. HCM</span>
					</a> <a href="<?= Url::to(['/building-project/view']) ?>" class="mask"> <strong>VỊ TRÍ DỰ ÁN</strong> <span>Nguyễn
							Hữu Thọ, Xã Phước Kiển, Huyện Nhà Bè, TP.HCM</span> <strong>DIỆN
							TÍCH KHU ĐẤT</strong> <span>39.305m²</span> <strong>SỐ LƯỢNG CĂN
							HỘ</strong> <span>Khoảng 2200 căn</span> <strong>SỐ TẦNG</strong>
						<span>20-25 tầng</span>
					</a>
				</div>
			</div>
			<div class="item-list col-md-4 col-sm-6 col-xs-6">
				<div>
					<a href="<?= Url::to(['/building-project/view']) ?>" class="wrap-img-duan"> <span class="wrap-img bgcover"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/img283x190-1.jpg);"></span> <span
						class="name-duan">Sunrise Riverside</span> <span>Khu căn hộ -
							H.Nhà Bè, TP. HCM</span>
					</a> <a href="<?= Url::to(['/building-project/view']) ?>" class="mask"> <strong>VỊ TRÍ DỰ ÁN</strong> <span>Nguyễn
							Hữu Thọ, Xã Phước Kiển, Huyện Nhà Bè, TP.HCM</span> <strong>DIỆN
							TÍCH KHU ĐẤT</strong> <span>39.305m²</span> <strong>SỐ LƯỢNG CĂN
							HỘ</strong> <span>Khoảng 2200 căn</span> <strong>SỐ TẦNG</strong>
						<span>20-25 tầng</span>
					</a>
				</div>
			</div>
			<div class="item-list col-md-4 col-sm-6 col-xs-6">
				<div>
					<a href="<?= Url::to(['/building-project/view']) ?>" class="wrap-img-duan"> <span class="wrap-img bgcover"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/2013080515PDR.jpg);"></span> <span
						class="name-duan">Sunrise Riverside</span> <span>Khu căn hộ -
							H.Nhà Bè, TP. HCM</span>
					</a> <a href="<?= Url::to(['/building-project/view']) ?>" class="mask"> <strong>VỊ TRÍ DỰ ÁN</strong> <span>Nguyễn
							Hữu Thọ, Xã Phước Kiển, Huyện Nhà Bè, TP.HCM</span> <strong>DIỆN
							TÍCH KHU ĐẤT</strong> <span>39.305m²</span> <strong>SỐ LƯỢNG CĂN
							HỘ</strong> <span>Khoảng 2200 căn</span> <strong>SỐ TẦNG</strong>
						<span>20-25 tầng</span>
					</a>
				</div>
			</div>
			<div class="text-center">
				<ul class="pagination">
					<li><a href="#"><em class="fa fa-chevron-left"></em></a></li>
					<li class="active"><a href="#">1</a></li>
					<li><a href="#">2</a></li>
					<li><a href="#">3</a></li>
					<li><a href="#">4</a></li>
					<li><a href="#">5</a></li>
					<li><a href="#">6</a></li>
					<li><a href="#">7</a></li>
					<li><a href="#">8</a></li>
					<li><a href="#">9</a></li>
					<li><a href="#">10</a></li>
					<li><a href="#"><em class="fa fa-chevron-right"></em></a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="col-sm-4 col-lg-3 col-left-home">
		<div class="siderbar widget-tinmoi clearfix siderbar-style">
			<div class="widget-title clearfix">
				<h2>Tin mới</h2>
			</div>
			<ul>
				<li>
					<div class="pull-left wrap-img bgcover"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/img82x55.jpg);">
						<a href="#" title=""></a>
					</div>
					<div>
						<a class="color-title-link" href="#">Việt Nam có điều kiện tự
							nhiên ...</a>
						<p>sản xuất nông nghiệp nhưng bao đời nay chỉ sản xuất nhỏ lẻ,
							manh mún nên không xây dựng được thương hiệu...</p>
					</div>
				</li>
				<li>
					<div class="pull-left wrap-img bgcover"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/img82x55.jpg);">
						<a href="#" title=""></a>
					</div>
					<div>
						<a class="color-title-link" href="#">Việt Nam có điều kiện tự
							nhiên ...</a>
						<p>sản xuất nông nghiệp nhưng bao đời nay chỉ sản xuất nhỏ lẻ,
							manh mún nên không xây dựng được thương hiệu...</p>
					</div>
				</li>
				<li>
					<div class="pull-left wrap-img bgcover"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/img82x55.jpg);">
						<a href="#" title=""></a>
					</div>
					<div>
						<a class="color-title-link" href="#">Việt Nam có điều kiện tự
							nhiên ...</a>
						<p>sản xuất nông nghiệp nhưng bao đời nay chỉ sản xuất nhỏ lẻ,
							manh mún nên không xây dựng được thương hiệu...</p>
					</div>
				</li>
				<li>
					<div class="pull-left wrap-img bgcover"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/img82x55.jpg);">
						<a href="#" title=""></a>
					</div>
					<div>
						<a class="color-title-link" href="#">Việt Nam có điều kiện tự
							nhiên ...</a>
						<p>sản xuất nông nghiệp nhưng bao đời nay chỉ sản xuất nhỏ lẻ,
							manh mún nên không xây dựng được thương hiệu...</p>
					</div>
				</li>
			</ul>
		</div>
		<div class="siderbar widget-ads clearfix">
			<a class="wrap-img" href="#"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/img295x210.jpg" alt=""></a>
		</div>
		<div class="siderbar widget-dqt clearfix siderbar-style">
			<div class="widget-title clearfix">
				<h2>đáng quan tâm</h2>
			</div>
			<ul>
				<li>
					<div class="pull-left wrap-img bgcover"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/img82x55.jpg);">
						<a href="#" title=""></a>
					</div>
					<div>
						<a class="color-title-link" href="#">Việt Nam có điều kiện tự
							nhiên ...</a>
						<p>sản xuất nông nghiệp nhưng bao đời nay chỉ sản xuất nhỏ lẻ,
							manh mún nên không xây dựng được thương hiệu...</p>
					</div>
				</li>
				<li>
					<div class="pull-left wrap-img bgcover"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/img82x55.jpg);">
						<a href="#" title=""></a>
					</div>
					<div>
						<a class="color-title-link" href="#">Việt Nam có điều kiện tự
							nhiên ...</a>
						<p>sản xuất nông nghiệp nhưng bao đời nay chỉ sản xuất nhỏ lẻ,
							manh mún nên không xây dựng được thương hiệu...</p>
					</div>
				</li>
				<li>
					<div class="pull-left wrap-img bgcover"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/img82x55.jpg);">
						<a href="#" title=""></a>
					</div>
					<div>
						<a class="color-title-link" href="#">Việt Nam có điều kiện tự
							nhiên ...</a>
						<p>sản xuất nông nghiệp nhưng bao đời nay chỉ sản xuất nhỏ lẻ,
							manh mún nên không xây dựng được thương hiệu...</p>
					</div>
				</li>
				<li>
					<div class="pull-left wrap-img bgcover"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/img82x55.jpg);">
						<a href="#" title=""></a>
					</div>
					<div>
						<a class="color-title-link" href="#">Việt Nam có điều kiện tự
							nhiên ...</a>
						<p>sản xuất nông nghiệp nhưng bao đời nay chỉ sản xuất nhỏ lẻ,
							manh mún nên không xây dựng được thương hiệu...</p>
					</div>
				</li>
				<li>
					<div class="pull-left wrap-img bgcover"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/img82x55.jpg);">
						<a href="#" title=""></a>
					</div>
					<div>
						<a class="color-title-link" href="#">Việt Nam có điều kiện tự
							nhiên ...</a>
						<p>sản xuất nông nghiệp nhưng bao đời nay chỉ sản xuất nhỏ lẻ,
							manh mún nên không xây dựng được thương hiệu...</p>
					</div>
				</li>
				<li>
					<div class="pull-left wrap-img bgcover"
						style="background-image: url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/img82x55.jpg);">
						<a href="#" title=""></a>
					</div>
					<div>
						<a class="color-title-link" href="#">Việt Nam có điều kiện tự
							nhiên ...</a>
						<p>sản xuất nông nghiệp nhưng bao đời nay chỉ sản xuất nhỏ lẻ,
							manh mún nên không xây dựng được thương hiệu...</p>
					</div>
				</li>
			</ul>
		</div>
	</div>
</div>

<div class="social-share">
	<ul>
		<li><a href="#"><em class="fa fa-facebook"></em></a></li>
		<li><a href="#"><em class="fa fa-twitter"></em></a></li>
		<li><a href="#"><em class="fa fa-instagram"></em></a></li>
		<li><a href="#"><em class="fa fa-google-plus"></em></a></li>
		<li><a href="#"><em class="fa fa-youtube-play"></em></a></li>
		<li><a href="#"><em class="fa fa-pinterest"></em></a></li>
		<li><a href="#"><em class="fa fa-linkedin"></em></a></li>
	</ul>
</div>