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
use vsoft\buildingProject\models\BuildingProject;

$this->registerJsFile(Yii::getAlias('@web') . '/js/jquery.maphilight.min.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerJsFile(Yii::getAlias('@web') . '/js/building-project.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerJsFile(Yii::getAlias('@web') . '/js/gmap.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyASTv_J_7DuXskr5SaCZ_7RVEw7oBKiHi4&callback=initMap', ['depends' => ['yii\web\YiiAsset']]);
$this->registerCssFile(Yii::getAlias('@web') . '/css/building-project.css', ['depends' => ['yii\bootstrap\BootstrapAsset']]);
$this->registerJs('buildingProject.initForm();', View::POS_READY, 'initform');

$this->registerJsFile(Yii::getAlias('@web') . '/js/jquery.colorbox-min.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerCssFile(Yii::getAlias('@web') . '/css/colorbox.css', ['depends' => ['yii\bootstrap\BootstrapAsset']]);

$areaTypes = BuildingProject::getAreaTypes();

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
    	'options' => ['spellcheck' => 'false'],
    	'enableClientValidation' => false,
    	'enableAjaxValidation' => false
    ]); ?>
    <div class="side-bar">
    	<ul class="bp-contents">
		    <li class="show-content active"><a href="#">Tổng quan dự án</a></li>
		    <li class="show-content"><a href="#">Bản đồ vị trí</a></li>
		    <li class="show-content"><a href="#">Tiện ích</a></li>
		    <li class="show-content"><a href="#">Phim 3D dự án</a></li>
		    <li class="show-content"><a href="#">Tiến độ xây dựng</a></li>
		    <li class="show-content"><a href="#">SEO</a></li>
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
			<?= $model->isNewRecord ? '' : Html::hiddenInput('deleteLater', '', ['id' => 'delete-later']) ?>
	        <?= Html::submitButton($model->isNewRecord ? Module::t('cms', 'Lưu lại') : Module::t('cms', 'Update'), ['class' => 'btn btn-primary btn-block']) ?>
	    </div>
    	<div style="min-height: 400px;">
    		<ul class="bp-fields">
			    <li class="active">
			    	<?= $form->field($model, 'title') ?>
			    	<?= $form->field($model, 'bpLogo')->widget(FileUploadUI::className(), [
						'url' => Url::to('/express/upload/image'),
						'clientOptions' => ['maxNumberOfFiles' => 1] ]) ?>
					<?= $form->field($model, 'bpGallery')->widget(FileUploadUI::className(), ['url' => Url::to('/express/upload/image')]) ?>
			    	<div class="form-group">
						<label class="control-label"><?= $model->getAttributeLabel('bpLocation') ?></label>
						<div>
							<?= Html::activeTextInput($model, 'bpLocation', ['class' => 'form-control']) ?>
						</div>
						<div class="help-block"></div>
						<div id="map" style="height: 320px; width: 100%"></div>
						<?= Html::activeHiddenInput($model, 'bpLat') ?>
						<?= Html::activeHiddenInput($model, 'bpLng') ?>
					</div>
			    	<?= $form->field($model, 'bpType') ?>
			    	<?= $form->field($model, 'bpAcreage') ?>
			    	<?= $form->field($model, 'bpAcreageCenter')->textArea() ?>
			    	<?= $form->field($model, 'bpApartmentNo') ?>
			    	<?= $form->field($model, 'bpFloorNo') ?>
			    	<?= $form->field($model, 'bpStartTime') ?>
			    	<?= $form->field($model, 'bpEstimateFinished') ?>
			    	<?= $form->field($model, 'bpOwnerType') ?>
			    	<?= $form->field($model, 'bpFacilities') ?>
			    	<?= $form->field($model, 'bpHotline')->textArea()->hint('Mổi số điện thoại trên 1 dòng') ?>
			    	<?= $form->field($model, 'bpWebsite') ?>
			    </li>
			    <li>
			    	<?= $form->field($model, 'bpMapLocationDes')->widget(CKEditor::className(), [
			    		'editorOptions' => [
							'preset' => 'basic',
							'inline' => false,
							'removePlugins' => 'image',
							'height' => 150,
							'resize_enabled' => true
			    		]
			    	]) ?>
			    	<?= $form->field($model, 'bpMapLocation')->widget(FileUploadUI::className(), ['url' => Url::to('/express/upload/image')]) ?>
			    </li>
			    <li>
			    	<?= $form->field($model, 'bpFacilitiesDetailDes')->widget(CKEditor::className(), [
			    		'editorOptions' => [
							'preset' => 'basic',
							'inline' => false,
							'removePlugins' => 'image',
							'height' => 150,
							'resize_enabled' => true
			    		]
			    	]) ?>
			    	
			    	<?= $form->field($model, 'bpFacilitiesDetail')->widget(FileUploadUI::className(), ['url' => Url::to('/express/upload/image')]) ?>
			    </li>
			    <li>
			    	<?= $form->field($model, 'bpVideo')->textArea(['style' => 'height: 120px;'])->hint('Nhập đường dẫn youtube, mổi video trên 1 dòng.<br />Ví dụ:<br />https://www.youtube.com/watch?v=BNJIcJyN3o4<br />https://www.youtube.com/watch?v=G1Xi8zDD37I') ?>
			    </li>
			    <li>
			    	<div id="progress-list" class="dynamic-list">
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
											'url' => Url::to('/express/upload/image'),
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
			    <li>
			    	<?= $form->field($model, 'seo_title') ?>
			    	<?= $form->field($model, 'seo_keywords') ?>
			    	<?= $form->field($model, 'seo_description') ?>
			    </li>
			    <?php foreach($areaTypes as $name => $areaType): $area = json_decode($model->$name); ?>
			    <li>
			    	<?= Html::hiddenInput('BuildingProject[' . $name . '][floorPlan]', '') ?>
			    	<div class="floor-plan-list dynamic-list">
			    		<?php
			    			$count = count($area->floorPlan);
			    			foreach($area->floorPlan as $k => $bpa):
			    		?>
				    	<div class="panel panel-default">
				    		<div class="panel-body">
				    			<i class="glyphicon glyphicon-remove"></i>
				    			<div class="form-group">
									<label class="control-label" for="buildingproject-bpvideo">Tầng</label>
									<?= Html::textInput('BuildingProject[' . $name . '][floorPlan][' . $k . '][title]', $bpa->title, ['class' => 'form-control']) ?>
									<div class="help-block"></div>
								</div>
								<div class="form-group">
									<label class="control-label" for="buildingproject-bpvideo">Ảnh</label>
									<?= FileUploadUI::widget([
											'options' => ['data-callback' => 'buildingProject.makeMapArea', 'class' => 'map-area'],
											'name' => 'BuildingProject[' . $name . '][floorPlan][' . $k . '][images]',
											'id' => $name . $k,
											'url' => Url::to('/express/upload/image'),
											'fieldOptions' => ['values' => $bpa->images]
										]) ?>
									<div class="help-block"></div>
								</div>
				    		</div>
				    	</div>
				    	<?php endforeach;?>
			    	</div>
			    	<?= Html::button('<i class="glyphicon glyphicon-plus"></i><span style="margin-left: 4px;">Thêm</span>', ['class' => 'btn btn-clone', 'data-name' => $name, 'data-count' => $count]) ?>
			    </li>
			    <li>
			    	<?= CKEditor::widget([
			    		'value' => $area->payment,
						'name' => 'BuildingProject[' . $name . '][payment]',
						'editorOptions' => [
							'preset' => 'basic',
							'inline' => false,
							'height' => 150,
							'resize_enabled' => true,
							'removePlugins' => '',
						]
					]); ?>
			    </li>
			    <li>
			    	<?= CKEditor::widget([
			    		'value' => $area->promotion,
						'name' => 'BuildingProject[' . $name . '][promotion]',
						'editorOptions' => [
							'preset' => 'basic',
							'inline' => false,
							'height' => 150,
							'resize_enabled' => true,
							'removePlugins' => '',
						]
					]); ?>
			    </li>
			    <li>
			    	<div class="form-group">
						<label class="control-label" for="buildingproject-bpvideo">Tài liệu bán hàng</label>
						<?= FileUploadUI::widget([
							'name' => 'BuildingProject[' . $name . '][document]',
							'url' => Url::to('/express/upload/image'),
							'fieldOptions' => ['values' => $area->document],
				    		'clientOptions' => ['maxNumberOfFiles' => 1],
						]) ?>
						<div class="help-block"></div>
					</div>
			    </li>
			    <?php endforeach; ?>
			</ul>
    	</div>
		<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Module::t('cms', 'Lưu lại') : Module::t('cms', 'Update'), ['style' => 'margin-top: 22px;', 'class' => 'btn btn-primary btn-block']) ?>
	    </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php 
	$this->registerJs('buildingProject.customColorbox($(".map-area >.files >li >.preview >a"), false);', View::POS_READY, 'colorboxMapArea');
?>