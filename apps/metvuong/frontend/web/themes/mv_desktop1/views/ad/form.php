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
	$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/string-helper.js', ['position' => View::POS_END]);
	$this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/post-listing.js', ['position' => View::POS_END]);
	
	$cities = AdCity::find()->all();
	$citiesDropdown = ArrayHelper::map($cities, 'id', 'name');
	// $citiesOptions = ArrayHelper::map($cities, 'id', function($city){ return ['disabled' => ($city->id != AdProduct::DEFAULT_CITY)]; });
	$citiesOptions = [];
	
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
	
	$this->registerJs("var tempSlide = " . ($product->adImages ? json_encode(ArrayHelper::map($product->adImages, 'id', 'url')) : 'false') . "; var isNewRecord = " . ($product->isNewRecord ? 'true' : 'false') . "; var categories = " . json_encode($categoriesJs) . "; var APPLY_TO_TYPE_BOTH = " . AdCategory::APPLY_TO_TYPE_BOTH . "; var CHCK = " . AdCategory::CATEGORY_CHCK . ";", View::POS_HEAD);
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
						<?= Html::activeDropDownList($product, 'category_id', $categoriesDropDown, ['options' => $categoriesDropDownOptions, 'class' => 'form-control', 'prompt' => "..."]) ?>
						<div class="help-block"></div>
					</div>
					<div class="form-group col-xs-6 project-wrap<?= $product->projectBuilding ? ' has-project' : '' ?>"<?= $product->category_id == AdCategory::CATEGORY_CHCK ? '' : ' style="display: none;"' ?>>
						<label for="" class="fs-13 mgB-10">Tên dự án <span class="color-cd pdL-15">+3 điểm</span></label>
						<?= Html::activeHiddenInput($product, 'project_building_id') ?>
						<a target="_blank" href="<?= $product->projectBuilding ? Url::to(['building-project/view', 'slug' => $product->projectBuilding->slug]) : '#'; ?>" id="project-value"><span class="icon-mv fs-12 mgR-5"><span class="icon-close-icon"></span></span><span class="name"><?= $product->projectBuilding ? $product->projectBuilding->name . ', ' . $districtDropdown[$product->projectBuilding->district_id] . ', ' . $citiesDropdown[$product->projectBuilding->city_id] : '' ?></span></a>
						<input type="text" class="form-control" id="projectMask" placeholder="...">
						<div id="search-list" class="hide"><ul></ul></div>
					</div>
					<div class="form-group col-xs-6">
						<label for="<?= Html::getInputId($product, 'city_id') ?>" class="fs-13 mgB-10"><?= $product->getAttributeLabel('city_id') ?><span class="require-hint">*</span></label>
						<?= Html::activeDropDownList($product, 'city_id', $citiesDropdown, ['class' => 'form-control search', 'options' => $citiesOptions, 'prompt' => "...", 'disabled' => ($product->projectBuilding && $product->projectBuilding->city_id) ? true : false]) ?>
						<div class="help-block"></div>
					</div>
					<div class="form-group col-xs-6"<?= $product->city_id ? '' : ' style="display: none;"' ?>>
						<label for="<?= Html::getInputId($product, 'district_id') ?>" class="fs-13 mgB-10"><?= $product->getAttributeLabel('district_id') ?><span class="require-hint">*</span></label>
						<?= Html::activeDropDownList($product, 'district_id', $districtDropdown, ['class' => 'form-control search', 'prompt' => "...", 'data-no-results' => Yii::t('ad', 'Choose City to show Districts'), 'disabled' => ($product->projectBuilding && $product->projectBuilding->district_id) ? true : false]) ?>
						<div class="help-block"></div>
					</div>
					<div class="form-group col-xs-6"<?= $product->district_id ? '' : ' style="display: none;"' ?>>
						<label for="<?= Html::getInputId($product, 'ward_id') ?>" class="fs-13 mgB-10"><?= $product->getAttributeLabel('ward_id') ?><span class="require-hint">*</span></label>
						<?= Html::activeDropDownList($product, 'ward_id', $wardsDropdown, ['options' => $wardsOptions, 'class' => 'form-control search', 'prompt' => "...", 'data-no-results' => Yii::t('ad', 'Choose District to show Wards'), 'disabled' => ($product->projectBuilding && $product->projectBuilding->ward_id) ? true : false]) ?>
						<div class="help-block"></div>
					</div>
					<div class="form-group col-xs-6"<?= $product->district_id ? '' : ' style="display: none;"' ?>>
						<label for="<?= Html::getInputId($product, 'street_id') ?>" class="fs-13 mgB-10"><?= $product->getAttributeLabel('street_id') ?><span class="require-hint">*</span></label>
						<?= Html::activeDropDownList($product, 'street_id', $streetsDropdown, ['options' => $streetsOptions, 'class' => 'form-control search', 'prompt' => "...", 'data-no-results' => Yii::t('ad', 'Choose District to show Streets'), 'disabled' => ($product->projectBuilding && $product->projectBuilding->street_id) ? true : false]) ?>
						<div class="help-block"></div>
					</div>
					<div class="form-group col-xs-6 fild-address"<?= $product->district_id ? '' : ' style="display: none;"' ?>>
						<label for="<?= Html::getInputId($product, 'home_no') ?>" class="fs-13 mgB-10"><?= $product->getAttributeLabel('home_no') ?></label>
						<?= Html::activeTextInput($product, 'home_no', ['class' => 'form-control', 'placeholder' => '...', 'disabled' => ($product->projectBuilding && $product->projectBuilding->home_no) ? true : false]) ?>
						<label class="checkbox-inline fs-13 checkbox-ui">
							<?= Html::activeCheckbox($product, 'show_home_no', ['label' => false]) ?>
							<span class="icon-mv"><span class="icon-checkbox"></span></span> <?= $product->getAttributeLabel('show_home_no') ?>
						</label>
					</div>
					<div class="clearfix"></div>
					<div class="form-group col-xs-6">
						<label for="<?= Html::getInputId($product, 'area') ?>" class="fs-13 mgB-10"><?= $product->getAttributeLabel('area') ?><span class="require-hint">*</span></label>
						<?= Html::activeTextInput($product, 'area', ['class' => 'form-control number-only number-float', 'placeholder' => '...']) ?>
						<div class="help-block"></div>
					</div>
					<div class="form-group col-xs-6 price-type">
						<label for="<?= Html::getInputId($product, 'price') ?>" class="fs-13 mgB-10"><?= $product->getAttributeLabel('price') ?><span class="require-hint">*</span></label><span class="price-show-wrap"><span id="price-show"></span><span class="vnd"><?= Yii::t('ad', 'VND') ?></span></span>
						<?= Html::activeHiddenInput($product, 'price', ['class' => 'form-control text-right', 'placeholder' => '...']) ?>
						<div style="position: relative;">
							<input type="text" id="priceMask" class="form-control text-right number-only number-float" placeholder="...">
							<div class="price-unit">
								<label class="radio-inline radio-ui">
									<input type="radio" name="price-unit" id="price-unit-mil" value="1000000" checked="checked"> Triệu
								</label>
								<label class="radio-inline radio-ui">
									<input type="radio" name="price-unit" id="price-unit-bil" value="1000000000"> Tỷ
								</label>
							</div>
						</div>
						<div class="help-block"></div>
					</div>
					<div class="form-group col-xs-12">
						<label for="<?= Html::getInputId($product, 'content') ?>" class="fs-13 mgB-10"><?= $product->getAttributeLabel('content') ?><span class="require-hint">*</span></label>
						<?= Html::activeTextarea($product, 'content', ['class' => 'form-control', 'rows' => 5]) ?>
						<div class="help-block"></div>
					</div>
					<div class="form-group col-xs-4">
						<label for="<?= Html::getInputId($additionInfo, 'room_no') ?>" class="fs-13 mgB-10"><?= $additionInfo->getAttributeLabel('room_no') ?></label>
						<?= Html::activeTextInput($additionInfo, 'room_no', ['class' => 'form-control number-only', 'placeholder' => '...']) ?>
					</div>
					<div class="form-group col-xs-4">
						<label for="<?= Html::getInputId($additionInfo, 'toilet_no') ?>" class="fs-13 mgB-10"><?= $additionInfo->getAttributeLabel('toilet_no') ?></label>
						<?= Html::activeTextInput($additionInfo, 'toilet_no', ['class' => 'form-control number-only', 'placeholder' => '...']) ?>
					</div>
					<div class="form-group col-xs-4">
						<label for="<?= Html::getInputId($additionInfo, 'floor_no') ?>" class="fs-13 mgB-10"><?= $additionInfo->getAttributeLabel('floor_no') ?></label>
						<?= Html::activeTextInput($additionInfo, 'floor_no', ['class' => 'form-control number-only', 'placeholder' => '...']) ?>
					</div>
					<div class="form-group col-xs-4">
						<label for="<?= Html::getInputId($additionInfo, 'facade_width') ?>" class="fs-13 mgB-10"><?= $additionInfo->getAttributeLabel('facade_width') ?></label>
						<?= Html::activeTextInput($additionInfo, 'facade_width', ['class' => 'form-control number-only number-float', 'placeholder' => '...']) ?>
					</div>
					<div class="form-group col-xs-4">
						<label for="<?= Html::getInputId($additionInfo, 'land_width') ?>" class="fs-13 mgB-10"><?= $additionInfo->getAttributeLabel('land_width') ?></label>
						<?= Html::activeTextInput($additionInfo, 'land_width', ['class' => 'form-control number-only number-float', 'placeholder' => '...']) ?>
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
									<?= Html::checkbox($facilityName, in_array($facility->id, $activeFacility), ['value' => $facility->id]) ?>
									<span class="icon-mv"><span class="icon-checkbox"></span></span> <?= Yii::t('ad', $facility->name) ?>
								</label>
							</li>
							<?php endforeach; ?>
						</ul>
					</div>
					<div class="form-group col-xs-12">
						<label for="" class="fs-13 mgB-10">Tải hình ảnh dư án <span class="color-cd pdL-15">+3 điểm</span></label>
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
					<div class="form-group col-xs-12 mgT-30">
						<label for="" class="fs-16 mgB-10 text-uper">Liên hệ</label>
						<div class="clearfix agent-postlisting">
							<div class="fs-13 row">
								<div class="form-group col-xs-6">
									<label for="<?= Html::getInputId($contactInfo, 'name') ?>" class="fs-13 mgB-10"><?= $contactInfo->getAttributeLabel('name') ?></label>
									<?= Html::activeTextInput($contactInfo, 'name', ['class' => 'form-control', 'placeholder' => '...']) ?>
								</div>
								<div class="form-group col-xs-6">
									<label for="<?= Html::getInputId($contactInfo, 'mobile') ?>" class="fs-13 mgB-10"><?= $contactInfo->getAttributeLabel('mobile') ?><span class="require-hint">*</span></label>
									<?= Html::activeTextInput($contactInfo, 'mobile', ['class' => 'form-control number-only', 'placeholder' => '...']) ?>
									<div class="help-block"></div>
								</div>
								<div class="form-group col-xs-6">
									<label for="<?= Html::getInputId($contactInfo, 'email') ?>" class="fs-13 mgB-10"><?= $contactInfo->getAttributeLabel('email') ?></label>
									<?= Html::activeTextInput($contactInfo, 'email', ['class' => 'form-control', 'placeholder' => '...']) ?>
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
					<div class="error-hint form-group col-xs-12 mgT-30" style="display: none;"><?= Yii::t('ad', 'Có lỗi xảy ra, vui lòng kiểm tra lại các trường bên trên') ?></div>
					<div class="text-right col-xs-12 pdT-50">
						<button id="preview" type="button" class="btn-common"><?= Yii::t('ad', 'Preview') ?> <span class="icon-mv"><span class="icon-angle-right"></span></span></button>
					</div>
					<?= Html::activeHiddenInput($product, 'lat') ?>
					<?= Html::activeHiddenInput($product, 'lng') ?>
					<div id="review-listing">
						<div class="overlay"></div>
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
						<a href="#" data-toggle="tooltip" data-placement="bottom" title="Sao chép đường dẫn" data-title-success="Đã sao chép" class="btn-copy" data-clipboard-text="http://local.metvuong.com/real-estate/detail/25074-phuong-ben-nghe-quan-1-ho-chi-minh">
							<span class="icon-mv"><span class="icon-link"></span></span>
							<span>Sao chép đường dẫn</span>
						</a>
					</li>
					<li class="color-2">
						<a href="#" class="share-facebook" data-url="http://local.metvuong.com/ad/tracking-share?product_id=25074&amp;type=1">
							<span class="icon-mv"><span class="icon-facebook"></span></span>
							<span>Chia sẻ Facebook</span>
						</a>
					</li>
					<li class="color-3">
						<a href="#" data-toggle="modal" data-target="#popup_email" data-type="share" class="email-btn">
							<span class="icon-mv fs-18"><span class="icon-mail-profile"></span></span>
							<span>Chia sẻ Email</span>
						</a>
					</li>
					<li class="color-4">
						<a href="#" class="save-item  " data-id="25074" data-url="/ad/favorite">
							<span class="icon-mv"><span class="icon-heart-icon-listing"></span></span>
							<span>Thêm yêu thích</span>
						</a>
					</li>
					<li class="color-5">
						<a href="#" data-toggle="modal" data-target="#popup-map">
							<span class="icon-mv"><span class="icon-pin-active-copy-3"></span></span>
							<span>Vị trí</span>
						</a>
					</li>
					<li class="color-6">
						<a href="#" class="report">
							<span class="icon-mv"><span class="icon-warning"></span></span>
							<span>Báo cáo</span>
						</a>
					</li>
					<li class="color-8">
						<a href="#" data-popover="true">
							<span class="icon-mv"><span class="icon-phone-profile"></span></span>
							<span>Liên hệ Môi giới</span>
						</a>
						<div class="popover-append hide">
							<div class="infor-agent clearfix">
                                                                    <a href="/demo" class="wrap-img">
                                        <img src="/images/default-avatar.jpg" alt="demo"></a>
                                								<div class="img-agent">
                                                                            <a href="/demo" class="name-agent">Demo</a>
                                        <div class="stars">
	                                        <span id="rating-all" class="rateit" data-rateit-value="0" data-rateit-ispreset="true" data-rateit-readonly="true"></span>
	                                        <span class="fs-13 font-600 count_review">(0)</span>
	                                    </div>
                                    
                                                                            <div class="item-agent">
                                            <span class="icon-mv">
                                            	<span class="icon-phone-profile"></span>
                                            </span>
                                            <a href="tel:12345678">12345678</a>
                                        </div>
                                    
                                                                            <div class="item-agent">
                                            <span class="icon-mv"><span class="icon-mail-profile"></span></span>
                                            vinh@dwm.vn                                        </div>
                                    
                                                                                <div class="item-agent">
                                                <span class="icon-mv"><span class="icon-pin-active-copy-3"></span></span>
                                                Hồ Chí Minh                                            </div>
                                                                                                                    <a href="#" data-toggle="modal" data-target="#popup_email" data-type="contact" class="email-btn btn-common btn-small">Email</a>
                                        <a href="#" class="chat-btn btn-common btn-small chat-now" data-chat-user="demo">Chat</a>
                                    								</div>
							</div>
						</div>
					</li>
				</ul>
			</div>
			<div class="infor-listing">
				<div class="address-feat clearfix">
					<p class="infor-by-up">
						Căn hộ chung cư Bán bởi <a href="javascript:;">Chủ nhà</a>
					</p>
					<div class="address-listing">
						<p>Phường Bến Nghé, Quận 1, Hồ Chí Minh</p>
					</div>
					<div class="pull-left left-attr-detail">
						<p class="id-duan">Mã tin:<span>MV25074</span></p>
						<ul class="clearfix list-attr-td">
	                        <li> <span class="icon-mv"><span class="icon-page-1-copy"></span></span>100m2 </li><li><span class="icon-mv"><span class="icon-bed-search"></span></span>2 </li><li> <span class="icon-mv"><span class="icon-bathroom-search-copy-2"></span></span>2 </li>						</ul>
					</div>
					<div class="overflow-all right-attr-detail">
						<p class="price-td">
							Giá<br>
							<span>9 tỷ</span>
						</p>
					</div>
				</div>
				<meta name="keywords" content="Phường Bến Nghé, Quận 1, Hồ Chí Minh">
