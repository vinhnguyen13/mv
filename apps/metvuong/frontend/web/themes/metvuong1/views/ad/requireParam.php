<?php
	use yii\widgets\ActiveForm;
	use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;
use vsoft\ad\models\AdCity;
use yii\helpers\ArrayHelper;
use vsoft\ad\models\AdDistrict;
use vsoft\ad\models\AdCategory;
use vsoft\ad\models\AdProduct;

	$this->registerCssFile(Yii::$app->view->theme->baseUrl . '/resources/css/select2.css', ['depends' => ['yii\bootstrap\BootstrapAsset']]);
	$this->registerCss('#s2id_category span {text-transform: capitalize;} .preview canvas {vertical-align: middle;} .price-editable{position: relative;} .price-editable .price-format {display: inline; position: absolute; top: 10px; left: 100%; white-space: nowrap; padding-left: 9px;} .preview img {width: 120px; height: 80px;} .select2-drop-active {border-color: #ccc;} .select2-drop {margin-top: -2px;} .editable select.select2 {padding: 0 20px;} .editable {cursor: pointer; max-width: 100%; min-width: 160px;} .editable:not(.editing):hover {background: rgba(0, 0, 0, 0.15);border-radius: 4px;padding: 3px;}');
	$this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/select2.min.js', ['position' => View::POS_END]);
	$this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/require-post-param.js', ['position' => View::POS_END]);
	$this->registerJs('var TYPE_BOTH = ' . AdCategory::APPLY_TO_TYPE_BOTH . ';', View::POS_BEGIN);
	
	if($cityId) {
		$cities = ArrayHelper::map(AdCity::findAll(['`status`' => 1]), 'id', 'name');
		$districts = ['' => ''] + ArrayHelper::map(AdDistrict::findAll(['`status`' => 1, '`city_id`' => $cityId]), 'id', 'name');
	} else {
		$cities = ['' => ''];
		$districts = ['' => ''];
	}
	
	$categories = AdCategory::findAll(['`status`' => 1]);
	$catDropDown = ArrayHelper::map($categories, 'id', 'name');
	$catDataType = ArrayHelper::map($categories, 'id', function($cat){ return ['data-type' => $cat->apply_to_type]; });
?>
<div class="wrap-dangtin">
	<div class="title-frm" clearfix>Đăng tin</div>
	<div class="wrap-step-post clearfix">
		<?php $form = ActiveForm::begin ( [
				'action' => Url::to(['/ad/post']),
				'id' => 'frm-post-tin',
				'method' => 'get',
				'options' => [ 
					'spellcheck' => 'false',
					'class' => 'form-horizontal',
				]]); ?>
			<div class="fieldset clearfix" style="display: block;">
				<div class="form-group">
					<label for="" class="col-sm-3 control-label">Hình thức</label>
					<div class="col-sm-9 group-item-frm">
						<?= Html::dropDownList('type', $type, AdProduct::getAdTypes(), ['class' => 'form-control', 'id' => 'type']) ?>
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-3 control-label">Tỉnh/Thành Phố</label>
					<div class="col-sm-9 group-item-frm">
						<?= Html::dropDownList('city', Yii::$app->request->get('city'), $cities, ['class' => 'select2', 'id' => 'city', 'data-placeholder' => 'Tỉnh/Thành Phố']) ?>
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-3 control-label">Quận/Huyện</label>
					<div class="col-sm-9 group-item-frm">
						<?= Html::dropDownList('district', Yii::$app->request->get('district'), $districts, ['class' => 'select2', 'id' => 'district', 'data-placeholder' => 'Quận/Huyện']) ?>
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-3 control-label">Loại BĐS</label>
					<div class="col-sm-9 group-item-frm">
						<?= Html::dropDownList('category', $categoryId, $catDropDown, ['options' => $catDataType, 'class' => 'form-control', 'id' => 'category', 'data-placeholder' => 'Loại BĐS']) ?>
					</div>
				</div>
				<button id="preview" type="submit" class="btn btn-primary btn-common mgT-15 pull-right" data-toggle="modal" data-target="#detail-listing">Tiếp theo</button>
			</div>
		<?php $form->end() ?>
	</div>
</div>