<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use funson86\cms\Module;
use yii\web\View;
use vsoft\express\components\UploadHelper;
use vsoft\ad\models\AdAreaType;
use vsoft\express\components\StringHelper;
use vsoft\ad\models\AdProduct;
use vsoft\ad\models\AdProductAdditionInfo;
use vsoft\ad\models\AdFacility;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\CmsShow */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Module::t('cms', 'Building Project'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$types = AdProduct::getAdTypes();
$di = AdProductAdditionInfo::directionList();
$facilities = ArrayHelper::map(AdFacility::find()->all(), 'id', 'name');

$this->registerCss("table td {padding: 6px; vertical-align: middle;} table td.td-label {font-weight: bold; padding-left: 0px; color: black;}");
?>
<div class="cms-show-view">

    <p>
        <?= Html::a(Module::t('cms', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Module::t('cms', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
 	<table>
 		<tr>
 			<td class="td-label">Phân loại</td>
 			<td><?= $model->category->name ?></td>
 		</tr>
 		<tr>
 			<td class="td-label">Hình thức</td>
 			<td><?= Yii::t('ad', $types[$model->type]) ?></td>
 		</tr>
 		<tr>
 			<td class="td-label">Thuộc dự án</td>
 			<td><?= $model->project->name ?></td>
 		</tr>
 		<tr>
 			<td class="td-label">Tỉnh/Thành phố</td>
 			<td><?= $model->city->name ?></td>
 		</tr>
 		<tr>
 			<td class="td-label">Quận/Huyện</td>
 			<td><?= $model->district->name ?></td>
 		</tr>
 		<tr>
 			<td class="td-label">Phường/Xã</td>
 			<td><?= $model->ward->name ?></td>
 		</tr>
 		<tr>
 			<td class="td-label">Đường/Phố</td>
 			<td><?= $model->street->name ?></td>
 		</tr>
 		<tr>
 			<td class="td-label">Số nhà</td>
 			<td><?= $model->home_no ?></td>
 		</tr>
 		<tr>
 			<td class="td-label">Diện tích</td>
 			<td><?= $model->area ?></td>
 		</tr>
 		<tr>
 			<td class="td-label">Giá</td>
 			<td><?= StringHelper::formatCurrency($model->price) ?></td>
 		</tr>
 		<tr>
 			<td class="td-label">Nội dung</td>
 			<td><?= htmlspecialchars($model->content, ENT_QUOTES, 'UTF-8') ?></td>
 		</tr>
 		<tr>
 			<td class="td-label">Phòng ngủ</td>
 			<td><?= $model->room_no ?></td>
 		</tr>
 		<tr>
 			<td class="td-label">Phòng tắm</td>
 			<td><?= $model->toilet_no ?></td>
 		</tr>
 		<tr>
 			<td class="td-label">số tầng</td>
 			<td><?= $model->floor_no ?></td>
 		</tr>
 		<tr>
 			<td class="td-label">Mặt tiền</td>
 			<td><?= $model->facade_width ?></td>
 		</tr>
 		<tr>
 			<td class="td-label">Đường vào</td>
 			<td><?= $model->land_width ?></td>
 		</tr>
 		<tr>
 			<td class="td-label">Hướng nhà</td>
 			<td><?= $model->home_direction ? $di[$model->home_direction] : '' ?></td>
 		</tr>
 		<tr>
 			<td class="td-label">Hướng ban công</td>
 			<td><?= $model->facade_direction ? $di[$model->facade_direction] : '' ?></td>
 		</tr>
 		<tr>
 			<td class="td-label">Tiện ích</td>
 			<td>
 				<?php
 					if($model->facility) :
	 					$faci = explode(",", $model->facility);
	 					$facimap = [];
	 					foreach ($faci as $fa) {
	 						$facimap[] = $facilities[$fa];
	 					}
 				?>
 				<?= implode(", ", $facimap) ?>
 				<?php endif; ?>
 			</td>
 		</tr>
 		<tr>
 			<td class="td-label">Hình ảnh</td>
 			<td>
 				<?php if($model->images): ?>
 				<?php foreach ($model->images as $image): ?>
 				<img alt="" src="<?= $image->url ?>" width="120" height="90" />
 				<?php endforeach; ?>
 				<?php endif; ?>
 			</td>
 		</tr>
 		<tr>
 			<td class="td-label">Tên người đăng</td>
 			<td><?= $model->name ?></td>
 		</tr>
 		<tr>
 			<td class="td-label">Số điện thoại</td>
 			<td><?= $model->mobile ?></td>
 		</tr>
 		<tr>
 			<td class="td-label">Email</td>
 			<td><?= $model->email ?></td>
 		</tr>
 	</table>
</div>
