<?php
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use vsoft\ad\models\AdProduct;
use yii\helpers\Html;
use common\widgets\FileUploadUI;
use yii\helpers\ArrayHelper;
use vsoft\ad\models\AdProductAdditionInfo;
use vsoft\ad\models\AdContactInfo;
$this->title = Yii::t ( 'express', 'We offer exeptional amenities and renowned white - glove services' );

$this->registerCssFile(Yii::$app->view->theme->baseUrl . '/resources/css/select2.css', ['depends' => ['yii\bootstrap\BootstrapAsset']]);

Yii::$app->getView ()->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/select2.min.js', ['position' => View::POS_END]);
Yii::$app->getView ()->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/gmap.js', ['position' => View::POS_END]);
$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyASTv_J_7DuXskr5SaCZ_7RVEw7oBKiHi4&callback=apiLoaded', ['depends' => ['yii\web\YiiAsset'], 'async' => true, 'defer' => true]);
Yii::$app->getView ()->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/dang-tin.js', ['position' => View::POS_END]);

$type = [ 
	AdProduct::TYPE_FOR_SELL => Yii::t ( 'post', 'Nhà đất bán' ),
	AdProduct::TYPE_FOR_RENT => Yii::t ( 'post', 'Nhà đất cho thuê' ) 
];
?>
<div class="wrap-dangtin">
	<div class="title-frm" clearfix>Đăng tin</div>
	<div class="wrap-step-post clearfix">
		<?php $form = ActiveForm::begin ( [ 
				'id' => 'frm-post-tin',
				'options' => [ 
					'spellcheck' => 'false',
					'class' => 'form-horizontal'
				]]); ?>
			<ul id="progressbar" class="clearfix">
				<li class="active">
					<div>
						<a href="#">1</a> <span class="line-process"></span>
						<div>Step 1</div>
					</div>
				</li>
				<li class="step-center">
					<div>
						<a href="#">2</a> <span class="line-process"></span>
						<div>Step 2</div>
					</div>
				</li>
				<li class="text-left">
					<div>
						<a href="#">3</a> <span class="line-process"></span>
						<div>Hoàn thành</div>
					</div>
				</li>
			</ul>
			<div class="fieldset clearfix" style="display: block;">
				<?= Html::activeHiddenInput($model, 'city_id') ?>
				<?= Html::activeHiddenInput($model, 'district_id') ?>
				<?= Html::activeHiddenInput($model, 'category_id') ?>
				<?= $form->field($model, 'type', [
						'labelOptions' => ['class' => 'col-sm-3 control-label'],
						'inputOptions' => ['class' => 'col-xs-6 form-control mgB-0'],
						'template' => '{label}<div class="col-sm-9 group-item-frm"><div class="inline-group pdR-0">{input}</div></div>{hint}{error}']
					)->dropDownList($type) ?>
				<div class="form-group">
					<label for="" class="col-sm-3 control-label">Địa chỉ *</label>
					<div class="col-sm-9 group-item-frm">
						<div class="form-group">
							<?= Html::activeDropDownList($model, 'ward_id', [], ['class' => 'select2', 'prompt' => 'Phường/Xã'])?>
							<div class="help-block" style="display: none;"></div>
						</div>
						<div class="form-group">
							<?= Html::activeDropDownList($model, 'street_id', [], ['class' => 'select2', 'prompt' => 'Đường'])?>
							<div class="help-block" style="display: none;"></div>
						</div>
						<div class="mgB-0">
							<?= Html::activeTextInput($model, 'home_no', ['class' => 'form-control', 'placeholder' => $model->getAttributeLabel('home_no')]) ?>
							<div class="help-block" style="display: none;"></div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-3 control-label">Thuộc dự án</label>
					<div class="col-sm-9 group-item-frm">
						<?= Html::activeDropDownList($model, 'project_building_id', [], ['class' => 'form-control mgB-0', 'prompt' => 'Dự án'])?>
					</div>
				</div>
				<div class="form-group text-inline">
					<label for="" class="col-sm-3 control-label">Diện tích *</label>
					<div class="col-sm-9">
						<div class="inline-group col-xs-6">
							<?= Html::activeTextInput($model, 'area', ['class' => 'form-control number-only', 'data-float' => '1']) ?>
						<div class="help-block" style="display: none;"></div>
						</div>
						<div class="inline-group col-xs-6 pdR-0">
							<span>
								<span style="display: inline;" id="area-format"></span>
								m<sup>2</sup>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-3 control-label">Giá *</label>
					<div class="col-sm-9 group-item-frm">
						<div class="inline-group col-xs-6 mgB-10">
							<?= Html::activeTextInput($model, 'price', ['class' => 'form-control number-only']) ?>
							<div class="help-block" style="display: none;"></div>
						</div>
						<div class="inline-group col-xs-12">
							<span style="display: inline;" id="price-format"></span>
						</div>
					</div>
				</div>
				<button type="button"
					class="btn btn-primary btn-common mgT-15 next action-button pull-right">
					Tiếp theo<em class="fa fa-chevron-right"></em>
				</button>
			</div>
			<div class="fieldset clearfix" style="display: none;">
				<div class="form-group">
					<?= Html::activeTextarea($model, 'content', ['class' => 'form-control', 'placeholder' => $model->getAttributeLabel('content')]) ?>
					<div class="help-block" style="display: none;"></div>
				</div>
				<?= FileUploadUI::widget([
					'name' => 'images',
					'id' => 'images',
					'url' => Url::to(['upload']),
					'fieldOptions' => ['values' => implode(',', ArrayHelper::getColumn($model->adImages, 'file_name'))]
				]) ?>
				<div class="form-group">
					<div class="title-sub-frm">Thông tin mở rộng</div>
				</div>
				<div class="form-group">
					<div class="row row-group-inline">
						<div class="col-xs-3">
							<?= Html::activeTextInput($adProductAdditionInfo, 'facade_width', ['class' => 'form-control number-only', 'data-float' => '1', 'placeholder' => 'Mặt tiền (m)']) ?>
						</div>
						<div class="col-xs-3">
							<?= Html::activeTextInput($adProductAdditionInfo, 'land_width', ['class' => 'form-control number-only', 'data-float' => '1', 'placeholder' => 'Đường vào (m)']) ?>
						</div>
						<div class="col-xs-3">
							<?= Html::activeDropDownList($adProductAdditionInfo, 'home_direction', AdProductAdditionInfo::directionList(), ['class' => 'form-control', 'prompt' => 'Hướng nhà'])?>
						</div>
						<div class="col-xs-3">
							
							<?= Html::activeDropDownList($adProductAdditionInfo, 'facade_direction', AdProductAdditionInfo::directionList(), ['class' => 'form-control', 'prompt' => 'Hướng ban công'])?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row row-group-inline">
						<div class="col-xs-3">
							<?= Html::activeTextInput($adProductAdditionInfo, 'floor_no', ['class' => 'form-control number-only', 'placeholder' => 'Số tầng']) ?>
						</div>
						<div class="col-xs-3">
							<?= Html::activeTextInput($adProductAdditionInfo, 'room_no', ['class' => 'form-control number-only', 'placeholder' => 'Số phòng ngủ']) ?>
						</div>
						<div class="col-xs-3">
							<?= Html::activeTextInput($adProductAdditionInfo, 'toilet_no', ['class' => 'form-control number-only', 'placeholder' => 'Số toilet']) ?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?= Html::activeTextarea($adProductAdditionInfo, 'interior', ['class' => 'form-control', 'placeholder' => 'Nội thất']) ?>
				</div>
				<div class="clearfix"></div>
				<div class="form-group">
					<div class="title-sub-frm">Thông tin liện hệ</div>
				</div>
				<div class="form-group">
					<div class="row row-group-inline">
						<div class="col-xs-3">
							<?= Html::activeTextInput($adContactInfo, 'name', ['class' => 'form-control', 'placeholder' => 'Tên liên hệ']) ?>
						</div>
						<div class="col-xs-3">
							<?= Html::activeTextInput($adContactInfo, 'phone', ['class' => 'form-control', 'placeholder' => 'Điện thoại']) ?>
						</div>
						<div class="col-xs-3">
							<?= Html::activeTextInput($adContactInfo, 'mobile', ['class' => 'form-control', 'placeholder' => 'Di động *']) ?>
							<div class="help-block" style="display: none;"></div>
						</div>
						<div class="col-xs-3">
							<?= Html::activeTextInput($adContactInfo, 'email', ['class' => 'form-control', 'placeholder' => 'Email']) ?>
							<div class="help-block" style="display: none;"></div>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="form-group">
					<?= Html::activeTextInput($adContactInfo, 'address', ['class' => 'form-control', 'placeholder' => 'Địa chỉ']) ?>
				</div>
				<div class="form-group">
					<div class="title-sub-frm">Bản đồ</div>
				</div>
				<div class="form-group" style="display: none;">
					<p>
						Để tăng độ tin cậy và tin rao được nhiều người quan tâm hơn, hãy
						sửa vị trí tin rao của bạn trên bản đồ bằng cách kéo icon<em
							class="fa fa-map-marker"></em>tới đúng vị trí của tin rao.
					</p>
					<div id="map" style="height: 450px;"></div>
					<?= Html::activeHiddenInput($model, 'lat') ?>
					<?= Html::activeHiddenInput($model, 'lng') ?>
				</div>
				<button type="button"
					class="btn btn-primary btn-common mgT-15 next action-button pull-right" data-ajax-post="true">
					Tiếp theo<em class="fa fa-chevron-right"></em>
				</button>
				<button type="button"
					class="btn btn-primary btn-common mgT-15 previous action-button pull-right">
					<em class="fa fa-chevron-left"></em>Quay lại
				</button>
			</div>
			<div class="fieldset clearfix finish-post" style="display: none;">
				text
				<button type="button"
					class="btn btn-primary btn-common mgT-15 next action-button pull-right">Đăng
					bài</button>
			</div>
		<?php $form->end()?>
	</div>
</div>

<!-- <div class="social-share">
	<ul>
		<li><a href="#"><em class="fa fa-facebook"></em></a></li>
		<li><a href="#"><em class="fa fa-twitter"></em></a></li>
		<li><a href="#"><em class="fa fa-instagram"></em></a></li>
		<li><a href="#"><em class="fa fa-google-plus"></em></a></li>
		<li><a href="#"><em class="fa fa-youtube-play"></em></a></li>
		<li><a href="#"><em class="fa fa-pinterest"></em></a></li>
		<li><a href="#"><em class="fa fa-linkedin"></em></a></li>
	</ul>
</div> -->
