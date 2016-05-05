<?php 
	use yii\widgets\ActiveForm;
	use yii\helpers\Html;
use vsoft\ad\models\AdCategory;
use yii\helpers\ArrayHelper;
use vsoft\ad\models\AdCity;
use vsoft\ad\models\AdProduct;
use vsoft\ad\models\AdDistrict;
use vsoft\ad\models\AdWard;
use vsoft\ad\models\AdStreet;
use vsoft\ad\models\AdProductAdditionInfo;
use vsoft\ad\models\AdFacility;
use common\widgets\fileupload\FileUpload;
use yii\helpers\Url;

	/**
	 * @var $product vsoft\ad\models\AdProduct
	 * @var $this yii\web\View
	 * @var $additionInfo vsoft\ad\models\AdProductAdditionInfo
	 */

	$this->registerCss(".require-hint {color: red; margin-left: 4px;}");
			
	$categories = AdCategory::find()->orderBy('order')->all();
	$categoriesDropdown = ArrayHelper::map($categories, 'id', function($model){return ucfirst(Yii::t('ad', $model->name));});
	$categoriesOptions = ArrayHelper::map($categories, 'id', function($category){ return ['data-type' => $category->apply_to_type, 'data-limit' => $category->limit_area]; });
	
	$cities = AdCity::find()->all();
	$citiesDropdown = ArrayHelper::map($cities, 'id', 'name');
	$citiesOptions = ArrayHelper::map($cities, 'id', function($city){ return ['disabled' => ($city->id != AdProduct::DEFAULT_CITY)]; });
