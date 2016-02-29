<?php 
use vsoft\ad\models\AdCategory;
use vsoft\ad\models\AdProduct;
use vsoft\express\components\StringHelper;
use yii\web\View;
use frontend\models\User;
use yii\helpers\Url;
	
	$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyASTv_J_7DuXskr5SaCZ_7RVEw7oBKiHi4&callback=loaded', ['depends' => ['yii\web\YiiAsset'], 'async' => true, 'defer' => true]);
	$this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/detail.js', ['position' => View::POS_END]);
	$this->registerCss('.map-wrap {position: relative;} .map-wrap:after {display: block; content: ""; padding-top: 75%;} .map-inside {position: absolute; width: 100%; height: 100%;} #map {height: 100%;}');
	
	$categories = AdCategory::find()->indexBy('id')->asArray(true)->all();
	$types = AdProduct::getAdTypes();
	
	$owner = User::findOne($product->user_id);

	if($owner && $owner->profile) {
		$avatar = $owner->profile->getAvatarUrl();
	} else {
		$avatar = Yii::$app->view->theme->baseUrl . '/resources/images/default-avatar.jpg';
	}
?>

<div class="detail-listing">
	<?php 
		$images = $product->adImages;
		if($images):
	?>
	<div class="gallery-detail swiper-container">
		<div class="swiper-wrapper">
			<?php foreach ($images as $image): ?>
			<div class="swiper-slide">
				<div class="img-show">
					<div>
						<img src="<?= $image->imageMedium ?>">
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
		<div class="swiper-pagination"></div>
	</div>
	<?php endif; ?>
	<p class="infor-by-up">
		<?= ucfirst($categories[$product->category_id]['name']) ?> <?= strtolower($types[$product->type]) ?> bởi <a href="#">Môi Giới</a>
	</p>
	<div class="infor-listing">
		<div class="address-listing">
			<p><?= $product->getAddress(true) ?></p>
		</div>
		<p class="id-duan">ID:<span><?=$product->id;?></span></p>
		<!-- <p class="attr-home">
			<?= $product->adProductAdditionInfo->room_no ? $product->adProductAdditionInfo->room_no . ' <span class="icon icon-bed"></span> | ' : '' ?>
			<?= $product->adProductAdditionInfo->toilet_no ? $product->adProductAdditionInfo->toilet_no . ' <span class="icon icon-bath"></span> | ' : '' ?>
			<span class="price"><?= StringHelper::formatCurrency($product->price) ?></span>
		</p> -->
		<ul class="clearfix list-attr-td">
			<li>
				<span class="icon icon-dt-1"></span>80m2
			</li>
			<li>
				<span class="icon icon-bed-1"></span> 02
			</li>
			<li>
				<span class="icon icon-pt-1"></span> 02
			</li>
		</ul>
		<ul class="pull-right icons-detail">
			<li><a href="#" class="icon icon-share-td"></a></li>
			<li><a href="#" class="icon save-item" data-id="<?=$product->id;?>" data-url="<?=Url::to(['/ad/favorite'])?>"></a></li>
			<li><a href="#" class="icon icon-map-loca"></a></li>
		</ul>
		<p class="price-td">
			<span>Giá</span>
			<?= StringHelper::formatCurrency($product->price) ?>
		</p>
	</div>
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        Diễn tả chi tiết<span class="icon"></span>
                    </a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body" name="about" contenteditable="true" placeholder="Vui lòng chia sẻ tiểu sử">
                    <p><?= $product->content ?></p>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingTwo">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Thông tin chi tiết<span class="icon"></span>
                    </a>
                </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                <div class="panel-body" name="activity" contenteditable="true" placeholder="Vui lòng chia sẻ hoạt động">
                    <?php if($product->area): ?>
					<p>Diện tích: <?= $product->area ?>m<sup>2</sup></p>
					<?php endif; ?>
					<?php if($product->adProductAdditionInfo->facade_width): ?>
					<p>Mặt tiền: <?= $product->adProductAdditionInfo->facade_width ?>m</p>
					<?php endif; ?>
					<?php if($product->adProductAdditionInfo->land_width): ?>
					<p>Đường vào: <?= $product->adProductAdditionInfo->land_width ?>m</p>
					<?php endif; ?>
					<?php if($product->adProductAdditionInfo->floor_no): ?>
					<p>Tầng cao: <?= $product->adProductAdditionInfo->floor_no ?>  Tầng</p>
					<?php endif; ?>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingFour">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        Tiện ích<span class="icon"></span>
                    </a>
                </h4>
            </div>
            <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                <div class="panel-body" name="experience" contenteditable="true" placeholder="Vui lòng nhập chia sẻ kinh nghiệm">
                    
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingSeven">
                <h4 class="panel-title">
                    <a class="" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEght" aria-expanded="false" aria-controls="collapseSeven">
                        Liên hệ<span class="icon"></span>
                    </a>
                </h4>
            </div>
            <div id="collapseEght" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingSeven">
                <div class="panel-body text-center">
            	    <div class="infor-agent clearfix">
						<?php
						/**
						 * auto register user
						 */
						$url = '#';
						if($product->adContactInfo->email){
							$user = $product->adContactInfo->getUserInfo();
							$url = $user->urlProfile();
						}
						?>
			            <?php if(!empty($owner->username)) { ?>
						<a href="<?=$url;?>" class="wrap-img">
			                <img src="<?= $avatar ?>" alt="" /></a>
			            <?php } else { ?>
			                <a class="wrap-img" href="<?=$url;?>"><img src="<?= $avatar ?>" alt="" /></a>
			            <?php } ?>
			            <div class="img-agent">
				            <a href="<?=$url;?>" class="name-agent"><?= $product->adContactInfo->name ?></a>
							<div class="rating-start">
								<fieldset class="rate">
									<input type="radio" id="rating10" name="rating" value="10"> <label
										for="rating10" title="5 stars"> </label> <input type="radio"
										id="rating9" name="rating" value="9"> <label for="rating9"
										class="half" title="5 stars"> </label> <input type="radio"
										id="rating8" name="rating" value="8"> <label for="rating8"
										title="4 stars"> </label> <input type="radio" id="rating7"
										name="rating" value="7"> <label for="rating7" class="half"
										title="4 stars"> </label> <input type="radio" id="rating6"
										name="rating" value="6"> <label for="rating6" title="3 stars"> </label>
									<input type="radio" id="rating5" name="rating" value="5"> <label
										for="rating5" class="half" title="3 stars"> </label> <input
										type="radio" id="rating4" name="rating" value="4"> <label
										for="rating4" title="2 stars"> </label> <input type="radio"
										id="rating3" name="rating" value="3"> <label for="rating3"
										class="half" title="2 stars"> </label> <input type="radio"
										id="rating2" name="rating" value="2"> <label for="rating2"
										title="1 stars"> </label> <input type="radio" id="rating1"
										name="rating" value="1"> <label for="rating1" class="half"
										title="1 stars"> </label>
								</fieldset>
							</div>
							<div class="item-agent">
								<div>
									<span class="icon icon-person"></span>
								</div>
								Người Môi Giới
							</div>
							<?php if($product->adContactInfo->mobile): ?>
							<div class="item-agent">
								<div>
									<span class="icon icon-phone"></span>
								</div>
								<a href="tel:<?= $product->adContactInfo->mobile ?>"><?= $product->adContactInfo->mobile ?></a>
							</div>
							<?php endif; ?>
							<?php if($product->adContactInfo->email): ?>
							<div class="item-agent">
								<div>
									<span class="icon icon-email"></span>
								</div>
								<?= $product->adContactInfo->email ?>
							</div>
							<?php endif; ?>
							<div class="item-agent">
								<div>
									<span class="icon address-icon"></span>
								</div>
								Ho Chi Minh City, Vietnam
							</div>
						</div>
					</div>
					<button id="" class="email-btn btn-common btn-small pull-left">Email</button>
					<?php if(!Yii::$app->user->isGuest && !empty($owner->username) && !$owner->isMe()) { ?>
						<a href="<?=Url::to(['/chat/with', 'username'=>$owner->username])?>" id="" class="chat-btn btn-common btn-small pull-right">Chat</a>
					<?php }?>
					
                </div>
            </div>

        </div>
    </div>
</div>

<?php
/**
 * notification
 */
if(!Yii::$app->user->isGuest && !empty($owner->username) && !$owner->isMe()) {
	$userVisit = Yii::$app->user->identity;
	$userTo = $owner;

	$nameUserTo = $userTo->profile->getDisplayName();
	$nameUserFrom = Yii::$app->user->identity->profile->getDisplayName();
	?>
	<script>
		$(document).ready(function () {
			$(document).on('click', '.save-item', function (e) {
				var to_jid = chatUI.genJid('<?=$userTo->username?>');
				Chat.sendMessage(to_jid , 'save product', 'notify', {fromName: '<?=$nameUserFrom;?>', toName: '<?=$nameUserTo;?>'});
			});
			$(document).bind('chat/afterConnect', function (event, data) {
				var to_jid = chatUI.genJid('<?=$userTo->username?>');
				Chat.sendMessage(to_jid , 'view product', 'notify', {fromName: '<?=$nameUserFrom;?>', toName: '<?=$nameUserTo;?>'});
			});
		});
	</script>
	<?php
}
?>