<meta name="description" content="jdaklsfjldasjfljasdlf">
<meta property="og:title" content="Phường Bến Nghé, Quận 1, Hồ Chí Minh">
<meta property="og:description" content="jdaklsfjldasjfljasdlf">
<meta property="og:type" content="article">
<meta property="og:image" content="http://local.metvuong.com/store/ad/2016/05/04/480x360/5729cea37ef4b.jpg">
<meta property="og:url" content="http://local.metvuong.com/real-estate/detail/25074-phuong-ben-nghe-quan-1-ho-chi-minh">
<style>.map-wrap {position: relative;} .map-wrap:after {display: block; content: ""; padding-top: 75%;} .map-inside {position: absolute; width: 100%; height: 100%;} #map {height: 100%;}</style><div id="popup_email" class="modal fade popup-common" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header title-popup clearfix">
                <div class="text-center popup_title">LIÊN HỆ</div>
                <a href="#" class="txt-cancel btn-cancel close" data-dismiss="modal" aria-label="Close">Hủy bỏ</a>
                <a href="#" class="txt-done btn-done send_mail" data-alert="Gửi email thành công !">Gửi</a>
            </div>
            <div class="modal-body">
                <div class="wrap-popup">
                    <div class="inner-popup">
                        <form id="share_form" action="/ad/sendmail" method="post" role="form">
