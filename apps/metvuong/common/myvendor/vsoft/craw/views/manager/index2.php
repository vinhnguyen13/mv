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

$this->registerCss('#content-setting-wrap table label {font-size: 13px; padding-left: 6px; font-weight: normal; margin: 0;} #content-setting-wrap {position: absolute; white-space: nowrap; text-align: left; background: #FFF; padding: 12px; box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.5); z-index: 1; display: none;} #content-setting-wrap.show {display: block;} .mask-wrap .range-value {padding-right: 22px; display: inline-block; width: 80px;} .show-range .range {display: block;} .range {position: absolute; background: #FFF; padding: 8px; display: none; box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.5); width: auto;} .result-search a {display: block; padding: 5px 10px;} .result-search {display: none; position: absolute; background: #FFF; box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5); list-style-type: none; margin: 0; padding: 0;} .show-search .result-search {display: block;} .mask-wrap .form-control {padding-right: 22px;} .mask-wrap {position: relative;} .mask-close {cursor: pointer; position: absolute; right: 6px; top: -2px; font-size: 25px; font-family: monospace; font-weight: bold;} #filter_columns #columns-wrap {display: none;} #filter_columns.show #columns-wrap {z-index: 2; display: block;} #columns-wrap label {padding-left: 4px; vertical-align: middle; display: inline-block; margin-right: 22px;} #columns-wrap {position: absolute; background: #FFF; padding: 12px; text-align: left; box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);} #filter_columns {position: absolute; left: 0px;} .summary {position: absolute; right: 0px; top: -20px;} .cms-show-index {padding-top: 40px; position: relative;} .filter-col {margin-right: 12px;} .container {max-width: none; width: auto;} .summary {float: right;font-size: 20px;margin-top: 28px;} .title {float: left;} .min {width: 100px; display: inline-block;} table {white-space: nowrap;}');
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
    'appmi' => [
    	'attribute' => 'product_main_id',
    	'label' => 'Live ID',
    	'value' => function($model) use ($type) {
    		return $model['product_main_id'] ? $model['product_main_id'] : null;
    	},
    	'filter' =>	Html::activeDropDownList($searchModel, 'product_main_filter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
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
    	'label' => 'Thuộc dự án',
    	'filter' =>	Html::activeDropDownList($searchModel, 'project_name_filter', ['1' => 'Có', '2' => 'Không', '3' => 'Tìm theo tên'], ['class' => 'form-control filter-type-3', 'prompt' => 'Tất cả', 'style' => $searchModel->project_name_filter == 3 ? 'display: none;' : '']) .
					'<div class="mask-wrap"' . ($searchModel->project_name_filter != 3 ? ' style="display: none"' : '') . '>' . Html::activeTextInput($searchModel, 'project_name_mask', ['class' => 'form-control search', 'data-type' => 'project_building']) . '<span class="mask-close">x</span><ul class="result-search"></ul></div>' .
    				Html::activeHiddenInput($searchModel, 'project_building_id')
    ],
    'acn' => [
    	'attribute' => 'city_name',
    	'label' => 'Tỉnh/Thành',
    	'filter' =>	'<div class="mask-wrap">' . Html::activeTextInput($searchModel, 'city_name_mask', ['class' => 'form-control search', 'data-type' => 'city']) . '<span class="mask-close simple-close"' . (!$searchModel->city_name_mask ? ' style="display: none"' : '') . '>x</span><ul class="result-search"></ul></div>' .
    				Html::activeHiddenInput($searchModel, 'city_id')
    ],
    'adn' => [
    	'attribute' => 'district_name',
    	'label' => 'Quận/Huyện',
    	'filter' =>	'<div class="mask-wrap">' . Html::activeTextInput($searchModel, 'district_name_mask', ['class' => 'form-control search', 'data-type' => 'district']) . '<span class="mask-close simple-close"' . (!$searchModel->district_name_mask ? ' style="display: none"' : '') . '>x</span><ul class="result-search"></ul></div>' .
    				Html::activeHiddenInput($searchModel, 'district_id')
    ],
    'awn' => [
    	'attribute' => 'ward_name',
    	'label' => 'Phường/Xã',
    	'filter' =>	Html::activeDropDownList($searchModel, 'ward_name_filter', ['1' => 'Có', '2' => 'Không', '3' => 'Tìm theo tên'], ['class' => 'form-control filter-type-3', 'prompt' => 'Tất cả', 'style' => $searchModel->ward_name_filter == 3 ? 'display: none;' : '']) .
					'<div class="mask-wrap"' . ($searchModel->ward_name_filter != 3 ? ' style="display: none"' : '') . '>' . Html::activeTextInput($searchModel, 'ward_name_mask', ['class' => 'form-control search', 'data-type' => 'ward']) . '<span class="mask-close">x</span><ul class="result-search"></ul></div>' .
    				Html::activeHiddenInput($searchModel, 'ward_id')
    ],
    'asn' => [
    	'attribute' => 'street_name',
    	'label' => 'Đường/Phố',
    	'filter' =>	Html::activeDropDownList($searchModel, 'street_name_filter', ['1' => 'Có', '2' => 'Không', '3' => 'Tìm theo tên'], ['class' => 'form-control filter-type-3', 'prompt' => 'Tất cả', 'style' => $searchModel->street_name_filter == 3 ? 'display: none;' : '']) .
					'<div class="mask-wrap"' . ($searchModel->street_name_filter != 3 ? ' style="display: none"' : '') . '>' . Html::activeTextInput($searchModel, 'street_name_mask', ['class' => 'form-control search', 'data-type' => 'street']) . '<span class="mask-close">x</span><ul class="result-search"></ul></div>' .
    				Html::activeHiddenInput($searchModel, 'street_id')
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
		'filter' =>	Html::activeDropDownList($searchModel, 'price_type', ['1' => 'Có', '0' => 'Không', '2' => 'Lọc theo range'], ['data-max' => 2, 'class' => 'form-control filter-type-2', 'prompt' => 'Tất cả', 'style' => $searchModel->price_type == 2 ? 'display: none;' : '']) .
					'<div class="mask-wrap price-wrap range-wrap"' . ($searchModel->price_type != 2 ? ' style="display: none"' : '') . '>' .
					Html::activeTextInput($searchModel, 'price_mask', ['class' => 'form-control search-range search', 'style' => 'width: 110px;', 'readonly' => true]) .
					'<span class="mask-close">x</span><div class="range">' .
					Html::activeTextInput($searchModel, 'price_min', ['class' => 'form-control range-value range-min', 'placeholder' => 'min']) . ' - ' .
					Html::activeTextInput($searchModel, 'price_max', ['class' => 'form-control range-value range-max', 'placeholder' => 'max']) . ' ' .
					Html::activeDropDownList($searchModel, 'price_unit', [1 => 'tỷ', 2 => 'triệu'], ['class' => 'form-control range-value']) .
					'<input type="button" class="submit-range btn btn-success" value="OK" style="margin-left: 8px;" />' .
					'</div></div>'
    ],
    'apa' => [
    	'attribute' => 'area',
    	'label' => 'Diện tích (m)',
    	'value' => function($model) {
    		return $model['area'] ? $model['area'] : null;
    	},
		'filter' =>	Html::activeDropDownList($searchModel, 'area_filter', ['1' => 'Có', '2' => 'Không', '3' => 'Lọc theo range'], ['data-max' => 3, 'class' => 'form-control filter-type-2', 'prompt' => 'Tất cả', 'style' => $searchModel->area_filter == 3 ? 'display: none;' : '']) .
					'<div class="mask-wrap range-wrap"' . ($searchModel->area_filter != 3 ? ' style="display: none"' : '') . '>' .
					Html::activeTextInput($searchModel, 'area_mask', ['class' => 'form-control search-range search', 'data-type' => 'street', 'readonly' => true]) .
					'<span class="mask-close">x</span><div class="range">' .
					Html::activeTextInput($searchModel, 'area_min', ['class' => 'form-control range-value range-min', 'placeholder' => 'min']) . ' - ' .
					Html::activeTextInput($searchModel, 'area_max', ['class' => 'form-control range-value range-max', 'placeholder' => 'max']) .
					'<input type="button" class="submit-range btn btn-success" value="OK" style="margin-left: 8px;" />' .
					'</div></div>'
    ],
    'apairn' => [
    	'attribute' => 'room_no',
    	'label' => 'Phòng ngủ',
    	'value' => function($model) {
    		return $model['room_no'] ? $model['room_no'] : null;
    	},
		'filter' =>	Html::activeDropDownList($searchModel, 'room_no_filter', ['1' => 'Có', '2' => 'Không', '3' => 'Lọc theo...'], ['class' => 'form-control filter-type-3', 'prompt' => 'Tất cả', 'style' => $searchModel->room_no_filter == 3 ? 'display: none;' : '']) .
					'<div class="mask-wrap"' . ($searchModel->room_no_filter != 3 ? ' style="display: none"' : '') . '>' . Html::activeTextInput($searchModel, 'room_no_mask', ['class' => 'form-control search']) . '<span class="mask-close">x</span><ul class="result-search"></ul></div>'
    ],
    'apaitn' => [
   		'attribute' => 'toilet_no',
    	'label' => 'Phòng tắm',
    	'value' => function($model) {
    		return $model['toilet_no'] ? $model['toilet_no'] : null;
    	},
		'filter' =>	Html::activeDropDownList($searchModel, 'toilet_no_filter', ['1' => 'Có', '2' => 'Không', '3' => 'Lọc theo...'], ['class' => 'form-control filter-type-3', 'prompt' => 'Tất cả', 'style' => $searchModel->toilet_no_filter == 3 ? 'display: none;' : '']) .
					'<div class="mask-wrap"' . ($searchModel->toilet_no_filter != 3 ? ' style="display: none"' : '') . '>' . Html::activeTextInput($searchModel, 'toilet_no_mask', ['class' => 'form-control search']) . '<span class="mask-close">x</span><ul class="result-search"></ul></div>'
    
    ],
    'apaifn' => [
    	'attribute' => 'floor_no',
    	'label' => 'Số tầng',
    	'value' => function($model) {
    		return $model['floor_no'] ? $model['floor_no'] : null;
    	},
		'filter' =>	Html::activeDropDownList($searchModel, 'floor_no_filter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả']),
		'filter' =>	Html::activeDropDownList($searchModel, 'floor_no_filter', ['1' => 'Có', '2' => 'Không', '3' => 'Lọc theo...'], ['class' => 'form-control filter-type-3', 'prompt' => 'Tất cả', 'style' => $searchModel->floor_no_filter == 3 ? 'display: none;' : '']) .
					'<div class="mask-wrap"' . ($searchModel->floor_no_filter != 3 ? ' style="display: none"' : '') . '>' . Html::activeTextInput($searchModel, 'floor_no_mask', ['class' => 'form-control search']) . '<span class="mask-close">x</span><ul class="result-search"></ul></div>'
    
    
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
		'filter' =>	Html::activeDropDownList($searchModel, 'created_filter', [AdProduct::EXPIRED => 'Hoạt động'], ['class' => 'form-control', 'prompt' => 'Tất cả']),
    	'value' => function($model) {
    		return date("F d, Y", $model['created_at']);
    	}
    ],
];

