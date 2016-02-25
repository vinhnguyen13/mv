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
				<div class="img-show">
					<div>
						<a href="<?=Url::to(['/dashboard/statistics', 'id' => $product->id])?>"><img src="<?= $product->getImage() ?>"></a>
					</div>
				</div>
				<div class="intro-detail">
					<a href="#" class="icon-edit-small icon"></a>
					<?php if($product->projectBuilding): ?>
					<p class="name-duan"><a href="<?=Url::to(['/dashboard/statistics', 'id' => $product->id])?>"><?= $product->projectBuilding->name ?></a></p>
					<?php endif; ?>
					<p class="date-post">Ngày đăng tin: <?= date("d/m/Y", $product->created_at) ?></p>
					<?php if($product->end_date < time()): ?>
					<div><div><span class="icon icon-inactive-pro"></span></div><strong>Inactive Project</strong></div>
					<?php else: ?>
					<div><div><span class="icon icon-active-pro"></span></div><strong>Active Project</strong></div>
					<?php endif; ?>
					<div><div><span class="icon icon-view-small"></span></div><strong>1000</strong> Lượt xem</div>
					<div><div><span class="icon icon-per-small"></span></div><strong>300</strong> Visitor</div>
					<div><div><span class="icon icon-heart-small"></span></div><strong>100</strong> Thích</div>
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