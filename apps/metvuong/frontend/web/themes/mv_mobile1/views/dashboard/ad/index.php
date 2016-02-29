<?php
use yii\web\View;
use yii\helpers\Url;
?>
<div class="title-fixed-wrap">
	<div class="u-allduan">
		<div class="title-top">Tất cả dự án</div>
		<?php if($products): ?>
		<div class="wrap-list-duan">
			<ul class="clearfix">
				<?php foreach($products as $product): ?>
				<li>
					<div class="img-show">
						<div>
							<a href="<?=Url::to(['/dashboard/statistics', 'id' => $product->id])?>"><img src="<?= $product->getImage() ?>">
								<div class="name-address-duan">
									<?php if($product->projectBuilding): ?>
									<p class="name-duan"><?= $product->projectBuilding->name ?></p>
									<?php endif; ?>
									<p class="loca-duan"><?= $product->address ?></p>
								</div>
							</a>
						</div>
						<a href="#" class="edit-duan"><span class="icon-edit-small icon"></span></a>
					</div>
					<div class="intro-detail">
						<div class="status-duan clearfix">
							<?php if($product->end_date < time()): ?>
							<div class="pull-right wrap-icon"><div><span class="icon icon-inactive-pro"></span></div><strong>Inactive Project</strong></div>
							<?php else: ?>
							<div class="pull-right wrap-icon"><div><span class="icon icon-active-pro"></span></div><strong>Active Project</strong></div>
							<?php endif; ?>

							<p class="date-post">Ngày đăng tin: <span><?= date("d/m/Y", $product->created_at) ?></span></p>
							<p class="id-duan">ID:<span><?=$product->id;?></span></p>
						</div>
						<div class="pull-right push-price">
							<p>Tin còn <strong>15 ngày</strong></p>
							<a href="#nang-cap" class="btn-nang-cap">Nâng cấp</a>
						</div>
						<div class="wrap-icon"><div><span class="icon icon-view-small"></span></div><strong>1000</strong> Lượt xem</div><div class="wrap-icon"><div><span class="icon icon-per-small"></span></div><strong>300</strong> Visitor</div>
						<div class="wrap-icon"><div><span class="icon icon-heart-small"></span></div><strong>100</strong> Thích</div>
					</div>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php else: ?>
		<div class="no-duan">
			<div>
				<p>Hiện tại, bạn không có<br>dự án nào.</p>
				<a href="#" class="btn-000">Đăng Dự Án</a>
			</div>
		</div>
		<?php endif; ?>
	</div>
</div>

<div id="nang-cap" class="popup-common hide-popup">
	<div class="wrap-popup">
		<div class="inner-popup">
			<a href="#" class="btn-close btn-cancel"><span class="icon icon-close"></span></a>
			<p class="alert-num-date">Tin đăng còn <span>0 ngày</span></p>
			<p>Nâng cấp tin đăng thêm 30 ngày?  </p>
			<div class="text-center">
				<a href="#" class="btn-common btn-cancel">Từ chối</a>
				<a href="#" class="btn-common">Đồng ý</a>
			</div>
		</div>
	</div>
</div>



<script>
	$(document).ready(function () {
		$('#nang-cap').popupMobi({
			btnClickShow: '.btn-nang-cap',
			styleShow: 'center',
			closeBtn: '#nang-cap .btn-cancel'
		});
    });
</script>