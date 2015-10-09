<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use funson86\cms\Module;
use yii\web\View;
use vsoft\express\components\UploadHelper;

/* @var $this yii\web\View */
/* @var $model app\models\CmsShow */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Module::t('cms', 'Cms Shows'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile(Yii::getAlias('@web') . '/js/jquery.colorbox-min.js', ['depends' => ['yii\web\YiiAsset']]);
$this->registerCssFile(Yii::getAlias('@web') . '/css/colorbox.css', ['depends' => ['yii\bootstrap\BootstrapAsset']]);
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
    <table class="table table-bordered table-striped">
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
		    <tr><th><?= $model->getAttributeLabel('bpAcreageCenter') ?></th><td><?= $model->bpAcreageCenter ?></td></tr>
		    <tr><th><?= $model->getAttributeLabel('bpApartmentNo') ?></th><td><?= $model->bpApartmentNo ?></td></tr>
		    <tr><th><?= $model->getAttributeLabel('bpFloorNo') ?></th><td><?= $model->bpFloorNo ?></td></tr>
		    <tr><th><?= $model->getAttributeLabel('bpFacilities') ?></th><td><?= $model->bpFacilities ?></td></tr>
		    <tr><th><?= $model->getAttributeLabel('bpHotline') ?></th><td><?= $model->bpHotline ?></td></tr>
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
		</tbody>
	</table>
</div>
