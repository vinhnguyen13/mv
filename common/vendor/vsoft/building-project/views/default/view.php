<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use funson86\cms\Module;
use yii\web\View;
use vsoft\express\components\UploadHelper;
use vsoft\buildingProject\models\BuildingProject;

/* @var $this yii\web\View */
/* @var $model app\models\CmsShow */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Module::t('cms', 'Cms Shows'), 'url' => ['index']];
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
		    <tr><th><?= $model->getAttributeLabel('title') ?></th><td><?= $model->title ?></td></tr>
		    <tr><th><?= $model->getAttributeLabel('bpLogo') ?></th><td><?= UploadHelper::getThumbs($model->bpLogo, true, ['class' => 'gal']) ?></td></tr>
		    <tr>
		    	<th><?= $model->getAttributeLabel('bpGallery') ?></th>
		    	<td><?= UploadHelper::getThumbs($model->bpGallery, true, ['class' => 'gal']) ?></td>
		    </tr>
		    <tr><th><?= $model->getAttributeLabel('bpLocation') ?></th><td><?= $model->bpLocation ?></td></tr>
		    <tr><th><?= $model->getAttributeLabel('bpType') ?></th><td><?= $model->bpType ?></td></tr>
		    <tr><th><?= $model->getAttributeLabel('bpAcreage') ?></th><td><?= $model->bpAcreage ?></td></tr>
		    <tr><th><?= $model->getAttributeLabel('bpAcreageCenter') ?></th><td><?= $model->formatMultiline('bpAcreageCenter') ?></td></tr>
		    <tr><th><?= $model->getAttributeLabel('bpApartmentNo') ?></th><td><?= $model->bpApartmentNo ?></td></tr>
		    <tr><th><?= $model->getAttributeLabel('bpFloorNo') ?></th><td><?= $model->bpFloorNo ?></td></tr>
		    <tr><th><?= $model->getAttributeLabel('bpFacilities') ?></th><td><?= $model->bpFacilities ?></td></tr>
		    <tr><th><?= $model->getAttributeLabel('bpHotline') ?></th><td><?= $model->formatMultiline('bpHotline') ?></td></tr>
		    <tr><th><?= $model->getAttributeLabel('bpWebsite') ?></th><td><a target="_blank" href="<?= $model->bpWebsite ?>"><?= $model->bpWebsite ?></a></td></tr>
		    <tr class="info"><th colspan="2">Bản đồ vị trí</th></tr>
		    <tr>
		    	<td><?= $model->getAttributeLabel('bpMapLocationDes') ?></td>
		    	<td><?= $model->bpMapLocationDes ?></td>
		    </tr>
		    <tr>
		    	<td><?= $model->getAttributeLabel('bpMapLocation') ?></td>
		    	<td><?= UploadHelper::getThumbs($model->bpMapLocation, true, ['class' => 'gal']) ?></td>
		    </tr>
		    <tr class="info"><th colspan="2">Tiện ích</th></tr>
		    <tr>
		    	<td><?= $model->getAttributeLabel('bpFacilitiesDetailDes') ?></td>
		    	<td><?= $model->bpFacilitiesDetailDes ?></td>
		    </tr>
		    <tr>
		    	<td><?= $model->getAttributeLabel('bpFacilitiesDetail') ?></td>
		    	<td><?= UploadHelper::getThumbs($model->bpFacilitiesDetail, true, ['class' => 'gal']) ?></td>
		    </tr>
		    <tr class="info"><th colspan="2"><?= $model->getAttributeLabel('bpVideo') ?></th></tr>
		    <tr>
		    	<td><?= $model->getAttributeLabel('bpVideo') ?></td>
		    	<td><?= $model->getVideos() ?></td>
		    </tr>
		    <tr class="info"><th colspan="2">Tiến độ xây dựng</th></tr>
	    	<?php
	    		$bpProgress = json_decode($model->bpProgress, true);
	    		foreach($bpProgress as $bpp) :
	    	?>
	    	<tr>
		    	<td>Tháng <?= $bpp['month']?>, năm <?= $bpp['year']?></td>
		    	<td><?= UploadHelper::getThumbs($bpp['images'], true, ['class' => 'gal']) ?></td>
	    	</tr>
	    	<?php endforeach; ?>
		    <tr class="info"><th colspan="2">SEO</th></tr>
		    <tr>
		    	<td><?= $model->getAttributeLabel('seo_title') ?></td>
		    	<td><?= $model->seo_title ?></td>
		    </tr>
		    <tr>
		    	<td><?= $model->getAttributeLabel('seo_keywords') ?></td>
		    	<td><?= $model->seo_keywords ?></td>
		    </tr>
		    <tr>
		    	<td><?= $model->getAttributeLabel('seo_description') ?></td>
		    	<td><?= $model->seo_description ?></td>
		    </tr>
		    <?php
		    	$areaTypes = BuildingProject::getAreaTypes();
		    	foreach ($areaTypes as $name => $label) :
		    		$areaType = json_decode($model->$name, true);
		    		if($areaType['floorPlan'] || $areaType['payment'] || $areaType['promotion'] || $areaType['document']) :
		    ?>
		    <tr class="info"><th colspan="2"><?= $label ?></th></tr>
		    <tr>
		    	<td>Mặt bằng</td>
		    	<td>
		    		<?php foreach ($areaType['floorPlan'] as $bpfp): ?>
		    		<div class="floorplan">
		    			<div><?= $bpfp['title'] ?></div>
		    			<?= UploadHelper::getThumbs($bpfp['images']) ?>
		    		</div>
		    		<?php endforeach; ?>
		    	</td>
		    </tr>
		    <tr>
		    	<td>Giá bán & thanh toán</td>
		    	<td><?= $areaType['payment'] ?></td>
		    </tr>
		    <tr>
		    	<td>Chương trình bán hàng</td>
		    	<td><?= $areaType['promotion'] ?></td>
		    </tr>
		    <tr>
		    	<td>Tài liệu bán hàng</td>
		    	<td><?= UploadHelper::getThumbs($areaType['document'], true, ['class' => 'gal']) ?></td>
		    </tr>
		    <?php endif; endforeach; ?>
		</tbody>
	</table>
</div>
