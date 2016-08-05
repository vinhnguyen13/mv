<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use vsoft\express\components\StringHelper;
use vsoft\craw\models\AdProductAdditionInfo;
use vsoft\craw\models\AdProduct;
use vsoft\craw\models\AdBuildingProject;
use vsoft\craw\models\AdCategory;
use vsoft\craw\models\AdProductSearch2;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CmsShowSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->registerCss('#filter_columns #columns-wrap {display: none;} #filter_columns.show #columns-wrap {display: block;} #columns-wrap label {padding-left: 4px; vertical-align: middle; display: inline-block; margin-right: 22px;} #columns-wrap {position: absolute; background: #FFF; padding: 12px; text-align: left; box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);} #filter_columns {position: absolute; left: 0px;} .summary {position: absolute; right: 0px; top: -20px;} .cms-show-index {padding-top: 40px; position: relative;} .filter-col {margin-right: 12px;} .container {max-width: none; width: auto;} .summary {float: right;font-size: 20px;margin-top: 28px;} .title {float: left;} .min {width: 100px; display: inline-block;} table {white-space: nowrap;}');
$this->registerJsFile(Yii::getAlias('@web') . '/js/craw-manager.js', ['depends' => ['yii\web\YiiAsset']]);

$this->title = Yii::t('cms', 'Craw Manager');
$this->params['breadcrumbs'][] = $this->title;

Yii::$app->language = 'vi-VN';

$type = AdProduct::getAdTypes();
$categories = ArrayHelper::map(AdCategory::find()->asArray(true)->all(), 'id', function($item) {
	return Yii::t('ad', $item['name']);
});
$directions = AdProductAdditionInfo::directionList();

