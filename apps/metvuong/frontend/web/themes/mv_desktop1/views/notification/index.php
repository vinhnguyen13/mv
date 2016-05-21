<?php
use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Json;
use vsoft\ad\models\AdProduct;
use frontend\models\UserActivity;
use yii\helpers\Html;

$owner = $current_user = Yii::$app->user->identity;
?>
<div class="title-fixed-wrap container">
	
	<div class="noti-alert">
		<div class="title-top">Notification</div>
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
                            $product = AdProduct::findOne(['id' => $params['product']]);
                            $pid = $product->id;
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

									<a href="#" class="pdL-10 pdR-10 tooltip-show btn-email-item" data-placement="bottom" title="Send email" data-target="#popup_email" data-type="contact" data-toggle="modal"
                                       data-url="<?=Url::to(['member/profile-render-email', 'username'=>$owner->username, 'pid' => $pid])?>">
										<span class="icon-mv fs-18 color-cd"><span class="icon-mail-profile"></span></span>
									</a>
	                                <a href="#" class="chat-now tooltip-show" data-chat-user="<?=$owner->username?>" data-placement="bottom" title="Send message">
	                                	<span class="icon-mv fs-20 color-cd"><span class="icon-bubbles-icon"></span></span>
	                                </a>
									<?php

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
			<div class="wrap-alert-info">
				<div class="alert alert-info">
					<p><?=Yii::t('common', '{object} no data', ['object' => Yii::t('activity', 'Notification')])?></p>
				</div>
			</div>
			<?php
		}
		?>
	</div>
	
</div>
<?php
$pid = empty($pid) ? AdProduct::find()->select(['id'])->asArray()->one()['id'] : $pid;
echo $this->renderAjax('/ad/_partials/shareEmail', [
    'popup_email_name' => 'popup_email_contact',
    'pid' => $pid,
    'yourEmail' => empty($current_user) ? "" : (empty($current_user->profile->public_email) ? $current_user->email : $current_user->profile->public_email),
    'recipientEmail' => null,
    'params' => ['your_email' => false, 'recipient_email' => false]]);?>
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

        $(document).on('click', '.btn-email-item', function() {
            var url = $(this).data('url');
            if(url) {
                $.ajax({
                    type: "get",
                    dataType: 'json',
                    url: url,
                    success: function (data) {
                        if(data.email) {
                            $('#share_form #shareform-recipient_email').attr('value', data.email);
                            $('#share_form .img-show img').attr('src', data.product.imageUrl);
                            $('#share_form .name').text(data.product.address);
                            $('#share_form .description').text(data.product.description);

                            $('#share_form #shareform-address').attr('value', data.product.address);
                            $('#share_form #shareform-detailurl').attr('value', data.product.detailUrl);
                            $('#share_form #shareform-domain').attr('value', data.product.domain);
                            $('#share_form #shareform-area').attr('value', data.product.area);
                            $('#share_form #shareform-room_no').attr('value', data.product.room_no);
                            $('#share_form #shareform-toilet_no').attr('value', data.product.toilet_no);
                            $('#share_form #shareform-price').attr('value', data.product.price);
                            $('#share_form #shareform-imageurl').attr('value', data.product.imageUrl);
                        }
                    }
                });
            }
            return false;
        });


    });
</script>