?>
<div class="title-fixed-wrap container">
	<div class="post-listing">
		<div class="title-top">Post Listing</div>
		<div class="wrap-frm-listing row">
			<div class="col-sm-8">
				<?php $form = ActiveForm::begin([
						'options' => [
							'class' => 'clearfix row'
						]
					]) ?>
					<div class="form-group col-xs-6">
						<label for="<?= Html::getInputId($product, 'type') ?>" class="fs-13 mgB-10"><?= $product->getAttributeLabel('type') ?><span class="require-hint">*</span></label>
						<?= Html::activeDropDownList($product, 'type', $product->getAdTypes(), ['class' => 'form-control']) ?>
					</div>
					<div class="form-group col-xs-6">
						<label for="<?= Html::getInputId($product, 'category_id') ?>" class="fs-13 mgB-10"><?= $product->getAttributeLabel('category_id') ?><span class="require-hint">*</span></label>
						<?= Html::activeDropDownList($product, 'category_id', $categoriesDropdown, ['options' => $categoriesOptions, 'class' => 'form-control', 'prompt' => "..."]) ?>
					</div>
					<div class="form-group col-xs-6">
						<label for="<?= Html::getInputId($product, 'city_id') ?>" class="fs-13 mgB-10"><?= $product->getAttributeLabel('city_id') ?><span class="require-hint">*</span></label>
						<?= Html::activeDropDownList($product, 'city_id', $citiesDropdown, ['class' => 'form-control', 'options' => $citiesOptions, 'prompt' => "..."]) ?>
					</div>
					<div class="form-group col-xs-6">
						<label for="" class="fs-13 mgB-10">Tên dự án <span class="color-cd pdL-15">+3 điểm</span></label>
						<input type="text" class="form-control" id="" placeholder="...">
					</div>
					<div class="form-group col-xs-6">
						<label for="<?= Html::getInputId($product, 'district_id') ?>" class="fs-13 mgB-10"><?= $product->getAttributeLabel('district_id') ?><span class="require-hint">*</span></label>
						<?= Html::activeDropDownList($product, 'district_id', ArrayHelper::map(AdDistrict::getListByCity($product->city_id), 'id', 'name'), ['class' => 'form-control', 'prompt' => "..."]) ?>
					</div>
					<div class="form-group col-xs-6">
						<label for="<?= Html::getInputId($product, 'ward_id') ?>" class="fs-13 mgB-10"><?= $product->getAttributeLabel('ward_id') ?><span class="require-hint">*</span></label>
						<?= Html::activeDropDownList($product, 'ward_id', ArrayHelper::map(AdWard::getListByDistrict($product->district_id), 'id', 'name'), ['class' => 'form-control', 'prompt' => "..."]) ?>
					</div>
					<div class="form-group col-xs-6">
						<label for="<?= Html::getInputId($product, 'street_id') ?>" class="fs-13 mgB-10"><?= $product->getAttributeLabel('street_id') ?><span class="require-hint">*</span></label>
						<?= Html::activeDropDownList($product, 'street_id', ArrayHelper::map(AdStreet::getListByDistrict($product->district_id), 'id', 'name'), ['class' => 'form-control', 'prompt' => "..."]) ?>
					</div>
					<div class="form-group col-xs-6 fild-address">
						<label for="<?= Html::getInputId($product, 'home_no') ?>" class="fs-13 mgB-10"><?= $product->getAttributeLabel('home_no') ?></label>
						<?= Html::activeTextInput($product, 'home_no', ['class' => 'form-control', 'placeholder' => '...']) ?>
						<label class="checkbox-inline fs-13 checkbox-ui">
							<?= Html::activeCheckbox($product, 'show_home_no', ['label' => false]) ?>
							<span class="icon-mv"><span class="icon-checkbox"></span></span> <?= $product->getAttributeLabel('show_home_no') ?>
						</label>
					</div>
					<div class="form-group col-xs-6">
						<label for="<?= Html::getInputId($product, 'area') ?>" class="fs-13 mgB-10"><?= $product->getAttributeLabel('area') ?></label>
						<?= Html::activeTextInput($product, 'area', ['class' => 'form-control', 'placeholder' => '...']) ?>
					</div>
					<div class="form-group col-xs-6 price-type">
						<label for="<?= Html::getInputId($product, 'price') ?>" class="fs-13 mgB-10"><?= $product->getAttributeLabel('price') ?></label>
						<?= Html::activeTextInput($product, 'price', ['class' => 'form-control text-right', 'placeholder' => '...']) ?>
						<div class="price-unit">
							<label class="radio-inline radio-ui">
								<input type="radio" name="price-unit" id="inlineRadio1" value="" checked="checked"> Triệu
							</label>
							<label class="radio-inline radio-ui">
								<input type="radio" name="price-unit" id="inlineRadio1" value=""> Tỷ
							</label>
						</div>
					</div>
					<div class="form-group col-xs-12">
						<label for="<?= Html::getInputId($product, 'content') ?>" class="fs-13 mgB-10"><?= $product->getAttributeLabel('content') ?></label>
						<?= Html::activeTextarea($product, 'content', ['class' => 'form-control', 'rows' => 5]) ?>
					</div>
					<div class="form-group col-xs-4">
						<label for="<?= Html::getInputId($additionInfo, 'room_no') ?>" class="fs-13 mgB-10"><?= $additionInfo->getAttributeLabel('room_no') ?></label>
						<?= Html::activeTextInput($additionInfo, 'room_no', ['class' => 'form-control', 'placeholder' => '...']) ?>
					</div>
					<div class="form-group col-xs-4">
						<label for="<?= Html::getInputId($additionInfo, 'toilet_no') ?>" class="fs-13 mgB-10"><?= $additionInfo->getAttributeLabel('toilet_no') ?></label>
						<?= Html::activeTextInput($additionInfo, 'toilet_no', ['class' => 'form-control', 'placeholder' => '...']) ?>
					</div>
					<div class="form-group col-xs-4">
						<label for="<?= Html::getInputId($additionInfo, 'floor_no') ?>" class="fs-13 mgB-10"><?= $additionInfo->getAttributeLabel('floor_no') ?></label>
						<?= Html::activeTextInput($additionInfo, 'floor_no', ['class' => 'form-control', 'placeholder' => '...']) ?>
					</div>
					<div class="form-group col-xs-4">
						<label for="<?= Html::getInputId($additionInfo, 'facade_width') ?>" class="fs-13 mgB-10"><?= $additionInfo->getAttributeLabel('facade_width') ?></label>
						<?= Html::activeTextInput($additionInfo, 'facade_width', ['class' => 'form-control', 'placeholder' => '...']) ?>
					</div>
					<div class="form-group col-xs-4">
						<label for="<?= Html::getInputId($additionInfo, 'land_width') ?>" class="fs-13 mgB-10"><?= $additionInfo->getAttributeLabel('land_width') ?></label>
						<?= Html::activeTextInput($additionInfo, 'land_width', ['class' => 'form-control', 'placeholder' => '...']) ?>
					</div>
					<div class="form-group col-xs-4">
						<label for="<?= Html::getInputId($additionInfo, 'home_direction') ?>" class="fs-13 mgB-10"><?= $additionInfo->getAttributeLabel('home_direction') ?></label>
						<?= Html::activeDropDownList($additionInfo, 'home_direction', AdProductAdditionInfo::directionList(), ['class' => 'form-control', 'prompt' => "..."]) ?>
					</div>
					<div class="form-group col-xs-4">
						<label for="<?= Html::getInputId($additionInfo, 'facade_direction') ?>" class="fs-13 mgB-10"><?= $additionInfo->getAttributeLabel('facade_direction') ?></label>
						<?= Html::activeDropDownList($additionInfo, 'facade_direction', AdProductAdditionInfo::directionList(), ['class' => 'form-control', 'prompt' => "..."]) ?>
					</div>
					<div class="form-group col-xs-12">
						<label for="<?= Html::getInputId($additionInfo, 'interior') ?>" class="fs-13 mgB-10"><?= $additionInfo->getAttributeLabel('interior') ?></label>
						<?= Html::activeTextarea($additionInfo, 'interior', ['class' => 'form-control', 'rows' => 2]) ?>
					</div>
					<div class="form-group col-xs-12 tienich-frm">
						<label for="" class="fs-13 mgB-10">Tiện ích <span class="color-cd pdL-15">+3 điểm</span></label>
						<?php
							$facilities = AdFacility::find()->all();
							$activeFacility = $additionInfo->facility ? $additionInfo->facility : [];
							$facilityName = $additionInfo->formName() . '[facility][]';
						?>
						<ul class="clearfix">
							<?php foreach ($facilities as $facility): ?>
							<li>
								<label class="checkbox-inline fs-13 checkbox-ui">
									<?= Html::checkbox($facilityName, in_array($facility->id, $activeFacility)) ?>
									<span class="icon-mv"><span class="icon-checkbox"></span></span> <?= Yii::t('ad', $facility->name) ?>
								</label>
							</li>
							<?php endforeach; ?>
						</ul>
					</div>
					<div class="form-group col-xs-12">
						<label for="" class="fs-13 mgB-10">Tải hình ảnh dư án <span class="color-cd pdL-15">+3 điểm</span></label>
						<div class="upload-img">
							<span class="icon-mv">
								<span class="icon-file-image-o"></span><br>Tải hình ảnh
							</span>
							<?php
								$files = [];
								foreach ($product->adImages as $image) {
									$files[] = [
										'deleteType' => "DELETE",
										'deleteUrl'	=> Url::to(['/ad/delete-file', 'file' => $image->file_name]),
										'thumbnailUrl' => $image->getUrl(AdImages::SIZE_THUMB),
										'name' => $image->file_name,
										'url' => $image->getUrl(AdImages::SIZE_LARGE)
									];
								}
							?>
							<?= FileUpload::widget([
									'files' => $files,
									'name' => 'images', 
									'url' => Url::to(['upload']),
									'clientOptions' => [
										'previewMinWidth' => 130,
										'previewMinHeight' => 98,
										'previewMaxWidth' => 130,
										'previewMaxHeight' => 98,
										'previewCrop' => true,
									]
								]) ?>
						</div>
					</div>
					<div class="form-group col-xs-12 mgT-30">
						<label for="" class="fs-16 mgB-10 text-uper">Liên hệ</label>
						<div class="clearfix agent-postlisting">
							<div class="fs-13 row">
								<div class="form-group col-xs-6">
									<label for="" class="fs-13 mgB-10">Tên người đăng tin</label>
									<input type="text" class="form-control" id="" placeholder="">
								</div>
								<div class="form-group col-xs-6">
									<label for="" class="fs-13 mgB-10">Điện thoại</label>
									<input type="text" class="form-control" id="" placeholder="">
								</div>
								<div class="form-group col-xs-6">
									<label for="" class="fs-13 mgB-10">Email</label>
									<input type="email" class="form-control" id="" placeholder="">
								</div>
								<div class="form-group col-xs-6">
									<label for="" class="fs-13 mgB-10">Bạn là</label>
									<div>
										<label class="radio-inline radio-ui">
											<input type="radio" name="agent-type" id="inlineRadio1" value="option1" checked="checked"> Chủ nhà
										</label>
										<label class="radio-inline radio-ui">
											<input type="radio" name="agent-type" id="inlineRadio1" value="option1"> Môi giới
										</label>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="text-right col-xs-12 pdT-50">
						<button class="btn-common">Preview <span class="icon-mv"><span class="icon-angle-right"></span></span></button>
					</div>
				<?php $form->end() ?>
			</div>
			<div class="col-sm-4 checkpoint-listing">
				<div class="inner-checkpoint">
					<div class="title-point">Metvuong chấm điểm</div>
					<div class="point-select">
						<div class="text-center total-point">
							<span>30/100</span>điểm
							<div class="proccess-point">
								<div style="width: 50%"></div>
							</div>
						</div>
						<ul>
							<li><span class="icon-mv"><span class="selected-point icon-checked"></span></span>Tên dự án</li>
							<li><span class="icon-mv"><span class="selected-point icon-checked"></span></span>Địa chỉ</li>
							<li><span class="icon-mv"><span class="selected-point icon-checked"></span></span>Diện tích</li>
							<li><span class="icon-mv"><span class="selected-point icon-checked"></span></span>Giá</li>
							<li><span class="icon-mv"><span class="icon-checked"></span></span>Tiện ích</li>
							<li><span class="icon-mv"><span class="icon-checked"></span></span>Mô tả chi tiết</li>
							<li><span class="icon-mv"><span class="icon-checked"></span></span>Mô tả khu vực</li>
							<li><span class="icon-mv"><span class="icon-checked"></span></span>Hình ảnh</li>
							<li><span class="icon-mv"><span class="icon-checked"></span></span>Thông tin liên hệ</li>
						</ul>
						
					</div>
					<div class="btom-point">
						* Điểm số cụ thể sẽ được quyết định sau khi ban điều hành kiểm tra thông tin chi tiết dự án.
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function () {
		$('.radio-ui').radio();
		$('.checkbox-ui').checkbox_ui();
	});
</script>