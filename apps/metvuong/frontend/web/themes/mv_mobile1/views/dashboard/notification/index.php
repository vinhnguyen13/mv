<?php
use yii\web\View;
use yii\helpers\Url;
?>
<div class="noti-alert">
	<div class="title-top">Thông báo</div>
	<div class="wrap-noti">
		<div class="list-noti clearfix">
			<?php
			if(!empty(YII_ENV) && in_array(YII_ENV, [YII_ENV_DEV])){
				?>
				<ul>
					<li><a href="#" class="">Test save product</a></li>
					<li><a href="#">Test view product</a></li>
				</ul>
				<?php
			}
			?>
			<div class="item">
				<div class="user-get">
					<div class="avatar"><a href="#"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/default-avatar.jpg' ?>" alt="" width="40" height="40"></a></div>
					<a href="#" class="name">Hao Do</a>
					<p class="date-type"><span>TODAY, 14:35.</span> commented your message</p>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
				</div>
			</div>
			<div class="item">
				<div class="user-get">
					<div class="avatar"><a href="#"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/default-avatar.jpg' ?>" alt="" width="40" height="40"></a></div>
					<a href="#" class="name">Vinh</a>
					<p class="date-type"><span>TODAY, 14:35.</span> liked 3 your photos</p>
					<ul class="clearfix list-photo">
						<li><div class="wrap-img bgcover" style="background-image:url(<?= Yii::$app->view->theme->baseUrl . '/resources/images/bg2-1448820766-.jpg' ?>);"></div></li>
						<li><div class="wrap-img bgcover" style="background-image:url(<?= Yii::$app->view->theme->baseUrl . '/resources/images/img-duan-demo.jpg' ?>);"></div></li>
						<li><div class="wrap-img bgcover" style="background-image:url(<?= Yii::$app->view->theme->baseUrl . '/resources/images/nd_1450536426_k4uZ.jpg' ?>);"></div></li>
					</ul>
				</div>
			</div>
			<div class="item">
				<div class="user-get">
					<div class="avatar"><a href="#"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/default-avatar.jpg' ?>" alt="" width="40" height="40"></a></div>
					<a href="#" class="name">nguyen ngoc thuy</a>
					<p class="date-type"><span>TODAY, 14:35.</span> commented your post</p>
					<div class="post-get">
						<a href="#" class="clearfix">
							<span class="bgcover" style="background-image:url(<?= Yii::$app->view->theme->baseUrl . '/resources/images/bg2-1448820766-.jpg' ?>);"></span>
							<span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</span>
						</a>
					</div>
				</div>
			</div>
			<div class="item">
				<div class="user-get">
					<div class="avatar"><a href="#"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/default-avatar.jpg' ?>" alt="" width="40" height="40"></a></div>
					<a href="#" class="name">Hao Do</a>
					<p class="date-type"><span>TODAY, 14:35.</span> commented your message</p>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
				</div>
			</div>
			<div class="item">
				<div class="user-get">
					<div class="avatar"><a href="#"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/default-avatar.jpg' ?>" alt="" width="40" height="40"></a></div>
					<a href="#" class="name">Vinh</a>
					<p class="date-type"><span>TODAY, 14:35.</span> liked 3 your photos</p>
					<ul class="clearfix list-photo">
						<li><div class="wrap-img bgcover" style="background-image:url(<?= Yii::$app->view->theme->baseUrl . '/resources/images/bg2-1448820766-.jpg' ?>);"></div></li>
						<li><div class="wrap-img bgcover" style="background-image:url(<?= Yii::$app->view->theme->baseUrl . '/resources/images/img-duan-demo.jpg' ?>);"></div></li>
						<li><div class="wrap-img bgcover" style="background-image:url(<?= Yii::$app->view->theme->baseUrl . '/resources/images/nd_1450536426_k4uZ.jpg' ?>);"></div></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function () {

	});
</script>