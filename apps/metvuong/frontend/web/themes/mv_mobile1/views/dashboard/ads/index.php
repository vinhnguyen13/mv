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
					<div class="bgcover" style="background-image:url(<?= $product->getImage() ?>);"><a href="<?=Url::to(['/dashboard/statistics', 'id' => $product->id])?>"></a></div>
					<a href="<?=Url::to(['/dashboard/statistics', 'id' => $product->id])?>"><em class="icon-bar-chart"></em>View Stats</a>
					<?php if($product->end_date < time()): ?>
					<a class="unactive-pro" href="#"><em class="fa fa-close"></em>Inactive</a>
					<?php else: ?>
					<a class="active-pro" href="#"><em class="fa fa-check"></em>Active</a>
					<?php endif; ?>
				</div>
				<div class="intro-detail">
					<a href="#" class="icon-edit icon"></a>
					<div class="name-duan">
						<?php if($product->projectBuilding): ?>
						<p class="name"><a href="<?=Url::to(['/dashboard/statistics', 'id' => $product->id])?>"><?= $product->projectBuilding->name ?></a></p>
						<?php endif; ?>
						<p class="date-post">Ngày đăng tin: <?= date("d/m/Y", $product->created_at) ?></p>
						<p class="loca-duan"><em class="icon-pointer"></em> <?= $product->address ?></p>
					</div>
					<p><?= (count($product->content) > 162) ? mb_substr($product->content, 0, 162) . '...' : $product->content ?></p>
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