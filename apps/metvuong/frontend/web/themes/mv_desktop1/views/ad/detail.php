<?php
use frontend\models\Tracking;
use vsoft\ad\models\AdCategory;
use vsoft\ad\models\AdImages;
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

Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => $address
]);
Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => \yii\helpers\StringHelper::truncate($product->content, 500, $suffix = '...', $encoding = 'UTF-8')
]);

Yii::$app->view->registerMetaTag([
    'property' => 'og:title',
    'content' => $address
]);
Yii::$app->view->registerMetaTag([
    'property' => 'og:description',
    'content' => \yii\helpers\StringHelper::truncate($product->content, 500, $suffix = '...', $encoding = 'UTF-8')
]);
Yii::$app->view->registerMetaTag([
    'property' => 'og:type',
    'content' => 'article'
]);
Yii::$app->view->registerMetaTag([
    'property' => 'og:image',
    'content' => $product->representImage
]);

if(isset(Yii::$app->params['tracking']['all']) && (Yii::$app->params['tracking']['all'] == true) && ($product->user_id != Yii::$app->user->id)) {
    Tracking::find()->productVisitor(Yii::$app->user->id, $product->id, time());
}
?>
<div class="container">
	<div class="title-fixed-wrap">
	    <div class="detail-listing row detail-listing-extra">
	    	<div id="detail-wrap" class="col-xs-12 col-md-9 col-left">
				<?php if($owner): ?>
				<a href="<?= Url::to(['update', 'id' => $product->id]) ?>" class="edit-listing">
					<svg class="icon-svg edit-listing-white"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#edit-listing-white"></use></svg></a>
				<?php endif; ?>
				<?php
					$images = $product->adImages;
					if($images):
				?>
				<div class="wrap-swiper">
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
						<div class="swiper-button-next"><span></span></div>
        				<div class="swiper-button-prev"><span></span></div>
					</div>
				</div>
				<?php endif; ?>
				<div class="infor-listing">
					<div class="address-feat clearfix">
						<p class="infor-by-up">
							<?= ucfirst($categories[$product->category_id]['name']) ?> <?= $types[$product->type] ?>
						</p>
						<div class="address-listing">
							<p><?= $address ?></p>
						</div>
						<p class="id-duan">ID:<span><?= Yii::$app->params['listing_prefix_id'] . $product->id;?></span></p>
						<ul class="clearfix list-attr-td">
							<?= $product->area ? '<li> <span class="wrap-icon-svg"><svg class="icon-svg icon-dt-svg"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-dt-svg"></use></svg></span>' . $product->area . 'm2 </li>' : '' ?>
							<?= $product->adProductAdditionInfo->room_no ? '<li> <span class="wrap-icon-svg"><svg class="icon-svg icon-bed-svg"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-bed-svg"></use></svg></span> ' . $product->adProductAdditionInfo->room_no . ' </li>' : '' ?>
							<?= $product->adProductAdditionInfo->toilet_no ? '<li> <span class="wrap-icon-svg"><svg class="icon-svg icon-bathroom-svg"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-bathroom-svg"></use></svg></span> ' . $product->adProductAdditionInfo->toilet_no . ' </li>' : '' ?>
						</ul>	
					</div>
					<ul class="pull-right icons-detail">
						<li>
							<button data-toggle="tooltip" data-placement="bottom" title="Copy link" class="btn-copy tooltip-show" type="button" data-clipboard-text="<?= $product->urlDetail(true) ?>">
								<span class="wrap-icon-svg"><svg class="icon-svg icon-copy-svg"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-copy-svg"></use></svg></span>
							</button>
						</li>
						<li><a href="#" data-toggle="modal" data-placement="bottom" data-target="#popup-share-social" class="icon icon-share-td tooltip-show" title="Share social"></a></li>
			            <?php if($product->user_id != Yii::$app->user->id){ ?>
						<li><a data-toggle="tooltip" data-placement="bottom" title="Favourite" href="#" class="icon save-item tooltip-show <?=!empty($product->productSaved->saved_at) ? 'active' : '';?>" data-id="<?=$product->id;?>" data-url="<?=Url::to(['/ad/favorite'])?>"></a></li>
			            <?php } ?>
						<li><a href="#" data-toggle="modal" data-placement="bottom" data-target="#popup-map" class="icon icon-map-loca tooltip-show" title="Location"></a></li>
					</ul>

					<?=$this->renderAjax('/ad/_partials/shareEmail',[
				    'product' => $product,
				    'yourEmail' => empty($user) ? "" : (empty($user->profile->public_email) ? $user->email : $user->profile->public_email),
				    'recipientEmail' => $product->adContactInfo->email,
				    'params' => ['your_email' => false, 'recipient_email' => false] ])?>


					<div id="popup-map" class="modal fade popup-common" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-body">
									<a href="#" class="btn-close-map close" data-dismiss="modal" aria-label="Close">trở lại</a>
									<div id="map_detail" data-lat="<?= $product->lat ?>" data-lng="<?= $product->lng ?>"></div>
								</div>
							</div>
						</div>
					</div>

					<?=$this->render('/ad/_partials/shareSocial',[
					    'url' => $product->urlDetail(true),
					    'title' => $address,
					    'description' => \yii\helpers\StringHelper::truncate($product->content, 200, $suffix = '...', $encoding = 'UTF-8'),
					    'image' => $product->image_file_name ? AdImages::getImageUrl($product->image_folder, $product->image_file_name, AdImages::SIZE_THUMB) : AdImages::defaultImage()
					])?>

					<script>
						$(document).ready(function () {

							var clipboard = new Clipboard('.btn-copy');

							var swiper = new Swiper('.detail-listing-extra .swiper-container', {
								pagination: '.swiper-pagination',
								paginationClickable: true,
						        nextButton: '.swiper-button-next',
						        prevButton: '.swiper-button-prev',
						        spaceBetween: 0
						    });

							$('.tooltip-show').tooltip();

							$('#popup-map').on('show.bs.modal', function (e) {
							    var mapEl = $('#map_detail');
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
							});

							$(document).on('click', '#popup-share-social .icon-email-1', function (e) {
								$('#popup-share-social').addClass('hide-popup');
								$('.email-btn').trigger('click');
							});
						});
					</script>

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
						            <?php if(!empty($owner)) { ?>
									<a href="/<?=$owner->username;?>" class="wrap-img">
						                <img src="<?= $avatar ?>" alt="<?=$owner->username;?>" /></a>
						            <?php } else { ?>
						                <a class="wrap-img" href="<?=$url;?>"><img src="<?= $avatar ?>" alt="" /></a>
						            <?php } ?>
						            <div class="img-agent">
							            <a href="/<?= !empty($owner) ? $owner->username : "#" ?>" class="name-agent"><?= $product->adContactInfo->name ?></a>
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
										<a href="#" data-toggle="modal" data-target="#popup-email" class="email-btn btn-common btn-small">Email</a>
										<?php if(!Yii::$app->user->isGuest && !empty($owner->username) && !$owner->isMe()) { ?>
											<a href="<?=Url::to(['/chat/with', 'username'=>$owner->username])?>" id="" class="chat-btn btn-common btn-small">Chat</a>
										<?php }?>
									</div>
								</div>

			                </div>
			            </div>

			        </div>
			    </div>
			</div>
			<?= $this->render('/ad/_partials/similarListing', ['city_id' => $product->city_id, 'district_id' => $product->district_id]) ?>
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