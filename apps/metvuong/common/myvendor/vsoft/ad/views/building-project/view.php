<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use funson86\cms\Module;
use yii\web\View;
use vsoft\express\components\UploadHelper;
use vsoft\ad\models\AdAreaType;

/* @var $this yii\web\View */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Module::t('cms', 'Building Project'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile(Yii::getAlias('@web') . '/js/building-project.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerJsFile(Yii::getAlias('@web') . '/js/gmap.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyASTv_J_7DuXskr5SaCZ_7RVEw7oBKiHi4&callback=initMapView', ['depends' => ['yii\web\YiiAsset']]);
$this->registerCssFile(Yii::getAlias('@web') . '/css/building-project.css', ['depends' => ['yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile(Yii::getAlias('@web') . '/js/jquery.colorbox-min.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerCssFile(Yii::getAlias('@web') . '/css/colorbox.css', ['depends' => ['yii\bootstrap\BootstrapAsset']]);
$this->registerJs('buildingProject.initView()', View::POS_READY, 'initform');
?>
<div class="cms-show-view">

    <p>
        <?= Html::a(Module::t('cms', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Module::t('cms', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Module::t('cms', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <table class="table table-bordered table-striped bp-detail">
    	<tbody>
    		<tr class="info"><th colspan="2">Tổng quan dự án</th></tr>
		    <tr><th><?= $model->getAttributeLabel('title') ?></th><td><?= $model->name ?></td></tr>
		    <tr><th><?= $model->getAttributeLabel('logo') ?></th><td><?= UploadHelper::getThumbs($model->logo, true, ['class' => 'gal', 'data-current' => 'Logo']) ?></td></tr>
		    <tr>
		    	<th><?= $model->getAttributeLabel('gallery') ?></th>
		    	<td><?= UploadHelper::getThumbs($model->gallery, true, ['class' => 'gal', 'data-current' => 'Thư viện ảnh']) ?></td>
		    </tr>
		    <tr><th><?= $model->getAttributeLabel('location') ?></th><td><?= $model->location ?><a id="map-view" href="#map">Xem bản đồ</a></td></tr>
		    <tr><th><?= $model->getAttributeLabel('investment_type') ?></th><td><?= $model->investment_type ?></td></tr>
		    <tr><th><?= $model->getAttributeLabel('land_area') ?></th><td><?= $model->land_area ?></td></tr>
		    <tr><th><?= $model->getAttributeLabel('apartment_no') ?></th><td><?= $model->apartment_no ?></td></tr>
		    <tr><th><?= $model->getAttributeLabel('units_no') ?></th><td><?= $model->units_no ?></td></tr>
		    <tr><th><?= $model->getAttributeLabel('facilities') ?></th>
                <td>
                    <?php
                    if($model->facilities) {
                        $facility = \vsoft\ad\models\AdFacility::find()->select("group_concat(' ', `name`) as facility")->where("id in ({$model->facilities})")->asArray()->one();
                        echo $facility['facility'];
                    }
                    ?>
                </td></tr>
            <tr><th><?= $model->getAttributeLabel('building_density') ?></th><td><?= $model->building_density ?></td></tr>
	    	<?php
		    	$mapLabels = AdAreaType::mapLabels();
		    	foreach ($model->adAreaTypes as $areaType) :
		    ?>
		    <tr class="info"><th colspan="2"><?= $mapLabels[$areaType->type] ?></th></tr>
		    <tr>
		    	<td>Mặt bằng</td>
		    	<td>
		    		<?php if($areaType->floor_plan): $areaType->floor_plan = json_decode($areaType->floor_plan, true) ?>
		    		<?php foreach ($areaType->floor_plan as $bpfp): ?>
		    		<div class="floorplan">
		    			<div><?= $bpfp['title'] ?>:</div>
		    			<?= UploadHelper::getThumbs($bpfp['images'], true, ['class' => 'gal', 'data-current' => $bpfp['title']]) ?>
		    		</div>
		    		<?php endforeach; ?>
		    		<?php endif; ?>
		    	</td>
		    </tr>
		    <tr>
		    	<td>Giá bán & thanh toán</td>
		    	<td>
		    		<?php if($areaType['payment']) : ?>
		    		<a href="#" data-content="<?= htmlentities($areaType['payment']) ?>" class="content-popup">Xem</a>
		    		<?php endif; ?>
		    	</td>
		    </tr>
		    <tr>
		    	<td>Chương trình bán hàng</td>
		    	<td>
		    		<?php if($areaType['promotion']) : ?>
		    		<a href="#" data-content="<?= htmlentities($areaType['promotion']) ?>" class="content-popup">Xem</a>
		    		<?php endif; ?>
		    	</td>
		    </tr>
		    <tr>
		    	<td>Tài liệu bán hàng</td>
		    	<td>
		    		<?php if($areaType['promotion']) : ?>
		    		<a href="#" data-content="<?= htmlentities($areaType['document']) ?>" class="content-popup">Xem</a>
		    		<?php endif; ?>
		    	</td>
		    </tr>
		    <?php endforeach; ?>
		</tbody>
	</table>
	<div class="map-view" >
		<div id="map" style="height: 100%; width: 100%"></div>
	</div>
	<?= Html::activeHiddenInput($model, 'lat') ?>
	<?= Html::activeHiddenInput($model, 'lng') ?>
</div>
