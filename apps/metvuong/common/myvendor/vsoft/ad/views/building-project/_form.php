<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use common\widgets\FileUploadUI;
use yii\helpers\Url;
use common\widgets\CKEditor;
use yii\web\View;
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
    <div class="main-content">
    	<div class="form-group">
			<?= $model->isNewRecord ? '' : Html::hiddenInput('deleteLater', '', ['id' => 'delete-later']) ?>
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('cms', 'Lưu lại') : Yii::t('cms', 'Update'), ['class' => 'btn btn-primary btn-block']) ?>
	    </div>
	    <div style="min-height: 400px;">
	    	<ul class="bp-fields">
	    		<li class="active">
                    <?php if($model->isNewRecord){ ?>
	    			<?= $form->field($model, 'name')->textInput() ?>
	    			<?= $form->field($model, 'city_id')->dropDownList(ArrayHelper::map(AdCity::find()->all(), 'id', 'name'), ['prompt' => '---', 'class' => 'select-2 form-control']) ?>
	    			<input type="hidden" name="BuildingProject[district_id]" value="" />
	    			<?= $form->field($model, 'district_id')->dropDownList($districtData, ['options' => $districtOptions, 'prompt' => '---', 'class' => 'select-2 form-control', 'disabled' => ($model->city_id) ? false : true]) ?>
                    <input type="hidden" id="urlWardStreet" data-ward="<?=$model->ward_id?>" data-street="<?=$model->street_id?>" value="<?=Url::to(['building-project/get-ward-street'])?>" />
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phường / xã</label>
                                <select id="buildingproject-ward_id" class="select-2 form-control p_ward_id" name="BuildingProject[ward_id]">
                                    <option>---</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Đường</label>
                                <select id="buildingproject-street_id" class="select-2 form-control p_street_id" name="BuildingProject[street_id]">
                                    <option>---</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?= $form->field($model, 'home_no') ?>
                    <?= $form->field($model, 'investment_type')->dropDownList($model->inv_type) ?>
                    <?php } else {
                        $city_name = null;
                        $_city = AdCity::find()->select(['name'])->where(['id' => $model->city_id])->asArray()->one();
                        if(count($_city) > 0)
                            $city_name = $_city['name'];

                        $district_name = null;
                        $_district = AdDistrict::find()->select(['name'])->where(['id' => $model->district_id, 'city_id' => $model->city_id])->asArray()->one();
                        if(count($_district) > 0)
                            $district_name = $_district['name'];

                        $ward_name = null;
                        if(!empty($model->ward_id)){
                            $_ward = \vsoft\ad\models\AdWard::find()->select(['name'])->where(['id' => $model->ward_id, 'district_id' => $model->district_id])->asArray()->one();
                            if(count($_ward) > 0)
                                $ward_name = $_ward['name'];
                        }
                        $street_name = null;
                        if(!empty($model->street_id)){
                            $_street = \vsoft\ad\models\AdStreet::find()->select(['name'])->where(['id' => $model->street_id, 'district_id' => $model->district_id])->asArray()->one();
                            if(count($_street) > 0)
                                $street_name = $_street['name'];
                        }
                        ?>
                        <div class="form-group field-buildingproject-name required">
                            <label class="control-label">Tên dự án</label>
                            <span class="form-control"><?=$model->name?></span>
                        </div>
                        <div class="form-group field-buildingproject-name required">
                            <label class="control-label">Thành Phố / Tỉnh</label>
                            <span class="form-control"><?=$city_name?></span>
                        </div>

                        <div class="form-group field-buildingproject-name required">
                            <label class="control-label">Quận / Huyện</label>
                            <span class="form-control"><?=$city_name?></span>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group field-buildingproject-name required">
                                    <label class="control-label">Phường / Xã</label>
                                    <span class="form-control"><?=$ward_name?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group field-buildingproject-name required">
                                    <label class="control-label">Đường</label>
                                    <span class="form-control"><?=$street_name?></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group field-buildingproject-name required">
                            <label class="control-label">Số nhà</label>
                            <span class="form-control"><?=$model->home_no?></span>
                        </div>
                        <div class="form-group field-buildingproject-name required">
                            <label class="control-label">Loại hình đầu tư</label>
                            <span class="form-control"><?=$model->investment_type?></span>
                        </div>
                    <?php
                    }
                    $tabKeys = [
                        'tong-quan' => Yii::t('project', 'General'),
                        'vi-tri' => Yii::t('project', 'Position'),
                        'ha-tang' => Yii::t('project', 'Facility'),
                        'thiet-ke' => Yii::t('project', 'Design'),
                        'tien-do' => Yii::t('project', 'Progress'),
                        'ban-hang' => Yii::t('project', 'Business'),
                        'ho-tro' => Yii::t('project', 'Support'),
                    ];
                    $tabProject = json_decode($model->data_html, true);
                    ?>
                    <div class="form-group field-buildingproject-description">
                        <label>Thông tin mô tả</label>
                        <div class="infor-bds">
                            <ul class="tabProject clearfix">
                                <?php
                                $key_index = key($tabKeys);
                                foreach ($tabKeys as $key1 => $tabValue1) {
                                    if ($key1 == $key_index) {
                                        ?>
                                        <li>
                                            <a href="javascript:void(0)" rel="nofollow"
                                               style="white-space:nowrap;"
                                               class="active"><?= Yii::t('project', $tabValue1) ?></a>
                                        </li>
                                    <?php } else { ?>
                                        <li>
                                            <a href="javascript:void(0)" rel="nofollow"
                                               style="white-space:nowrap;"><?= Yii::t('project', $tabValue1) ?></a>
                                        </li>
                                    <?php }
                                } ?>
                            </ul>
                            <?php
                            foreach ($tabKeys as $key => $tabValue) {
                                $desc = isset($tabProject[$key]) ? $tabProject[$key] : '';
                                echo CKEditor::widget([
                                    'name' => "data_html[" . $key . "]",
                                    'value' => $desc,
                                    'editorOptions' => [
                                        'preset' => 'full',
                                        'inline' => false,
                                        'height' => 500,
                                        'resize_enabled' => true,
                                        'removePlugins' => '',
                                    ]
                                ]);
                            } ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'investors')->dropDownList(ArrayHelper::map($investors, 'id', 'name'), ['multiple' => true, 'class' => 'select-2 form-control']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'architects')->dropDownList(ArrayHelper::map($architects, 'id', 'name'), ['multiple' => true, 'class' => 'select-2 form-control']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'contractors')->dropDownList(ArrayHelper::map($contractors, 'id', 'name'), ['multiple' => true, 'class' => 'select-2 form-control']) ?>
                        </div>
                    </div>

			    	<?= $form->field($model, 'logo')->widget(FileUploadUI::className(), [
						'url' => Url::to('/express/upload/image'),
						'clientOptions' => ['maxNumberOfFiles' => 1] ]) ?>
					<?= $form->field($model, 'gallery')->widget(FileUploadUI::className(), ['url' => Url::to('/express/upload/image')]) ?>
					<?= $form->field($model, 'location')?>
					<div id="map" style="height: 320px; width: 100%; margin-bottom: 15px; margin-top: -10px; border-radius: 4px;"></div>
					<?= Html::activeHiddenInput($model, 'lat') ?>
					<?= Html::activeHiddenInput($model, 'lng') ?>

                    <?= $form->field($model, 'start_time')->textarea()?>
                    <?= $form->field($model, 'estimate_finished')->textarea()?>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'building_density')?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'lift')?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'apartment_no')?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'units_no')?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'land_area')?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'gfa')?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'no_1_bed')?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'sqm_1_bed')?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'no_2_bed')?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'sqm_2_bed')?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'no_3_bed')?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'sqm_3_bed')?>
                        </div>
                    </div>
			    	<?= $form->field($model, 'facilities')->checkboxList(ArrayHelper::map($facility, 'id', 'name'),['class']) ?>
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