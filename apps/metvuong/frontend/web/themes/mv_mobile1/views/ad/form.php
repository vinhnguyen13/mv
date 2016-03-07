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

	$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/string-helper.js', ['position' => View::POS_END]);
	$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/post-listing.js', ['position' => View::POS_END]);
	$this->registerCss("#project-info {position: relative;} #project-info .loading-proccess span {border-top-color: #8C7777; border-left-color: #8C7777;} #project-info .loading-proccess {display: none;} #project-info.loading .loading-proccess {display: block;} #project-info.loading .result {display: none;}");

	$listRoom = [];
	for($i = 1; $i <= 10; $i++) {
		$listRoom[$i] = $i;
	}
?>
<div class="title-top">Đăng tin</div>
<div class="post-listing">
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
		<div class="step-link">
			<ul class="clearfix">
				<li><a data-active-section="tt-chung" class="active" href="#">1</a><span class="icon arrowLeft"></span></li>
				<li><a data-active-section="tt-chitiet" href="#">2</a><span class="icon arrowLeft"></span></li>
				<li><a data-active-section="hinh-anh" href="#">3</a><span class="icon arrowLeft"></span></li>
				<li><a data-active-section="tien-ich" href="#">4</a><span class="icon arrowLeft"></span></li>
			</ul>
		</div>
		<div id="step1" class="section select-type hide item-step">
			<p class="text-center step-txt">4 bước dễ dàng</p>
		
			<ul class="clearfix step-check">
				<li>
					<a class="frm-radio" href="#">
						<span class="radio-ui icon-postlisting icon-ban-post">
							<?php $typeName = Html::getInputName($product, 'type') ?>
							<?= Html::radio($typeName, ($product->type == AdProduct::TYPE_FOR_SELL || !$product->type), ['value' => AdProduct::TYPE_FOR_SELL]) ?>
						</span>
						<span><?= Yii::t('ad', 'Bán') ?></span>
					</a>
				</li>
				<li>
					<a class="frm-radio" href="#">
						<span class="radio-ui icon-postlisting icon-chothue">
							<?= Html::radio($typeName, ($product->type == AdProduct::TYPE_FOR_RENT), ['value' => AdProduct::TYPE_FOR_RENT]) ?>
						</span>
						<span><?= Yii::t('ad', 'Cho thuê') ?></span>
					</a>
				</li>
				<li>
					<a class="frm-radio" href="#">
						<?php $ownerName = Html::getInputName($product, 'owner') ?>
						<span class="radio-ui icon-postlisting icon-chunha">
							<?= Html::radio($ownerName, ($product->owner == AdProduct::OWNER_HOST || !$product->owner), ['value' => AdProduct::OWNER_HOST, 'id' => 'owner-host']) ?>
						</span>
						<span><?= Yii::t('ad', 'Chủ nhà') ?></span>
					</a>
				</li>
				<li>
					<a class="frm-radio" href="#">
						<span class="radio-ui icon-postlisting icon-mogioi">
							<?= Html::radio($ownerName, ($product->owner == AdProduct::OWNER_AGENT), ['value' => AdProduct::OWNER_AGENT]) ?>
						</span>
						<span><?= Yii::t('ad', 'Môi giới') ?></span>
					</a>
				</li>
			</ul>
			
			<?php
				$categories = AdCategory::find()->where('`status` = 1')->all();
				$categoriesDropdown = ArrayHelper::map($categories, 'id', 'name');
				$categoriesOptions = ArrayHelper::map($categories, 'id', function($category){ return ['data-type' => $category->apply_to_type, 'data-limit' => $category->limit_area]; });
					
				echo $form->field($product, 'category_id')
						 ->label(false)
						 ->dropDownList($categoriesDropdown, ['prompt' => $product->getAttributeLabel('category_id'), 'options' => $categoriesOptions])
			?>
		</div>

		<div id="step2" class="tt-chung item-step section hide">
			<div class="title-step">Thông tin chung</div>
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
						 ->dropDownList(AdDistrict::getListByCity($product->city_id), ['prompt' => $product->getAttributeLabel('district_id')]) ?>
				
				<?= $form->field($product, 'ward_id', ['options' => ['class' => 'col-xs-12 form-group']])
					->label(false)
					->dropDownList(AdWard::getListByDistrict($product->district_id), ['prompt' => $product->getAttributeLabel('ward_id')]) ?>
					
				<?= $form->field($product, 'street_id', ['options' => ['class' => 'col-xs-12 form-group']])
					->label(false)
					->dropDownList(AdStreet::getListByDistrict($product->district_id), ['prompt' => $product->getAttributeLabel('street_id')]) ?>
				
				<?= $form->field($product, 'home_no', ['options' => ['class' => 'col-xs-12 form-group']])
						->label(false)
						->textInput(['placeholder' => $product->getAttributeLabel('home_no')]) ?>
						
				<?= $form->field($product, 'show_home_no', ['options' => ['class' => 'col-xs-12 form-group toggle-num-home']])->label(false)->checkbox(['data-set' => $product->isNewRecord ? '0' : '1']) ?>
				
				<div class="col-xs-12 form-group">
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
			</div>
		</div>

		<div id="step3" class="tt-chitiet item-step section hide">
			<div class="title-step">Thông tin chi tiết</div>
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
			</div>
		</div>

		<div id="step4" class="hinh-anh item-step section hide">
			<?= FileUpload::widget([
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
						'fileuploadadded' => 'function(e, data) {upload.fileuploadadded(e, data, this);}',
						'fileuploadcompleted' => 'function(e, data) {upload.fileuploadcompleted(e, data, this);}'
					]
				]) ?>
		</div>

		<div class="tt-lienhe item-step section hide">
			<div class="title-step">Thông tin liên hệ</div>
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
			</div>
			<div class="text-center">
				<button type="button" class="preview">Preview</button>
				<button type="button" class="btn-post">Đăng tin</button>
			</div>
		</div>
	<?php $form->end() ?>
	<div id="popup-share-social" class="popup-common hide-popup">
	    <div class="inner-popup">
            <div class="wrap-body-popup">
                <div style="font-weight: bold; font-size: 20px; color: #514AB3; margin: -8px 0 22px 0;">Đăng tin thành công !</div>
				<div style="font-size: 14px; margin-bottom: 32px;">Tin của bạn đang chờ xét duyệt và sẽ được hiển thị đến người mua sớm nhất.<br /><br />Cảm ơn đã sử dụng dịch vụ của MetVuong</div>
				<a href="<?= Url::current() ?>" style="font-size: 16px; text-transform: uppercase;">Tiếp tục đăng tin</a>
            </div>
        </div>
	</div>
</div>

<div class="fixed-prev-next">
	<a href="#" id="back-screen"><span class="icon arrowRight-1"></span>Trở lại</a>
	<a href="#" id="next-screen" class=""><span class="icon arrowLeft-1"></span>Tiếp theo</a>
</div>