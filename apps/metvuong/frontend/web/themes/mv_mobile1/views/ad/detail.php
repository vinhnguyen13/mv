<?php 
use vsoft\ad\models\AdCategory;
use vsoft\ad\models\AdProduct;
use vsoft\express\components\StringHelper;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use frontend\models\User;
use yii\helpers\Url;
use vsoft\ad\models\AdProductAdditionInfo;

	$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyASTv_J_7DuXskr5SaCZ_7RVEw7oBKiHi4', ['depends' => ['yii\web\YiiAsset'], 'async' => true, 'defer' => true]);
	$this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/detail.js', ['position' => View::POS_END]);
	$this->registerCss('.map-wrap {position: relative;} .map-wrap:after {display: block; content: ""; padding-top: 75%;} .map-inside {position: absolute; width: 100%; height: 100%;} #map {height: 100%;}');

    $user = Yii::$app->user->identity;
	$categories = AdCategory::find()->indexBy('id')->asArray(true)->all();
	$types = AdProduct::getAdTypes();
	
	$owner = User::findOne($product->user_id);

	$url = '#';
	if($owner && $owner->profile) {
		$avatar = $owner->profile->getAvatarUrl();
	} else {
		/**
		 * auto register user
		 */
		if($product->adContactInfo->email){
			$user = $product->adContactInfo->getUserInfo();
			$url = $user->urlProfile();
		}
		$avatar = Yii::$app->view->theme->baseUrl . '/resources/images/default-avatar.jpg';
	}

    $address = $product->getAddress($product->show_home_no);
    
    $directionList = AdProductAdditionInfo::directionList();
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
						<img src="<?= $image->url ?>" alt="<?=$address?>">
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
		<div class="swiper-pagination"></div>
	</div>
	<?php endif; ?>
	<p class="infor-by-up">
		<?= ucfirst($categories[$product->category_id]['name']) ?> <?= $types[$product->type] ?> bởi <a href="#">Môi Giới</a>
	</p>
	<div class="infor-listing">
		<div class="address-listing">
			<p><?= $address ?></p>
		</div>
		<p class="id-duan">ID tin đăng:<span><?= Yii::$app->params['listing_prefix_id'] . $product->id;?></span></p>
		<ul class="clearfix list-attr-td">
			<?= $product->area ? '<li> <span class="icon icon-dt icon-dt-small"></span>' . $product->area . 'm2 </li>' : '' ?>
			<?= $product->adProductAdditionInfo->room_no ? '<li> <span class="icon icon-bed icon-bed-small"></span> ' . $product->adProductAdditionInfo->room_no . ' </li>' : '' ?>
			<?= $product->adProductAdditionInfo->toilet_no ? '<li> <span class="icon icon-pt icon-pt-small"></span> ' . $product->adProductAdditionInfo->toilet_no . ' </li>' : '' ?>
		</ul>
		<ul class="pull-right icons-detail">
			<li><a href="#popup-share-social" class="icon icon-share-td"></a></li>
			<li><a href="#" class="icon save-item <?=!empty($product->productSaved->saved_at) ? 'active' : '';?>" data-id="<?=$product->id;?>" data-url="<?=Url::to(['/ad/favorite'])?>"></a></li>
			<li><a href="#popup-map" class="icon icon-map-loca"></a></li>
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
                <div class="panel-body" name="about" placeholder="Vui lòng chia sẻ tiểu sử">
                    <p><?= str_replace("\n", "<br />", htmlspecialchars($product->content)) ?></p>
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
                <div class="panel-body" name="activity" contenteditable="true">
                	<ul class="clearfix list-tienich-detail">
	                    <?php if($product->area): ?>
						<li><strong>Diện tích:</strong> <?= $product->area ?>m2</li>
						<?php endif; ?>
						<?php if($product->adProductAdditionInfo->facade_width): ?>
						<li><strong>Mặt tiền:</strong> <?= $product->adProductAdditionInfo->facade_width ?>m</li>
						<?php endif; ?>
						<?php if($product->adProductAdditionInfo->land_width): ?>
						<li><strong>Đường vào:</strong> <?= $product->adProductAdditionInfo->land_width ?>m</li>
						<?php endif; ?>
						<?php if($product->adProductAdditionInfo->floor_no): ?>
						<li><strong>Tầng cao:</strong> <?= $product->adProductAdditionInfo->floor_no ?>  Tầng</li>
						<?php endif; ?>
						<?php if($product->adProductAdditionInfo->home_direction): ?>
						<li><strong>Hướng nhà:</strong> <?= $directionList[$product->adProductAdditionInfo->home_direction] ?></li>
						<?php endif; ?>
						<?php if($product->adProductAdditionInfo->facade_direction): ?>
						<li><strong>Hướng ban công:</strong> <?= $directionList[$product->adProductAdditionInfo->facade_direction] ?></li>
						<?php endif; ?>
						<?php if($product->adProductAdditionInfo->interior): ?>
						<li><strong>Nội thất:</strong> <?= $product->adProductAdditionInfo->interior ?></li>
						<?php endif; ?>
					</ul>
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
                <div class="panel-body" name="experience" placeholder="Vui lòng nhập chia sẻ kinh nghiệm">
                    
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
					<div class="text-center">
						<a href="#popup-email" id="" class="email-btn btn-common btn-small">Email</a>
						<?php if(!Yii::$app->user->isGuest && !empty($owner->username) && !$owner->isMe()) { ?>
							<a href="<?=Url::to(['/chat/with', 'username'=>$owner->username])?>" id="" class="chat-btn btn-common btn-small">Chat</a>
						<?php }?>
					</div>
                </div>
            </div>

        </div>
    </div>
