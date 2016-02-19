<?php
use yii\web\View;
use yii\helpers\Url;
?>

<div class="u-allduan">
	<div class="title-top">Tất cả dự án</div>
	<?php if($products): ?>
	<div class="wrap-list-duan">
		<ul class="clearfix">
			<?php foreach($products as $product): ?>
			<li>
				<div class="img-intro pull-left">
					<div class="bgcover" style="background-image:url(<?= $product->getImage() ?>);"><a href="<?=Url::to(['/dashboard/statistics'])?>"></a></div>
					<a href="<?=Url::to(['/dashboard/statistics', 'id' => $product->id])?>"><em class="icon-bar-chart"></em>View Stats</a>
					<a class="active-pro" href="#"><em class="fa fa-check"></em>Active Project</a>
				</div>
				<div class="intro-detail">
					<a href="#" class="icon-edit icon"></a>
					<div class="name-duan">
						<?php if($product->projectBuilding): ?>
						<p class="name"><a href="<?=Url::to(['/dashboard/statistics', 'id' => $product->id])?>"><?= $product->projectBuilding->name ?></a></p>
						<?php endif; ?>
						<p class="date-post">Ngày đăng tin: <?= date("d/m/Y", $product->created_at) ?></p>
						<p class="loca-duan"><em class="icon-pointer"></em> Quận 4, Ho Chi Minh</p>
					</div>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud...</p>
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