<input type="hidden" name="_csrf" value="bzBSR2V2QjAqfjA9UzAjRBp3HCoCBxtEA3IoNlFECGIcYBMVNikoYA=="><div class="form-group field-shareform-your_email required">

<input type="hidden" id="shareform-your_email" class="your_email" name="ShareForm[your_email]" value="lenh.quach@trungthuygroup.vn">

<p class="help-block help-block-error"></p>
</div>                        <div class="frm-item frm-email">
                            <div class="form-group field-shareform-recipient_email required">

<input type="text" id="shareform-recipient_email" class="recipient_email" name="ShareForm[recipient_email]" value="vinh@dwm.vn" placeholder="Email người nhận">

<p class="help-block help-block-error"></p>
</div>                        </div>

                        <div class="frm-item frm-email hide">
                            <div class="form-group field-shareform-subject">

<input type="hidden" id="shareform-subject" class="subject2" name="ShareForm[subject]" placeholder="Tiêu đề...">

<p class="help-block help-block-error"></p>
</div>                        </div>
                        <div class="frm-item frm-email">
                            <div class="form-group field-shareform-content">

<textarea id="shareform-content" class="content" name="ShareForm[content]" rows="5" cols="30" placeholder="Nội dung"></textarea>

<p class="help-block help-block-error"></p>
</div>                        </div>
                        <div class="item-send">
                            <div class="form-group field-shareform-type">

