<?php

use yii\helpers\Html;
use funson86\cms\Module;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use funson86\cms\models\CmsCatalog;
use common\widgets\CKEditor;
use yii\helpers\Url;
use common\widgets\FileUploadUI;
use yii\web\View;

$this->registerJsFile(Yii::getAlias('@web') . '/js/building-project.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerJsFile(Yii::getAlias('@web') . '/js/jquery-ui.min.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerCssFile(Yii::getAlias('@web') . '/css/building-project.css', ['depends' => ['yii\bootstrap\BootstrapAsset']]);
$this->registerCssFile(Yii::getAlias('@web') . '/css/jquery-ui.css', ['depends' => ['yii\bootstrap\BootstrapAsset']]);

$areaTypes = [
	'bpfApartmentArea' => 'Khu căn hộ',
	'bpfCommercialArea' => 'Khu thương mại',
	'bpfTownhouseArea' => 'Khu nhà phố',
	'bpfOffice' => 'Khu Office - Officetel'
];

$month = [];
for($i = 1; $i <= 12; $i++) {
	$month[$i] = 'Tháng ' + $i;
}

$year = [];
for($i = 1998; $i <= 2020; $i++) {
	$year[$i] = $i;
}
?>

<div id="project-building-form" class="cms-show-form">
    <?php $form = ActiveForm::begin([
		'id' => 'bp-form',
    	'enableClientValidation' => false,
    	'enableAjaxValidation' => false
    ]); ?>
    <div class="side-bar">
    	<ul class="bp-contents">
		    <li class="show-content"><a href="#">Tổng quan dự án</a></li>
		    <li class="show-content"><a href="#">Bản đồ vị trí</a></li>
		    <li class="show-content"><a href="#">Tiện ích</a></li>
		    <li class="show-content"><a href="#">Phim 3D dự án</a></li>
		    <li class="show-content"><a href="#">Tiến độ xây dựng</a></li>
		</ul>
		<div class="seperator"></div>
		<ul class="bp-contents">
		    <?php foreach($areaTypes as $f => $areaType): ?>
		    <li class="bp-subcontents">
		    	<a href="#"><?= $areaType ?></a>
		    	<ul>
		    		<li class="show-content"><a href="#">Mặt bằng</a></li>
			    	<li class="show-content"><a href="#">Giá bán & thanh toán</a></li>
			    	<li class="show-content"><a href="#">Chương trình bán hàng</a></li>
			    	<li class="show-content"><a href="#">Tài liệu bán hàng</a></li>
		    	</ul>
		    </li>
		    <?php endforeach; ?>
		</ul>
    </div>
    <div class="main-content">
		<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Module::t('cms', 'Lưu lại') : Module::t('cms', 'Update'), ['class' => 'btn btn-primary btn-block']) ?>
	    </div>
    	<div style="min-height: 400px;">
    		<ul class="bp-fields">
			    <li>
			    	<?= $form->field($model, 'title') ?>
			    	<?= $form->field($model, 'bpLogo')->widget(FileUploadUI::className(), [
						'url' => Url::to('/express/upload/building-project-image'),
						'clientOptions' => ['maxNumberOfFiles' => 1] ]) ?>
					<?= $form->field($model, 'bpGallery')->widget(FileUploadUI::className(), ['url' => Url::to('/express/upload/building-project-image')]) ?>
			    	<?= $form->field($model, 'bpLocation') ?>
			    	<?= $form->field($model, 'bpType') ?>
			    	<?= $form->field($model, 'bpAcreage') ?>
			    	<?= $form->field($model, 'bpApartmentNo') ?>
			    	<?= $form->field($model, 'bpFloorNo') ?>
			    	<?= $form->field($model, 'bpFacilities') ?>
			    </li>
			    <li>
			    	<?= $form->field($model, 'bpMapLocation')->widget(FileUploadUI::className(), ['url' => Url::to('/express/upload/building-project-image')]) ?>
			    </li>
			    <li>
			    	<?= $form->field($model, 'bpFacilitiesDetail')->widget(FileUploadUI::className(), ['url' => Url::to('/express/upload/building-project-image')]) ?>
			    </li>
			    <li>
			    	<?= $form->field($model, 'bpVideo') ?>
			    </li>
			    <li>
			    	<div id="progress-list">
			    		<?php
			    			$countBpp = 0;
			    			if($model->bpProgress):
			    				$bpProgress = json_decode($model->bpProgress);
			    				$countBpp = count($bpProgress);
			    				foreach ($bpProgress as $k => $bpp):
			    		?>
			    		<div class="panel panel-default">
				    		<div class="panel-body">
				    			<i class="glyphicon glyphicon-remove"></i>
				    			<div class="form-group">
									<label class="control-label" for="buildingproject-bpvideo">Tháng / Năm</label>
									<div>
										<?= Html::dropDownList('BuildingProject[bpProgress][' . $k . '][month]', $bpp->month,  $month, ['class' => 'form-control', 'style' => 'width: auto; display: inline-block;']) ?>
										<?= Html::dropDownList('BuildingProject[bpProgress][' . $k . '][year]', $bpp->year,  $year, ['class' => 'form-control', 'style' => 'width: auto; display: inline-block;']) ?>
									</div>
									<div class="help-block"></div>
								</div>
								<div class="form-group">
									<label class="control-label" for="buildingproject-bpvideo">Ảnh</label>
									<?= FileUploadUI::widget([
											'name' => 'BuildingProject[bpProgress][' . $k . '][images]',
											'url' => Url::to('/express/upload/building-project-image'),
											'fieldOptions' => ['values' => $bpp->images]
										]) ?>
									<div class="help-block"></div>
								</div>
				    		</div>
				    	</div>
			    		<?php endforeach; ?>
			    		<?php endif; ?>
			    	</div>
			    	<?= Html::button('<i class="glyphicon glyphicon-plus"></i><span style="margin-left: 4px;">Thêm</span>', ['class' => 'btn', 'id' => 'btn-add-progress', 'data-count' => $countBpp]) ?>
			    </li>
			</ul>
    	</div>
		<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Module::t('cms', 'Lưu lại') : Module::t('cms', 'Update'), ['style' => 'margin-top: 22px;', 'class' => 'btn btn-primary btn-block']) ?>
	    </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>