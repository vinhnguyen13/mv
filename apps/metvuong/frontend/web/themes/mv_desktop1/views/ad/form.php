<?php 
	use yii\widgets\ActiveForm;
	use yii\helpers\Html;
	use vsoft\ad\models\AdProduct;
	use yii\web\View;
	use vsoft\ad\models\AdBuildingProject;
	use yii\helpers\ArrayHelper;
	use yii\helpers\Url;
	use vsoft\ad\models\AdProductAdditionInfo;
	use common\widgets\fileupload\FileUpload;
	use vsoft\ad\models\AdCity;
	use vsoft\ad\models\AdDistrict;
	use vsoft\ad\models\AdWard;
	use vsoft\ad\models\AdStreet;
	use vsoft\ad\models\AdCategory;
use vsoft\ad\models\AdImages;

	$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/string-helper.js', ['position' => View::POS_END]);
	$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/post-listing.js', ['position' => View::POS_END]);
	$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyASTv_J_7DuXskr5SaCZ_7RVEw7oBKiHi4', ['depends' => ['yii\web\YiiAsset'], 'async' => true, 'defer' => true]);
	$this->registerCss("#project-info {position: relative;} #project-info .loading-proccess span {border-top-color: #8C7777; border-left-color: #8C7777;} #project-info .loading-proccess {display: none;} #project-info.loading .loading-proccess {display: block;} #project-info.loading .result {display: none;}");
	$this->registerJs("var APPLY_TO_TYPE_BOTH = " . AdCategory::APPLY_TO_TYPE_BOTH . ";", View::POS_HEAD);
	
	$listRoom = [];
	for($i = 1; $i <= 10; $i++) {
		$listRoom[$i] = $i;
	}
	
	$owner =  Yii::$app->user->identity;
	if($owner && $owner->profile) {
		$avatar = $owner->profile->getAvatarUrl();
	} else {
		$avatar = Yii::$app->view->theme->baseUrl . '/resources/images/default-avatar.jpg';
	}