<input type="hidden" id="shareform-type" class="type" name="ShareForm[type]" value="contact">

<p class="help-block help-block-error"></p>
</div><div class="form-group field-shareform-from_name">

<input type="hidden" id="shareform-from_name" class="from_name" name="ShareForm[from_name]" value="TTG | METVUONG QUACH TUAN LENH">

<p class="help-block help-block-error"></p>
</div><div class="form-group field-shareform-to_name">

<input type="hidden" id="shareform-to_name" class="to_name" name="ShareForm[to_name]" value="Demo">

<p class="help-block help-block-error"></p>
</div>                            <div class="img-show"><div><a href="http://local.metvuong.com/real-estate/detail/25074-phuong-ben-nghe-quan-1-ho-chi-minh"><img src="/store/ad/2016/05/04/480x360/5729cea37ef4b.jpg" alt="Phường Bến Nghé, Quận 1, Hồ Chí Minh"></a></div></div>
                            <div class="infor-send">
                                <p class="name"><a href="http://local.metvuong.com/real-estate/detail/25074-phuong-ben-nghe-quan-1-ho-chi-minh">Phường Bến Nghé, Quận 1, Hồ Chí Minh</a></p>
                                <p class="address"></p>
                                <p>jdaklsfjldasjfljasdlf</p>
                                <p class="send-by">BY METVUONG.COM</p>
                            </div>

                            <div class="form-group field-shareform-pid">

