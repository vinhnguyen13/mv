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
use yii\helpers\ArrayHelper;
use vsoft\ad\models\AdFacility;

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
			if(!empty($user)){
				$url = $user->urlProfile();
			}
		}
		$avatar = Yii::$app->view->theme->baseUrl . '/resources/images/default-avatar.jpg';
	}

    $address = $product->getAddress($product->show_home_no);

    $directionList = AdProductAdditionInfo::directionList();

$content = strip_tags($product->content);
$description = \yii\helpers\StringHelper::truncate($content, 500, $suffix = '...', $encoding = 'UTF-8');
$description = str_replace("\r", "", $description);
$description = str_replace("\n", "", $description);

Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => $address
]);
Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => $description
]);

if(isset(Yii::$app->params['tracking']['all']) && (Yii::$app->params['tracking']['all'] == true) && ($product->user_id != Yii::$app->user->id)) {
    Tracking::find()->productVisitor(Yii::$app->user->id, $product->id, time());
}

$userId = Yii::$app->user->identity ? Yii::$app->user->identity->id : null;
?>
<div class="title-fixed-wrap container">
	<div class="detail-listing row detail-listing-extra">
    	<div id="detail-wrap" class="col-xs-12 col-md-9 col-left">
			<div class="wrap-swiper clearfix">
				<?php
					$images = $product->adImages;
					if($images):
				?>
				<div class="gallery-detail swiper-container pull-left">
					<div class="swiper-wrapper">
						<?php foreach ($images as $image): ?>
						<div class="swiper-slide">
							<div class="img-show">
								<div>
									<img src="<?= $image->getUrl(AdImages::SIZE_LARGE) ?>" alt="<?=$address?>">
								</div>
							</div>
						</div>
						<?php endforeach; ?>
					</div>

					<div class="swiper-pagination"></div>

					<div class="swiper-button-prev icon-mv"><span class=""></span></div>
					<div class="swiper-button-next icon-mv"><span class=""></span></div>
				</div>
				<?php else: ?>
				<div class="no-gallery pull-left">
					<div class="img-show">
						<div>
							<img alt="" src="/themes/metvuong2/resources/images/default-ads.jpg" />
						</div>
					</div>
				</div>
				<?php endif; ?>
				<ul class="pull-right icons-detail">
					<li class="color-1">
						<a href="#" data-toggle="tooltip" data-placement="bottom" title="<?= Yii::t('ad', 'Copy link') ?>" data-title-success="<?= Yii::t('ad', 'Copied') ?>" class="btn-copy" data-clipboard-text="<?= $product->urlDetail(true) ?>">
							<span class="icon-mv"><span class="icon-link"></span></span>
							<?= Yii::t('ad', 'Copy link') ?>
						</a>
					</li>
					<li class="color-2">
						<a href="#" class="share-facebook" data-url="<?=Url::to(['/ad/tracking-share', 'product_id' => $product->id, 'type' => \vsoft\tracking\models\base\AdProductShare::SHARE_FACEBOOK], true)?>">
							<span class="icon-mv"><span class="icon-facebook"></span></span>
							<?= Yii::t('ad', 'Share Facebook') ?>
						</a>	
					</li>
					<li class="color-3">
						<a href="#" data-toggle="modal" data-target="#popup_email_share">
							<span class="icon-mv fs-18"><span class="icon-mail-profile"></span></span>
							<?= Yii::t('ad', 'Share Email') ?>
						</a>	
					</li>
		            <?php if($product->user_id != Yii::$app->user->id){ ?>
					<li class="color-4">
						<a href="#" class="save-item <?=!empty($product->productSaved->saved_at) ? 'active' : '';?>" data-id="<?=$product->id;?>" data-url="<?=Url::to(['/ad/favorite'])?>">
							<span class="icon-mv"><span class="icon-heart-icon-listing"></span></span>
							<?= Yii::t('ad', 'Add to Favorites') ?>
						</a>
					</li>
		            <?php } ?>
					<li class="color-5">
						<a href="#" data-toggle="modal" data-target="#popup-map">
							<span class="icon-mv"><span class="icon-pin-active-copy-3"></span></span>	
							<?= Yii::t('ad', 'Location') ?>
						</a>
					</li>
					<li class="color-6">
						<a href="#">
							<span class="icon-mv"><span class="icon-warning"></span></span>	
							<?= Yii::t('ad', 'Report Abuse') ?>
						</a>
					</li>
					<?php if($userId == $product->user_id): ?>
					<li class="edit-item color-7">
						<a href="<?= Url::to(['update', 'id' => $product->id]) ?>" class="edit-listing">
							<span class="icon-mv"><span class="icon-edit-copy-4"></span></span>
							Edit Listing
						</a>
					</li>
					<?php endif; ?>
					<li class="color-8">
						<a href="#">
							<span class="icon-mv"><span class="icon-phone-profile"></span></span>	
							<?= Yii::t('ad', 'Contact Agent') ?>
						</a>
					</li>
				</ul>
			</div>
			<div class="infor-listing">
				<div class="address-feat clearfix">
					<p class="infor-by-up">
						<?= ucfirst($categories[$product->category_id]['name']) ?> <?= $types[$product->type] ?> <?= Yii::t('ad', 'by') ?> <a href="javascript:;"><?= $product->ownerString ?></a>
					</p>
					<div class="address-listing">
						<p><?= $address ?></p>
					</div>
					<p class="id-duan"><?= Yii::t('ad', 'ID') ?>:<span><?= Yii::$app->params['listing_prefix_id'] . $product->id;?></span></p>
					<ul class="clearfix list-attr-td">
						<?= $product->area ? '<li> <span class="icon-mv"><span class="icon-page-1-copy"></span></span>' . $product->area . 'm2 </li>' : '' ?>
						<?= $product->adProductAdditionInfo->room_no ? '<li> <span class="icon-mv"><span class="icon-bed-search"></span></span>' . $product->adProductAdditionInfo->room_no . ' </li>' : '' ?>
						<?= $product->adProductAdditionInfo->toilet_no ? '<li> <span class="icon-mv"><span class="icon-bathroom-search-copy-2"></span></span>' . $product->adProductAdditionInfo->toilet_no . ' </li>' : '' ?>
					</ul>	
				</div>
				<?=$this->renderAjax('/ad/_partials/shareEmail',[
                    'popup_email_name' => 'popup_email_contact',
                    'product' => $product,
                    'yourEmail' => empty($user) ? "" : (empty($user->profile->public_email) ? $user->email : $user->profile->public_email),
                    'recipientEmail' => null,
                    'params' => ['your_email' => false, 'recipient_email' => false] ])?>

                <?=$this->renderAjax('/ad/_partials/shareEmail',[
                    'popup_email_name' => 'popup_email_share',
                    'product' => $product,
                    'yourEmail' => empty($user) ? "" : (empty($user->profile->public_email) ? $user->email : $user->profile->public_email),
                    'recipientEmail' => null,
                    'params' => ['your_email' => false, 'recipient_email' => false] ])?>


				<div id="popup-map" class="modal fade popup-common" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-body">
								<a href="#" class="btn-close-map close" data-dismiss="modal" aria-label="Close"><?=Yii::t('listing','Back')?></a>
								<div id="map_detail" data-lat="<?= $product->lat ?>" data-lng="<?= $product->lng ?>"></div>
							</div>
						</div>
					</div>
				</div>

				<script>
					$(document).ready(function () {

						var clipboard = new Clipboard('.btn-copy');

						clipboard.on('success', function(e) {
						    var txtSuccess = $(e.trigger).data('titleSuccess');
						    $('.btn-copy').tooltip("show");
						    $('.tooltip .tooltip-inner').text(txtSuccess);
						    setTimeout(function () {
						    	$('.btn-copy').tooltip("destroy");
						    },500);
						});

						var swiper = new Swiper('.swiper-container', {
							pagination: '.swiper-pagination',
							paginationClickable: true,
					        nextButton: '.swiper-button-next',
					        prevButton: '.swiper-button-prev',
					        spaceBetween: 0
					    });

						$('.tooltip-show').tooltip();

						$('#popup-map').on('show.bs.modal', function (e) {
							setTimeout(function(){
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
							}, 400);
						});

                        function fbShare(url, title, descr, image, winWidth, winHeight) {
                            var winTop = (screen.height / 2) - (winHeight / 2);
                            var winLeft = (screen.width / 2) - (winWidth / 2);
                            window.open('http://www.facebook.com/sharer.php?s=100&p[url]=' + url + '&p[title]=' + title + '&p[summary]=' + descr + '&p[images][0]=' + image, 'sharer', 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight);
                        }

                        $('.share-facebook').click(function (){
                            $('body').loading();
                            var url = $(this).data("url");
                            if(url != undefined && url.length > 0) {
                                $.ajax({
                                    type: "get",
                                    dataType: 'json',
                                    url: url,
                                    success: function (data) {
                                        var link = '<?=$product->urlDetail(true)?>';
                                        if(link != '#' && link.length > 0) {
                                            var winWidth = 520;
                                            var winHeight = 350;
                                            var winTop = (screen.height / 2) - (winHeight / 2);
                                            var winLeft = (screen.width / 2) - (winWidth / 2);
                                            var title = '<?=$address?>';
                                            var descr = '<?=$description?>';
                                            var image = '<?=Yii::$app->request->hostInfo.$product->representImage?>';
                                            window.open('http://www.facebook.com/sharer.php?s=100&p[url]=' + link + '&p[title]=' + title + '&p[summary]=' + descr + '&p[images][0]=' + image, 'sharer', 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight);
                                        }
                                        $('body').loading({done:true});
                                        return true;
                                    }
                                });
                            }
                            return false;
                        });

						/*$(document).on('click', '#popup-share-social .icon-email-1', function (e) {
							$('#popup-share-social').addClass('hide-popup');
							$('.email-btn').trigger('click');
						});*/
					});
				</script>

				<p class="price-td">
					<span><?= Yii::t('ad', 'Price') ?></span>
					<?= StringHelper::formatCurrency($product->price) ?>
				</p>
			</div>
			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		        <div class="panel panel-default">
		            <div class="panel-heading" role="tab" id="headingOne">
		                <h4 class="panel-title">
		                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
		                        <?= Yii::t('ad', 'Content') ?>
		                        <span class="icon-mv"><span class="icon-plus"></span></span>
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
		                        <?= Yii::t('ad', 'Detail Information') ?>
		                        <span class="icon-mv"><span class="icon-plus"></span></span>
		                    </a>
		                </h4>
		            </div>
		            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
		                <div class="panel-body" name="activity">
		                	<ul class="clearfix list-tienich-detail">
			                    <?php if($product->projectBuilding): ?>
								<li><strong><?= Yii::t('ad', 'Project') ?>:</strong> <a href="<?= Url::to(["building-project/view", 'slug'=> $product->projectBuilding->slug]); ?>"><?= $product->projectBuilding->name ?></a></li>
								<?php endif; ?>
								<?php if($product->adProductAdditionInfo->facade_width): ?>
								<li><strong><?= Yii::t('ad', 'Facade') ?>:</strong> <?= $product->adProductAdditionInfo->facade_width ?>m</li>
								<?php endif; ?>
								<?php if($product->adProductAdditionInfo->land_width): ?>
								<li><strong><?= Yii::t('ad', 'Entry width') ?>:</strong> <?= $product->adProductAdditionInfo->land_width ?>m</li>
								<?php endif; ?>
								<?php if($product->adProductAdditionInfo->floor_no): ?>
								<li><strong><?= $product->projectBuilding ? Yii::t('ad', 'Floor plan') : Yii::t('ad', 'Number of storeys') ?>:</strong> <?= $product->adProductAdditionInfo->floor_no ?>  Tầng</li>
								<?php endif; ?>
								<?php if($product->adProductAdditionInfo->home_direction): ?>
								<li><strong><?= Yii::t('ad', 'House direction') ?>:</strong> <?= $directionList[$product->adProductAdditionInfo->home_direction] ?></li>
								<?php endif; ?>
								<?php if($product->adProductAdditionInfo->facade_direction): ?>
								<li><strong><?= Yii::t('ad', 'Balcony direction') ?>:</strong> <?= $directionList[$product->adProductAdditionInfo->facade_direction] ?></li>
								<?php endif; ?>
								<?php if($product->adProductAdditionInfo->interior): ?>
								<li><strong><?= Yii::t('ad', 'Furniture') ?>:</strong> <?= $product->adProductAdditionInfo->interior ?></li>
								<?php endif; ?>
							</ul>
		                </div>
		            </div>
		        </div>
		        <?php if($product->projectBuilding && $product->projectBuilding->adFacilities): ?>
		        <div class="panel panel-default">
		            <div class="panel-heading" role="tab" id="headingFour">
		                <h4 class="panel-title">
		                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
		                        <?= Yii::t('ad', 'Facilities') ?>
		                        <span class="icon-mv"><span class="icon-plus"></span></span>
		                    </a>
		                </h4>
		            </div>
		            <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
		                <div class="panel-body" name="experience">
							<?= implode(', ', ArrayHelper::getColumn($product->projectBuilding->adFacilities, 'name')) ?>
		                </div>
		            </div>
		        </div>
		        <?php elseif($product->adProductAdditionInfo->facility): ?>
		        <div class="panel panel-default">
		            <div class="panel-heading" role="tab" id="headingFour">
		                <h4 class="panel-title">
		                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
		                        <?= Yii::t('ad', 'Facilities') ?>
		                        <span class="icon-mv"><span class="icon-plus"></span></span>
		                    </a>
		                </h4>
		            </div>
		            <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
		                <div class="panel-body" name="experience">
							<?= implode(', ', ArrayHelper::getColumn(AdFacility::find()->where(['id' => $product->adProductAdditionInfo->facility])->all(), 'name')) ?>
		                </div>
		            </div>
		        </div>
		        <?php endif; ?>
		        <div class="panel panel-default">
		            <div class="panel-heading" role="tab" id="headingSeven">
		                <h4 class="panel-title">
		                    <a class="" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEght" aria-expanded="false" aria-controls="collapseSeven">
		                        <?= Yii::t('ad', 'Contact') ?>
		                        <span class="icon-mv"><span class="icon-plus"></span></span>
		                    </a>
		                </h4>
		            </div>
		            <div id="collapseEght" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingSeven">
		                <div class="panel-body text-center">
		            	    <div class="infor-agent clearfix">
					            <?php if(!empty($owner)) { ?>
								    <a href="<?=Url::to(['member/profile', 'username' => $owner->username])?>" class="wrap-img">
					                <img src="<?= $avatar ?>" alt="<?=$owner->username;?>" /></a>
					            <?php } else { ?>
					                <a class="wrap-img"><img src="<?= $avatar ?>" alt="" /></a>
					            <?php } ?>
					            <div class="img-agent">
                                    <?php if(!empty($owner)) { ?>
						            <a href="<?=Url::to(['member/profile', 'username' => $owner->username])?>" class="name-agent"><?= $owner->profile->name ?></a>
                                    <?php } else {?>
						            <span class="name-agent"><?= $product->adContactInfo->name ?></span>
                                    <?php } ?>
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
                                    <?php if($owner){
                                        if(!empty($owner->location)) {?>
									<div class="item-agent">
										<div>
											<span class="icon address-icon"></span>
										</div>
										<?= $owner->location->city?>
									</div>
                                    <?php } } ?>
									<?php if(!empty($owner->username) && !$owner->isMe()) { ?>
                                        <a href="#" data-toggle="modal" data-target="#popup_email_contact" class="email-btn btn-common btn-small">Email</a>
										<a href="<?=Url::to(['/chat/with', 'username'=>$owner->username])?>" id="" class="chat-btn btn-common btn-small chat-now" data-chat-user="<?=$owner->username?>">Chat</a>
									<?php }?>
								</div>
							</div>

		                </div>
		            </div>

		        </div>
		    </div>
		</div>
        <div class="col-xs-12 col-md-3 col-right sidebar-col">
            <div class="item-sidebar">
                <?=\vsoft\ad\widgets\ListingWidget::widget(['title' => Yii::t('listing','SIMILAR LISTINGS'), 'limit' => 4])?>
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