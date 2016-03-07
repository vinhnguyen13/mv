<?php
use yii\web\View;
use yii\helpers\Url;

?>
<div class="title-fixed-wrap">
	<div class="u-allduan">
		<div class="title-top">Tất cả tin</div>
		<?php if($products): ?>
		<div class="wrap-list-duan">
			<ul class="clearfix">
				<?php foreach($products as $product): ?>
				<li>
					<div class="img-show">
						<div>
							<a href="<?=Url::to(['/dashboard/statistics', 'id' => $product->id])?>"><img src="<?= $product->representImage ?>">
								<div class="name-address-duan">
									<?php if($product->projectBuilding): ?>
									<p class="name-duan"><?= $product->projectBuilding->name ?></p>
									<?php endif; ?>
									<p class="loca-duan"><?= $product->address ?></p>
								</div>
							</a>
						</div>
						<a href="<?= Url::to(['/ad/update', 'id' => $product->id]) ?>" class="edit-duan"><span class="icon-edit-small icon"></span></a>
					</div>
					<div class="intro-detail">
						<div class="status-duan clearfix">
							<?php if($product->end_date < time()): ?>
							<div class="pull-right wrap-icon"><div><span class="icon icon-inactive-pro"></span></div><strong>Inactive Project</strong></div>
							<?php else: ?>
							<div class="pull-right wrap-icon"><div><span class="icon icon-active-pro"></span></div><strong>Active Project</strong></div>
							<?php endif; ?>

							<p class="date-post">Ngày đăng tin: <span><?= date("d/m/Y", $product->created_at) ?></span></p>
							<p class="id-duan">ID tin đăng:<span><?= Yii::$app->params['listing_prefix_id'] . $product->id;?></span></p>
						</div>
						<div class="pull-right push-price">
							<?php if($product->end_date > time()){
                                $d = $product->end_date - time() ?>
                                <p>Tin còn <strong><?=floor($d/(60*60*24))?> ngày</strong></p>
                            <?php } ?>
							<a href="#nang-cap" class="btn-nang-cap">Nâng cấp</a>
						</div>
                        <?php
                        if(($search = \frontend\models\Tracking::find()->countFinders($product->id)) === null){
							$search = 0;
						}
						if(($click = \frontend\models\Tracking::find()->countVisitors($product->id)) === null){
							$click = 0;
						}
						if(($fav = \frontend\models\Tracking::find()->countFavourites($product->id)) === null){
							$fav = 0;
						}
                        ?>
						<div class="wrap-icon"><div><span class="icon icon-search-small-1"></span></div><strong><?=$search?></strong> Search</div>
                        <div class="wrap-icon"><div><span class="icon icon-view-small"></span></div><strong><?=$click?></strong> Click</div>
						<div class="wrap-icon"><div><span class="icon icon-heart-small"></span></div><strong><?=$fav?></strong> Favourite</div>
					</div>
				</li>
				<?php endforeach; ?>
			</ul>
			<div id="nang-cap" class="popup-common hide-popup">
				<div class="wrap-popup">
					<div class="inner-popup">
						<a href="#" class="btn-close btn-cancel"><span class="icon icon-close"></span></a>
						<p class="alert-num-date">Tin đăng còn <span>0 ngày</span></p>
						<p>Nâng cấp tin đăng thêm 30 ngày?  </p>
						<div class="text-center">
							<a href="#" class="btn-common btn-cancel">Từ chối</a>
							<a href="#" class="btn-common btn-ok">Đồng ý</a>
						</div>
					</div>
				</div>
			</div>
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

<script>
	$(document).ready(function () {
		$('#nang-cap').popupMobi({
			btnClickShow: '.btn-nang-cap',
			styleShow: 'center',
			closeBtn: '#nang-cap .btn-cancel, #nang-cap .btn-ok',
		});
    });
</script>