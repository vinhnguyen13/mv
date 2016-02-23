<?php
use yii\web\View;
use yii\helpers\Url;
?>
<div class="noti-alert">
	<div class="title-top">Thông báo</div>
	<div class="wrap-noti">
		<div class="list-noti clearfix">
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

<?php
if(!empty(YII_ENV) && in_array(YII_ENV, [YII_ENV_DEV])){
	$userVisit = Yii::$app->user->identity;
	$userTo = \frontend\models\User::find()->where(['username' => 'superadmin'])->one();

	$nameUserTo = $userTo->profile->getDisplayName();
	$nameUserFrom = Yii::$app->user->identity->profile->getDisplayName();
	?>
	<ul>
		<li><a href="javascript:void(0);" class="saveProduct">Save product</a></li>
		<li><a href="javascript:void(0);" class="viewProduct">View product</a></li>
	</ul>


	<script>
		$(document).ready(function () {
			$(document).on('click', '.saveProduct', function (e) {
				var to_jid = chatUI.genJid('<?=$userTo->username?>');
				Chat.sendMessage(to_jid , 'save product', 'notify', {fromName: '<?=$nameUserFrom;?>', toName: '<?=$nameUserTo;?>'});
			});
			$(document).on('click', '.viewProduct', function (e) {
				var to_jid = chatUI.genJid('<?=$userTo->username?>');
				Chat.sendMessage(to_jid , 'view product', 'notify', {fromName: '<?=$nameUserFrom;?>', toName: '<?=$nameUserTo;?>'});
			});
		});
	</script>
	<?php
}
?>