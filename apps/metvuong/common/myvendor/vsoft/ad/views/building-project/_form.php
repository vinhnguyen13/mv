<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use common\widgets\FileUploadUI;
use yii\helpers\Url;
use common\widgets\CKEditor;
use yii\web\View;
use vsoft\ad\models\AdBuildingProject;
use yii\helpers\ArrayHelper;
use vsoft\ad\models\AdDistrict;
use vsoft\ad\models\AdCity;

$this->registerJsFile(Yii::getAlias('@web') . '/js/jquery.maphilight.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerJsFile(Yii::getAlias('@web') . '/js/select2.min.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerJsFile(Yii::getAlias('@web') . '/js/building-project.js', ['depends' => ['yii\web\YiiAsset']]);

$this->registerJsFile(Yii::getAlias('@web') . '/js/gmap.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyASTv_J_7DuXskr5SaCZ_7RVEw7oBKiHi4&callback=initMap', ['depends' => ['yii\web\YiiAsset']]);

$this->registerCssFile(Yii::getAlias('@web') . '/css/building-project.css', ['depends' => ['yii\bootstrap\BootstrapAsset']]);

$this->registerJs('buildingProject.initForm();', View::POS_READY, 'initform');

$this->registerJsFile(Yii::getAlias('@web') . '/js/jquery.colorbox-min.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerCssFile(Yii::getAlias('@web') . '/css/colorbox.css', ['depends' => ['yii\bootstrap\BootstrapAsset']]);
$this->registerCssFile(Yii::getAlias('@web') . '/css/select2.min.css', ['depends' => ['yii\bootstrap\BootstrapAsset']]);

$districts = AdDistrict::find()->all();
$districtOptions = [];
$districtData = [];
foreach ($districts as $district) {
	$districtOptions[$district->id] = ['data-city-id' => $district->city_id];
	$districtData[$district->id] = $district->pre . ' ' . $district->name;
}

if(!$model->isNewRecord) {
    //get selected value from db if value exist
    $checkedList = explode(",", $model->facilities);
    $model->facilities = $checkedList;
}

?>
<div id="project-building-form" class="cms-show-form">
	<?php
		$form = ActiveForm::begin([
			'id' => 'bp-form',
	    	'options' => ['spellcheck' => 'false'],
	    	'enableClientValidation' => false,
	    	'enableAjaxValidation' => false,
		]);
	?>
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
		    <?php foreach($areaTypeMapLabels as $areaTypeLabel): ?>
		    <li class="bp-subcontents">
		    	<a href="#"><?= $areaTypeLabel ?></a>
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
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('cms', 'Lưu lại') : Yii::t('cms', 'Update'), ['class' => 'btn btn-primary btn-block']) ?>
	    </div>
	    <div style="min-height: 400px;">
	    	<ul class="bp-fields">
	    		<li class="active">
	    			<?= $form->field($model, 'name') ?>
	    			<?= $form->field($model, 'city_id')->dropDownList(ArrayHelper::map(AdCity::find()->all(), 'id', 'name'), ['prompt' => '---', 'class' => 'select-2 form-control']) ?>
	    			<input type="hidden" name="BuildingProject[district_id]" value="" />
	    			<?= $form->field($model, 'district_id')->dropDownList($districtData, ['options' => $districtOptions, 'prompt' => '---', 'class' => 'select-2 form-control', 'disabled' => ($model->city_id) ? false : true]) ?>
	  				<?= $form->field($model, 'categories')->dropDownList(ArrayHelper::map($categories, 'id', 'name'), ['multiple' => true, 'class' => 'select-2 form-control']) ?>

                    <?php
                    $tabKeys = [
                        'tong-quan' => Yii::t('project','General'),
                        'vi-tri' => Yii::t('project','Position'),
                        'ha-tang' => Yii::t('project','Facility'),
                        'thiet-ke' => Yii::t('project','Design'),
                        'tien-do' => Yii::t('project','Progress'),
                        'ban-hang' => Yii::t('project','Business'),
                        'ho-tro' => Yii::t('project','Support'),
                    ];
                    $tabProject = json_decode(strip_tags($model->data_html), true);
                    if(count($tabProject) > 0){ ?>
                        <div class="infor-bds">
                            <ul class="tabProject clearfix">
                                <?php
                                foreach($tabProject as $key1 => $tabValue){
                                    ?>
                                    <li class="">
                                        <a href="javascript:void(0)" rel="nofollow" style="white-space:nowrap;"><?=$tabKeys[$key1]?></a>
                                    </li>
                                <?php } ?>
                            </ul>
                            <?php
                            foreach($tabProject as $key => $tabValue) {
                                echo CKEditor::widget([
                                    'name' => $key,
                                    'value' => $tabValue,
                                    'editorOptions' => [
                                        'preset' => 'basic',
                                        'inline' => false,
                                        'height' => 150,
                                        'resize_enabled' => false,
                                        'removePlugins' => '',
                                    ]
                                ]);
                            } ?>
                        </div>
                    <?php } ?>

                    <?= $form->field($model, 'investors')->dropDownList(ArrayHelper::map($investors, 'id', 'name'), ['multiple' => true, 'class' => 'select-2 form-control']) ?>
                    <?= $form->field($model, 'architects')->dropDownList(ArrayHelper::map($architects, 'id', 'name'), ['multiple' => true, 'class' => 'select-2 form-control']) ?>
                    <?= $form->field($model, 'contractors')->dropDownList(ArrayHelper::map($contractors, 'id', 'name'), ['multiple' => true, 'class' => 'select-2 form-control']) ?>
			    	<?= $form->field($model, 'logo')->widget(FileUploadUI::className(), [
						'url' => Url::to('/express/upload/image'),
						'clientOptions' => ['maxNumberOfFiles' => 1] ]) ?>
					<?= $form->field($model, 'gallery')->widget(FileUploadUI::className(), ['url' => Url::to('/express/upload/image')]) ?>
					<?= $form->field($model, 'location') ?>
					<div id="map" style="height: 320px; width: 100%; margin-bottom: 15px; margin-top: -10px; border-radius: 4px;"></div>
					<?= Html::activeHiddenInput($model, 'lat') ?>
					<?= Html::activeHiddenInput($model, 'lng') ?>
					<?= $form->field($model, 'investment_type') ?>
			    	<?= $form->field($model, 'land_area') ?>
			    	<?= $form->field($model, 'commercial_leasing_area')->textArea() ?>
			    	<?= $form->field($model, 'apartment_no') ?>
			    	<?= $form->field($model, 'floor_no') ?>
			    	<?= $form->field($model, 'facade_width')->input('number') ?>
			    	<?= $form->field($model, 'lift')->input('number') ?>
			    	<?= $form->field($model, 'start_date')->widget(\yii\jui\DatePicker::className(), ['options' => ['class' => 'form-control']]) ?>
			    	<?= $form->field($model, 'start_time') ?>
			    	<?= $form->field($model, 'estimate_finished') ?>
			    	<?= $form->field($model, 'owner_type') ?>
			    	<?= $form->field($model, 'facilities')->checkboxList(ArrayHelper::map($facility, 'id', 'name')) ?>
			    	<?= $form->field($model, 'hotline')->textArea()->hint('Mổi số điện thoại trên 1 dòng') ?>
			    	<?= $form->field($model, 'website') ?>
			    	<?= $form->field($model, 'hot_project')->checkbox() ?>
	    		</li>
	    		<li>
	    			<?= $form->field($model, 'location_detail')->widget(CKEditor::className(), [
			    		'editorOptions' => [
							'preset' => 'basic',
							'inline' => false,
							'height' => 150,
							'resize_enabled' => true,
							'removePlugins' => '',
			    		]
			    	]) ?>
	    		</li>
	    		<li>
	    			<?= $form->field($model, 'facilities_detail')->widget(FileUploadUI::className(), ['url' => Url::to('/express/upload/image')]) ?>
	    		</li>
			    <li>
			    	<?= $form->field($model, 'video')->textArea(['style' => 'height: 120px;'])->hint('Nhập đường dẫn youtube, mổi video trên 1 dòng.<br />Ví dụ:<br />https://www.youtube.com/watch?v=BNJIcJyN3o4<br />https://www.youtube.com/watch?v=G1Xi8zDD37I') ?>
			    </li>
			    <li>
			    	<div id="progress-list" class="dynamic-list">
			    		<?php
			    			$countBpp = 0;
			    			if($model->progress):
			    				$progress = json_decode($model->progress);
			    				$countBpp = count($progress);
			    				foreach ($progress as $k => $bpp):
			    		?>
			    		<div class="panel panel-default">
				    		<div class="panel-body">
				    			<i class="glyphicon glyphicon-remove"></i>
				    			<div class="form-group">
									<label class="control-label" for="buildingproject-bpvideo">Tháng / Năm</label>
									<div>
										<?= Html::dropDownList('BuildingProject[progress][' . $k . '][month]', $bpp->month,  $month, ['class' => 'form-control', 'style' => 'width: auto; display: inline-block;']) ?>
										<?= Html::dropDownList('BuildingProject[progress][' . $k . '][year]', $bpp->year,  $year, ['class' => 'form-control', 'style' => 'width: auto; display: inline-block;']) ?>
									</div>
									<div class="help-block"></div>
								</div>
								<div class="form-group">
									<label class="control-label" for="buildingproject-bpvideo">Ảnh</label>
									<?= FileUploadUI::widget([
											'name' => 'BuildingProject[progress][' . $k . '][images]',
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
			    <?php
			    	foreach($areaTypes as $type => $areaType):
			    ?>
			    <li>
			    	<div class="floor-plan-list dynamic-list">
			    		<?php
			    			$floorPlan = $areaType->floor_plan ? json_decode($areaType->floor_plan, true) : [];
			    			$count = count($floorPlan);
			    			foreach($floorPlan as $k => $bpa):
			    				$imagesDetail = isset($bpa['imagesDetail'][0]) ? $bpa['imagesDetail'][0] : '';
			    				$imagesCoordinate = isset($bpa['imagesCoordinate'][0]) ? $bpa['imagesCoordinate'][0] : '';
			    		?>
				    	<div class="panel panel-default">
				    		<div class="panel-body">
				    			<i class="glyphicon glyphicon-remove"></i>
				    			<div class="form-group">
									<label class="control-label" for="buildingproject-bpvideo">Tầng</label>
									<?= Html::textInput($areaType->formName() . '[floor_plan][' . $k . '][title]', $bpa['title'], ['class' => 'form-control']) ?>
									<div class="help-block"></div>
								</div>
								<div class="form-group">
									<label class="control-label" for="buildingproject-bpvideo">Ảnh</label>
									<?= FileUploadUI::widget([
											'options' => ['data-callback' => 'buildingProject.makeMapArea', 'class' => 'map-area', 'data-images-detail' => $imagesDetail, 'images-coordinate' => $imagesCoordinate],
											'name' => $areaType->formName() . '[floor_plan][' . $k . '][images]',
											'id' => $areaType->formName() . $k,
											'url' => Url::to('/express/upload/image'),
											'fieldOptions' => ['values' => $bpa['images']],
											'clientOptions' => ['maxNumberOfFiles' => 1]
										]) ?>
									<div class="help-block"></div>
								</div>
				    		</div>
				    	</div>
				    	<?php endforeach;?>
			    	</div>
			    	<?= Html::button('<i class="glyphicon glyphicon-plus"></i><span style="margin-left: 4px;">Thêm</span>', ['class' => 'btn btn-clone', 'data-name' => $areaType->formName(), 'data-count' => $count]) ?>
			    </li>
			    <li>
			    	<?= $form->field($areaType, 'payment')->widget(CKEditor::className(), [
			    		'editorOptions' => [
							'preset' => 'basic',
							'inline' => false,
							'height' => 150,
							'resize_enabled' => true,
							'removePlugins' => '',
			    		]
			    	]) ?>
			    </li>
			    <li>
			    	<?= $form->field($areaType, 'promotion')->widget(CKEditor::className(), [
			    		'editorOptions' => [
							'preset' => 'basic',
							'inline' => false,
							'height' => 150,
							'resize_enabled' => true,
							'removePlugins' => '',
			    		]
			    	]) ?>
			    </li>
			    <li>
			    	<?= $form->field($areaType, 'document')->widget(CKEditor::className(), [
			    		'editorOptions' => [
							'preset' => 'basic',
							'inline' => false,
							'height' => 150,
							'resize_enabled' => true,
							'removePlugins' => '',
			    		]
			    	]) ?>
			    </li>
			    <?php endforeach; ?>
	    	</ul>
	    </div>
	    <div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('cms', 'Lưu lại') : Yii::t('cms', 'Update'), ['style' => 'margin-top: 22px;', 'class' => 'btn btn-primary btn-block']) ?>
	    </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
	$this->registerJs('buildingProject.customColorbox($(".map-area >.files >li >.preview >a"), false);', View::POS_READY, 'colorboxMapArea');
?>