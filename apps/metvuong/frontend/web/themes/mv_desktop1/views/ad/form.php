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
use yii\web\View;
use vsoft\ad\models\AdImages;

	/**
	 * @var $product vsoft\ad\models\AdProduct
	 * @var $this yii\web\View
	 * @var $additionInfo vsoft\ad\models\AdProductAdditionInfo
	 */

// $product->category_id = AdCategory::CATEGORY_CHCK;
// $product->project_building_id = 1491;
// $product->city_id = 1;
// $product->district_id = 19;
// $product->ward_id = 253;
// $product->street_id = 2755;

	$this->registerCssFile(Yii::$app->view->theme->baseUrl . '/resources/css/select2.min.css');
	$this->registerCssFile(Yii::$app->view->theme->baseUrl . '/resources/css/swiper.min.css');
	$this->registerCssFile(Yii::$app->view->theme->baseUrl . '/resources/css/post-listing.css');
	$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyASTv_J_7DuXskr5SaCZ_7RVEw7oBKiHi4', ['depends' => ['yii\web\YiiAsset'], 'async' => true, 'defer' => true]);
	$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/swiper.jquery.min.js', ['position' => View::POS_END]);
	$this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/jquery-ui.min.js', ['position' => View::POS_END]);
	$this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/select2.full.min.js', ['position' => View::POS_END]);
	$this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/post-listing.js', ['position' => View::POS_END]);
	
	$cities = AdCity::find()->all();
	$citiesDropdown = ArrayHelper::map($cities, 'id', 'name');
	$citiesOptions = ArrayHelper::map($cities, 'id', function($city){ return ['disabled' => ($city->id != AdProduct::DEFAULT_CITY)]; });
	
	$wards = AdWard::getListByDistrict($product->district_id);
	$wardsDropdown = ArrayHelper::map($wards, 'id', 'name');
	$wardsOptions = ArrayHelper::map($wards, 'id', function($ward){ return ['data-pre' => $ward['pre']]; });
	
	$streets = AdStreet::getListByDistrict($product->district_id);
	$streetsDropdown = ArrayHelper::map($streets, 'id', 'name');
	$streetsOptions = ArrayHelper::map($streets, 'id', function($street){ return ['data-pre' => $street['pre']]; });
	
	$districtDropdown = ArrayHelper::map(AdDistrict::getListByCity($product->city_id), 'id', 'name');
	
	$categories = AdCategory::find()->orderBy('order')->all();
	$categoriesDropDown = [];
	$categoriesDropDownOptions = [];
	$categoriesJs = [];
	
	foreach ($categories as $category) {
		if(adCategory::APPLY_TO_TYPE_BOTH == $category->apply_to_type || $category->apply_to_type == $product->type) {
			$categoriesDropDown[$category->id] = ucfirst(Yii::t('ad', $category->name));
			$categoriesDropDownOptions[$category->id] = ['data-limit' => $category->limit_area];
		}
		$categoriesJs[] = [
			'id' => $category->id,
			'name' => ucfirst(Yii::t('ad', $category->name)),
			'apply_to_type' => $category->apply_to_type,
			'limit_area' => $category->limit_area,
		];
	}
	
	$this->registerJs("var totalImage = " . count($product->adImages) . "; var landTypeApplyFields = " . json_encode(AdCategory::$landTypeApplyFields) . "; var landType = " . json_encode(AdCategory::$landType) . "; var isNewRecord = " . ($product->isNewRecord ? 'true' : 'false') . "; var categories = " . json_encode($categoriesJs) . "; var APPLY_TO_TYPE_BOTH = " . AdCategory::APPLY_TO_TYPE_BOTH . "; var CHCK = " . AdCategory::CATEGORY_CHCK . ";", View::POS_HEAD);

	$disabledCity = ($product->projectBuilding && $product->projectBuilding->city_id) || $expiredEdit;
	$disabledDistrict = ($product->projectBuilding && $product->projectBuilding->district_id) || $expiredEdit;
	$disabledWard = ($product->projectBuilding && $product->projectBuilding->ward_id) || $expiredEdit;
	$disabledStreet = ($product->projectBuilding && $product->projectBuilding->street_id) || $expiredEdit;
	$disabledHome = ($product->projectBuilding && $product->projectBuilding->home_no) || $expiredEdit;
