<?php
use yii\web\View;
use yii\helpers\Url;
?>
<div class="title-fixed-wrap">
	<div class="noti-alert">
		<div class="title-top">Thông báo</div>
		<div class="wrap-noti">
			<div class="list-noti clearfix">
				<?php
				$query = \frontend\models\UserActivity::find();
				$query->andWhere(['buddy_id' => Yii::$app->user->id]);
				$query->orderBy('created,updated DESC');
				$activities = $query->all();
				if(!empty($activities)) {
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
								<p class="date-type"><span><?=date('H:i:s d-m-Y', $activity->created);?>.</span> <?=$activity->getMessage();?></p>
								<?php
								if($activity->action == \frontend\models\UserActivity::ACTION_AD_FAVORITE) {
								?>
									<div class="post-get">
										<a href="#" class="clearfix">
											<div class="img-show">
												<div>
													<a href=""><img
															src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/bg2-1448820766-.jpg' ?>"></a>
												</div>
											</div>
											<span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</span>
										</a>
									</div>
								<?php
								}else{
								?>
									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
								<?php
								}
								?>
							</div>
						</div>
						<?php
					}
				}
				?>
			</div>
		</div>
	</div>
</div>