?>
<div class="title-fixed-wrap">
	<div class="post-listing">
		<div class="title-top"><?= $product->isNewRecord ? Yii::t('ad', 'Post Listing') : sprintf(Yii::t('ad', 'Update Listing MV%s'), $product->id) ?></div>
		<div class="wrap-frm-listing clearfix">
			<?php
				$form = ActiveForm::begin ( [ 
					'id' => 'listing-form',
					'enableClientValidation' => false,
					'options' => [ 
						'autocomplete' => 'off',
						'spellcheck' => 'false'
					]
				]);
			?>
				<input type="hidden" id="is-update" value="<?= $product->isNewRecord ? 0 : 1 ?>" />
				<div class="step-link">
					<ul class="clearfix">
						<li><a data-active-section="tt-chung" class="active" href="#">1</a><span class="icon arrowLeft"></span></li>
						<li><a data-active-section="tt-chitiet" href="#">2</a><span class="icon arrowLeft"></span></li>
						<li><a data-active-section="hinh-anh" href="#">3</a><span class="icon arrowLeft"></span></li>
						<li><a data-active-section="tien-ich" href="#">4</a><span class="icon arrowLeft"></span></li>
					</ul>
				</div>
				<div id="step1" class="section select-type item-step clearfix">
				<?php if($product->isNewRecord) :?>
					<p class="text-center step-txt"><?= Yii::t('ad', '4 basic steps') ?></p>
				<?php endif; ?>
					<div class="dt-hide">
						<ul class="clearfix">
							<li>BẠN LÀ?</li>
							<li>BẠN MUỐN?</li>
							<li>LOẠI BĐS BẠN TÌM?</li>
						</ul>
					</div>
					<div class="dt-show">
						<ul class="clearfix step-check">
							<li>
								<a class="frm-radio" href="#">
									<?php $ownerName = Html::getInputName($product, 'owner') ?>
									<span class="radio-ui icon-postlisting icon-chunha">
										<?= Html::radio($ownerName, ($product->owner == AdProduct::OWNER_HOST || !$product->owner), ['value' => AdProduct::OWNER_HOST, 'id' => 'owner-host']) ?>
									</span>
									<span class="txt-type-post"><?= Yii::t('ad', 'Owner') ?></span>
								</a>
							</li>
							<li>
								<a class="frm-radio" href="#">
									<span class="radio-ui icon-postlisting icon-mogioi">
										<?= Html::radio($ownerName, ($product->owner == AdProduct::OWNER_AGENT), ['value' => AdProduct::OWNER_AGENT]) ?>
									</span>
									<span class="txt-type-post"><?= Yii::t('ad', 'Agent') ?></span>
								</a>
							</li>
							<li>
								<a class="frm-radio" href="#">
									<span class="radio-ui icon-postlisting icon-ban-post">
										<?php $typeName = Html::getInputName($product, 'type') ?>
										<?= Html::radio($typeName, ($product->type == AdProduct::TYPE_FOR_SELL || !$product->type), ['value' => AdProduct::TYPE_FOR_SELL]) ?>
									</span>
									<span class="txt-type-post"><?= Yii::t('ad', 'Sell') ?></span>
								</a>
							</li>
							<li>
								<a class="frm-radio" href="#">
									<span class="radio-ui icon-postlisting icon-chothue">
										<?= Html::radio($typeName, ($product->type == AdProduct::TYPE_FOR_RENT), ['value' => AdProduct::TYPE_FOR_RENT]) ?>
									</span>
									<span class="txt-type-post"><?= Yii::t('ad', 'Rent') ?></span>
								</a>
							</li>
						</ul>
						
						<?php
							$categories = AdCategory::find()->all();
							$categoriesDropdown = ArrayHelper::map($categories, 'id', 'name');
							$categoriesOptions = ArrayHelper::map($categories, 'id', function($category){ return ['data-type' => $category->apply_to_type, 'data-limit' => $category->limit_area]; });
								
							echo $form->field($product, 'category_id')
									 ->label(false)
									 ->dropDownList($categoriesDropdown, ['prompt' => $product->getAttributeLabel('category_id'), 'options' => $categoriesOptions])
						?>
					</div>
				</div>

				<div id="step2" class="tt-chung item-step section">
					<div class="title-step"><?= Yii::t('ad', 'General Information') ?></div>
					<div class="row">
						<?php
							$cities = AdCity::find()->all();
							$citiesDropdown = ArrayHelper::map($cities, 'id', 'name');
							$citiesOptions = ArrayHelper::map($cities, 'id', function($city){ return ['disabled' => ($city->id != AdProduct::DEFAULT_CITY)]; });
							echo $form->field($product, 'city_id', ['options' => ['class' => 'col-xs-6 form-group']])
									  ->label(false)
									  ->dropDownList($citiesDropdown, ['prompt' => $product->getAttributeLabel('city_id'), 'options' => $citiesOptions])
						?>
						<?= $form->field($product, 'district_id', ['options' => ['class' => 'col-xs-6 form-group']])
								 ->label(false)
								 ->dropDownList(ArrayHelper::map(AdDistrict::getListByCity($product->city_id), 'id', 'name'), ['prompt' => $product->getAttributeLabel('district_id')]) ?>
						
						<?= $form->field($product, 'ward_id', ['options' => ['class' => 'col-xs-12 form-group']])
							->label(false)
							->dropDownList(ArrayHelper::map(AdWard::getListByDistrict($product->district_id), 'id', 'name'), ['prompt' => $product->getAttributeLabel('ward_id')]) ?>
							
						<?= $form->field($product, 'street_id', ['options' => ['class' => 'col-xs-12 form-group']])
							->label(false)
							->dropDownList(ArrayHelper::map(AdStreet::getListByDistrict($product->district_id), 'id', 'name'), ['prompt' => $product->getAttributeLabel('street_id')]) ?>
						
						<div id="home-wrap">
							<?= $form->field($product, 'home_no', ['options' => ['class' => 'col-xs-12 form-group']])
									->label(false)
									->textInput(['placeholder' => $product->getAttributeLabel('home_no')]) ?>
								
							<?= $form->field($product, 'show_home_no', ['options' => ['class' => 'col-xs-12 form-group toggle-num-home']])->label(false)->checkbox(['data-set' => $product->isNewRecord ? '0' : '1']) ?>
						</div>
						
						<div class="col-xs-12 form-group dt-post">
							<?= $form->field($product, 'area', ['options' => ['class' => '']])
								->label(false)
								->textInput(['placeholder' => $product->getAttributeLabel('area')]) ?>
							<span class="unit-dt">m2</span>
						</div>
						
						<?= $form->field($product, 'price', ['options' => ['class' => 'col-xs-12 form-group'], 'template' => '{input}<span style="display: none;" id="price-format"></span>{error}'])
								->textInput(['placeholder' => $product->getAttributeLabel('price')]) ?>
						
						<?= $form->field($additionInfo, 'room_no', ['options' => ['class' => 'col-xs-6 form-group']])
							->label(false)
							->dropDownList($listRoom, ['prompt' => $additionInfo->getAttributeLabel('room_no')]) ?>
						
						<?= $form->field($additionInfo, 'toilet_no', ['options' => ['class' => 'col-xs-6 form-group']])
							->label(false)
							->dropDownList($listRoom, ['prompt' => $additionInfo->getAttributeLabel('toilet_no')]) ?>
							
						<div id="project-info-wrap" class="col-xs-12">
							<?php
								$projects = $product->district_id ? AdBuildingProject::find()->where('district_id = :district_id', [':district_id' => $product->district_id])->all() : [];
								echo $form->field($product, 'project_building_id', ['options' => ['class' => 'form-group']])
									 ->label(false)
									 ->dropDownList(ArrayHelper::map($projects, 'id', 'name'), ['prompt' => $product->getAttributeLabel('project_building_id')])
							?>
							<div id="project-info" style="display: none;" data-url="<?= Url::to(['building-project/detail']) ?>">
								<div class="loading-proccess"><span></span></div>
								<div class="result">
									<div>Vị trí: <span id="project-info-location"></span></div>
									<a target="_blank" id="project-info-detail" href="#">Xem chi tiết dự án</a>
								</div>
							</div>
						</div>
						
						<?= Html::activeHiddenInput($product, 'lat') ?>
						<?= Html::activeHiddenInput($product, 'lng') ?>
					</div>
				</div>

				<div id="step3" class="tt-chitiet item-step section">
					<div class="title-step"><?= Yii::t('ad', 'Detail Information') ?></div>
					<div class="row">
						<?= $form->field($product, 'content', ['options' => ['class' => 'col-xs-12 form-group']])
								->label(false)
								->textArea(['placeholder' => $product->getAttributeLabel('content')]) ?>
						
						<div class="col-xs-6 form-group">
							<?= $form->field($additionInfo, 'facade_width')
								->label(false)
								->textInput(['placeholder' => $additionInfo->getAttributeLabel('facade_width')]) ?>
							<span class="unit-dt">m</span>
						</div>
						
						<div class="col-xs-6 form-group">		
						<?= $form->field($additionInfo, 'land_width')
								->label(false)
								->textInput(['placeholder' => $additionInfo->getAttributeLabel('land_width')]) ?>
							<span class="unit-dt">m</span>
						</div>
						
						<?= $form->field($additionInfo, 'home_direction', ['options' => ['class' => 'col-xs-12 form-group']])
								->label(false)
								->dropDownList(AdProductAdditionInfo::directionList(), ['prompt' => $additionInfo->getAttributeLabel('home_direction'), 'data-default' => $additionInfo->home_direction]) ?>
						
						<?= $form->field($additionInfo, 'facade_direction', ['options' => ['class' => 'col-xs-12 form-group']])
								->label(false)
								->dropDownList(AdProductAdditionInfo::directionList(), ['prompt' => $additionInfo->getAttributeLabel('facade_direction'), 'data-default' => $additionInfo->facade_direction]) ?>
								
						<?= $form->field($additionInfo, 'floor_no', ['options' => ['class' => 'col-xs-12 form-group']])
								->label(false)
								->textInput(['placeholder' => $additionInfo->getAttributeLabel('floor_no')]) ?>
								
						<?= $form->field($additionInfo, 'interior', ['options' => ['class' => 'col-xs-12 form-group']])
								->label(false)
								->textArea(['placeholder' => $additionInfo->getAttributeLabel('interior')]) ?>
						
						<div id="addition-field-template" style="display: none;">
							<div class="addition-field row">
								<?= $form->field($additionInfo, 'addition_fields', ['options' => ['class' => 'col-xs-12 col-md-4 col-sm-4 col-lg-4 form-group']])
										->label(false)
										->textInput(['placeholder' => Yii::t('ad', 'Title'), 'class' => 'form-control field-title', 'id' => false]) ?>
								<?= $form->field($additionInfo, 'addition_fields', ['options' => ['class' => 'col-xs-9 col-md-7 pdL-0 pdR-0 col-sm-5 col-lg-7 form-group']])
										->label(false)
										->textInput(['placeholder' => Yii::t('ad', 'Content'), 'class' => 'form-control field-content', 'id' => false]) ?>
								<div class="col-xs-3 col-md-1 col-lg-1 text-center pdL-0">
									<button class="field-remove" type="button">
										<span class="icon-mv fs-20"><span class="icon-close-icon"></span></span>
									</button>
								</div>
							</div>
						</div>		
						<div class="col-xs-12 form-group">
							<div id="addition-fields"></div>
							<button id="add-field" type="button" class="btn-common"><?= Yii::t('ad', 'Add field') ?></button>		
						</div>
					</div>
				</div>

				<div id="step4" class="hinh-anh item-step section">
					<div class="title-step"><?= Yii::t('ad', 'Upload Photo') ?></div>
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
								'fileuploadadded' => 'function(e, data) {form.upload.fileuploadadded(e, data, this);}',
								'fileuploadcompleted' => 'function(e, data) {form.upload.fileuploadcompleted(e, data, this);}'
							]
						]) ?>
				</div>

				<div id="step5" class="tt-lienhe item-step section">
					<div class="title-step"><?= Yii::t('ad', 'Contact Information') ?></div>
					<div class="row">
						<?= $form->field($contactInfo, 'name', ['options' => ['class' => 'col-xs-12 form-group']])
								->label(false)
								->textInput(['placeholder' => $contactInfo->getAttributeLabel('name')]) ?>
						<?= $form->field($contactInfo, 'mobile', ['options' => ['class' => 'col-xs-12 form-group']])
								->label(false)
								->textInput(['placeholder' => $contactInfo->getAttributeLabel('mobile')]) ?>
						<?= $form->field($contactInfo, 'email', ['options' => ['class' => 'col-xs-12 form-group']])
								->label(false)
								->textInput(['placeholder' => $contactInfo->getAttributeLabel('email')]) ?>
						<?= $form->field($contactInfo, 'address', ['options' => ['class' => 'col-xs-12 form-group']])
								->label(false)
								->textInput(['placeholder' => $contactInfo->getAttributeLabel('address')]) ?>
					</div>
					<div class="text-center pdT-25">
						<button type="button" class="preview btn-common" data-toggle="modal" data-target="#review-listing-post">Preview</button>
						<button type="button" class="btn-post btn-common"><?= $product->isNewRecord ? Yii::t('ad', 'Post') : Yii::t('ad', 'Update') ?></button>
					</div>
				</div>
			<?php $form->end() ?>
		</div>
	</div>
	<div id="success-notify" class="hide">
		<?php if($product->isNewRecord): ?>
			<div style="font-weight: bold; font-size: 20px; color: #514AB3; margin: -8px 0 22px 0;">Đăng tin thành công !</div>
			<div style="font-size: 14px; margin-bottom: 32px;">Tin của bạn đang chờ xét duyệt và sẽ được hiển thị đến người mua sớm nhất.<br /><br />Cảm ơn đã sử dụng dịch vụ của MetVuong</div>
			<a style="margin-right: 22px;" href="<?= Url::current() ?>" style="font-size: 16px; text-transform: uppercase;">Tiếp tục đăng tin</a>
			<a style="display: inline-block;" id="to-detail" href="#" style="font-size: 16px; text-transform: uppercase;">Xem tin vừa đăng</a>
		<?php else: ?>
			<div style="font-weight: bold; font-size: 20px; color: #514AB3; margin: -8px 0 22px 0;">Cập nhật thành công !</div>
			<div style="font-size: 14px; margin-bottom: 32px;">Tin của bạn sẽ chờ xét duyệt lại trước khi hiển thị đến người xem.<br /><br />Cảm ơn đã sử dụng dịch vụ của MetVuong</div>
			<a style="margin-right: 22px;" href="<?=Url::to(['/dashboard/ad', 'username'=> Yii::$app->user->identity->username])?>" style="font-size: 16px; text-transform: uppercase;">Quay trở về dashboard</a>
			<a style="display: inline-block;" id="to-detail" href="#" style="font-size: 16px; text-transform: uppercase;">Xem tin vừa cập nhật</a>
		<?php endif; ?>
	</div>