<input type="hidden" id="shareform-pid" class="pid" name="ShareForm[pid]" value="MV25074">

<p class="help-block help-block-error"></p>
</div>                            <div class="form-group field-shareform-address">

<input type="hidden" id="shareform-address" class="_address" name="ShareForm[address]" value="Phường Bến Nghé, Quận 1, Hồ Chí Minh">

<p class="help-block help-block-error"></p>
</div>                            <div class="form-group field-shareform-detailurl">

<input type="hidden" id="shareform-detailurl" class="_detailUrl" name="ShareForm[detailUrl]" value="http://local.metvuong.com/real-estate/detail/25074-phuong-ben-nghe-quan-1-ho-chi-minh">

<p class="help-block help-block-error"></p>
</div>                            <div class="form-group field-shareform-domain">

<input type="hidden" id="shareform-domain" class="_domain" name="ShareForm[domain]" value="http://local.metvuong.com">

<p class="help-block help-block-error"></p>
</div>                                                    </div>
                        </form>                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
			</div>
			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		        <div class="panel panel-default">
		            <div class="panel-heading" role="tab" id="headingOne">
		                <h4 class="panel-title">
		                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
		                        Mô tả		                        <span class="icon-mv"><span class="icon-plus"></span></span>
		                    </a>
		                </h4>
		            </div>
		            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
		                <div class="panel-body" name="about" placeholder="Vui lòng chia sẻ tiểu sử">
		                    <p>jdaklsfjldasjfljasdlf</p>
		                </div>
		            </div>
		        </div>
		        <div class="panel panel-default">
		            <div class="panel-heading" role="tab" id="headingTwo">
		                <h4 class="panel-title">
		                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
		                        Thông tin chi tiết		                        <span class="icon-mv"><span class="icon-plus"></span></span>
		                    </a>
		                </h4>
		            </div>
		            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
		                <div class="panel-body" name="activity">
		                	<ul class="clearfix list-tienich-detail">
			                    								<li><strong>Dự án:</strong> <a href="/du-an/ben-thanh-times-square">Ben Thanh Times Square</a></li>
																								<li><strong>Mặt tiền:</strong> 22m</li>
																								<li><strong>Đường vào:</strong> 22m</li>
																								<li><strong>Tầng cao:</strong> 23  Tầng</li>
																								<li><strong>Hướng nhà:</strong> Tây</li>
																								<li><strong>Hướng ban công:</strong> Tây</li>
																								<li><strong>Nội thất:</strong> 34234234</li>
															</ul>
		                </div>
		            </div>
		        </div>
		        		        <div class="panel panel-default">
		            <div class="panel-heading" role="tab" id="headingSeven">
		                <h4 class="panel-title">
		                    <a class="" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEght" aria-expanded="false" aria-controls="collapseSeven">
		                        Liên hệ		                        <span class="icon-mv"><span class="icon-plus"></span></span>
		                    </a>
		                </h4>
		            </div>
		            <div id="collapseEght" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingSeven">
		                <div class="panel-body text-center">
		            	    <div class="infor-agent clearfix">
					            								    <a href="/demo" class="wrap-img">
					                <img src="/images/default-avatar.jpg" alt="demo"></a>
					            					            <div class="img-agent">
                                    						            <a href="/demo" class="name-agent">Demo</a>
                                    <div class="stars">
                                        <span id="rating-all-bottom" class="rateit" data-rateit-value="0" data-rateit-ispreset="true" data-rateit-readonly="true"></span>
                                        <span class="fs-13 font-600 count_review">(0)</span>
                                    </div>
                                    


																		<div class="item-agent">
										<div>
											<span class="icon icon-phone"></span>
										</div>
										<a href="tel:12345678">12345678</a>
									</div>
																											<div class="item-agent">
										<div>
											<span class="icon icon-email"></span>
										</div>
										vinh@dwm.vn									</div>
									                                    									<div class="item-agent">
										<div>
											<span class="icon address-icon"></span>
										</div>
										Hồ Chí Minh									</div>
                                    									                                        
																	</div>
							</div>

		                </div>
		            </div>

		        </div>
		    </div>
		</div>	
		</div>			
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
							<div class="text-center">
								<button id="post" type="button" class="btn-common"><?= Yii::t('ad', 'Back') ?></button>
								<button id="close" type="button" class="btn-common"><?= $product->isNewRecord ? Yii::t('ad', 'Post') : Yii::t('ad', 'Update') ?></button>
							</div>
						</div>
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
		$('.checkbox-ui').checkbox_ui();
	});
</script>