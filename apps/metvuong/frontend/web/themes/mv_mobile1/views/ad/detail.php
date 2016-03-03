<?php 
use vsoft\ad\models\AdCategory;
use vsoft\ad\models\AdProduct;
use vsoft\express\components\StringHelper;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use frontend\models\User;
use yii\helpers\Url;

	$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyASTv_J_7DuXskr5SaCZ_7RVEw7oBKiHi4&callback=loaded', ['depends' => ['yii\web\YiiAsset'], 'async' => true, 'defer' => true]);
	$this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/detail.js', ['position' => View::POS_END]);
	$this->registerCss('.map-wrap {position: relative;} .map-wrap:after {display: block; content: ""; padding-top: 75%;} .map-inside {position: absolute; width: 100%; height: 100%;} #map {height: 100%;}');
	
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

    $address = $product->getAddress();
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
			<li><a href="#" class="icon save-item <?=!empty($product->productSaved->saved_at) ? 'active' : '';?>" data-id="<?=$product->id;?>" data-url="<?=Url::to(['/ad/favorite'])?>"></a></li>
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

<div id="popup-email" class="popup-common hide-popup">
	<div class="wrap-popup">
		<div class="title-popup clearfix">
			<div class="text-center">SHARE VIA EMAIL</div>
			<a href="#" class="txt-cancel pull-left btn-cancel">Cancel</a>
		</div>
		<div class="inner-popup">
            <?php
            $share_form = Yii::createObject([
                'class'    => \frontend\models\ShareForm::className(),
                'scenario' => 'share',
            ]);

            $f = ActiveForm::begin([
                'id' => 'share_form',
                'enableAjaxValidation' => false,
                'enableClientValidation' => true,
                'action' => Url::to(['/ad/sendmail'])
            ]);

            if(!Yii::$app->user->isGuest){
            ?>
            <?= $f->field($share_form, 'your_email')->hiddenInput(['class'=>'your_email', 'value' => Yii::$app->user->identity->profile->public_email])->label(false) ?>
            <?php } else { ?>
				<div class="frm-item frm-email">
                    <?= $f->field($share_form, 'your_email')->textInput(['class'=>'your_email', 'placeholder'=>Yii::t('subject', 'Email của bạn...')])->label(false) ?>
				</div>
            <?php } ?>
                <div class="frm-item frm-email clearfix">
					<?= $f->field($share_form, 'subject')->textInput(['class'=>'subject', 'placeholder'=>Yii::t('subject', 'Tiêu đề...')])->label(false)?>
				</div>
				<div class="frm-item frm-email clearfix frm-textarea">
                    <?= $f->field($share_form, 'content')->textarea(['class'=>'content', 'cols' => 30, 'rows' => 5, 'placeholder'=>Yii::t('content', 'Nội dung...')])->label(false) ?>
				</div>
				<div class="item-send">
					<div class="img-show"><div><a href="<?=Yii::$app->request->absoluteUrl?>"><img src="<?= !empty($images[0]) ? $images[0]->url : '#' ?>" alt="<?=$address?>"></a></div></div>
					<div class="infor-send">
						<p class="name"><?=$address?></p>
						<p class="address"></p>
						<p><?=StringHelper::truncate($product->content, 150)?></p>
						<p class="send-by">BY METVUONG.COM</p>
					</div>
                    <?= $f->field($share_form, 'recipient_email')->hiddenInput(['class'=>'recipient_email', 'value'=> $product->adContactInfo->email])->label(false) ?>
                    <?= $f->field($share_form, 'address')->hiddenInput(['class' => '_address', 'value'=> $address])->label(false) ?>
                    <?= $f->field($share_form, 'detailUrl')->hiddenInput(['class' => '_detailUrl', 'value'=> Yii::$app->request->absoluteUrl])->label(false) ?>
                    <?= $f->field($share_form, 'domain')->hiddenInput(['class' => '_domain', 'value'=>Yii::$app->urlManager->getHostInfo()])->label(false) ?>
				</div>
				<div class="text-right">
					<button class="btn-common rippler rippler-default btn-cancel">Cancel</button>
					<button class="btn-common rippler rippler-default send_mail">Send</button>
				</div>
            <?php $f->end(); ?>
		</div>
	</div>
</div>

<div id="popup-map" class="popup-common hide-popup">
	<div class="wrap-popup">
		<div class="inner-popup">
			<a href="#" class="btn-close-map">trở lại</a>
        	<iframe width="600" height="450" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?q=place_id:ChIJT7lZ30cvdTER8skpPrOuvGs&key=AIzaSyDgukAnWQNq0fitebULUbottG5gvK64OCQ" allowfullscreen></iframe>
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
				var to_jid = chatUI.genJid('<?=$userTo->username?>');
				Chat.sendMessage(to_jid , 'view product', 'notify', {fromName: '<?=$nameUserFrom;?>', toName: '<?=$nameUserTo;?>', total: 1});
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
			closeBtn: '#popup-email .btn-cancel'
		});

		$('#popup-map').popupMobi({
			btnClickShow: ".infor-listing .icon-map-loca",
			closeBtn: "#popup-map .btn-close-map"
		});
	});
    $(document).on('click', '.send_mail', function(){
        var timer = 0;
        var recipient_email = $('#share_form .recipient_email').val();
        var your_email = $('#share_form .your_email').val();
        if(recipient_email != null && your_email != null) {
            var content_mail = $('#share_form .content').val();
            clearTimeout(timer);
            timer = setTimeout(function () {
                $('#popup-email').addClass('hide-popup');
                $.ajax({
                    type: "post",
                    dataType: 'json',
                    url: $('#share_form').attr('action'),
                    data: $('#share_form').serializeArray(),
                    success: function (data) {
                        if(data.status == 200){
//                                alert("success");
                        }
                        else {
                            var strMessage = '';
                            $.each(data.parameters, function(idx, val){
                                var element = 'share_form_'+idx;
                                strMessage += "\n" + val;
                            });
                            alert(strMessage+"\nTry again");
                            $('#share_form .recipient_email').focus();
                        }
                        return true;
                    },
                    error: function (data) {
                        var strMessage = '';
                        $.each(data.parameters, function(idx, val){
                            var element = 'share_form_'+idx;
                            strMessage += "\n" + val;
                        });
                        alert(strMessage);
                        return false;
                    }
                });
            }, 500);
        }
        return false;
    });
</script>