$columns = [
    'apid' => [
    	'attribute' => 'id',
    	'label' => 'ID'
    ],
    'apt' => [
    	'attribute' => 'type',
    	'label' => 'Hình thức',
    	'value' => function($model) use ($type) {
    		return $type[$model['type']];
    	},
    	'filter' => Html::activeDropDownList($searchModel, 'type', $type, ['class' => 'form-control', 'prompt' => 'Hình thức'])
    ],
   	'apcid' => [
   		'attribute' => 'category_id',
   		'label' => 'Phân loại',
    	'value' => function($model) use ($categories) {
    		return $categories[$model['category_id']];
    	},
    	'filter' => Html::activeDropDownList($searchModel, 'category_id', $categories, ['class' => 'form-control', 'prompt' => 'Loại BĐS'])
   	],
    'abpn' => [
    	'attribute' => 'project_name',
    	'label' => 'Thuộc dự án'
    ],
    'acn' => [
    	'attribute' => 'city_name',
    	'label' => 'Tỉnh/Thành'
    ],
    'adn' => [
    	'attribute' => 'district_name',
    	'label' => 'Quận/Huyện'
    ],
    'awn' => [
    	'attribute' => 'ward_name',
    	'label' => 'Phường/Xã'
    ],
    'asn' => [
    	'attribute' => 'street_name',
    	'label' => 'Đường/Phố'
    ],
    'aphn' => [
    	'attribute' => 'home_no',
    	'label' => 'Số nhà',
		'filter' =>	Html::activeDropDownList($searchModel, 'home_no_filter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
    ],
    'app' => [
    	'format' => 'raw',
    	'attribute' => 'price',
    	'label' => 'Giá',
    	'value' => function($model) {
    		return $model['price'] ? StringHelper::formatCurrency($model['price']) : null;
    	},
		'filter' =>	Html::activeDropDownList($searchModel, 'price_type', ['1' => 'Có', '0' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
    ],
    'apa' => [
    	'attribute' => 'area',
    	'label' => 'Diện tích (m)',
    	'value' => function($model) {
    		return $model['area'] ? $model['area'] : null;
    	},
    	'filter' =>	Html::activeDropDownList($searchModel, 'area_filter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
    ],
    'apairn' => [
    	'attribute' => 'room_no',
    	'label' => 'Phòng ngủ',
    	'value' => function($model) {
    		return $model['room_no'] ? $model['room_no'] : null;
    	},
		'filter' =>	Html::activeDropDownList($searchModel, 'room_no_filter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
    ],
    'apaitn' => [
   		'attribute' => 'toilet_no',
    	'label' => 'Phòng tắm',
    	'value' => function($model) {
    		return $model['toilet_no'] ? $model['toilet_no'] : null;
    	},
		'filter' =>	Html::activeDropDownList($searchModel, 'toilet_no_filter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
    ],
    'apaifn' => [
    	'attribute' => 'floor_no',
    	'label' => 'Số tầng',
    	'value' => function($model) {
    		return $model['floor_no'] ? $model['floor_no'] : null;
    	},
		'filter' =>	Html::activeDropDownList($searchModel, 'floor_no_filter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
    ],
    'apaifw' => [
    	'attribute' => 'facade_width',
    	'label' => 'Mặt tiền(m)',
		'filter' =>	Html::activeDropDownList($searchModel, 'facade_width_filter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
    ],
    'apailw' => [
   		'attribute' => 'land_width',
    	'label' => 'Đường vào(m)',
		'filter' =>	Html::activeDropDownList($searchModel, 'land_width_filter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
    ],
    'apaihd' => [
    	'attribute' => 'home_direction',
    	'label' => 'Hướng nhà',
    	'value' => function($model) use ($directions) {
    		return $model['home_direction'] ? $directions[$model['home_direction']] : null;
    	},
		'filter' =>	Html::activeDropDownList($searchModel, 'home_direction_filter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
    ],
    'apaifd' => [
    	'attribute' => 'facade_direction',
    	'label' => 'Hướng ban công',
    	'value' => function($model) use ($directions) {
    		return $model['facade_direction'] ? $directions[$model['facade_direction']] : null;
    	},
		'filter' =>	Html::activeDropDownList($searchModel, 'facade_direction_filter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
    ],
    'apaii' => [
    	'attribute' => 'interior',
    	'label' => 'Nội thất',
		'filter' =>	Html::activeDropDownList($searchModel, 'interior_filter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
    ],
    'apc' => [
    	'attribute' => 'content',
    	'label' => 'Nội dung',
    	'value' => function($model) use ($directions) {
    		return $model['content'] ? mb_substr($model['content'], 0, 10, 'UTF-8') . '...' : null;
    	},
		'filter' =>	Html::activeDropDownList($searchModel, 'content_filter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
    ],
    'acin' => [
    	'attribute' => 'contact_name',
    	'label' => 'Tên liên hệ',
		'filter' =>	Html::activeDropDownList($searchModel, 'contact_name_filter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
    ],
    'acia' => [
    	'attribute' => 'contact_address',
    	'label' => 'Địa chỉ',
    	'value' => function($model) use ($directions) {
    		return $model['contact_address'] ? mb_substr($model['contact_address'], 0, 10, 'UTF-8') . '...' : null;
    	},
		'filter' =>	Html::activeDropDownList($searchModel, 'contact_address_filter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
    ],
    'acip' => [
    	'attribute' => 'phone',
    	'label' => 'Số điện thoại',
		'filter' =>	Html::activeDropDownList($searchModel, 'phone_filter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
    ],
    'acim' => [
    	'attribute' => 'mobile',
    	'label' => 'Số di động',
		'filter' =>	Html::activeDropDownList($searchModel, 'mobile_filter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
    ],
    'acie' => [
    	'attribute' => 'email',
    	'label' => 'Email',
		'filter' =>	Html::activeDropDownList($searchModel, 'email_filter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
    ],
    'apca' => [
    	'attribute' => 'created_at',
    	'label' => 'Ngày đăng',
    	'value' => function($model) {
    		return date("F d, Y", $model['created_at']);
    	}
    ],
];

$columns = array_intersect_key($columns, $searchModel->columns);
?>
<div class="cms-show-index">
	<div style="text-align: center; width: 100%; position: absolute; top: -20px;">
		<div id="filter_columns">
			<a href="#" id="toggle" style="font-size: 20px; display: inline-block; margin-top: 22px;">Show/Hide Columns</a>
			<div id="columns-wrap">
				<table>
				<?php
					$columnsToggle = AdProductSearch2::$_columns;
					$columnsToggleName = AdProductSearch2::$_columnsName;
					$count = 1;
					$totalColumns = count($columnsToggle);
					
					foreach ($columnsToggle as $alias => $field) {
						if(($count - 1) % 4 == 0) {
							echo '<tr>';
						}
				?>
				<td><input id="<?= $alias ?>" type="checkbox" class="toggle-column" value="<?= $alias ?>"<?= isset($searchModel->columns[$alias]) ? ' checked="checked"' : '' ?> /><label for="<?= $alias ?>"><?= $columnsToggleName[$field] ?></label></td>
				<?php
						if($count % 4 == 0 || $count == $totalColumns) {
							echo '</tr>';
						}
						$count++;
					}
				?>
				</table>
				<a href="<?= Url::current() ?>" id="ok" style="text-decoration: none; font-size: 20px; display: inline-block; margin-top: 22px; background: #5a5aad; color: #FFF; padding: 4px 72px;">OK</a>
			</div>
		</div>
		<a style="font-size: 20px; display: inline-block; margin-top: 22px;" href="<?= Url::to(['/craw/manager/index2']) ?>">Reset filter</a>
	</div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => array_values($columns)
    ]); ?>
</div>