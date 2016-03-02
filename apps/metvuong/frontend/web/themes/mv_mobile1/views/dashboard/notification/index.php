<?php
use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Json;
use vsoft\ad\models\AdProduct;
use frontend\models\UserActivity;
use yii\helpers\Html;
?>
<div class="title-fixed-wrap">
	<div class="noti-alert">
		<div class="title-top">Thông báo</div>
		<?php
		$query = \frontend\models\UserActivity::find();
		$query->andWhere(['buddy_id' => Yii::$app->user->id]);
		$query->orderBy('created,updated DESC');
		$query->limit(100);
		$activities = $query->all();
		if(!empty($activities)) {
			?>
			<div class="wrap-noti">
			<div class="list-noti clearfix">
				<?php
					foreach($activities as $activity) {
						$owner = $activity->getOwner();
						$buddy = $activity->getBuddy();
						?>
						<div class="item">
							<div class="user-get clearfix">
<!--								<span class="icon icon-message"></span>-->
								<span class="icon save-item-1"></span>
								<div class="avatar"><a href="<?=$owner->urlProfile();?>"><img src="<?=Url::to(['member/avatar', 'usrn'=>$owner->username])?>" alt="" width="40" height="40"></a></div>
								<a href="#" class="name"><?=$owner->profile->getDisplayName();?></a>
								<?php
								$params = Json::decode($activity->params);
								if($activity->action == UserActivity::ACTION_AD_FAVORITE) {
									$product = AdProduct::findOne(['id'=>$params['product']]);
									if(!empty($product)) {
										$params['user'] = '';
										$params['product'] = Html::a(Yii::t('activity', 'post'), $product->urlDetail());
										$params['product_owner'] = Html::a($activity->getBuddy()->profile->getDisplayName(), $activity->getBuddy()->urlProfile());
										$message = Yii::t('activity', $activity->message, $params);
										?>
										<p class="date-type"><span><?= date('H:i:s d-m-Y', $activity->created); ?>.</span> <?= $message; ?></p>
										<div class="post-get">
											<a href="<?=$product->urlDetail()?>" class="clearfix">
												<div class="img-show">
													<div>
														<img src="<?= $product->getRepresentImage()?>">
													</div>
												</div>
												<span><?= $product->getAddress()?></span>
											</a>
										</div>
										<?php
									}
								}elseif($activity->action == UserActivity::ACTION_AD_CLICK) {
								?>
									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
								<?php
								}
								?>
							</div>
						</div>
						<?php
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
