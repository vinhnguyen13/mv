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
		<ul class="clearfix icons-detail">
			<li><a href="#" class=""><span class="icon icon-loca"></span></a></li>
			<li><a href="#" class="save-item" data-id="<?=$product->id;?>" data-url="<?=Url::to(['/ad/favorite'])?>"><span class="icon icon-fave"></span></a></li>
		</ul>
		<div class="swiper-wrapper">
			<?php foreach ($images as $image): ?>
			<div class="swiper-slide">
				<div class="bgcover" style="background-image: url(<?= $image->imageMedium ?>)"></div>
			</div>
			<?php endforeach; ?>
		</div>
		<div class="swiper-pagination"></div>
	</div>
	<?php endif; ?>
	<div class="infor-listing">
		<p class="infor-by-up">
			<?= ucfirst($categories[$product->category_id]['name']) ?> <?= strtolower($types[$product->type]) ?> bởi <a href="#">Môi Giới</a>
		</p>
		<p class="address-listing"><?= $product->getAddress(true) ?></p>
		<p class="attr-home">
			<?= $product->adProductAdditionInfo->room_no ? $product->adProductAdditionInfo->room_no . ' <span class="icon icon-bed"></span> | ' : '' ?>
			<?= $product->adProductAdditionInfo->toilet_no ? $product->adProductAdditionInfo->toilet_no . ' <span class="icon icon-bath"></span> | ' : '' ?>
			<span class="price"><?= StringHelper::formatCurrency($product->price) ?></span>
		</p>
	</div>
	<div class="attr-detail">
		<div class="title-attr-listing">Diễn tả chi tiết</div>
		<p><?= $product->content ?></p>
		
	</div>
	<div class="attr-detail">
		<div class="title-attr-listing">Thông tin chi tiết</div>
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
	<div class="attr-detail">
		<div class="title-attr-listing">Tiện ích (6)</div>
		<p>Hồ bơi</p>
		<p>Mailbox</p>
		<p>BBQ Area</p>
		<p>Tennis Court</p>
		<p>24/7 Bảo Vệ</p>
		<p>Gym</p>
		
	</div>
	<!-- <div class="attr-detail">
		<div class="title-attr-listing">Địa điểm</div>
		<div class="map-wrap">
			<div class="map-inside">
				<div data-lat="<?= $product->lat ?>" data-lng="<?= $product->lng ?>" id="map"></div>
			</div>
		</div>
	</div> -->
	<div class="attr-detail">
		<div class="title-attr-listing">Liên hệ</div>
		<div class="infor-agent clearfix">
            <?php if(!empty($owner->username)) { ?>
			<a href="<?=\yii\helpers\Url::to(['member/profile','username' => $owner->username])?>" class="wrap-img">
                <img src="<?= $avatar ?>" alt="" /></a>
            <?php } else { ?>
                <a class="wrap-img"><img src="<?= $avatar ?>" alt="" /></a>
            <?php } ?>
            <a href="#" class="name-agent"><?= $product->adContactInfo->name ?></a>
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
			<div class="phone-agent">
				<div>
					<span class="icon"></span>
				</div>
				<a href="tel:<?= $product->adContactInfo->mobile ?>"><?= $product->adContactInfo->mobile ?></a>
			</div>
			<?php endif; ?>
			<?php if($product->adContactInfo->email): ?>
			<div class="email-agent">
				<div>
					<span class="icon"></span>
				</div>
				<?= $product->adContactInfo->email ?>
			</div>
			<?php endif; ?>
		</div>
	</div>
	<div class="attr-detail text-center">
		<?php if(!empty($owner->username)) { ?>
			<a href="<?=Url::to(['/chat/with', 'username'=>$owner->username])?>" class="contact-agent">Liên hệ Môi giới</a>
		<?php } ?>
	</div>
</div>