$columns = array_intersect_key($columns, $searchModel->columns);

$exportSetting = isset($_COOKIE['export-setting']) ? $_COOKIE['export-setting'] : 1;
?>
<div class="cms-show-index">
	<div style="text-align: center; width: 100%; position: absolute; top: -20px;">
		<div id="filter_columns">
			<a href="#" id="toggle" style="font-size: 20px; display: inline-block; margin-top: 22px;">Show/Hide Columns</a>
			<a id="export-link" href="<?= Url::to(array_merge(['/craw/manager/export'], Yii::$app->request->get())) ?>" class="btn btn-success btn-sm" style="margin-left: 22px;">Export to Excel</a>
			<div style="position: relative; display: inline-block; vertical-align: middle;">
				<a id="content-setting" href="#" style="font-size: 0px; display: inline-block; width: 20px; height: 20px; background: url(/admin/images/setting.png); vertical-align: middle;">Settings</a>
				<div id="content-setting-wrap">
					<div style="font-weight: bold; font-size: 15px;">Thiết lập khi export nội dung dài</div>
					<table style="margin: 12px; border-collapse: collapse;">
						<tr>
							<td><input<?= $exportSetting == 1 ? ' checked="checked"' : '' ?> name="content-setting" type="radio" id="cs-keep" value="1" /></td>
							<td><label for="cs-keep">Giữ nguyên</label></td>
						</tr>
						<tr>
							<td><input<?= $exportSetting == 2 ? ' checked="checked"' : '' ?> name="content-setting" type="radio" id="cs-hide" value="2" /></td>
							<td><label for="cs-hide">Thay bằng dấu ...</label></td>
						</tr>
						<tr>
							<td><input<?= $exportSetting == 3 ? ' checked="checked"' : '' ?> name="content-setting" type="radio" id="cs-replace" value="3" /></td>
							<td><label for="cs-replace">Bỏ breakline</label></td>
						</tr>
					</table>
				</div>
			</div>
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
		<?php 
			if(isset($_GET['main_id'])) {
				$to = ['/craw/manager/index2', 'main_id' => 1];
			} else {
				$to = ['/craw/manager/index2'];
			}
		?>
		<a style="font-size: 20px; display: inline-block; margin-top: 22px;" href="<?= Url::to($to) ?>">Reset filter</a>
	</div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => array_values($columns)
    ]); ?>
</div>