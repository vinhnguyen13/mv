<?php
use yii\web\View;
use yii\helpers\Url;
use yii\widgets\LinkPager;

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
			<?php
				foreach ($models as $model):
					$image = '';
					if($gallery = explode(',', $model->bpGallery)) {
						$image = $gallery[0];
					}
			?>
			<div class="col-md-4 item-list col-sm-6 col-xs-6">
				<div>
					<a href="<?= Url::to(['/building-project/view', 'slug' => $model->slug]) ?>" class="wrap-img-duan">
						<span class="wrap-img bgcover" style="background-image: url(<?= Url::to('/store/building-project-images/' . $image) ?>);"></span>
						<span class="name-duan"><?= $model->title ?></span>
						<span><?= $model->bpLocation ?></span>
					</a>
					<a href="<?= Url::to(['/building-project/view', 'slug' => $model->slug]) ?>" class="mask">
						<strong>VỊ TRÍ DỰ ÁN</strong>
						<span><?= $model->bpLocation ?></span>
						<strong>DIỆN TÍCH KHU ĐẤT</strong>
						<span><?= $model->bpAcreage ?></span>
						<strong>SỐ LƯỢNG CĂN HỘ</strong>
						<span><?= $model->bpApartmentNo ?></span>
						<strong>SỐ TẦNG</strong>
						<span><?= $model->bpFloorNo ?></span>
					</a>
				</div>
			</div>
			<?php endforeach; ?>
			<?= LinkPager::widget(['pagination' => $pages, 'nextPageLabel' => '<em class="fa fa-chevron-right"></em>', 'prevPageLabel' => '<em class="fa fa-chevron-left"></em>']) ?>
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