<?php
use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Json;
use vsoft\ad\models\AdProduct;
use frontend\models\UserActivity;
use yii\helpers\Html;
?>
<div class="title-fixed-wrap">
	<div class="container">
		<?php $this->beginContent('@app/views/layouts/_partials/menuUser.php'); ?><?php $this->endContent();?>
		<div class="noti-alert">
			<?php
			$query = \frontend\models\UserActivity::find();
	//		$query->andWhere(['params.owner'=>[1]]);
	//		$query->andWhere(['NOT IN', 'objects', [5070]]);
			$query->andWhere(['buddy_id' => Yii::$app->user->id]);
			$query->orderBy('updated DESC');
			$query->limit(100);
			$activities = $query->all();
			if(!empty($activities)) {
				?>
				<div class="wrap-noti">
					<div class="line-noti"></div>
					<div class="list-noti clearfix">
						<?php
						foreach($activities as $activity) {
							$id = (string) $activity->_id ;
							$owner = $activity->getOwner();
							$buddy = $activity->getBuddy();
							$params = $activity->params;
							if($activity->isAction(UserActivity::ACTION_AD_FAVORITE) || $activity->isAction(UserActivity::ACTION_AD_CLICK)) {
								?>
								<div
									class="item <?= (!empty($activity->read_status) && $activity->read_status == UserActivity::READ_YES) ? 'read' : 'unread'; ?>"
									data-id="<?= $id ?>">
									<div class="user-get clearfix">
										<!--<span class="icon icon-message"></span>-->
										<div class="icon-noti-type">
											<?php if ($activity->isAction(UserActivity::ACTION_AD_CLICK)) { ?>
												<span class="icon-mv fs-18 color-body"><span class="icon-eye-copy"></span></span>
				                            <?php } elseif ($activity->isAction(UserActivity::ACTION_AD_FAVORITE)) { ?>
												<span class="icon-mv fs-19 color-body"><span class="icon-heart-icon-listing"></span></span>
				                            <?php } ?>
			                            </div>
		                                <div class="avatar"><a href="<?= $owner->urlProfile(); ?>"><img src="<?= Url::to(['member/avatar', 'usrn' => $owner->username]) ?>" alt="" width="40" height="40"></a></div>
										<a href="#" class="name"><?= $owner->profile->getDisplayName(); ?></a>
										<a href="#" class="pdL-10 pdR-10">
											<span class="icon-mv fs-18 color-cd"><span class="icon-mail-profile"></span></span>
										</a>
		                                <a href="#" class="chat-now" data-chat-user="<?=$owner->username?>">
		                                	<span class="icon-mv fs-20 color-cd"><span class="icon-bubbles-icon"></span></span>
		                                </a>
										<?php
										$product = AdProduct::findOne(['id' => $params['product']]);
										if (!empty($product)) {
											$params['owner'] = '';
											$params['product'] = Html::a($product->getAddress(), $product->urlDetail());
											$params['buddy'] = '';
											Html::a($activity->getBuddy()->profile->getDisplayName(), $activity->getBuddy()->urlProfile());
											$message = Yii::t('activity', $activity->message, $params);
											?>

											<div class="post-get" style="display: none;">
												<a href="<?= $product->urlDetail() ?>" class="clearfix">
													<span><?= $product->getAddress() ?></span>
												</a>
											</div>
											<p class="message"><?= $message; ?></p>
											<p class="date-type"><span><?= date('H:i:s d-m-Y', $activity->created); ?></span></p>
											<?php
										}
										?>
									</div>
								</div>
								<?php
							}
	//						$activity->read();
						}
						?>
					</div>
				</div>
				<?php
			}else{
				?>
				<div class="alert alert-info">
					<p><?=Yii::t('common', '{object} no data', ['object' => Yii::t('activity', 'Notification')])?></p>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>
<script>
	$(document).ready(function () {
		$(document).on('click', '.item a', function(){
			var _itemObj = $(this).closest('.item');
			if(_itemObj.hasClass('unread')){
				var _id = $(this).closest('.item').attr('data-id');
				var timer = 0;
				var href = $(this).attr('href');
				clearTimeout(timer);
				timer = setTimeout(function () {
					$.ajax({
						type: "post",
						url: "<?=Url::to(['/notification/index', 'username'=> Yii::$app->user->identity->username])?>",
						data: {id: _id, stt: 'read'},
						success: function (data) {
							location.href = href;
						}
					});
				}, 500);
				$(this).removeClass('unread').addClass('read');
				return false;
			}
		});
	});
</script>