</div>
	
<div id="review-listing-post" class="detail-listing modal fade popup-common" style="display: none;" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<div class="inner-popup">
					<div class="wrap-swiper">
						<div class="gallery-detail swiper-container">
							<div class="swiper-wrapper"></div>
							<div class="swiper-pagination"></div>
							<div class="swiper-button-next"><span></span></div>
							<div class="swiper-button-prev"><span></span></div>
						</div>	
					</div>
		            
					<div class="infor-listing">
						<div class="address-feat clearfix">
							<p class="infor-by-up"></p>
							<div class="address-listing">
								<p></p>
							</div>
							<p class="id-duan">ID:<span>MV000</span></p>
							<ul class="clearfix list-attr-td">
								<li> <span class="icon-mv"><span class="icon-page-1-copy"></span></span><span class="area-show"></span>m2 </li>
								<li> <span class="icon-mv"><span class="icon-bed-search"></span></span><span class="bed-show"></span> </li>
								<li> <span class="icon-mv"><span class="icon-bathroom-search-copy-2"></span></span><span class="toilet-show"></span> </li>
							</ul>
							<span class="edit-listing">
		                    	<span class="icon-mv fs-20 color-cd"><span class="icon-edit-copy-4"></span></span>
		                    </span>
						</div>
						<div class="posi_relative">
							<p class="price-td">
								<span><?= Yii::t('ad', 'Price') ?></span>
								<span class="price-show"></span>
							</p>
							<span class="edit-listing">
		                    	<span class="icon-mv fs-20 color-cd"><span class="icon-edit-copy-4"></span></span>
		                    </span>
						</div>
					</div>
					<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
				        <div class="panel panel-default">
				            <div class="panel-heading" role="tab" id="headingOne">
				                <h4 class="panel-title">
				                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
				                        <?= Yii::t('ad', 'Content') ?>
				                        <span class="edit-listing">
					                    	<span class="icon-mv fs-20 color-cd"><span class="icon-edit-copy-4"></span></span>
					                    </span>
				                        <span class="icon"></span>
				                    </a>
				                </h4>
				            </div>
				            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
				                <div class="panel-body" name="about" placeholder="Vui lòng chia sẻ tiểu sử">
				                    <p class="content-show"></p>
				                </div>
				            </div>
				        </div>
				        <div class="panel panel-default">
				            <div class="panel-heading" role="tab" id="headingTwo">
				                <h4 class="panel-title">
				                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
				                        <?= Yii::t('ad', 'Detail Information') ?>
				                        <span class="edit-listing">
					                    	<span class="icon-mv fs-20 color-cd"><span class="icon-edit-copy-4"></span></span>
					                    </span>
				                        <span class="icon"></span>
				                    </a>
				                </h4>
				            </div>
				            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
				                <div class="panel-body" name="activity">
				                	<ul class="clearfix list-tienich-detail">
										<li class="project-item"><strong><?= Yii::t('ad', 'Project') ?>:</strong> <span class="project-show"></span></li>
										<li class="facade-width-item"><strong><?= Yii::t('ad', 'Facade') ?>:</strong> <span class="facade-width-show"></span>m</li>
										<li class="land-width-item"><strong><?= Yii::t('ad', 'Entry width') ?>:</strong> <span class="land-width-show"></span>m</li>
										<li class="floor-no-item"><strong><span id="floor-no-text"></span>:</strong> <span class="floor-no-show"></span></li>
										<li class="home-di-item"><strong><?= Yii::t('ad', 'House direction') ?>:</strong> <span class="home-di-show"></span></li>
										<li class="facade-di-item"><strong><?= Yii::t('ad', 'Balcony direction') ?>:</strong> <span class="facade-di-show"></span></li>
										<li class="interior-item"><strong><?= Yii::t('ad', 'Furniture') ?>:</strong> <span class="interior-show"></span></li>
				                	</ul>
				                </div>
				            </div>
				        </div>
				        <div id="facilities-section" class="panel panel-default">
				            <div class="panel-heading" role="tab" id="headingFour">
				                <h4 class="panel-title">
				                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
				                        <?= Yii::t('ad', 'Facilities') ?>
				                        <span class="icon"></span>
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
				                        <?= Yii::t('ad', 'Contact') ?>
				                        <span class="edit-listing">
					                    	<span class="icon-mv fs-20 color-cd"><span class="icon-edit-copy-4"></span></span>
					                    </span>
				                        <span class="icon"></span>
				                    </a>
				                </h4>
				            </div>
				            <div id="collapseEght" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingSeven">
				                <div class="panel-body text-center">
				            	    <div class="infor-agent clearfix">
							            <?php if(!empty($owner->username)) { ?>
										<a href="#" class="wrap-img">
							                <img src="<?= $avatar ?>" alt="" /></a>
							            <?php } else { ?>
							                <a class="wrap-img" href=""><img src="<?= $avatar ?>" alt="" /></a>
							            <?php } ?>
							            <div class="img-agent">
								            <a href="" class="name-agent"></a>
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
											<div class="item-agent icon-phone-item">
												<div>
													<span class="icon icon-phone"></span>
												</div>
												<a class="phone-show" href="tel:"></a>
											</div>
											<div class="item-agent icon-email-item">
												<div>
													<span class="icon icon-email"></span>
												</div>
												<span class="email-show"></span>
											</div>
											<div class="item-agent address-icon-item">
												<div>
													<span class="icon address-icon"></span>
												</div>
												<span class="address-show"></span>
											</div>
										</div>
									</div>
									<div class="text-center">
										<a href="#" data-toggle="modal" data-target="#popup-email" class="email-btn btn-common btn-small">Email</a>
										<?php if(!Yii::$app->user->isGuest && !empty($owner->username) && !$owner->isMe()) { ?>
											<a href="<?=Url::to(['/chat/with', 'username'=>$owner->username])?>" id="" class="chat-btn btn-common btn-small">Chat</a>
										<?php }?>
									</div>
				                </div>
				            </div>

				        </div>
				    </div>
					<div class="text-center">
						<button type="button" class="back-form btn-common"><?= Yii::t('ad', 'Back') ?></button>
						<button type="button" class="btn-post btn-common"><?= $product->isNewRecord ? Yii::t('ad', 'Post') : Yii::t('ad', 'Update') ?></button>
					</div>
		        </div>
			</div>
		</div>
	</div>
</div>

<div class="fixed-prev-next">
	<div>
		<a href="#" id="back-screen"><span class="icon arrowRight-1"></span><?= Yii::t('ad', 'Back') ?></a>
		<a href="#" id="next-screen" class=""><span class="icon arrowLeft-1"></span><?= Yii::t('ad', 'Next') ?></a>		
	</div>
</div>