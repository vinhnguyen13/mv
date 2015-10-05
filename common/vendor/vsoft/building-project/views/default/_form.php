<?php

use yii\helpers\Html;
use funson86\cms\Module;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use funson86\cms\models\CmsCatalog;
use common\widgets\CKEditor;
use yii\helpers\Url;

$this->registerJsFile(Yii::getAlias('@web') . '/js/building-project.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerCssFile(Yii::getAlias('@web') . '/css/building-project.css', ['depends' => ['yii\bootstrap\BootstrapAsset']]);

$areaTypes = [
	'bpfApartmentArea' => 'Khu căn hộ',
	'bpfCommercialArea' => 'Khu thương mại',
	'bpfTownhouseArea' => 'Khu nhà phố',
	'bpfOffice' => 'Khu Office - Officetel'
];
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
		    <li class="show-content"><a href="#">Thư viện ảnh</a></li>
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
    	<ul class="bp-fields">
		    <li>
		    	<?= $form->field($model, 'bpLogo')->widget(\kartik\file\FileInput::classname(), [
    'options' => ['multiple' => true, 'accept' => 'image/*'],
    'pluginOptions' => [
        'previewFileType' => 'image',
        'uploadUrl' => \yii\helpers\Url::to(['/user/admin/avatar?id=1']),
        'uploadExtraData' => [
            'album_id' => 20,
            'cat_id' => 'Nature'
        ],
        'maxFileCount' => 10,
    ]
]); ?>
		    	<?= $form->field($model, 'bpLocation') ?>
		    	<?= $form->field($model, 'bpType') ?>
		    	<?= $form->field($model, 'bpAcreage') ?>
		    	<?= $form->field($model, 'bpApartmentNo') ?>
		    	<?= $form->field($model, 'bpFloorNo') ?>
		    	<?= $form->field($model, 'bpFacilities') ?>
		    </li>
		</ul>
		<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Module::t('cms', 'Create') : Module::t('cms', 'Update'), ['id' => 'bp-save-button', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
