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
				$query->andWhere(['object_owner_id' => Yii::$app->user->id]);
				$query->orderBy('created,updated DESC');
				$activities = $query->all();
				if(!empty($activities)) {
					foreach($activities as $activity) {
						?>
						<div class="item">
							<div class="user-get clearfix">
<!--								<span class="icon icon-message"></span>-->
								<span class="icon save-item-1"></span>

								<div class="avatar"><a href="#"><img src="<?=Url::to(['member/avatar', 'usrn'=>$activity->username])?>" alt="" width="40" height="40"></a></div>
								<a href="#" class="name"><?=$activity->username;?></a>
								<p class="date-type"><span><?=date('H:i:s d-m-Y', $activity->created);?>.</span> <?=$activity->getMessage();?></p>
								<div class="post-get">
									<a href="#" class="clearfix">
										<div class="img-show">
											<div>
												<a href=""><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/bg2-1448820766-.jpg' ?>"></a>
											</div>
										</div>
										<span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</span>
									</a>
								</div>
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