?>
<div class="title-fixed-wrap container">
	<div class="post-listing">
		<div class="title-top"><?= Yii::t('ad', 'Post Listing') ?></div>
		<div id="notify" class="hide"></div>
		<div class="wrap-frm-listing row">
			<div class="col-sm-8">
				<?php if(!isset($_COOKIE['cpl'])): ?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<a href="#" class="font-700 fs-14 color-cd-hover close-alert"><span class="icon-mv fs-12"><span class="icon-close-icon"></span></span></a>
					<h2><?= Yii::t('ad', 'METVUONG khuyến khích các tin rao chất lượng') ?></h2>
					<p><?= Yii::t('ad', 'Quý vị nhập thông tin chi tiết càng nhiều, thì tin rao này sẽ hiển thị lên kết quả tìm kiếm nhiều hơn và sẽ có nhiều khách hàng liên lạc hơn.') ?></p>
				</div>
				<?php endif; ?>
				<?php $form = ActiveForm::begin([
						'options' => [
							'class' => 'clearfix',
							'autocomplete' => 'off',
							'data-auto-save' => Url::to(['/ad/auto-save'])
						]
					]) ?>
					<div class="group-frm">
						<div class="title-frm"><?= Yii::t('ad', 'Loại tin') ?> <span class="pdL-10"><?= sprintf(Yii::t('ad', '%s điểm'), '+<span class="max-point">8</span>') ?></span></div>
						<div class="row">
							<?php if($expiredEdit): ?>
							<div class="expired-edit"><?= Yii::t("ad", "Không thể cập nhật các mục dưới đây khi tin đã được đăng hơn 2 ngày") ?></div>
							<?php endif; ?>
							<div class="form-group col-xs-12 col-sm-6">
								<label for="<?= Html::getInputId($product, 'type') ?>" class="fs-13 mgB-5"><?= $product->getAttributeLabel('type') ?><span class="require-hint">*</span><span class="hint"><?= sprintf(Yii::t('ad', '%s điểm'), '+<span class="point">3</span>') ?></span></label>
								<?= Html::activeDropDownList($product, 'type', $product->getAdTypes(), ['class' => 'form-control', 'disabled' => $expiredEdit]) ?>
							</div>
							<div class="form-group col-xs-12 col-sm-6">
								<?php $catP = ($product->category_id == AdCategory::CATEGORY_CHCK) ? 2 : 5 ?>
								<label for="<?= Html::getInputId($product, 'category_id') ?>" class="fs-13 mgB-5"><span class="label-attr"><?= $product->getAttributeLabel('category_id') ?></span><span class="require-hint">*</span><span class="hint"><?= sprintf(Yii::t('ad', '%s điểm'), '+<span class="point">' . $catP . '</span>') ?></span></label>
								<?= Html::activeDropDownList($product, 'category_id', $categoriesDropDown, ['options' => $categoriesDropDownOptions, 'class' => 'form-control', 'prompt' => "...", 'disabled' => $expiredEdit]) ?>
								<div class="help-block"></div>
							</div>
							<?php
								$projectMaskAttr = '';
								$projectMaskVal = '';
								$projectWrapClass = '';
								
								if($product->projectBuilding) {
									$projectMaskAttr = 'readonly="readonly" data-url="' . Url::to(['building-project/view', 'slug' => $product->projectBuilding->slug]) . '" ';
									$projectMaskVal = $product->projectBuilding->name . ', ' . $districtDropdown[$product->projectBuilding->district_id] . ', ' . $citiesDropdown[$product->projectBuilding->city_id];
									$projectWrapClass = ' class="show-project"';
								}
							?>
							<div class="form-group col-xs-12"<?= $product->category_id == AdCategory::CATEGORY_CHCK ? '' : ' style="display: none;"' ?>>
								<label for="" class="fs-13 mgB-5"><span class="label-attr"><?= $product->getAttributeLabel('project_building_id') ?></span><span class="hint"><?= sprintf(Yii::t('ad', '%s điểm'), '+<span class="point">3</span>') ?></span></label>
								<?= Html::activeHiddenInput($product, 'project_building_id') ?>
								<div id="project-wrap"<?= $projectWrapClass ?>>
									<input <?= $projectMaskAttr ?>type="text" class="form-control" id="projectMask" placeholder="..." value="<?= $projectMaskVal ?>" />
									<?php if(!$expiredEdit): ?>
									<span class="icon-mv"><span class="icon-close-icon"></span></span>
									<?php endif; ?>
									<div id="search-list" class="hide">
										<span class="search-hint">Nhập tên dự án và chọn từ gợi ý dưới đây</span>
										<ul></ul>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="group-frm">
						<div class="title-frm"><?= Yii::t('ad', 'Địa chỉ') ?> <span class="pdL-10"><?= sprintf(Yii::t('ad', '%s điểm'), '+<span class="max-point">12</span>') ?></span></div>
						<div class="row">
							<?php if($expiredEdit): ?>
							<div class="expired-edit"><?= Yii::t("ad", "Không thể cập nhật các mục dưới đây khi tin đã được đăng hơn 2 ngày") ?></div>
							<?php endif; ?>
							<div class="form-group col-xs-12 col-sm-6">
								<label for="<?= Html::getInputId($product, 'city_id') ?>" class="fs-13 mgB-5"><span class="label-attr"><?= $product->getAttributeLabel('city_id') ?></span><span class="require-hint">*</span><span class="hint"><?= sprintf(Yii::t('ad', '%s điểm'), '+<span class="point">2</span>') ?></span></label>
								<?= Html::activeDropDownList($product, 'city_id', $citiesDropdown, ['class' => 'form-control search', 'options' => $citiesOptions, 'prompt' => "...", 'disabled' => $disabledCity]) ?>
								<div class="help-block"></div>
							</div>
							<div class="form-group col-xs-12 col-sm-6"<?= $product->city_id ? '' : ' style="display: none;"' ?>>
								<label for="<?= Html::getInputId($product, 'district_id') ?>" class="fs-13 mgB-5"><span class="label-attr"><?= $product->getAttributeLabel('district_id') ?></span><span class="require-hint">*</span><span class="hint"><?= sprintf(Yii::t('ad', '%s điểm'), '+<span class="point">2</span>') ?></span></label>
								<?= Html::activeDropDownList($product, 'district_id', $districtDropdown, ['class' => 'form-control search', 'prompt' => "...", 'data-no-results' => Yii::t('ad', 'Choose City to show Districts'), 'disabled' => $disabledDistrict]) ?>
								<div class="help-block"></div>
							</div>
							<div id="address-detail-wrap"<?= $product->district_id ? '' : ' style="display: none;"' ?>>
								<div class="form-group col-xs-12 col-sm-6">
									<label for="<?= Html::getInputId($product, 'ward_id') ?>" class="fs-13 mgB-5"><span class="label-attr"><?= $product->getAttributeLabel('ward_id') ?></span><span class="require-hint">*</span><span class="hint"><?= sprintf(Yii::t('ad', '%s điểm'), '+<span class="point">2</span>') ?></span></label>
									<?= Html::activeDropDownList($product, 'ward_id', $wardsDropdown, ['options' => $wardsOptions, 'class' => 'form-control search', 'prompt' => "...", 'data-no-results' => Yii::t('ad', 'Choose District to show Wards'), 'disabled' => $disabledWard]) ?>
									<div class="help-block"></div>
								</div>
								<div class="form-group col-xs-12 col-sm-6">
									<label for="<?= Html::getInputId($product, 'street_id') ?>" class="fs-13 mgB-5"><span class="label-attr"><?= $product->getAttributeLabel('street_id') ?></span><span class="require-hint">*</span><span class="hint"><?= sprintf(Yii::t('ad', '%s điểm'), '+<span class="point">2</span>') ?></span></label>
									<?= Html::activeDropDownList($product, 'street_id', $streetsDropdown, ['options' => $streetsOptions, 'class' => 'form-control search', 'prompt' => "...", 'data-no-results' => Yii::t('ad', 'Choose District to show Streets'), 'disabled' => $disabledStreet]) ?>
									<div class="help-block"></div>
								</div>
								<div class="form-group col-xs-12 col-sm-6 fild-address"<?= in_array($product->category_id, AdCategory::$landType) ? ' style="display: none;"' : '' ?>>
									<label for="<?= Html::getInputId($product, 'home_no') ?>" class="fs-13 mgB-5"><?= $product->getAttributeLabel('home_no') ?><span class="hint"><?= sprintf(Yii::t('ad', '%s điểm'), '+<span class="point">4</span>') ?></span></label>
									<?= Html::activeTextInput($product, 'home_no', ['class' => 'form-control', 'placeholder' => '...', 'disabled' => $disabledHome]) ?>
									<div class="help-block"></div>
									<label class="checkbox-inline fs-13 checkbox-ui">
										<?= Html::activeCheckbox($product, 'show_home_no', ['label' => false, 'disabled' => $disabledHome ]) ?>
										<span class="icon-mv"><span class="icon-checkbox"></span></span> <?= $product->getAttributeLabel('show_home_no') ?>
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class="group-frm">
						<div class="title-frm"><?= Yii::t('ad', 'General Information') ?> <span class="pdL-10"><?= sprintf(Yii::t('ad', '%s điểm'), '+<span class="max-point">35</span>') ?></span></div>
						<div class="row">
							<div class="form-group col-xs-12 col-sm-6">
								<label for="<?= Html::getInputId($product, 'area') ?>" class="fs-13 mgB-5"><span class="label-attr"><?= $product->getAttributeLabel('area') ?></span> (m2)<span class="require-hint">*</span><span class="hint"><?= sprintf(Yii::t('ad', '%s điểm'), '+<span class="point">5</span>') ?></span></label>
								<?= Html::activeTextInput($product, 'area', ['class' => 'form-control number-only number-float', 'placeholder' => '...']) ?>
								<div class="help-block"></div>
							</div>
							<div class="form-group col-xs-12 col-sm-6 price-type">
								<label for="<?= Html::getInputId($product, 'price') ?>" class="fs-13 mgB-5"><span class="label-attr"><?= $product->getAttributeLabel('price') ?></span> (<?= Yii::t('ad', 'VND') ?>)<span class="require-hint">*</span><span class="hint"><?= sprintf(Yii::t('ad', '%s điểm'), '+<span class="point">5</span>') ?></span></label><span class="price-show-wrap"><span id="price-show"></span></span>
								<?= Html::activeHiddenInput($product, 'price', ['class' => 'form-control text-right', 'placeholder' => '...']) ?>
								<div style="position: relative;">
									<input type="text" id="priceMask" class="form-control text-right number-only number-float" placeholder="...">
									<span class="small-text"><?= Yii::t("ad", "VD: 1,5 triệu hoặc 1,5 tỷ") ?></span>
									<div class="price-unit">
										<label class="radio-inline radio-ui">
											<input type="radio" name="price-unit" id="price-unit-mil" value="1000000" checked="checked"> <?= Yii::t('ad', 'million') ?>
										</label>
										<label class="radio-inline radio-ui">
											<input type="radio" name="price-unit" id="price-unit-bil" value="1000000000"> <?= Yii::t('ad', 'billion') ?>
										</label>
									</div>
								</div>
								<div class="help-block"></div>
							</div>
							<div class="form-group col-xs-12 col-sm-6">
								<label for="<?= Html::getInputId($additionInfo, 'room_no') ?>" class="fs-13 mgB-5"><?= $additionInfo->getAttributeLabel('room_no') ?><span class="hint"><?= sprintf(Yii::t('ad', '%s điểm'), '+<span class="point">5</span>') ?></span></label>
								<?= Html::activeTextInput($additionInfo, 'room_no', ['class' => 'form-control number-only', 'placeholder' => '...']) ?>
								<div class="help-block"></div>
							</div>
							<div class="form-group col-xs-12 col-sm-6">
								<label for="<?= Html::getInputId($additionInfo, 'toilet_no') ?>" class="fs-13 mgB-5"><?= $additionInfo->getAttributeLabel('toilet_no') ?><span class="hint"><?= sprintf(Yii::t('ad', '%s điểm'), '+<span class="point">5</span>') ?></span></label>
								<?= Html::activeTextInput($additionInfo, 'toilet_no', ['class' => 'form-control number-only', 'placeholder' => '...']) ?>
								<div class="help-block"></div>
							</div>
							<div class="form-group col-xs-12">
								<label for="<?= Html::getInputId($product, 'content') ?>" class="fs-13 mgB-5"><span class="label-attr"><?= $product->getAttributeLabel('content') ?></span><span class="require-hint">*</span><span class="hint"><?= Yii::t('ad', '10 từ(+5), 20 từ(+10), 30 từ(+15)') ?></span></label>
								<?= Html::activeTextarea($product, 'content', ['class' => 'form-control', 'rows' => 5]) ?>
								<div class="help-block"></div>
							</div>
						</div>
					</div>
					<div class="group-frm">
						<div class="title-frm"><?= Yii::t('ad', 'Detail Information') ?> <span class="pdL-10"><?= sprintf(Yii::t('ad', '%s điểm'), '+<span class="max-point">15</span>') ?></span></div>
						<div class="row">
							<div class="form-group col-xs-12 col-sm-4">
								<label for="<?= Html::getInputId($additionInfo, 'floor_no') ?>" class="fs-13 mgB-5"><?= $additionInfo->getAttributeLabel('floor_no') ?><span class="hint"><?= sprintf(Yii::t('ad', '%s điểm'), '+<span class="point">2</span>') ?></span></label>
								<?= Html::activeTextInput($additionInfo, 'floor_no', ['class' => 'form-control number-only', 'placeholder' => '...']) ?>
								<div class="help-block"></div>
							</div>
							<div class="form-group col-xs-12 col-sm-4">
								<label for="<?= Html::getInputId($additionInfo, 'facade_width') ?>" class="fs-13 mgB-5"><?= $additionInfo->getAttributeLabel('facade_width') ?> (m)<span class="hint"><?= sprintf(Yii::t('ad', '%s điểm'), '+<span class="point">2</span>') ?></span></label>
								<?= Html::activeTextInput($additionInfo, 'facade_width', ['class' => 'form-control number-only number-float', 'placeholder' => '...']) ?>
								<div class="help-block"></div>
							</div>
							<div class="form-group col-xs-12 col-sm-4">
								<label for="<?= Html::getInputId($additionInfo, 'land_width') ?>" class="fs-13 mgB-5"><?= $additionInfo->getAttributeLabel('land_width') ?> (m)<span class="hint"><?= sprintf(Yii::t('ad', '%s điểm'), '+<span class="point">2</span>') ?></span></label>
								<?= Html::activeTextInput($additionInfo, 'land_width', ['class' => 'form-control number-only number-float', 'placeholder' => '...']) ?>
								<div class="help-block"></div>
							</div>
							<div class="form-group col-xs-12 col-sm-4">
								<label for="<?= Html::getInputId($additionInfo, 'home_direction') ?>" class="fs-13 mgB-5"><?= $additionInfo->getAttributeLabel('home_direction') ?><span class="hint"><?= sprintf(Yii::t('ad', '%s điểm'), '+<span class="point">2</span>') ?></span></label>
								<?= Html::activeDropDownList($additionInfo, 'home_direction', AdProductAdditionInfo::directionList(), ['class' => 'form-control', 'prompt' => "..."]) ?>
								<div class="help-block"></div>
							</div>
							<div class="form-group col-xs-12 col-sm-4">
								<label for="<?= Html::getInputId($additionInfo, 'facade_direction') ?>" class="fs-13 mgB-5"><?= $additionInfo->getAttributeLabel('facade_direction') ?><span class="hint"><?= sprintf(Yii::t('ad', '%s điểm'), '+<span class="point">2</span>') ?></span></label>
								<?= Html::activeDropDownList($additionInfo, 'facade_direction', AdProductAdditionInfo::directionList(), ['class' => 'form-control', 'prompt' => "..."]) ?>
								<div class="help-block"></div>
							</div>
							<div class="form-group col-xs-12 tienich-frm">
								<label for="" class="fs-13 mgB-5"><?= Yii::t('ad', 'Facilities') ?> / <?= $additionInfo->getAttributeLabel('interior') ?><span class="hint"><?= sprintf(Yii::t('ad', '%s điểm'), '+<span class="point">1-5</span>') ?></span></label>
								<?php
									$facilities = AdFacility::find()->all();
									$activeFacility = $additionInfo->facility ? $additionInfo->facility : [];
									$facilityName = $additionInfo->formName() . '[facility][]';
								?>
								<ul class="clearfix">
									<?php foreach ($facilities as $facility): ?>
									<li>
										<label class="checkbox-inline fs-13 checkbox-ui">
											<?= Html::checkbox($facilityName, in_array($facility->id, $activeFacility), ['value' => $facility->id]) ?>
											<span class="icon-mv"><span class="icon-checkbox"></span></span> <?= Yii::t('ad', $facility->name) ?>
										</label>
									</li>
									<?php endforeach; ?>
								</ul>
							</div>
						</div>
					</div>
					<div class="group-frm clearfix">
						<div class="title-frm"><?= Yii::t('ad', 'Hình ảnh') ?> <span class="pdL-10"><?= sprintf(Yii::t('ad', '%s điểm'), '+<span class="max-point">10</span>') ?></span></div>
						<label for="" class="fs-13 mgB-5"><?= Yii::t('ad', 'Tải hình ảnh') ?><span class="hint"><?= Yii::t('ad', '1-2 ảnh(+5), 3 ảnh trở lên(+10)') ?></span></label>
						<div class="upload-img-listing">
							<span id="upload-hint"><?= Yii::t('ad', 'Change the position of images by dragging the image into the right position you want!') ?></span>
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
									],
									'clientEvents' => [
										'fileuploadcompleted' => 'function(e, data) {form.fileuploadcompleted(e, data, this);}',
										'fileuploaddestroyed' => 'function(e, data) {form.fileuploaddestroyed(e, data, this);}',
									]
								]) ?>
						</div>
					</div>
					<div class="group-frm">
						<div class="title-frm"><?= Yii::t('ad', 'Contact Information') ?> <span class="pdL-10"><?= sprintf(Yii::t('ad', '%s điểm'), '+<span class="max-point">20</span>') ?></span></div>
						<div class="clearfix agent-postlisting">
							<div class="fs-13 row">
								<div class="form-group col-xs-12 col-sm-6">
									<label for="<?= Html::getInputId($contactInfo, 'name') ?>" class="fs-13 mgB-5"><?= $contactInfo->getAttributeLabel('name') ?><span class="hint"><?= sprintf(Yii::t('ad', '%s điểm'), '+<span class="point">5</span>') ?></span></label>
									<?= Html::activeTextInput($contactInfo, 'name', ['class' => 'form-control', 'placeholder' => '...', 'disabled' => $contactInfo->name ? true : false]) ?>
								</div>
								<div class="form-group col-xs-12 col-sm-6">
									<label for="<?= Html::getInputId($contactInfo, 'mobile') ?>" class="fs-13 mgB-5"><span class="label-attr"><?= $contactInfo->getAttributeLabel('mobile') ?></span><span class="require-hint">*</span><span class="hint"><?= sprintf(Yii::t('ad', '%s điểm'), '+<span class="point">5</span>') ?></span></label>
									<?= Html::activeTextInput($contactInfo, 'mobile', ['class' => 'form-control number-only', 'maxlength' => 11, 'data-zero-first' => 1, 'placeholder' => '...', 'disabled' => $contactInfo->mobile ? true : false]) ?>
									<div class="help-block"></div>
								</div>
								<div class="form-group col-xs-12 col-sm-6">
									<label for="<?= Html::getInputId($contactInfo, 'email') ?>" class="fs-13 mgB-5"><span class="label-attr"><?= $contactInfo->getAttributeLabel('email') ?></span><span class="hint"><?= sprintf(Yii::t('ad', '%s điểm'), '+<span class="point">5</span>') ?></span></label>
									<?= Html::activeTextInput($contactInfo, 'email', ['class' => 'form-control', 'placeholder' => '...', 'disabled' => $contactInfo->email ? true : false]) ?>
									<div class="help-block"></div>
								</div>
								<div class="form-group col-xs-12 col-sm-6">
									<label for="" class="fs-13 mgB-5"><?= Yii::t('ad', 'You are') ?><span class="hint"><?= sprintf(Yii::t('ad', '%s điểm'), '+<span class="point">5</span>') ?></span></label>
									<div>
										<?php $ownerName = Html::getInputName($product, 'owner') ?>
										<label class="radio-inline radio-ui">
											<?= Html::radio($ownerName, ($product->owner == AdProduct::OWNER_HOST || !$product->owner), ['value' => AdProduct::OWNER_HOST, 'class' => 'owner']) ?> <?= Yii::t('ad', 'Owner') ?>
										</label>
										<label class="radio-inline radio-ui">
											<?= Html::radio($ownerName, ($product->owner == AdProduct::OWNER_AGENT), ['value' => AdProduct::OWNER_AGENT, 'class' => 'owner']) ?> <?= Yii::t('ad', 'Agent') ?>
										</label>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="error-hint" style="display: none;"><?= Yii::t('ad', 'Có lỗi xảy ra, vui lòng kiểm tra lại: ') ?> <span id="error-fields"></span></div>
					<div class="text-right col-xs-12 text-center">
						<button id="preview" type="button" class="btn-common text-uper btn-bd-radius"><?= Yii::t('ad', 'Preview') ?></button>
					</div>
					<?= Html::activeHiddenInput($product, 'lat') ?>
					<?= Html::activeHiddenInput($product, 'lng') ?>
					<div id="review-listing">
						<div class="popup-wrap">
							<div class="detail-listing detail-listing-extra">	
								<div id="detail-wrap">
									<div class="wrap-swiper clearfix">
										<div class="no-gallery pull-left">
											<div class="img-show">
												<div>
													<img alt="" src="/themes/metvuong2/resources/images/default-ads.jpg">
												</div>
											</div>
										</div>
									
										<div class="gallery-detail swiper-container pull-left">
											<div class="swiper-wrapper"></div>
											<div class="swiper-button-prev icon-mv"><span class=""></span></div>
											<div class="swiper-button-next icon-mv"><span class=""></span></div>
										</div>
										
										<ul class="pull-right icons-detail">
											<li class="color-1">
												<a href="javascript:;" data-toggle="tooltip" data-placement="bottom" title="<?= Yii::t('ad', 'Copy link') ?>" class="btn-copy">
													<span class="icon-mv"><span class="icon-link"></span></span>
													<span><?= Yii::t('ad', 'Copy link') ?></span>
												</a>
											</li>
											<li class="color-2">
												<a href="javascript:;" class="share-facebook">
													<span class="icon-mv"><span class="icon-facebook"></span></span>
													<span><?= Yii::t('ad', 'Share Facebook') ?></span>
												</a>
											</li>
											<li class="color-3">
												<a href="javascript:;" data-toggle="modal" data-target="#popup_email" data-type="share" class="email-btn">
													<span class="icon-mv fs-18"><span class="icon-mail-profile"></span></span>
													<span><?= Yii::t('ad', 'Share Email') ?></span>
												</a>
											</li>
											<li class="color-4">
												<a href="javascript:;" class="save-item  " data-id="25074" data-url="/ad/favorite">
													<span class="icon-mv"><span class="icon-heart-icon-listing"></span></span>
													<span><?= Yii::t('ad', 'Add to Favorites') ?></span>
												</a>
											</li>
											<li class="color-5">
												<a href="javascript:;" data-toggle="modal" data-target="#popup-map">
													<span class="icon-mv"><span class="icon-pin-active-copy-3"></span></span>
													<span><?= Yii::t('ad', 'Location') ?></span>
												</a>
											</li>
											<li class="color-6">
												<a href="javascript:;" class="report">
													<span class="icon-mv"><span class="icon-warning"></span></span>
													<span><?= Yii::t('ad', 'Report Abuse') ?></span>
												</a>
											</li>
											<li class="color-8">
												<a href="javascript:;" data-popover="true">
													<span class="icon-mv"><span class="icon-phone-profile"></span></span>
													<span><?= Yii::t('ad', 'Contact Agent') ?></span>
												</a>
											</li>
										</ul>
									</div>
									<div class="infor-listing">
										<div class="address-feat clearfix">
											<p class="infor-by-up">
												<span class="ref" data-ref="#adproduct-category_id"></span>
												<span class="ref" data-ref="#adproduct-type"></span>
												<?= Yii::t('ad', 'by') ?>
												<a class="ref" data-ref=".owner" href="javascript:;"></a>
											</p>
											<div class="address-listing"><p class="address-show"></p></div>
											<div class="left-attr-detail">
												<ul class="clearfix list-attr-td">
							                        <li><span class="icon-mv"><span class="icon-page-1-copy"></span></span><span class="ref" data-ref="#adproduct-area"></span>m2</li>
							                        <li><span class="icon-mv"><span class="icon-bed-search"></span></span><span class="ref" data-ref="#adproductadditioninfo-room_no"></span></li>
							                        <li><span class="icon-mv"><span class="icon-icon-bathroom"></span></span><span class="ref" data-ref="#adproductadditioninfo-toilet_no"></span></li>
												</ul>
											</div>
											<div class="right-attr-detail">
												<p class="price-item">
													<?= Yii::t('ad', 'Price') ?> <strong class="ref" data-format="formatPrice" data-ref="#adproduct-price"></strong>
												</p>
											</div>
										</div>
									</div>
								
									<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
								        <div class="panel panel-default">
								            <div class="panel-heading" role="tab" id="headingOne">
								                <h4 class="panel-title">
								                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
								                        <?= Yii::t('ad', 'Content') ?><span class="icon-mv"><span class="icon-plus"></span></span>
								                    </a>
								                </h4>
								            </div>
								            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
								                <div class="panel-body" name="about" placeholder="Vui lòng chia sẻ tiểu sử">
								                    <p class="ref" data-ref="#adproduct-content" data-format="formatContent" data-html="true"></p>
								                </div>
								            </div>
								        </div>
								        <div class="panel panel-default">
								            <div class="panel-heading" role="tab" id="headingTwo">
								                <h4 class="panel-title">
								                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
								                        <?= Yii::t('ad', 'Detail Information') ?><span class="icon-mv"><span class="icon-plus"></span></span>
								                    </a>
								                </h4>
								            </div>
								            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
								                <div class="panel-body" name="activity">
								                	<ul class="clearfix list-tienich-detail">
									                	<li><strong><?= $product->getAttributeLabel('project_building_id') ?>:</strong> <a href="javascript:;" class="ref" data-ref="#project-name"></a></li>
														<li><strong><?= $additionInfo->getAttributeLabel('floor_no') ?>:</strong> <span class="ref" data-ref="#adproductadditioninfo-floor_no"></span> <?= Yii::t('ad', 'storeys') ?></li>
														<li><strong><?= $additionInfo->getAttributeLabel('facade_width') ?>:</strong> <span class="ref" data-ref="#adproductadditioninfo-facade_width"></span>m</li>
														<li><strong><?= $additionInfo->getAttributeLabel('land_width') ?>:</strong> <span class="ref" data-ref="#adproductadditioninfo-land_width"></span>m</li>
														<li><strong><?= $additionInfo->getAttributeLabel('home_direction') ?>:</strong> <span class="ref" data-ref="#adproductadditioninfo-home_direction"></span></li>
														<li><strong><?= $additionInfo->getAttributeLabel('facade_direction') ?>:</strong> <span class="ref" data-ref="#adproductadditioninfo-facade_direction"></span></li>
														<li><strong><?= $additionInfo->getAttributeLabel('interior') ?>:</strong> <span class="ref" data-ref="#adproductadditioninfo-interior"></span></li>
													</ul>
								                </div>
								            </div>
								        </div>
										<div class="panel panel-default">
								            <div class="panel-heading" role="tab" id="headingSeven">
								                <h4 class="panel-title">
								                    <a class="" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEght" aria-expanded="false" aria-controls="collapseSeven">
								                        <?= Yii::t('ad', 'Contact') ?><span class="icon-mv"><span class="icon-plus"></span></span>
								                    </a>
								                </h4>
								            </div>
								            <div id="collapseEght" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingSeven">
								                <div class="panel-body text-center">
								            	    <div class="infor-agent clearfix">
														<a href="javascript:;" class="wrap-img"><img src="<?= "/" . Yii::$app->user->identity->getUsername() . "/avatar" ?>" alt="demo"></a>
														<div class="img-agent">
															<a href="javascript:;" class="name-agent ref" data-ref="#adcontactinfo-name"></a>
						                                    <div class="stars"> <span id="rating-all-bottom" class="rateit" data-rateit-value="0" data-rateit-ispreset="true" data-rateit-readonly="true"><span id="rateit-range-2" class="rateit-range" tabindex="0" role="slider" aria-label="rating" aria-owns="rateit-reset-2" aria-valuemin="0" aria-valuemax="5" aria-valuenow="0" aria-readonly="true" style="width: 105px; height: 18px;"><span class="rateit-selected rateit-preset" style="height: 18px; width: 0px;"></span><span class="rateit-hover" style="height:18px"></span></span></span> <span class="fs-13 font-600 count_review">(0)</span> </div>
					                                    	<div class="item-agent">
																<div><span class="icon icon-phone"></span></div>
																<a class="ref" data-ref="#adcontactinfo-mobile" href="javascript:;"></a>
															</div>
															<div class="item-agent">
																<div><span class="icon icon-email"></span></div>
																<a class="ref" data-ref="#adcontactinfo-email" href="javascript:;"></a>
															</div>					                                        
														</div>
													</div>
								                </div>
								            </div>
		        						</div>
									</div>
								</div>	
							</div>
							<div class="text-center">
								<input type="hidden" name="start_edit" value="<?= time() ?>" />
								<button id="back" type="button" class="btn-common"><?= Yii::t('ad', 'Back') ?></button>
								<button id="post" type="button" class="btn-common"><?= $product->isNewRecord ? Yii::t('ad', 'Post') : Yii::t('ad', 'Update') ?></button>
							</div>
						</div>
					</div>
				<?php $form->end() ?>
			</div>
			<div class="col-sm-4 checkpoint-listing">
				<div class="inner-checkpoint">
					<div class="title-point"><?= Yii::t('ad', 'Metvuong chấm điểm') ?></div>
					<div class="point-select">
						<div class="text-center total-point">
							<span><span id="score">0</span>/100</span><?= Yii::t('ad', 'điểm') ?>
							<div class="proccess-point">
								<div id="score-percent" style="width: 0%"></div>
							</div>
						</div>
						<ul>
							<li data-id="type"><span class="icon-mv"><span class="icon-checked"></span></span><?= Yii::t('ad', 'Loại tin') ?><span style="display: none;" class="color-cd pdL-15">+<span class="count">0</span> <?= Yii::t('ad', 'điểm') ?></span></li>
							<li data-id="address"><span class="icon-mv"><span class="icon-checked"></span></span><?= Yii::t('ad', 'Địa chỉ') ?><span style="display: none;" class="color-cd pdL-15">+<span class="count">0</span> <?= Yii::t('ad', 'điểm') ?></span></li>
							<li data-id="info"><span class="icon-mv"><span class="icon-checked"></span></span><?= Yii::t('ad', 'General Information') ?><span style="display: none;" class="color-cd pdL-15">+<span class="count">0</span> <?= Yii::t('ad', 'điểm') ?></span></li>
							<li data-id="additionInfo"><span class="icon-mv"><span class="icon-checked"></span></span><?= Yii::t('ad', 'Detail Information') ?><span style="display: none;" class="color-cd pdL-15">+<span class="count">0</span> <?= Yii::t('ad', 'điểm') ?></span></li>
							<li data-id="photo"><span class="icon-mv"><span class="icon-checked"></span></span><?= Yii::t('ad', 'Hình ảnh') ?><span style="display: none;" class="color-cd pdL-15">+<span class="count">0</span> <?= Yii::t('ad', 'điểm') ?></span></li>
							<li data-id="contact"><span class="icon-mv"><span class="icon-checked"></span></span><?= Yii::t('ad', 'Contact Information') ?><span style="display: none;" class="color-cd pdL-15">+<span class="count">0</span> <?= Yii::t('ad', 'điểm') ?></span></li>
						</ul>
						
					</div>
					<div class="btom-point"><?= Yii::t('ad', '* Điểm số cụ thể sẽ được quyết định sau khi ban điều hành kiểm tra thông tin chi tiết tin đăng.') ?></div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function () {
		$('.checkbox-ui').checkbox_ui();
		$('.add-gold').on('click', function (e) {
			e.preventDefault();
			$('.ajax-loading-inner').toggle();
		});
	});
</script>