</div>

<?=$this->renderAjax('/ad/_partials/shareEmail',[
    'product' => $product,
    'yourEmail' => empty($user) ? "" : (empty($user->profile->public_email) ? $user->email : $user->profile->public_email),
    'recipientEmail' => $product->adContactInfo->email,
    'params' => ['your_email' => false, 'recipient_email' => false] ])?>


<div id="popup-map" class="popup-common hide-popup">
	<div class="wrap-popup">
		<div class="inner-popup">
			<a href="#" class="btn-close-map">trở lại</a>
        	<div id="map" data-lat="<?= $product->lat ?>" data-lng="<?= $product->lng ?>"></div>
		</div>
	</div>
</div>

<div id="popup-share-social" class="popup-common hide-popup">
    <div class="wrap-popup">
        <div class="inner-popup">
            <a href="#" class="btn-close"><span class="icon icon-close"></span></a>
            <div class="wrap-body-popup">
                <span>Share on Social Network</span>
                <ul class="clearfix">
                    <li>
                        <a href="#" class="share-facebook">
                            <div class="circle"><div><span class="icon icon-face"></span></div></div>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="email-btn">
                            <div class="circle"><div><span class="icon icon-email-1"></span></div></div>
                        </a>
                    </li>
                </ul>
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
				e.preventDefault();
				$(this).toggleClass('active');
				var timer = 0;
				clearTimeout(timer);
				var _id = $(this).attr('data-id');
				var _url = $(this).attr('data-url');
				var _stt = ($(this).hasClass('active')) ? 1 : 0;
				timer = setTimeout(function () {
					$.ajax({
						type: "post",
						url: _url,
						data: {id: _id, stt: _stt},
						success: function (data) {
							if(data.statusCode == 200){
								var to_jid = chatUI.genJid('<?=$userTo->username?>');
								Chat.sendMessage(to_jid , 'save product', 'notify', {fromName: '<?=$nameUserFrom;?>', toName: '<?=$nameUserTo;?>', total: data.parameters.msg});
							}
						}
					});
				}, 500);

			});
			$(document).bind('chat/afterConnect', function (event, data) {
				<?php if(Yii::$app->session->getFlash('notify_other')){?>
					var to_jid = chatUI.genJid('<?=$userTo->username?>');
					Chat.sendMessage(to_jid , 'view product', 'notify', {fromName: '<?=$nameUserFrom;?>', toName: '<?=$nameUserTo;?>', total: <?=Yii::$app->session->getFlash('notify_other');?>});
				<?php }?>
			});
		});
	</script>
	<?php
}
?>

<script>
	$(document).ready(function () {
		$('#popup-email').popupMobi({
			btnClickShow: ".email-btn",
			closeBtn: '#popup-email .btn-cancel',
			styleShow: "full"
		});

		$('#popup-map').popupMobi({
			btnClickShow: ".infor-listing .icon-map-loca",
			closeBtn: "#popup-map .btn-close-map",
			effectShow: "show-hide",
			funCallBack: function() {
				var mapEl = $('#map');
				var latLng = {lat: Number(mapEl.data('lat')), lng:  Number(mapEl.data('lng'))};
				var map = new google.maps.Map(mapEl.get(0), {
					center: latLng,
				    zoom: 16,
				    mapTypeControl: false,
				    zoomControl: false,
				    streetViewControl: false
				});
				
				var marker = new google.maps.Marker({
				    position: latLng,
				    map: map
				});
			}
		});

		$('#popup-share-social').popupMobi({
            btnClickShow: ".icons-detail .icon-share-td",
            closeBtn: ".btn-close",
            styleShow: "center"
        });

		$(document).on('click', '#popup-share-social .icon-email-1', function (e) {
			$('#popup-share-social').addClass('hide-popup');
			$('.email-btn').trigger('click');
		});

        $(document).on('click', '.share-facebook', function() {
            FB.ui({
                method: 'share',
                href: '<?=Yii::$app->request->absoluteUrl?>'
            }, function(response){});
        });

	});

</script>
<?php
$fb_appId = '680097282132293'; // stage.metvuong.com
if(strpos(Yii::$app->urlManager->hostInfo, 'dev.metvuong.com'))
    $fb_appId = '736950189771012';
else if(strpos(Yii::$app->urlManager->hostInfo, 'local.metvuong.com'))
    $fb_appId = '891967050918314';
?>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : <?=$fb_appId?>,
            xfbml      : true,
            version    : 'v2.5'
        });
    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/vi_VN/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>