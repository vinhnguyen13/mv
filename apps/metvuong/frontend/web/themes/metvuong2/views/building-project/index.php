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
					if($gallery = explode(',', $model->gallery)) {
						$image = $gallery[0];
					}
			?>
			<div class="col-md-4 item-list col-sm-6 col-xs-6">
				<div>
					<a href="<?= Url::to(['/building-project/view', 'slug' => $model->slug]) ?>" class="wrap-img-duan">
						<span class="wrap-img bgcover" style="background-image: url(<?= Url::to('/store/building-project-images/' . $image) ?>);"></span>
						<span class="name-duan"><?= $model->name ?></span>
						<span><?= $model->location ?></span>
					</a>
					<a href="<?= Url::to(['/building-project/view', 'slug' => $model->slug]) ?>" class="mask">
						<strong>VỊ TRÍ DỰ ÁN</strong>
						<span><?= $model->location ?></span>
						<strong>DIỆN TÍCH KHU ĐẤT</strong>
						<span><?= $model->land_area ?></span>
						<?php if($model->apartment_no): ?>
						<strong>SỐ LƯỢNG CĂN HỘ</strong>
						<span><?= $model->apartment_no ?></span>
						<?php endif; ?>
						<?php if($model->floor_no): ?>
						<strong>SỐ TẦNG</strong>
						<span><?= $model->floor_no ?></span>
						<?php endif; ?>
					</a>
				</div>
			</div>
			<?php endforeach; ?>
			<?= LinkPager::widget(['pagination' => $pages, 'nextPageLabel' => '<em class="fa fa-chevron-right"></em>', 'prevPageLabel' => '<em class="fa fa-chevron-left"></em>']) ?>
		</div>
	</div>
	<div class="col-sm-4 col-lg-3 col-left-home">
		<?= \vsoft\news\widgets\NewsWidget::widget(['view' => 'hotnews'])?>
		<div class="siderbar widget-ads clearfix">
			<a class="wrap-img" href="http://www.dreamplex.co/"><img src="http://www.reic.info/Content/themes/v1/Images/banner/Dreamplex-300x250.jpg" alt="dreamplex"></a>
		</div>
		<?= \vsoft\news\widgets\NewsWidget::widget(['view' => 'important'])?>
	</div>
</div>

<!-- <div class="social-share">
	<ul>
		<li><a href="#"><em class="fa fa-facebook"></em></a></li>
		<li><a href="#"><em class="fa fa-twitter"></em></a></li>
		<li><a href="#"><em class="fa fa-instagram"></em></a></li>
		<li><a href="#"><em class="fa fa-google-plus"></em></a></li>
		<li><a href="#"><em class="fa fa-youtube-play"></em></a></li>
		<li><a href="#"><em class="fa fa-pinterest"></em></a></li>
		<li><a href="#"><em class="fa fa-linkedin"></em></a></li>
	</ul>
</div> -->