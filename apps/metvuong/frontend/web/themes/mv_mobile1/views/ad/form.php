<?php 
	use yii\widgets\ActiveForm;
	use yii\helpers\Html;
	use vsoft\ad\models\AdProduct;
use yii\web\View;

	$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/post-listing.js', ['position' => View::POS_END]);
?>
<div class="title-top">Đăng tin</div>
<div class="post-listing">
	<?php
		$form = ActiveForm::begin ( [ 
			'id' => 'listing-form',
			'options' => [ 
				'autocomplete' => 'off',
				'spellcheck' => 'false'
			]
		]);
	?>
		<div class="step-link">
			<ul class="clearfix">
				<li><a data-active-section="tt-chung" class="active" href="#">1</a><span class="icon arrowLeft"></span></li>
				<li><a data-active-section="tt-chitiet" href="#">2</a><span class="icon arrowLeft"></span></li>
				<li><a data-active-section="hinh-anh" href="#">3</a><span class="icon arrowLeft"></span></li>
				<li><a data-active-section="tien-ich" href="#">4</a><span class="icon arrowLeft"></span></li>
			</ul>
		</div>
		<div class="section select-type hide item-step">
			<p class="text-center step-txt">4 bước dễ dàng</p>
		
			<ul class="clearfix step-check">
				<li>
					<a class="frm-radio" href="#">
						<span class="radio-ui icon">
							<?php $typeName = Html::getInputName($product, 'type') ?>
							<?= Html::radio($typeName, ($product->type == AdProduct::TYPE_FOR_SELL || !$product->type), ['value' => AdProduct::TYPE_FOR_SELL]) ?>
						</span>
						<span><?= Yii::t('ad', 'Bán') ?></span>
					</a>
				</li>
				<li>
					<a class="frm-radio" href="#">
						<span class="radio-ui icon">
							<?= Html::radio($typeName, ($product->type == AdProduct::TYPE_FOR_RENT), ['value' => AdProduct::TYPE_FOR_RENT]) ?>
						</span>
						<span><?= Yii::t('ad', 'Cho thuê') ?></span>
					</a>
				</li>
				<li>
					<a class="frm-radio" href="#">
						<?php $ownerName = Html::getInputName($product, 'owner') ?>
						<span class="radio-ui icon">
							<?= Html::radio($ownerName, ($product->owner == AdProduct::OWNER_HOST || !$product->owner), ['value' => AdProduct::OWNER_HOST]) ?>
						</span>
						<span><?= Yii::t('ad', 'Chủ nhà') ?></span>
					</a>
				</li>
				<li>
					<a class="frm-radio" href="#">
						<span class="radio-ui icon">
							<?= Html::radio($ownerName, ($product->owner == AdProduct::OWNER_AGENT), ['value' => AdProduct::OWNER_AGENT]) ?>
						</span>
						<span><?= Yii::t('ad', 'Môi giới') ?></span>
					</a>
				</li>
			</ul>
			<?php $product->category_id = 6 ?>
			<?= $form->field($product, 'category_id')
					->label(false)
					->dropDownList([], ['prompt' => $product->getAttributeLabel('category_id'), 'data-default' => $product->category_id]) ?>
		</div>

		<div class="tt-chung item-step section hide">
			<div class="title-step">Thông tin chung</div>
			<div class="row">
				<div class="col-xs-6 form-group">
					<select class="form-control" name="" id="">
						<option value="" disabled="" selected="">Tỉnh / Thành</option>
						<option value="">Hồ Chí Minh</option>
						<option value="">Hà Nội</option>
						<option value="">Đồng Nai</option>
					</select>
				</div>
				<div class="col-xs-6 form-group">
					<select class="form-control" name="" id="">
						<option value="" disabled="" selected="">Quận / Huyện</option>
						<option value="">Hồ Chí Minh</option>
						<option value="">Hà Nội</option>
						<option value="">Đồng Nai</option>
					</select>
				</div>
				<div class="col-xs-12 form-group">
					<input type="text" class="form-control" id="" placeholder="Đường">
				</div>
				<?= $form->field($product, 'home_no', ['options' => ['class' => 'col-xs-12 form-group']])
						->label(false)
						->textInput(['placeholder' => $product->getAttributeLabel('home_no')]) ?>
				<div class="col-xs-12 form-group">
					<select class="form-control" name="" id="">
						<option value="" disabled="" selected="">Phường</option>
						<option value="">Hồ Chí Minh</option>
						<option value="">Hà Nội</option>
						<option value="">Đồng Nai</option>
					</select>
				</div>
				<div class="col-xs-12 form-group txt-unit">
					<input type="text" class="form-control" id="" placeholder="Số nhà">
					<span>m2</span>
				</div>
				<div class="col-xs-12 form-group txt-unit">
					<input type="text" class="form-control" id="" placeholder="Giá">
					<span>VNĐ</span>
				</div>
				<div class="col-xs-6 form-group">
					<select class="form-control" name="" id="">
						<option value="" disabled="" selected="">Phòng ngủ</option>
						<option value="">Hồ Chí Minh</option>
						<option value="">Hà Nội</option>
						<option value="">Đồng Nai</option>
					</select>
				</div>
				<div class="col-xs-6 form-group">
					<select class="form-control" name="" id="">
						<option value="" disabled="" selected="">Phòng tắm</option>
						<option value="">Hồ Chí Minh</option>
						<option value="">Hà Nội</option>
						<option value="">Đồng Nai</option>
					</select>
				</div>
			</div>
		</div>

		<div class="tt-chitiet item-step section hide">
			<div class="title-step">Thông tin chi tiết</div>
			<div class="row">
				<div class="col-xs-12 form-group">
					<input type="text" class="form-control" id="" placeholder="Mặt tiền (m)">
				</div>
				<div class="col-xs-12 form-group">
					<input type="text" class="form-control" id="" placeholder="Đường vào (m)">
				</div>
				<div class="col-xs-6 form-group">
					<select class="form-control" name="" id="">
						<option value="" disabled="" selected="">Tầng cao</option>
						<option value="">Hồ Chí Minh</option>
						<option value="">Hà Nội</option>
						<option value="">Đồng Nai</option>
					</select>
				</div>
				<div class="col-xs-6 form-group">
					<select class="form-control" name="" id="">
						<option value="" disabled="" selected="">Thang máy</option>
						<option value="">Hồ Chí Minh</option>
						<option value="">Hà Nội</option>
						<option value="">Đồng Nai</option>
					</select>
				</div>
				<div class="col-xs-12 form-group">
					<input type="text" class="form-control" id="" placeholder="Chủ đầu tư">
				</div>
				<div class="col-xs-12 form-group">
					<input type="text" class="form-control" id="" placeholder="Nhà thầu xây dựng">
				</div>
				<div class="col-xs-6 form-group">
					<select class="form-control" name="" id="">
						<option value="" disabled="" selected="">Ngày khởi công</option>
						<option value="">Hồ Chí Minh</option>
						<option value="">Hà Nội</option>
						<option value="">Đồng Nai</option>
					</select>
				</div>
				<div class="col-xs-6 form-group">
					<select class="form-control" name="" id="">
						<option value="" disabled="" selected="">Ngày hoàn thiện</option>
						<option value="">Hồ Chí Minh</option>
						<option value="">Hà Nội</option>
						<option value="">Đồng Nai</option>
					</select>
				</div>
				<div class="col-xs-12 form-group txt-unit">
					<input type="text" class="form-control" id="" placeholder="Phí đậu xe gắn máy">
					<span>VNĐ</span>
				</div>
				<div class="col-xs-12 form-group txt-unit">
					<input type="text" class="form-control" id="" placeholder="Phí đậu xe hơi">
					<span>VNĐ</span>
				</div>
				<div class="col-xs-6 form-group">
					<select class="form-control" name="" id="">
						<option value="" disabled="" selected="">Phòng ngủ</option>
						<option value="">Hồ Chí Minh</option>
						<option value="">Hà Nội</option>
						<option value="">Đồng Nai</option>
					</select>
				</div>
				<div class="col-xs-6 form-group">
					<select class="form-control" name="" id="">
						<option value="" disabled="" selected="">Phòng tắm</option>
						<option value="">Hồ Chí Minh</option>
						<option value="">Hà Nội</option>
						<option value="">Đồng Nai</option>
					</select>
				</div>
				<div class="col-xs-12 form-group">
					<textarea class="form-control" rows="3" placeholder="Chính sách ưu đãi (dưới 200 chữ)"></textarea>
				</div>
				<div class="col-xs-12 form-group">
					<textarea class="form-control" rows="3" placeholder="Mô tả dự án (dưới 200 chữ)"></textarea>
				</div>
			</div>
			<div class="tien-ich item-step hide">
				<div class="title-step">Tiện ích</div>
				<ul class="clearfix list-tienich">
					<li>
						<a href="#"><span class="icon"></span><span>24/7 Security</span></a>
					</li>
					<li>
						<a href="#"><span class="icon"></span><span>Mailbox</span></a>
					</li>
					<li>
						<a href="#"><span class="icon"></span><span>24/7 Security</span></a>
					</li>
					<li>
						<a href="#"><span class="icon"></span><span>Swimming Pool</span></a>
					</li>
					<li>
						<a href="#"><span class="icon"></span><span>Gym</span></a>
					</li>
					<li>
						<a href="#"><span class="icon"></span><span>BBQ Area</span></a>
					</li>
				</ul>
			</div>
		</div>

		<div class="hinh-anh item-step section hide">
			<div class="title-step">Hình ảnh</div>
			
		</div>

		<div class="tt-lienhe item-step section hide">
			<div class="title-step">Thông tin liên hệ</div>
			<div class="row">
				<div class="col-xs-12 form-group">
					<input type="text" class="form-control" id="" placeholder="Họ / Tên">
				</div>
				<div class="col-xs-12 form-group">
					<input type="text" class="form-control" id="" placeholder="Điện thoại di động">
				</div>
				<div class="col-xs-12 form-group">
					<input type="text" class="form-control" id="" placeholder="E-mail">
				</div>
				<div class="col-xs-12 form-group">
					<input type="text" class="form-control" id="" placeholder="Công ty môi giới">
				</div>
			</div>
			<div class="agent-avatar">
				<div class="wrap-img"><img src="images/MV-Agent Photo.jpg" alt="" /></div>
				<button class="upload-avatar">Tải hình đại diện khác</button>
			</div>
			<div class="text-center">
				<button class="preview">Preview</button>
				<button class="btn-post">Đăng tin</button>
			</div>
		</div>
	<?php $form->end() ?>
</div>

<div class="fixed-prev-next">
	<a href="#" id="back-screen"><span class="icon arrowRight-1"></span>Trở lại</a>
	<a href="#" id="next-screen" class=""><span class="icon arrowLeft-1"></span>Tiếp theo</a>
</div>